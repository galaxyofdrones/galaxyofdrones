<?php

namespace Koodilab\Models\Transformers;

class ScoutTransformer extends Transformer
{
    /**
     * The movement transformer instance.
     *
     * @var MovementTransformer
     */
    protected $movementTransformer;

    /**
     * Constructor.
     *
     * @param MovementTransformer $movementTransformer
     */
    public function __construct(MovementTransformer $movementTransformer)
    {
        $this->movementTransformer = $movementTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Grid $item
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
