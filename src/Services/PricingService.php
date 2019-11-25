<?php

namespace LaravelDaily\Invoices\Services;

class PricingService
{
    public static function applyDiscount(float $target, float $discount, int $decimals, bool $rate = false)
    {
        if ($rate) {
            return round($target * (1 - $discount / 100), $decimals);
        }

        return round($target - $discount, $decimals);
    }

    public static function applyTax(float $target, float $tax, int $decimals, bool $rate = false)
    {
        if ($rate) {
            return round($target * (1 + $tax / 100), $decimals);
        }

        return round($target + $tax, $decimals);
    }

    public static function applyQuantity(float $target, float $quantity, int $decimals)
    {
        return round($target * $quantity, $decimals);
    }
}
