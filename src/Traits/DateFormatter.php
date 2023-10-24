<?php

namespace LaravelDaily\Invoices\Traits;

use Carbon\CarbonInterface;

/**
 * Trait DateFormatter
 */
trait DateFormatter
{
    /**
     * @var CarbonInterface
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
     * @param CarbonInterface $date
     * @return $this
     */
    public function date(CarbonInterface $date)
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
}
