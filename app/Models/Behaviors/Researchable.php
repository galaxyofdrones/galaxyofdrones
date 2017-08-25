<?php

namespace Koodilab\Models\Behaviors;

use Koodilab\Models\Relations\MorphManyResearch;

trait Researchable
{
    use MorphManyResearch;

    /**
     * Get the research time attribute.
     *
     * @return int
     */
    public function getResearchTimeAttribute()
    {
        return round(
            $this->getAttribute($this->researchTimeKey()) / config('app.speed')
        );
    }

    /**
     * Get the research time key.
     *
     * @return string
     */
    protected function researchTimeKey()
    {
        return 'research_time';
    }
}
