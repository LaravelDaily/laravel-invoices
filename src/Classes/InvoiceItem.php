<?php

namespace LaravelDaily\Invoices\Classes;

use Exception;

class InvoiceItem
{
    public $title;
    public $units;
    public $quantity;
    public $price_per_unit;
    public $sub_total_price;
    public $discount;
    public $discount_by_percent;

    public function __construct()
    {
        $this->quantity            = 1;
        $this->discount            = 0;
        $this->discount_by_percent = false;
    }

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function units(string $units)
    {
        $this->units = $units;

        return $this;
    }

    public function qty(float $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function pricePerUnit(float $price)
    {
        $this->price_per_unit = $price;

        return $this;
    }

    public function subTotalPrice(float $price)
    {
        $this->sub_total_price = $price;

        return $this;
    }

    public function discount(float $amount, bool $byPercent = false)
    {
        $this->discount            = $amount;
        $this->discount_by_percent = $byPercent;

        return $this;
    }

    public function discountByPercent(float $amount)
    {
        $this->discount($amount, true);

        return $this;
    }

    public function getTotalPrice()
    {
        if (is_null($this->sub_total_price)) {
            $total = $this->quantity * $this->price_per_unit;

            $this->totalPrice();
        }

        return $this->sub_total_price;
    }

    public function calculate()
    {
        if (!is_null($this->sub_total_price)) {
            return $this;
        }

        $total = $this->quantity * $this->price_per_unit;

        if ($this->discount_by_percent) {
            $ratio    = $this->discount / 100;
            $newPrice = round($total * (1 - $ratio), 2);

            $this->subTotalPrice($newPrice);
            $this->discount($total - $newPrice);
        } else {
            $this->subTotalPrice($total -= $this->discount);
        }

        return $this;
    }

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
