<?php

namespace LaravelDaily\Invoices\Traits;

use Illuminate\Support\Facades\App;
use NumberFormatter;

/**
 * Trait QuantityFormatter
 * @package LaravelDaily\Invoices\Traits
 */
trait QuantityFormatter
{
    /**
     * @var int
     */
    public $quantity_decimals;

    /**
     * @var string
     */
    public $quantity_decimal_point;

    /**
     * @var string
     */
    public $quantity_thousands_separator;

    /**
     * @param int $decimals
     * @return $this
     */
    public function quantityDecimals(int $decimals)
    {
        $this->quantity_decimals = $decimals;

        return $this;
    }

    /**
     * @param string $decimal_point
     * @return $this
     */
    public function quantityDecimalPoint(string $decimal_point)
    {
        $this->quantity_decimal_point = $decimal_point;

        return $this;
    }

    /**
     * @param string $thousands_separator
     * @return $this
     */
    public function quantityThousandsSeparator(string $thousands_separator)
    {
        $this->quantity_thousands_separator = $thousands_separator;

        return $this;
    }

    /**
     * @param float $quantity
     * @return string
     */
    public function formatQuantityFixed(float $quantity)
    {
        return number_format(
            $quantity,
            $this->quantity_decimals,
            $this->quantity_decimal_point,
            $this->quantity_thousands_separator
        );
    }

    /**
     * @param float $quantity
     * @return string
     */
    public function formatQuantityDynamic(float $quantity)
    {
        $countDecimals = (int) strpos(strrev((float)$quantity), ".");

        return number_format(
            $quantity,
            $countDecimals,
            $this->quantity_decimal_point,
            $this->quantity_thousands_separator
        );
    }

}
