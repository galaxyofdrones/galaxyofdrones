<?php

namespace App\Transformers;

class ScoutTransformer extends Transformer
{
    /**
     * The movement transformer instance.
     *
     * @var MovementScoutTransformer
     */
    protected $movementTransformer;

    /**
     * Constructor.
     */
    public function __construct(MovementScoutTransformer $movementTransformer)
    {
        $this->movementTransformer = $movementTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Grid $item
     */
    public function transform($item)
    {
        return [
            'incoming_movements' => $this->movementTransformer->transformCollection(
                $item->planet->findIncomingMovements()
            ),
            'outgoing_movements' => $this->movementTransformer->transformCollection(
                $item->planet->findOutgoingMovements()
            ),
        ];
    }
}
