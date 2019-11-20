<?php

namespace LaravelDaily\Invoices\Traits;

use Carbon\Carbon;
use Exception;
use LaravelDaily\Invoices\Contracts\PartyContract;
use NumberFormatter;

trait InvoiceHelpers
{
    // Setters
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function serial(string $serial)
    {
        $this->serial = $serial;

        return $this;
    }

    public function sequence($sequence)
    {
        $this->sequence = str_pad((string) $sequence, config('invoices.invoice.padding'), 0, STR_PAD_LEFT);

        return $this;
    }

    public function date(string $date)
    {
        $this->date = Carbon::parse($date);

        return $this;
    }

    public function totalDiscount(string $amount)
    {
        $this->total_discount = $amount;

        return $this;
    }

    public function totalAmount(string $amount)
    {
        $this->total_amount = $amount;

        return $this;
    }

    public function seller(PartyContract $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    public function buyer(PartyContract $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function template($template = 'default')
    {
        $this->template = $template;

        return $this;
    }

    public function filename(string $filename)
    {
        $this->filename = sprintf('%s.pdf', $filename);

        return $this;
    }

    // Getters
    public function getDate()
    {
        return $this->date->toDateString(config('invoices.invoice.date_format'));
    }

    public function getPayUntil()
    {
        return $this->date->addWeek()->toDateString(config('invoices.invoice.date_format'));
    }

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

    public function getSS()
    {
        return sprintf(
            '%s%s%s',
            $this->serial,
            config('invoices.invoice.delimiter'),
            $this->sequence
        );
    }

    // Default values for future

    protected function getDefaultSequence()
    {
        return config('invoices.invoice.sequence');
    }

    protected function getDefaultSerial()
    {
        return config('invoices.invoice.serial');
    }

    protected function getDefaultFilename(string $name)
    {
        if ($name === '') {
            return sprintf('%s_%s', $this->serial, $this->sequence);
        }

        return sprintf('%s_%s_%s', $name, $this->serial, $this->sequence);
    }

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
