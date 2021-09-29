![![Banner]](https://banners.beyondco.de/Laravel%20Invoices.png?theme=light&packageManager=composer+require&packageName=laraveldaily%2Flaravel-invoices&pattern=architect&style=style_1&description=PDFs+made+easy&md=1&showWatermark=0&fontSize=100px&images=document-download)

# Laravel Invoices

[![Latest Stable Version](https://poser.pugx.org/laraveldaily/laravel-invoices/v/stable?2)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![Total Downloads](https://poser.pugx.org/laraveldaily/laravel-invoices/downloads?2)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![Latest Unstable Version](https://poser.pugx.org/laraveldaily/laravel-invoices/v/unstable)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![License](https://poser.pugx.org/laraveldaily/laravel-invoices/license)](https://packagist.org/packages/laraveldaily/laravel-invoices)

[![version 2](https://img.shields.io/badge/v2%20maintenance-yes-green?style=flat)](https://packagist.org/packages/laraveldaily/laravel-invoices)
[![version 1](https://img.shields.io/badge/v1%20maintenance-no-red?style=flat)](https://packagist.org/packages/laraveldaily/laravel-invoices)

This Laravel package provides an easy to use interface to generate **Invoice PDF files** with your provided data.

Invoice file can be stored, downloaded, streamed on any of the filesystems you have configured. Supports different templates and locales.

Originally package was developed on PHP 7.3.11 and Laravel 6.2, but should work on lower versions too.

## Features
- Taxes - fixed or rate - for item or for invoice
- Discounts - fixed or by percentage - for item or for invoice
- Shipping - add shipping price to your invoices
- Automatic calculation - provide minimal set of information, or calculate yourself and provide what to print
- Due date
- Easy to customize currency format
- Serial numbers as you like it
- Templates
- Translations
- Global settings and overrides on-the-fly

## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Installation

Via Composer

### Laravel version <= 8

```bash
$ composer require laraveldaily/laravel-invoices:^2.0
```

### Laravel version <= 7

```bash
$ composer require laraveldaily/laravel-invoices:^1.3
```

After installing Laravel Invoices, publish its assets, views, translations and config using the `invoices:install` Artisan command:
```bash
$ php artisan invoices:install
```

### Updates

Since it is evolving fast you might want to have latest template after update using Artisan command:
```bash
$ php artisan invoices:update
```
> It will give a warning if you really want to override default resources

Or alternatively it can be done separately.
```bash
$ php artisan vendor:publish --tag=invoices.views --force
```

```bash
$ php artisan vendor:publish --tag=invoices.translations --force
```

### For Laravel version < 5.5

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
use LaravelDaily\Invoices\Classes\InvoiceItem;

<...>

        $customer = new Buyer([
            'name'          => 'John Doe',
            'custom_fields' => [
                'email' => 'test@example.com',
            ],
        ]);

        $item = (new InvoiceItem())->title('Service 1')->pricePerUnit(2);

        $invoice = Invoice::make()
            ->buyer($customer)
            ->discountByPercent(10)
            ->taxRate(15)
            ->shipping(1.99)
            ->addItem($item);

        return $invoice->stream();
```

See result [Invoice_AA_00001.pdf](examples/invoice_AA_00001.pdf).

## Advanced Usage

``` php
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

<...>

        $client = new Party([
            'name'          => 'Roosevelt Lloyd',
            'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Party([
            'name'          => 'Ashley Medina',
            'address'       => 'The Green Street 12',
            'code'          => '#22663214',
            'custom_fields' => [
                'order number' => '> 654321 <',
            ],
        ]);

        $items = [
            (new InvoiceItem())
                ->title('Service 1')
                ->description('Your product or service description')
                ->pricePerUnit(47.79)
                ->quantity(2)
                ->discount(10),
            (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
            (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
            (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
            (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
            (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
            (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
            (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
            (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
            (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
            (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
            (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
            (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
            (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
            (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
            (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
            (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
            (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
        ];

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
```

See result [Roosevelt Lloyd Ashley Medina.pdf](examples/Roosevelt%20Lloyd%20Ashley%20Medina.pdf).

### Alternatives using facade

Optionally you can use a facade to make new party or item

```php
use Invoice;

$customer = Invoice::makeParty([
    'name' => 'John Doe',
]);

$item = Invoice::makeItem('Your service or product title')->pricePerUnit(9.99);

return Invoice::make()->buyer($customer)->addItem($item)->stream();
```

## Templates

After publishing assets you can modify or make your own template for invoices.

Templates are stored in the `resources/views/vendor/invoices/templates` directory. There you will find `default.blade.php` template which is used by default.

You can specify which template to use by calling `template` method on Invoice object.

For example if you have `resources/views/vendor/invoices/templates/my_company.blade.php` it should look like this:

```php
Invoice::make('receipt')->template('my_company');
```

Too see how things work in a template you can view `default.blade.php` as an example.

## Config

``` php
return [
    'date' => [
        /**
         * Carbon date format
         */
        'format'         => 'Y-m-d',
        /**
         * Due date for payment since invoice's date.
         */
        'pay_until_days' => 7,
    ],

    'serial_number' => [
        'series'           => 'AA',
        'sequence'         => 1,
        /**
         * Sequence will be padded accordingly, for ex. 00001
         */
        'sequence_padding' => 5,
        'delimiter'        => '.',
        /**
         * Supported tags {SERIES}, {DELIMITER}, {SEQUENCE}
         * Example: AA.00001
         */
        'format'           => '{SERIES}{DELIMITER}{SEQUENCE}',
    ],

    'currency' => [
        'code'                => 'eur',
        /**
         * Usually cents
         * Used when spelling out the amount and if your currency has decimals.
         *
         * Example: Amount in words: Eight hundred fifty thousand sixty-eight EUR and fifteen ct.
         */
        'fraction'            => 'ct.',
        'symbol'              => '€',
        /**
         * Example: 19.00
         */
        'decimals'            => 2,
        /**
         * Example: 1.99
         */
        'decimal_point'       => '.',
        /**
         * By default empty.
         * Example: 1,999.00
         */
        'thousands_separator' => '',
        /**
         * Supported tags {VALUE}, {SYMBOL}, {CODE}
         * Example: 1.99 €
         */
        'format'              => '{VALUE} {SYMBOL}',
    ],

    'paper' => [
        // A4 = 210 mm x 297 mm = 595 pt x 842 pt
        'size'        => 'a4',
        'orientation' => 'portrait',
    ],

    'disk' => 'local',

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
```

## Available Methods
Almost every configuration value can be overrided dynamically by methods.

## Invoice
#### General
- addItem(InvoiceItem $item)
- addItems(Iterable)
- name(string)
- status(string) - invoice status [paid/due] if needed
- seller(PartyContract)
- buyer(PartyContract)
- setCustomData(mixed) - allows user to attach additional data to invoice
- getCustomData() - retrieves additional data to use in template
- template(string)
- logo(string) - path to logo
- getLogo() - returns base64 encoded image, used in template to avoid path issues
- filename(string) - overrides automatic filename
- taxRate(float)
- shipping(float) - shipping amount
- **totalDiscount(float) - If not provided calculates itself**
- **totalTaxes(float) - If not provided calculates itself**
- **totalAmount(float) - If not provided calculates itself**
- **taxableAmount(float) - If not provided calculates itself**

#### Serial number
- series(string)
- sequence(int)
- delimiter(string)
- sequencePadding(int)
- serialNumberFormat(string)
- getSerialNumber() - returns formatted serial number

#### Date
- date(Carbon)
- dateFormat(string) - Carbon format of date
- payUntilDays(int) - Days payment due since invoice issued
- getDate() - returns formatted date
- getPayUntilDate() - return formatted due date

#### Currency
- currencyCode(string) - EUR, USD etc.
- currencyFraction(string) - Cents, Centimes, Pennies etc.
- currencySymbol(string)
- currencyDecimals(int)
- currencyDecimalPoint(string)
- currencyThousandsSeparator(string)
- currencyFormat(string)
- getAmountInWords(float, ?string $locale) - Spells out float to words, second parameter is locale
- getTotalAmountInWords() - spells out total_amount
- formatCurrency(float) - returns formatted value with currency settings '$ 1,99'

#### File
- stream() - opens invoice in browser
- download() - offers to download invoice
- save($disk) - saves invoice to storage, use ->filename() for filename
- url() - return url of saved invoice
- toHtml() - render html view instead of pdf

## InvoiceItem
- title(string) - product or service name
- description(string) - additional information for service entry
- units(string) - measurement units of item (adds units columns if set)
- quantity(float) - amount of units of item
- pricePerUnit(float)
- discount(float) - discount in currency
- discountByPercent(float) - discount by percents discountByPercent(15) means 15%
- tax(float)
- taxByPercent(float)
- **subTotalPrice(float) - If not provided calculates itself**

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mysticcode@gmail.com instead of using the issue tracker.

## Author

- [David Lun][link-author]

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
