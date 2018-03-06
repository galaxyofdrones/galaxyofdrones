<?php

namespace Koodilab\Models\Behaviors;

use Koodilab\Models\Relations\MorphManyResearch;

trait Researchable
{
    use MorphManyResearch;

    /**
     * Get the research experience attribute.
     *
     * @return int
     */
    public function getResearchExperienceAttribute()
    {
        return $this->getAttributeFromArray($this->researchExperienceKey());
    }

    /**
     * Get the research cost attribute.
     *
     * @return int
     */
    public function getResearchCostAttribute()
    {
        return $this->getAttributeFromArray($this->researchCostKey());
    }

    /**
     * Get the research time attribute.
     *
     * @return int
     */
    public function getResearchTimeAttribute()
    {
        return round(
            $this->getAttributeFromArray($this->researchTimeKey()) / config('app.speed')
        );
    }

    /**
     * Get the research experience key.
     *
     * @return string
     */
    protected function researchExperienceKey()
    {
        return 'research_experience';
    }

    /**
     * Get the research cost key.
     *
     * @return string
     */
    protected function researchCostKey()
    {
        return 'research_cost';
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
