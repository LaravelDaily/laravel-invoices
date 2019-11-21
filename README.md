# Laravel Invoices

[![Latest Stable Version](https://poser.pugx.org/laraveldaily/laravel-invoices/v/stable)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![Total Downloads](https://poser.pugx.org/laraveldaily/laravel-invoices/downloads)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![Latest Unstable Version](https://poser.pugx.org/laraveldaily/laravel-invoices/v/unstable)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![License](https://poser.pugx.org/laraveldaily/laravel-invoices/license)](https://packagist.org/packages/laraveldaily/laravel-invoices)

Missing invoices for Laravel.

This laravel package provides an easy to use interface in order to generate Invoices with your provided data. The invoice can be stored, downloaded, streamed on any of the filesystems you have configured. Supports different templates and locales.

Easy to use, easy to install and extend. Originally package was developed on PHP 7.3.11 and Laravel 6.2, but should work on lower versions too.

## Installation

Via Composer

```bash
$ composer require laraveldaily/laravel-invoices
```

#### Publish views
```bash
$ php artisan vendor:publish --tag=invoices.views --force
```

#### Publish config
```bash
$ php artisan vendor:publish --tag=invoices.config --force
```

### Laravel 5.5+

If you don't use auto-discovery, add the ServiceProvider to the providers array in `config/app.php`

```php
LaravelDaily\Invoices\InvoiceServiceProvider::class,
```

If you want to use the facade to generate invoices, add this to your facades in `config/app.php`

```php
'Invoice' => LaravelDaily\Invoices\Facades\Invoice::class
```

## Basic Usage

**RandomController.php**
```php
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;

$customer = new Buyer([
    'name'    => 'Customer1',
    'vat'     => '',
    'address' => 'Customer address',
    'code'    => 'customer_id',
    'custom_fields' => [
        'SWIFT'        => 'BANK101',
        'custom_field' => 'Additional customer info',
    ],
]);

$invoice = Invoice::make()
    ->sequence(65)
    ->buyer($customer)
    ->addItem('My Service', 'Hour', '10', '50.00 €', '500.00 €')
    ->totalAmount('500.00 €');

return $invoice->stream();
```

See result [Invoice_AA_00065.pdf](examples/Invoice_AA_00065.pdf).

## Advanced Usage

``` php
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;

$client = new Party([
    'name'    => 'Roosevelt Lloyd',
    'phone'   => '(520) 318-9486',
    'custom_fields' => [
        'note'        => 'IDDQD',
        'business id' => '365#GG',
    ],
]);

$customer = new Party([
    'name'    => 'Ashley Medina',
    'address' => 'The Green Street 12',
    'code'    => '#22663214',
    'custom_fields' => [
        'order number' => '> 654321 <',
    ],
]);

$items = [
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
    ['My Service', 'Hour', '10', '50.00 €', '500.00 €', '10%'],
];

$invoice = Invoice::make('future invoice')
    ->serial('BIG')
    ->sequence(667)
    ->seller($client)
    ->buyer($customer)
    ->date('2009-11-20')
    ->filename($client->name . ' ' . $customer->name)
    ->addItems($items)
    ->totalDiscount('900.00 €')
    ->totalAmount('8100.00 €');

return $invoice->stream();
```

See result [Roosevelt Lloyd Ashley Medina.pdf](examples/Roosevelt%20Lloyd%20Ashley%20Medina.pdf).

## Config

``` php
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
```

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mysticcode@gmail.com instead of using the issue tracker.

## Credits

- [David Lun][link-author]
- [All Contributors][link-contributors]

## License

GPL-3.0-only. Please see the [license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/laraveldaily/laravel-invoices.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/laraveldaily/laravel-invoices.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/laraveldaily/laravel-invoices/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/laraveldaily/laravel-invoices
[link-downloads]: https://packagist.org/packages/laraveldaily/laravel-invoices
[link-travis]: https://travis-ci.org/laraveldaily/laravel-invoices
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/mc0de

