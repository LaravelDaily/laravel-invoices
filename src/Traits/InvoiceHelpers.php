<?php

namespace LaravelDaily\Invoices\Traits;

use Exception;
use Illuminate\Support\Str;
use LaravelDaily\Invoices\Contracts\PartyContract;

/**
 * Trait InvoiceHelpers
 * @package LaravelDaily\Invoices\Traits
 */
trait InvoiceHelpers
{
    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param float $amount
     * @param bool $byPercent
     * @return $this
     * @throws Exception
     */
    public function totalTaxes(float $amount, bool $byPercent = false)
    {
        if ($this->hasTax()) {
            throw new Exception('Invoice: unable to set tax twice.');
        }

        $this->total_taxes             = $amount;
        !$byPercent ?: $this->tax_rate = $amount;

        return $this;
    }

    /**
     * @param float $amount
     * @return $this
     * @throws Exception
     */
    public function taxRate(float $amount)
    {
        $this->totalTaxes($amount, true);

        return $this;
    }

    /**
     * @param float $taxable_amount
     * @return $this
     */
    public function taxableAmount(float $taxable_amount)
    {
        $this->taxable_amount = $taxable_amount;

        return $this;
    }

    /**
     * @param float $total_discount
     * @param bool $byPercent
     * @return $this
     * @throws Exception
     */
    public function totalDiscount(float $total_discount, bool $byPercent = false)
    {
        if ($this->hasDiscount()) {
            throw new Exception('Invoice: unable to set discount twice.');
        }

        $this->total_discount                     = $total_discount;
        !$byPercent ?: $this->discount_by_percent = $total_discount;

        return $this;
    }

    /**
     * @param float $discount
     * @return $this
     * @throws Exception
     */
    public function discountByPercent(float $discount)
    {
        $this->totalDiscount($discount, true);

        return $this;
    }

    /**
     * @param float $total_amount
     * @return $this
     */
    public function totalAmount(float $total_amount)
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    /**
     * @param PartyContract $seller
     * @return $this
     */
    public function seller(PartyContract $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @param PartyContract $buyer
     * @return $this
     */
    public function buyer(PartyContract $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function template(string $template = 'default')
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function filename(string $filename)
    {
        $this->filename = sprintf('%s.pdf', $filename);

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getDefaultFilename(string $name)
    {
        if ($name === '') {
            return sprintf('%s_%s', $this->series, $this->sequence);
        }

        return sprintf('%s_%s_%s', Str::snake($name), $this->series, $this->sequence);
    }

    /**
     * @return mixed
     */
    public function getTotalAmountInWords()
    {
        return $this->getAmountInWords($this->total_amount);
    }

    /**
     * @throws Exception
     */
    protected function beforeRender(): void
    {
        $this->validate();
        $this->calculate();
    }

    /**
     * @throws Exception
     */
    protected function validate()
    {
        if (!$this->buyer) {
            throw new Exception('Buyer not defined.');
        }
    }

    /**
     * @return $this
     */
    protected function calculate()
    {
        $total_amount   = null;
        $total_discount = null;
        $total_taxes    = null;

        $this->items->each(
            function ($item) use (&$total_amount, &$total_discount, &$total_taxes) {
                // Gates
                if ($item->hasTax() && $this->hasTax()) {
                    throw new Exception('Invoice: you must have taxes only on items or only on invoice.');
                }

                if ($item->hasDiscount() && $this->hasDiscount()) {
                    throw new Exception('Invoice: you must have discounts only on items or only on invoice.');
                }

                $item->calculate($this->currency_decimals);

                (!$item->hasUnits()) ?: $this->hasItemUnits = true;

                if ($item->hasDiscount()) {
                    $total_discount += $item->discount;
                    $this->hasItemDiscount = true;
                }

                if ($item->hasTax()) {
                    $total_taxes += $item->tax;
                    $this->hasItemTax = true;
                }

                // Totals
                $total_amount += $item->sub_total_price;
            });

        $this->applyColspan();

        /**
         * Apply calculations for provided overrides with:
         * totalAmount(), totalDiscount(), discountByPercent(), totalTaxes(), taxRate()
         * or use values calculated from items.
         */
        (!is_null($this->total_amount)) ?: $this->total_amount                = $total_amount;
        $this->hasDiscount() ? $this->applyDiscount() : $this->total_discount = $total_discount;
        $this->hasTax() ? $this->applyTaxes() : $this->total_taxes            = $total_taxes;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasTax()
    {
        return !is_null($this->total_taxes);
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return !is_null($this->total_discount);
    }

    /**
     * @return bool
     */
    public function hasItemOrInvoiceTax()
    {
        return $this->hasTax() || $this->hasItemTax;
    }

    /**
     * @return bool
     */
    public function hasItemOrInvoiceDiscount()
    {
        return $this->hasDiscount() || $this->hasItemDiscount;
    }

    public function applyColspan(): void
    {
        (!$this->hasItemUnits) ?: $this->table_columns++;
        (!$this->hasItemDiscount) ?: $this->table_columns++;
        (!$this->hasItemTax) ?: $this->table_columns++;
    }

    public function applyDiscount(): void
    {
        $total = $this->total_amount;

        if ($this->discount_by_percent) {
            $ratio       = $this->total_discount / 100;
            $newPrice    = round($total * (1 - $ratio), $this->currency_decimals);
            $newDiscount = $total - $newPrice;

            $this->totalAmount($newPrice);
            $this->total_discount = $newDiscount;
        } else {
            $newPrice = $total - $this->total_discount;
            $this->totalAmount($newPrice);
        }
    }

    public function applyTaxes() :void
    {
        $this->taxable_amount = $this->total_amount;
        $total                = $this->taxable_amount;

        if ($this->tax_rate) {
            $ratio    = $this->total_taxes / 100;
            $newPrice = round($total * (1 + $ratio), $this->currency_decimals);
            $newTax   = $newPrice - $total;

            $this->totalAmount($newPrice);
            $this->total_taxes = $newTax;
        } else {
            $newPrice = $total + $this->total_taxes;
            $this->totalAmount($newPrice);
        }
    }
}
