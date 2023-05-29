<?php

namespace Esyede\BiSnap;

use Illuminate\Contracts\Support\Arrayable;

class Amount implements Arrayable
{
    public $value; // float  ex: 1000.00
    public $currency = 'IDR';

    /**
     * Constructor
     *
     * @param int|string $value
     * @param string $currency
     */
    public function __construct($value, $currency = 'IDR')
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'value' => number_format($this->value, 2, '.', ''),
            'currency' => $this->currency,
        ];
    }
}
