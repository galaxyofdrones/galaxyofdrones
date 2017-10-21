<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Grid;
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
     * @param Grid $item
     */
    public function transform($item)
    {
        return [
            'remaining' => $item->training
                ? $item->training->remaining
                : null,
            'supply' => $item->planet->free_supply,
            'units' => $item->trainingUnits()->transform([
                $this->unitTransformer, 'transform',
            ]),
        ];
    }
}
