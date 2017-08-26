<?php

namespace Koodilab\Starmap;

use Koodilab\Contracts\Starmap\NameGenerator as NameGeneratorContract;

class NameGenerator implements NameGeneratorContract
{
    /**
     * Generate a name.
     */
    public function generate()
    {
        return ucfirst($this->randC().$this->randV().$this->randC().$this->randE());
    }

    /**
     * Get a consonant.
     *
     * @return string
     */
    protected function randC()
    {
        return $this->rand([
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z',
            'br', 'cr', 'dr', 'gr', 'kr', 'pr', 'sr', 'tr', 'str', 'vr', 'zr', 'bl', 'cl', 'fl', 'gl', 'kl', 'pl',
            'sl', 'vl', 'zl', 'ch', 'sh', 'ph', 'th',
        ]);
    }

    /**
     * Get a vowel.
     *
     * @return string
     */
    protected function randV()
    {
        return $this->rand([
            'a', 'i', 'e', 'o', 'u',
        ]);
    }

    /**
     * Get an ending.
     *
     * @return string
     */
    protected function randE()
    {
        return $this->rand([
            'ia', 'io', 'ion', 'ios', 'ium',
        ]);
    }

    /**
     * Get a random item.
     *
     * @param array $items
     *
     * @return string
     */
    protected function rand(array $items)
    {
        return $items[mt_rand(0, count($items) - 1)];
    }
}
