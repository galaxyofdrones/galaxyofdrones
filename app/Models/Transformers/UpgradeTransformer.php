<?php

namespace Koodilab\Models\Transformers;

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
     * @param \Koodilab\Models\Grid $item
     */
    public function transform($item)
    {
        $building = $item->currentBuilding();
        $upgrade = $item->upgradeBuilding();

        return [
            'hasTraining' => ! empty($item->training),
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
