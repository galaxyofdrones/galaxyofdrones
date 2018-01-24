<?php

namespace Koodilab\Models\Transformers;

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
     *
     * @param ResourceTransformer $resourceTransfomer
     */
    public function __construct(ResourceTransformer $resourceTransfomer)
    {
        $this->resourceTransfomer = $resourceTransfomer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Grid $item
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
