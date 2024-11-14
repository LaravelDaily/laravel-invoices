<?php

namespace Jhosagid\Invoices\Classes;

use Jhosagid\Invoices\Contracts\PartyContract;

/**
 * Class Party
 */
class Party implements PartyContract
{
    public $custom_fields;

    /**
     * Party constructor.
     * @param $properties
     */
    public function __construct($properties)
    {
        $this->custom_fields = [];

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
