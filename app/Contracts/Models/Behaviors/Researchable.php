<?php

namespace Koodilab\Contracts\Models\Behaviors;

interface Researchable
{
    /**
     * Get the research experience attribute.
     *
     * @return int
     */
    public function getResearchExperienceAttribute();

    /**
     * Get the research cost attribute.
     *
     * @return int
     */
    public function getResearchCostAttribute();

    /**
     * Get the research time attribute.
     *
     * @return int
     */
    public function getResearchTimeAttribute();
}
