<?php

namespace LaravelDaily\Invoices\Traits;

/**
 * Trait SerialNumberFormatter.
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
     * @return $this
     */
    public function series(string $series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @return $this
     */
    public function sequence(int $sequence)
    {
        $this->sequence = str_pad((string) $sequence, $this->sequence_padding, 0, STR_PAD_LEFT);
        $this->filename($this->getDefaultFilename($this->name));

        return $this;
    }

    /**
     * @return $this
     */
    public function delimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @return $this
     */
    public function sequencePadding(int $value)
    {
        $this->sequence_padding = $value;

        return $this;
    }

    /**
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
