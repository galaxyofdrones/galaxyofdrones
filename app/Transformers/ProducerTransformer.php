<?php

namespace App\Transformers;

class ProducerTransformer extends Transformer
{
    /**
     * The resource transformer instance.
     *
     * @var ResourceTransformer
     */
    protected $resourceTransfomer;

    /**
     * Constructor.
     */
    public function __construct(ResourceTransformer $resourceTransfomer)
    {
        $this->resourceTransfomer = $resourceTransfomer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Grid $item
     */
    public function transform($item)
    {
        return [
            'resources' => $this->resourceTransfomer->transformCollection(
                $item->planet->user->findResearchedResources()
            ),
        ];
    }
}
