<?php

namespace App\Transformers;

class UpgradeTransformer extends Transformer
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
        $building = $item->currentBuilding();
        $upgrade = $item->upgradeBuilding();

        return [
            'has_training' => ! empty($item->training),
            'remaining' => $item->upgrade
                ? $item->upgrade->remaining
                : null,
            'building' => $building
                ? $this->buildingTransformer->transform($building)
                : null,
            'upgrade' => $upgrade
                ? $this->buildingTransformer->transform($upgrade)
                : null,
        ];
    }
}
