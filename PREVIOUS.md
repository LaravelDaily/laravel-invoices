### Laravel version <= 9

```bash
$ composer require jhosagid/laravel-invoices:^1.0
```

### Laravel version <= 8

```bash
$ composer require jhosagid/laravel-invoices:^1.0
```

### Laravel version <= 7

```bash
$ composer require jhosagid/laravel-invoices:^1.0
```

### If you're using Laravel version < 5.5

If you don't use auto-discovery, add the ServiceProvider to the providers array in `config/app.php`

```php
Jhosagid\Invoices\InvoiceServiceProvider::class,
```

If you want to use the facade to generate invoices, add this to your facades in `config/app.php`

```php
'Invoice' => Jhosagid\Invoices\Facades\Invoice::class
```
