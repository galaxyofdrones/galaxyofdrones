<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Transformers\Transformer;

class TrainingTransformer extends Transformer
{
    /**
     * The unit transformer instance.
     *
     * @var UnitTransformer
     */
    protected $unitTransformer;

    /**
     * Constructor.
     *
     * @param UnitTransformer $unitTransformer
     */
    public function __construct(UnitTransformer $unitTransformer)
    {
        $this->unitTransformer = $unitTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Grid $item
     */
    public function transform($item)
    {
        return [
            'remaining' => $item->training
                ? $item->training->remaining
                : null,
            'units' => $this->unitTransformer->transformCollection(
                $item->trainingUnits()
            ),
        ];
    }
}
