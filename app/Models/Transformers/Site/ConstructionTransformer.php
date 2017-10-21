<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\Transformer;

class ConstructionTransformer extends Transformer
{
    /**
     * The building transformer instance.
     *
     * @var BuildingTransformer
     */
    protected $buildingTransformer;

    /**
     * Constructor.
     *
     * @param BuildingTransformer $buildingTransformer
     */
    public function __construct(BuildingTransformer $buildingTransformer)
    {
        $this->buildingTransformer = $buildingTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param Grid $item
     */
    public function transform($item)
    {
        return [
            'remaining' => $item->construction
                ? $item->construction->remaining
                : null,
            'buildings' => $item->constructionBuildings()->transform([
                $this->buildingTransformer, 'transform',
            ]),
        ];
    }
}
