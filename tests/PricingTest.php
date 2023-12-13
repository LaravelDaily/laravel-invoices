<?php

namespace LaravelDaily\Invoices\Tests;

use LaravelDaily\Invoices\Services\PricingService;
use PHPUnit\Framework\TestCase;

class PricingTest extends TestCase
{
    public function test_discount_value(): void
    {
        $target   = 49.99;
        $discount = 9.555;
        $decimals = 2;

        $newPrice = PricingService::applyDiscount($target, $discount, $decimals);

        $this->assertEquals(40.44, $newPrice);
    }

    public function test_discount_rate(): void
    {
        $target   = 49.99;
        $discount = 15.666;
        $decimals = 2;

        $newPrice = PricingService::applyDiscount($target, $discount, $decimals, true);

        $this->assertEquals(42.16, $newPrice);
    }

    public function test_tax_value(): void
    {
        $target   = 49.99856;
        $tax      = 10.11111;
        $decimals = 2;

        $newPrice = PricingService::applyTax($target, $tax, $decimals);

        $this->assertEquals(60.11, $newPrice);
    }

    public function test_tax_rate(): void
    {
        $target   = 49.99;
        $tax      = 21;
        $decimals = 2;

        $newPrice = PricingService::applyTax($target, $tax, $decimals, true);

        $this->assertEquals(60.49, $newPrice);
    }

    public function test_quantity_price(): void
    {
        $target   = 25.55;
        $quantity = 0.5;
        $decimals = 2;

        $newPrice = PricingService::applyQuantity($target, $quantity, $decimals);

        $this->assertEquals(12.78, $newPrice);
    }
}
