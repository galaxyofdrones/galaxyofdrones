<?php

namespace Koodilab\Models\Transformers;

use Illuminate\Contracts\Translation\Translator;

class BuildingTransformer extends Transformer
{
    /**
     * The translator instance.
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
     * @param \Koodilab\Models\Building $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->translation('name'),
            'name_with_level' => $this->translator->trans('messages.building.name_with_level', [
                'name' => $item->translation('name'),
                'level' => $item->level,
            ]),
            'type' => $item->type,
            'construction_experience' => $item->construction_experience,
            'construction_cost' => $item->construction_cost,
            'construction_time' => $item->construction_time,
            'description' => $item->translation('description'),
            'defense' => $item->defense,
            'detection' => $item->detection,
            'capacity' => $item->capacity,
            'supply' => $item->supply,
            'mining_rate' => $item->mining_rate,
            'production_rate' => $item->production_rate,
            'defense_bonus' => $item->defense_bonus,
            'construction_time_bonus' => $item->construction_time_bonus,
            'trade_time_bonus' => $item->trade_time_bonus,
            'train_time_bonus' => $item->train_time_bonus,
            'has_lower_level' => $item->hasLowerLevel(),
        ];
    }
}
