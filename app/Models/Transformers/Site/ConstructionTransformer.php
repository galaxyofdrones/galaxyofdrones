<?php

namespace Koodilab\Models\Transformers\Site;

use Illuminate\Contracts\Translation\Translator;
use Koodilab\Models\Grid;
use Koodilab\Models\Transformers\Transformer;

class ConstructionTransformer extends Transformer
{
    /**
     * The translator.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
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
            'buildings' => $this->buildings($item),
        ];
    }

    /**
     * Get the buildings.
     *
     * @param Grid $grid
     *
     * @return array
     */
    protected function buildings(Grid $grid)
    {
        $buildings = [];

        foreach ($grid->constructionBuildings() as $building) {
            $buildings[] = [
                'id' => $building->id,
                'name' => $building->translation('name'),
                'name_with_level' => $this->translator->trans('messages.building.name_with_level', [
                    'name' => $building->translation('name'),
                    'level' => $building->level,
                ]),
                'type' => $building->type,
                'construction_experience' => $building->construction_experience,
                'construction_cost' => $building->construction_cost,
                'construction_time' => $building->construction_time,
                'description' => $building->translation('description'),
                'defense' => $building->defense,
                'detection' => $building->detection,
                'capacity' => $building->capacity,
                'supply' => $building->supply,
                'mining_rate' => $building->mining_rate,
                'production_rate' => $building->production_rate,
                'mission_time' => $building->mission_time,
                'defense_bonus' => $building->defense_bonus,
                'construction_time_bonus' => $building->construction_time_bonus,
                'train_time_bonus' => $building->train_time_bonus,
            ];
        }

        return $buildings;
    }
}
