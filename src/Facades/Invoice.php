<?php

namespace LaravelDaily\Invoices\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Invoice
 */
class Invoice extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'invoice';
    }
}
