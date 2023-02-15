<?php

namespace LaravelDaily\Invoices\Traits;

use Exception;
use Illuminate\Support\Str;
use LaravelDaily\Invoices\Contracts\PartyContract;
use LaravelDaily\Invoices\Services\PricingService;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Trait InvoiceHelpers.
 */
trait InvoiceHelpers
{
    /**
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function status(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function notes(string $notes)
    {
        $this->notes = $notes;

        return $this;
    }

    public function logo(string $logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @throws Exception
     *
     * @return $this
     */
    public function totalTaxes(float $amount, bool $byPercent = false)
    {
        if ($this->hasTax()) {
            throw new Exception('Invoice: unable to set tax twice.');
        }

        $this->total_taxes              = $amount;
        ! $byPercent ?: $this->tax_rate = $amount;

        return $this;
    }

    /**
     * @return $this
     */
    public function shipping(float $amount)
    {
        $this->shipping_amount = $amount;

        return $this;
    }

    /**
     * @throws Exception
     *
     * @return $this
     */
    public function taxRate(float $amount)
    {
        $this->totalTaxes($amount, true);

        return $this;
    }

    /**
     * @return $this
     */
    public function taxableAmount(float $taxable_amount)
    {
        $this->taxable_amount = $taxable_amount;

        return $this;
    }

    /**
     * @throws Exception
     *
     * @return $this
     */
    public function totalDiscount(float $total_discount, bool $byPercent = false)
    {
        if ($this->hasDiscount()) {
            throw new Exception('Invoice: unable to set discount twice.');
        }

        $this->total_discount                      = $total_discount;
        ! $byPercent ?: $this->discount_percentage = $total_discount;

        return $this;
    }

    /**
     * @throws Exception
     *
     * @return $this
     */
    public function discountByPercent(float $discount)
    {
        $this->totalDiscount($discount, true);

        return $this;
    }

    /**
     * @return $this
     */
    public function totalAmount(float $total_amount)
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    /**
     * @return $this
     */
    public function seller(PartyContract $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return $this
     */
    public function buyer(PartyContract $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @param mixed
     * @param mixed $value
     *
     * @return $this
     */
    public function setCustomData($value)
    {
        $this->userDefinedData = $value;

        return $this;
    }

    public function getCustomData()
    {
        return $this->userDefinedData;
    }

    /**
     * @return $this
     */
    public function template(string $template = 'default')
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return $this
     */
    public function filename(string $filename)
    {
        $this->filename = sprintf('%s.pdf', $filename);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalAmountInWords()
    {
        return $this->getAmountInWords($this->total_amount);
    }

    public function getLogo()
    {
        $file = new File($this->logo);

        return 'data:' . $file->getMimeType() . ';base64,' . base64_encode($file->getContent());
    }

    /**
     * @return bool
     */
    public function hasTax()
    {
        return ! is_null($this->total_taxes);
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return ! is_null($this->total_discount);
    }

    /**
     * @return bool
     */
    public function hasShipping()
    {
        return ! is_null($this->shipping_amount);
    }

    /**
     * @return bool
     */
    public function hasTotalAmount()
    {
        return ! is_null($this->total_amount);
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
        (! $this->hasItemUnits) ?: $this->table_columns++;
        (! $this->hasItemDiscount) ?: $this->table_columns++;
        (! $this->hasItemTax) ?: $this->table_columns++;
    }

    public function calculateDiscount(): void
    {
        $totalAmount = $this->total_amount;

        if ($this->discount_percentage) {
            $newTotalAmount = PricingService::applyDiscount($totalAmount, $this->discount_percentage, $this->currency_decimals, true);
        } else {
            $newTotalAmount = PricingService::applyDiscount($totalAmount, $this->total_discount, $this->currency_decimals);
        }

        $this->total_amount   = $newTotalAmount;
        $this->total_discount = $totalAmount - $newTotalAmount;
    }

    public function calculateTax(): void
    {
        if ($this->taxable_amount) {
            return;
        }

        $this->taxable_amount = $this->total_amount;
        $totalAmount          = $this->taxable_amount;

        if ($this->tax_rate) {
            $newTotalAmount = PricingService::applyTax($totalAmount, $this->tax_rate, $this->currency_decimals, true);
        } else {
            $newTotalAmount = PricingService::applyTax($totalAmount, $this->total_taxes, $this->currency_decimals);
        }

        $this->total_amount = $newTotalAmount;
        $this->total_taxes  = $newTotalAmount - $totalAmount;
    }

    public function calculateShipping(): void
    {
        $this->total_amount = PricingService::applyTax($this->total_amount, $this->shipping_amount, $this->currency_decimals);
    }

    /**
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
    public function validate()
    {
        if (! $this->buyer) {
            throw new Exception('Buyer not defined.');
        }

        if (! $this->seller) {
            throw new Exception('Seller not defined.');
        }

        if (! count($this->items)) {
            throw new Exception('No items to invoice defined.');
        }
    }

    /**
     * @return $this
     */
    public function calculate()
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

                (! $item->hasUnits()) ?: $this->hasItemUnits = true;

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
            }
        );

        $this->applyColspan();

        /*
         * Apply calculations for provided overrides with:
         * totalAmount(), totalDiscount(), discountByPercent(), totalTaxes(), taxRate()
         * or use values calculated from items.
         */
        $this->hasTotalAmount() ?: $this->total_amount                            = $total_amount;
        $this->hasDiscount() ? $this->calculateDiscount() : $this->total_discount = $total_discount;
        $this->hasTax() ? $this->calculateTax() : $this->total_taxes              = $total_taxes;
        ! $this->hasShipping() ?: $this->calculateShipping();

        return $this;
    }
}
