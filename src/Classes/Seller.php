<?php

namespace LaravelDaily\Invoices\Classes;

use LaravelDaily\Invoices\Contracts\PartyContract;

class Seller implements PartyContract
{
    public $name;
    public $address;
    public $code;
    public $vat;
    public $phone;
    public $custom_fields;

    public function __construct()
    {
        $this->name          = config('invoices.seller.attributes.name');
        $this->address       = config('invoices.seller.attributes.address');
        $this->code          = config('invoices.seller.attributes.code');
        $this->vat           = config('invoices.seller.attributes.vat');
        $this->phone         = config('invoices.seller.attributes.phone');
        $this->custom_fields = config('invoices.seller.attributes.custom_fields');
    }
}
