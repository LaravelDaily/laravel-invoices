<?php

namespace LaravelDaily\Invoices\Traits;

use Carbon\Carbon;
use Exception;
use LaravelDaily\Invoices\Contracts\PartyContract;
use NumberFormatter;

/**
 * Trait InvoiceHelpers
 * @package LaravelDaily\Invoices\Traits
 */
trait InvoiceHelpers
{
    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $serial
     * @return $this
     */
    public function serial(string $serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * @param $sequence
     * @return $this
     */
    public function sequence($sequence)
    {
        $this->sequence = str_pad((string) $sequence, config('invoices.invoice.padding'), 0, STR_PAD_LEFT);

        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function date(string $date)
    {
        $this->date = Carbon::parse($date);

        return $this;
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function totalDiscount(string $amount)
    {
        $this->total_discount = $amount;

        return $this;
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function totalAmount(string $amount)
    {
        $this->total_amount = $amount;

        return $this;
    }

    /**
     * @param PartyContract $seller
     * @return $this
     */
    public function seller(PartyContract $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @param PartyContract $buyer
     * @return $this
     */
    public function buyer(PartyContract $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function template($template = 'default')
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function filename(string $filename)
    {
        $this->filename = sprintf('%s.pdf', $filename);

        return $this;
    }

    // Getters

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date->toDateString(config('invoices.invoice.date_format'));
    }

    /**
     * @return mixed
     */
    public function getPayUntil()
    {
        return $this->date->addWeek()->toDateString(config('invoices.invoice.date_format'));
    }

    /**
     * @return string
     */
    public function getAmountInWords()
    {
        $formatter = new NumberFormatter(config('invoices.invoice.locale'), NumberFormatter::SPELLOUT);
        $int       = explode('.', $this->total_amount);
        $result    = [];

        foreach ($int as $value) {
            if ($value == '0') {
                $result[] = '0';
            } else {
                $result[] = $formatter->format($value);
            }
        }

        $result = implode(' Eur and ', $result);

        return ucfirst($result) . ' ct.';
    }

    /**
     * @return string
     */
    public function getSS()
    {
        return sprintf(
            '%s%s%s',
            $this->serial,
            config('invoices.invoice.delimiter'),
            $this->sequence
        );
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDefaultSequence()
    {
        return config('invoices.invoice.sequence');
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDefaultSerial()
    {
        return config('invoices.invoice.serial');
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getDefaultFilename(string $name)
    {
        if ($name === '') {
            return sprintf('%s_%s', $this->serial, $this->sequence);
        }

        return sprintf('%s_%s_%s', $name, $this->serial, $this->sequence);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws Exception
     */
    protected function deriveDefaultValues(): void
    {
        if (!$this->sequence) {
            // setter
            $this->sequence($this->getDefaultSequence());
        }

        if (!$this->serial) {
            $this->serial($this->getDefaultSerial());
        }

        if (!$this->template) {
            $this->template();
        }

        if (!$this->filename) {
            $this->filename($this->getDefaultFilename($this->name));
        }

        if (!$this->seller) {
            $this->seller(app()->make(config('invoices.seller.class')));
        }

        if (!$this->buyer) {
            throw new Exception('Buyer not defined.');
        }

        if ($this->hasDiscount === null) {
            $this->hasDiscount = !$this->items->filter(function ($item) {
                return $item['discount'];
            })->isEmpty();
        }
    }
}
