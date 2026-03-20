<?php

namespace LaravelDaily\Invoices\Tests;

use Orchestra\Testbench\TestCase;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

class InvoiceInclusiveTaxTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Barryvdh\DomPDF\ServiceProvider::class,
            \LaravelDaily\Invoices\InvoiceServiceProvider::class,
        ];
    }
    /** @test */
    public function exclusive_tax_still_works()
    {
        $invoice = Invoice::make('Test')
            ->seller(new Party(['name' => 'Seller']))
            ->buyer(new Party(['name' => 'Buyer']))
            ->addItem(
                InvoiceItem::make('Product')
                    ->pricePerUnit(100)
                    ->quantity(1)
                    ->taxByPercent(20)
            );

        $invoice->calculate();

        $this->assertEquals(120.00, $invoice->total_amount);
        $this->assertEquals(20.00, $invoice->total_taxes);
    }

    /** @test */
    public function inclusive_tax_extracts_correct_tax()
    {
        $invoice = Invoice::make('Test')
            ->seller(new Party(['name' => 'Seller']))
            ->buyer(new Party(['name' => 'Buyer']))
            ->taxInclusive()
            ->addItem(
                InvoiceItem::make('Product')
                    ->pricePerUnit(120)
                    ->quantity(1)
                    ->taxByPercent(20)
            );

        $invoice->calculate();

        $this->assertEquals(120.00, $invoice->total_amount);
        $this->assertEquals(20.00, $invoice->total_taxes);
    }
}