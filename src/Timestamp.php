<?php

namespace Esyede\BiSnap;

class Timestamp
{
    protected $timestamp;

    /**
     * Constructor
     *
     * @param string $timestamp
     */
    public function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Stringify this object
     *
     * @return string
     */
    public function __toString()
    {
        return preg_match('/^\d{10}+/', $this->timestamp)
            ? date(DATE_ATOM, $this->timestamp)
            : $this->timestamp;
    }
}
