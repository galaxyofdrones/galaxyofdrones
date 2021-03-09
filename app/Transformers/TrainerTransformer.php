<?php

namespace App\Transformers;

class TrainerTransformer extends Transformer
{
    /**
     * The unit transformer instance.
     *
     * @var UnitTransformer
     */
    protected $unitTransformer;

    /**
     * Constructor.
     */
    public function __construct(UnitTransformer $unitTransformer)
    {
        $this->unitTransformer = $unitTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Grid $item
     */
    public function transform($item)
    {
        $training = $item->training;

        return [
            'remaining' => $training
                ? $training->remaining
                : null,
            'quantity' => $training
                ? $training->quantity
                : null,
            'units' => $this->unitTransformer->transformCollection(
                $item->trainingUnits()
            ),
        ];
    }
}
