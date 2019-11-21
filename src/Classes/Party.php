<?php

namespace LaravelDaily\Invoices\Classes;

use LaravelDaily\Invoices\Contracts\PartyContract;

/**
 * Class Party
 * @package LaravelDaily\Invoices\Classes
 */
class Party implements PartyContract
{
    /**
     * Party constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        foreach ($properties as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->{$key} ?? null;
    }
}
