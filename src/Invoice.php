<?php

namespace LaravelDaily\Invoices;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
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
 * Class Invoices.
 */
class Invoice
{
    use CurrencyFormatter;
    use DateFormatter;
    use InvoiceHelpers;
    use SavesFiles;
    use SerialNumberFormatter;

    public const TABLE_COLUMNS = 4;

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
     * @var string
     */
    public $notes;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $logo;

    /**
     * @var float
     */
    public $discount_percentage;

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
    public $shipping_amount;

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
     * @var int
     */
    public $table_columns;

    /**
     * @var PDF
     */
    public $pdf;

    /**
     * @var string
     */
    public $output;

    /**
     * @var mixed
     */
    protected $userDefinedData;

    /**
     * @var array
     */
    protected array $paperOptions;

    /**
     * @var array
     */
    protected $options;

    /**
     * Invoice constructor.
     *
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($name = '')
    {
        // Invoice
        $this->name     = $name ?: __('invoices::invoice.invoice');
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

        // Paper
        $this->paperOptions = config('invoices.paper');

        // DomPDF options
        $this->options = array_merge(app('dompdf.options'), config('invoices.dompdf_options') ?? ['enable_php' => true]);

        $this->disk          = config('invoices.disk');
        $this->table_columns = static::TABLE_COLUMNS;
    }

    /**
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return Invoice
     */
    public static function make($name = '')
    {
        return new static($name);
    }

    /**
     * @return Party
     */
    public static function makeParty(array $attributes = [])
    {
        return new Party($attributes);
    }

    /**
     * @return InvoiceItem
     */
    public static function makeItem(string $title = '')
    {
        return (new InvoiceItem())->title($title);
    }

    /**
     * @return $this
     */
    public function addItem(InvoiceItem $item)
    {
        $this->items->push($item);

        return $this;
    }

    /**
     * @param $items
     *
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
     * @throws Exception
     *
     * @return $this
     */
    public function render()
    {
        if ($this->pdf) {
            return $this;
        }

        $this->beforeRender();

        $template = sprintf('invoices::templates.%s', $this->template);
        $view     = View::make($template, ['invoice' => $this]);
        $html     = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $this->pdf = PDF::setOptions($this->options)
            ->setPaper($this->paperOptions['size'], $this->paperOptions['orientation'])
            ->loadHtml($html);
        $this->output = $this->pdf->output();

        return $this;
    }

    public function toHtml()
    {
        $template = sprintf('invoices::templates.%s', $this->template);

        return View::make($template, ['invoice' => $this]);
    }

    /**
     * @throws Exception
     *
     * @return Response
     */
    public function stream()
    {
        $this->render();

        return new Response($this->output, Response::HTTP_OK, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $this->filename . '"',
        ]);
    }

    /**
     * @throws Exception
     *
     * @return Response
     */
    public function download()
    {
        $this->render();

        return new Response($this->output, Response::HTTP_OK, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"',
            'Content-Length'      => strlen($this->output),
        ]);
    }
}
