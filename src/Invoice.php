<?php

namespace LaravelDaily\Invoices;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Contracts\PartyContract;
use LaravelDaily\Invoices\Traits\CurrencyFormatter;
use LaravelDaily\Invoices\Traits\DateFormatter;
use LaravelDaily\Invoices\Traits\InvoiceHelpers;
use LaravelDaily\Invoices\Traits\SavesFiles;
use LaravelDaily\Invoices\Traits\SerialNumberFormatter;

/**
 * Class Invoices
 * @package LaravelDaily\Invoices
 */
class Invoice
{
    use CurrencyFormatter;
    use DateFormatter;
    use InvoiceHelpers;
    use SavesFiles;
    use SerialNumberFormatter;

    const TABLE_COLUMNS = 4;

    /**
     * @var string
     */
    public $name;

    /**
     * @var PartyContract
     */
    public $seller;

    /**
     * @var PartyContract
     */
    public $buyer;

    /**
     * @var Collection
     */
    public $items;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var float
     */
    public $discount_by_percent;

    /**
     * @var float
     */
    public $total_discount;

    /**
     * @var float
     */
    public $tax_rate;

    /**
     * @var float
     */
    public $taxable_amount;

    /**
     * @var float
     */
    public $total_taxes;

    /**
     * @var float
     */
    public $total_amount;

    /**
     * @var bool
     */
    public $hasItemUnits;

    /**
     * @var bool
     */
    public $hasItemDiscount;

    /**
     * @var bool
     */
    public $hasItemTax;

    /**
     * @var bool
     */
    public $hasRendering;

    /**
     * @var int
     */
    public $table_columns;

    /**
     * @var PDF
     */
    public $pdf;

    /**
     * Invoice constructor.
     * @param string $name
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($name = 'Invoice')
    {
        // Invoice
        $this->name     = $name;
        $this->seller   = app()->make(config('invoices.seller.class'));
        $this->items    = Collection::make([]);
        $this->template = 'default';

        // Date
        $this->date           = Carbon::now();
        $this->date_format    = config('invoices.date.format');
        $this->pay_until_days = config('invoices.date.pay_until_days');

        // Serial Number
        $this->series               = config('invoices.serial_number.series');
        $this->sequence_padding     = config('invoices.serial_number.sequence_padding');
        $this->delimiter            = config('invoices.serial_number.delimiter');
        $this->serial_number_format = config('invoices.serial_number.format');
        $this->sequence(config('invoices.serial_number.sequence'));

        // Filename
        $this->filename($this->getDefaultFilename($this->name));

        // Currency
        $this->currency_code                = config('invoices.currency.code');
        $this->currency_fraction            = config('invoices.currency.fraction');
        $this->currency_symbol              = config('invoices.currency.symbol');
        $this->currency_decimals            = config('invoices.currency.decimals');
        $this->currency_decimal_point       = config('invoices.currency.decimal_point');
        $this->currency_thousands_separator = config('invoices.currency.thousands_separator');
        $this->currency_format              = config('invoices.currency.format');

        $this->disk          = config('invoices.disk');
        $this->table_columns = self::TABLE_COLUMNS;
    }

    /**
     * @param string $name
     * @return Invoice
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function make($name = 'Invoice')
    {
        return new self($name);
    }

    /**
     * @param array $attributes
     * @return Party
     */
    public static function makeParty(array $attributes = [])
    {
        return new Party($attributes);
    }

    /**
     * @param string $title
     * @return InvoiceItem
     */
    public static function makeItem(string $title = '')
    {
        return (new InvoiceItem())->title($title);
    }

    /**
     * @param InvoiceItem $item
     * @return $this
     */
    public function addItem(InvoiceItem $item)
    {
        $this->items->push($item);

        return $this;
    }

    /**
     * @param $items
     * @return $this
     */
    public function addItems($items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function render()
    {
        if ($this->pdf) {
            return $this;
        }

        $this->beforeRender();

        $template = sprintf('invoices::templates.%s', $this->template);
        $view     = View::make($template, ['invoice' => $this]);

        $this->pdf = PDF::setOptions(['enable_php' => true])->loadHtml($view);

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function stream()
    {
        $this->render();

        return $this->pdf->stream($this->filename);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function download()
    {
        $this->render();

        return $this->pdf->download($this->filename);
    }
}
