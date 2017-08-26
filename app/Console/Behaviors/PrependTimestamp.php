<?php

namespace Koodilab\Console\Behaviors;

trait PrependTimestamp
{
    /**
     * Prepend with timestamp.
     *
     * @param $string
     *
     * @return string
     */
    protected function prependTimestamp($string)
    {
        return date('[Y-m-d H:i:s] ').$string;
    }
}
