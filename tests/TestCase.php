<?php

declare(strict_types=1);

namespace LaravelDaily\Invoices\Tests;

use LaravelDaily\Invoices\InvoiceServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    protected function getPackageProviders($app)
    {
        return [
            InvoiceServiceProvider::class,
        ];
    }
}
