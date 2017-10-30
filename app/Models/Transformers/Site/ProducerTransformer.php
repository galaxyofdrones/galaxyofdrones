<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Transformers\Transformer;

class ProducerTransformer extends Transformer
{
    /**
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
                $item->planet->user->findResourcesOrderBySortOrder()
            ),
        ];
    }
}
