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
     * @param float $total_discount
     * @return $this
     */
    public function totalDiscount(float $total_discount)
    {
        $this->total_discount = $total_discount;

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
        $total_amount   = 0;
        $total_discount = 0;

        $this->items->each(function ($item) use (&$total_amount, &$total_discount) {
            $item->calculate($this->currency_decimals);

            (is_null($item->units)) ?: $this->hasUnits   = true;
            ($item->discount <= 0) ?: $this->hasDiscount = true;

            $total_amount += $item->sub_total_price;
            $total_discount += $item->discount;
        });

        (!$this->hasUnits) ?: $this->table_columns++;
        (!$this->hasDiscount) ?: $this->table_columns++;

        if (is_null($this->total_amount)) {
            $this->total_amount = $total_amount;
        }

        if (is_null($this->total_discount)) {
            $this->total_discount = $total_discount;
        }

        return $this;
    }
}
