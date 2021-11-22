<?php

namespace LaravelDaily\Invoices\Traits;

use Carbon\Carbon;

/**
 * Trait DateFormatter
 * @package LaravelDaily\Invoices\Traits
 */
trait DateFormatter
{
    /**
     * @var Carbon
     */
    public $date;

    /**
     * @var string
     */
    public $date_format;

    /**
     * @var int|false
     */
    public $pay_until_days;

    /**
     * @param Carbon $date
     * @return $this
     */
    public function date(Carbon $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function dateFormat(string $format)
    {
        $this->date_format = $format;

        return $this;
    }

    /**
     * @param int|false $days
     * @return $this
     */
    public function payUntilDays($days)
    {
        if ($days === true) {
            throw new \Error('Invalid value of `true` for attribute '.self::class.'::pay_until_days.');
        }

        $this->pay_until_days = $days;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date->format($this->date_format);
    }

    /**
     * @return string|false
     */
    public function getPayUntilDate()
    {
        if ($this->pay_until_days === false) {
            return false;
        }

        return $this->date->copy()->addDays($this->pay_until_days)->format($this->date_format);
    }
}
