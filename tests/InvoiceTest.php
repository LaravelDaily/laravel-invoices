<?php

namespace LaravelDaily\Invoices\Tests;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\InvoiceServiceProvider;
use Orchestra\Testbench\TestCase;

class InvoiceTest extends TestCase
{
    public $invoice;
    public $data;

    public function setUp() : void
    {
        parent::setUp();
        $this->invoice = new Invoice('demo-invoice');
        $this->data = [
            'transaction_ref' => 'f8c76a78-ba8a-191c-bb4f',
            'signature'       => 'CEO Lorem ipsum',
        ];
        $this->invoice->setCustomData($this->data);
    }

    protected function getPackageProviders($app)
    {
        return [
            InvoiceServiceProvider::class,
        ];
    }

    /**
     * @return void
     */
    public function test_it_adds_new_data()
    {
        $data = [
            'invoice_paid' => true
        ];

        $this->invoice->setCustomData($data);

        $this->assertArrayHasKey('invoice_paid', $this->invoice->getCustomData());
        $this->assertTrue($this->invoice->getCustomData()['invoice_paid']);
    }

    /** @test */
    public function it_can_retrieve_value_by_key_from_custom_data()
    {
        $this->assertEquals('CEO Lorem ipsum', $this->invoice->getCustomData('signature'));
    }

    /** @test */
    public function it_has_right_number_of_values_in_custom_data()
    {
        $this->assertCount(2, $this->invoice->getCustomData());
    }

    /** @test */
    public function it_returns_an_array_of_data()
    {
        $this->assertIsArray($this->invoice->getCustomData());
        $this->assertArrayHasKey('signature', $this->invoice->getCustomData());
        $this->assertArrayHasKey('transaction_ref', $this->invoice->getCustomData());
    }
}
