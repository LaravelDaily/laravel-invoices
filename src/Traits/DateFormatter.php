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
     * @var int
     */
    public $pay_until_days;


    /**
     * @var Carbon
     */
    public $due_date;

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
     * @param int $days
     * @return $this
     */
    public function payUntilDays(int $days)
    {
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
     * @return mixed
     */
    public function getPayUntilDate()
    {
        return $this->date->copy()->addDays($this->pay_until_days)->format($this->date_format);
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function payDueDate(Carbon $dt){
        $this->due_date = $dt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayDueDate()
    {
        return $this->due_date ? $this->due_date->format($this->date_format) : null;
    }
}
