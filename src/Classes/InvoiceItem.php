<?php

namespace LaravelDaily\Invoices\Classes;

use Exception;

/**
 * Class InvoiceItem
 * @package LaravelDaily\Invoices\Classes
 */
class InvoiceItem
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $units;

    /**
     * @var float
     */
    public $quantity;

    /**
     * @var float
     */
    public $price_per_unit;

    /**
     * @var float
     */
    public $sub_total_price;

    /**
     * @var float
     */
    public $discount;

    /**
     * @var bool
     */
    public $discount_percentage;

    /**
     * @var float
     */
    public $tax;

    /**
     * @var float
     */
    public $tax_percentage;

    /**
     * InvoiceItem constructor.
     */
    public function __construct()
    {
        $this->quantity = 1.0;
        $this->discount = 0.0;
        $this->tax      = 0.0;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $units
     * @return $this
     */
    public function units(string $units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * @param float $quantity
     * @return $this
     */
    public function qty(float $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function pricePerUnit(float $price)
    {
        $this->price_per_unit = $price;

        return $this;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function subTotalPrice(float $price)
    {
        $this->sub_total_price = $price;

        return $this;
    }

    /**
     * @param float $amount
     * @param bool $byPercent
     * @return $this
     * @throws Exception
     */
    public function discount(float $amount, bool $byPercent = false)
    {
        if ($this->hasDiscount()) {
            throw new Exception('InvoiceItem: unable to set discount twice.');
        }

        $this->discount                           = $amount;
        !$byPercent ?: $this->discount_percentage = $amount;

        return $this;
    }

    /**
     * @param float $amount
     * @param bool $byPercent
     * @return $this
     * @throws Exception
     */
    public function tax(float $amount, bool $byPercent = false)
    {
        if ($this->hasTax()) {
            throw new Exception('InvoiceItem: unable to set tax twice.');
        }

        $this->tax                           = $amount;
        !$byPercent ?: $this->tax_percentage = $amount;

        return $this;
    }

    /**
     * @param float $amount
     * @return $this
     * @throws Exception
     */
    public function discountByPercent(float $amount)
    {
        $this->discount($amount, true);

        return $this;
    }

    /**
     * @param float $amount
     * @return $this
     * @throws Exception
     */
    public function taxByPercent(float $amount)
    {
        $this->tax($amount, true);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUnits()
    {
        return !is_null($this->units);
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->discount !== 0.0;
    }

    /**
     * @return bool
     */
    public function hasTax()
    {
        return $this->tax !== 0.0;
    }

    /**
     * @param int $decimals
     * @return $this
     */
    public function calculate(int $decimals)
    {
        if (!is_null($this->sub_total_price)) {
            return $this;
        }

        $this->calculateSubTotal($decimals);
        $this->applyDiscount($decimals);
        $this->applyTax($decimals);

        return $this;
    }

    /**
     * @param int $decimals
     */
    public function calculateSubTotal(int $decimals)
    {
        $total = round($this->quantity * $this->price_per_unit, $decimals);

        $this->subTotalPrice($total);
    }

    /**
     * @param int $decimals
     */
    public function applyDiscount(int $decimals)
    {
        $total = $this->sub_total_price;

        if ($this->discount_percentage) {
            $ratio       = $this->discount / 100;
            $newPrice    = round($total * (1 - $ratio), $decimals);
            $newDiscount = $total - $newPrice;

            $this->subTotalPrice($newPrice);
            $this->discount = $newDiscount;
        } else {
            $newPrice = $total - $this->discount;
            $this->subTotalPrice($newPrice);
        }
    }

    /**
     * @param int $decimals
     */
    public function applyTax(int $decimals)
    {
        $total = $this->sub_total_price;

        if ($this->tax_percentage) {
            $ratio    = $this->tax / 100;
            $newPrice = round($total * (1 + $ratio), $decimals);
            $newTax   = $newPrice - $total;

            $this->subTotalPrice($newPrice);
            $this->tax = $newTax;
        } else {
            $newPrice = $total + $this->tax;
            $this->subTotalPrice($newPrice);
        }
    }

    /**
     * @throws Exception
     */
    public function validate()
    {
        if (is_null($this->title)) {
            throw new Exception('InvoiceItem: title not defined.');
        }

        if (is_null($this->price_per_unit)) {
            throw new Exception('InvoiceItem: price_per_unit not defined.');
        }

    }
}
