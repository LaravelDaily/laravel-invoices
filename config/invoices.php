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
        // A4 =  210 mm x  297 mm =  595 pt x  842 pt
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
            'name'          => 'Towne, Smith and Ebert',
            'address'       => '89982 Pfeffer Falls Damianstad, CO 66972-8160',
            'code'          => '41-1985581',
            'vat'           => '123456789',
            'phone'         => '760-355-3930',
            'custom_fields' => [
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
];
