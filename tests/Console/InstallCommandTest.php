<?php

declare(strict_types=1);

namespace LaravelDaily\Invoices\Tests\Console;

use LaravelDaily\Invoices\Tests\TestCase;

final class InstallCommandTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app->config->set('app.locale', 'nl');
    }

    public function test_than_translations_are_loaded(): void
    {
        self::assertSame('Prijs', __('invoices::invoice.price', locale: 'nl'));
    }
}
