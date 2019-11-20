<?php

namespace LaravelDaily\Invoices;

use Carbon\Carbon;
use Exception;
use Faker\Factory as FakerFactory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Contracts\PartyContract;
use LaravelDaily\Invoices\Traits\InvoiceHelpers;
use PDF;

/**
 * Class Invoices
 * @package LaravelDaily\Invoices
 */
class Invoice
{
    use InvoiceHelpers;

    const TABLE_COLUMNS = 4;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $serial;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var Collection
     */
    public $items;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var Carbon
     */
    protected $date;

    /**
     * @var PartyContract
     */
    public $seller;

    /**
     * @var PartyContract
     */
    public $buyer;

    /**
     * @var
     */
    public $total_discount;

    /**
     * @var
     */
    public $total_amount;

    /**
     * @var bool
     */
    public $hasUnits;

    /**
     * @var bool
     */
    public $hasDiscount;

    /**
     * @var bool
     */
    public $hasRendering;

    /**
     * @var int
     */
    public $table_columns;

    /**
     * @var string
     */
    public $template;

    /**
     * @var PDF
     */
    public $pdf;

    /**
     * Invoice constructor.
     * @param string $name
     */
    public function __construct($name = 'Invoice')
    {
        $this->name($name);
        $this->serial($this->getDefaultSerial());
        $this->sequence($this->getDefaultSequence());
        $this->template();
        $this->filename($this->getDefaultFilename($this->name));
        $this->seller(app()->make(config('invoices.seller.class')));
        $this->date(Carbon::now());
        $this->items = Collection::make([]);

        $this->table_columns = self::TABLE_COLUMNS;
    }

    /**
     * @param string $name
     * @return Invoice
     */
    public static function make($name = 'Invoice')
    {
        return new self($name);
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
            $this->addItem(...array_values($item));
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

        $template = sprintf('invoice::templates.%s', $this->template);
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
