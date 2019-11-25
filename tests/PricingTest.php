<?php

namespace LaravelDaily\Invoices\Tests;

use LaravelDaily\Invoices\Services\PricingService;
use PHPUnit\Framework\TestCase;

class PricingTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function test_discount_value()
    {
        $target   = 49.99;
        $discount = 9.555;
        $decimals = 2;

        $newPrice = PricingService::applyDiscount($target, $discount, $decimals);

        $this->assertEquals($newPrice, 40.44);
    }

    /**
     * @test
     * @return void
     */
    public function test_discount_rate()
    {
        $target   = 49.99;
        $discount = 15.666;
        $decimals = 2;

        $newPrice = PricingService::applyDiscount($target, $discount, $decimals, true);

        $this->assertEquals($newPrice, 42.16);
    }

    /**
     * @test
     * @return void
     */
    public function test_tax_value()
    {
        $target   = 49.99856;
        $tax      = 10.11111;
        $decimals = 2;

        $newPrice = PricingService::applyTax($target, $tax, $decimals);

        $this->assertEquals($newPrice, 60.11);
    }

    /**
     * @test
     * @return void
     */
    public function test_tax_rate()
    {
        $target   = 49.99;
        $tax      = 21;
        $decimals = 2;

        $newPrice = PricingService::applyTax($target, $tax, $decimals, true);

        $this->assertEquals($newPrice, 60.49);
    }

    public function test_quantity_price()
    {
        $target   = 25.55;
        $quantity = 0.5;
        $decimals = 2;

        $newPrice = PricingService::applyQuantity($target, $quantity, $decimals);

        $this->assertEquals($newPrice, 12.78);
    }
}
