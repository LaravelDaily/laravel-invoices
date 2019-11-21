<?php

namespace LaravelDaily\Invoices\Traits;

/**
 * Trait SerialNumberFormatter
 * @package LaravelDaily\Invoices\Traits
 */
trait SerialNumberFormatter
{
    /**
     * @var string
     */
    public $series;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var int
     */
    public $sequence_padding;

    /**
     * @var string
     */
    public $delimiter;

    /**
     * @var string
     */
    public $serial_number_format;

    /**
     * @param string $series
     * @return $this
     */
    public function series(string $series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @param int $sequence
     * @return $this
     */
    public function sequence(int $sequence)
    {
        $this->sequence = str_pad((string) $sequence, $this->sequence_padding, 0, STR_PAD_LEFT);

        return $this;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function delimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function sequencePadding(int $value)
    {
        $this->sequence_padding = $value;

        return $this;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function serialNumberFormat(string $format)
    {
        $this->serial_number_format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {
        return strtr($this->serial_number_format, [
            '{SERIES}'    => $this->series,
            '{DELIMITER}' => $this->delimiter,
            '{SEQUENCE}'  => $this->sequence,
        ]);
    }
}
