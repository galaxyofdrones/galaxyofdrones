<?php

namespace App\Transformers;

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
     */
    public function __construct(BuildingTransformer $buildingTransformer)
    {
        $this->buildingTransformer = $buildingTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \App\Models\Grid $item
     */
    public function transform($item)
    {
        return [
            'remaining' => $item->construction
                ? $item->construction->remaining
                : null,
            'buildings' => $this->buildingTransformer->transformCollection(
                $item->constructionBuildings()
            ),
        ];
    }
}
