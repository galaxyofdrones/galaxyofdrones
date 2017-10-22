<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\Transformer;

class TransmuteTransformer extends Transformer
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
     * @param Grid $item
     */
    public function transform($item)
    {
        return [
            'resources' => $item->planet->user->findResourcesOrderBySortOrder()->transform([
                $this->resourceTransfomer, 'transform',
            ]),
        ];
    }
}
