<?php

namespace LaravelDaily\Invoices;

use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use LaravelDaily\Invoices\Classes\Buyer;
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

    /**
     * @var Collection
     */
    public $items;

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
     * @var bool
     */
    public $hasDiscount;

    public $total_discount;
    public $total_amount;

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
        $this->name  = $name;
        $this->items = Collection::make([]);
        $this->date  = Carbon::now();
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
     * @param string $name
     * @param int $price
     * @return $this
     */
    public function addItem(
        string $title,
        string $units,
        string $amount,
        string $price_per_unit,
        string $total_price,
        string $discount = ''
    ) {
        $this->items->push(Collection::make([
            'title'          => $title,
            'units'          => $units,
            'amount'         => $amount,
            'price_per_unit' => $price_per_unit,
            'total_price'    => $total_price,
            'discount'       => $discount,
        ]));

        return $this;
    }

    public function addItems($items)
    {
        foreach ($items as $item) {
            $this->addItem(...array_values($item));
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function render()
    {
        $this->deriveDefaultValues();
        // A4 =  210 mm x  297 mm =  595 pt x  842 pt
        $template = sprintf('invoice::templates.%s', $this->template);
        $view     = View::make($template, ['invoice' => $this]);

        $this->pdf = PDF::setOptions(['enable_php' => true])->loadHtml($view);

        return $this;
    }

    /**
     * @return mixed
     */
    public function stream()
    {
        $this->render();

        return $this->pdf->stream($this->filename);
    }

    /**
     * @return mixed
     */
    public function download()
    {
        $this->render();

        return $this->pdf->download($this->filename);
    }

    public function addFakeItem()
    {
        $faker = FakerFactory::create();
        $item  = [
            'title'          => $faker->words(rand(3, 4), true),
            'units'          => $faker->randomElement(array_values(config('invoices.units'))),
            'amount'         => $faker->numberBetween(1, 20),
            'price_per_unit' => $faker->numberBetween(100, 10000),
            'total_price'    => $faker->randomFloat(2, 100, 1000),
            'discount'       => $faker->numberBetween(1, 20),
        ];

        $this->addItem(...array_values($item));
    }

    public function addFakeItems(int $amount = 2)
    {
        for ($i = 0; $i < $amount; $i++) {
            $this->addFakeItem();
        }

        return $this;
    }
}
