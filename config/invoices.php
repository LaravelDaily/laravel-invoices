<?php

return [
    'invoice' => [
        /**
         * The format of full invoice number AA.00001
         */
        'serial'      => 'AA',
        'sequence'    => 1,
        'padding'     => 5,
        'delimiter'   => '.',
        'date_format' => '%Y-%m-%d',
        /**
         * Locale used in NumberFormatter
         *
         * Amount in words: Four hundred fifty Eur and 0 ct.
         */
        'locale'      => 'en',
    ],

    'paper' => [
        'size'        => 'a4',
        'orientation' => 'portrait',
    ],

    'seller' => [
        /**
         * Class used in templates via $invoice->seller
         *
         * Must implement LaravelDaily\Invoices\Contracts\PartyContract
         *      or extend LaravelDaily\Invoices\Classes\Party
         */
        'class' => \LaravelDaily\Invoices\Classes\Seller::class,

        /**
         * Default attributes for Seller::class
         */
        'attributes' => [
            'name'    => 'Towne, Smith and Ebert',
            'address' => '89982 Pfeffer Falls Damianstad, CO 66972-8160',
            'code'    => '41-1985581',
            'vat'     => '123456789',
            'phone'   => '760-355-3930',
            'generic' => [
                /**
                 * Custom attributes for Seller::class
                 *
                 * Used to display additional info on Seller section in invoice
                 * attribute => value
                 */
                'SWIFT' => 'BANK101',
            ],
        ],
    ],

    /**
     * For future uses
     */
    'units' => [
        'unit' => 'Unit',
        'hour' => 'Hour',
        'km'   => 'Km',
        'm2'   => 'm2',
        'm'    => 'm',
        'kg'   => 'kg',
        'day'  => 'Day',
    ],
];
