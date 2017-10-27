<?php

namespace Koodilab\Models\Transformers\Site;

use Koodilab\Models\Transformers\Transformer;

class UpgradeTransformer extends Transformer
{
    /**
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
