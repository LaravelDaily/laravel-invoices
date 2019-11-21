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
     * @var float
     */
    public $discount_original_percent;

    /**
     * @var bool
     */
    public $discount_by_percent;

    /**
     * InvoiceItem constructor.
     */
    public function __construct()
    {
        $this->quantity            = 1.0;
        $this->discount            = 0.0;
        $this->discount_by_percent = false;
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
     */
    public function discount(float $amount, bool $byPercent = false)
    {
        $this->discount            = $amount;
        $this->discount_by_percent = $byPercent;

        if ($byPercent) {
            $this->discount_original_percent = $amount;
        }

        return $this;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function discountByPercent(float $amount)
    {
        $this->discount($amount, true);

        return $this;
    }

    /**
     * @param int $decimals
     * @return $this
     */
    public function calculate(int $decimals = 2)
    {
        if (!is_null($this->sub_total_price)) {
            return $this;
        }

        $total = $this->quantity * $this->price_per_unit;

        if ($this->discount_by_percent) {
            $ratio       = $this->discount / 100;
            $newPrice    = round($total * (1 - $ratio), $decimals);
            $newDiscount = $total - $newPrice;

            $this->subTotalPrice($newPrice);
            $this->discount($newDiscount);
        } else {
            $newPrice = $total - $this->discount;
            $this->subTotalPrice($newPrice);
        }

        return $this;
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
