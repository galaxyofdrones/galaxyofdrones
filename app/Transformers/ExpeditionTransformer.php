<?php

namespace App\Transformers;

use App\Models\Expedition;
use App\Models\Unit;

class ExpeditionTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param Expedition $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'star' => $item->star->name,
            'solarion' => $item->solarion,
            'experience' => $item->experience,
            'remaining' => $item->remaining,
            'units' => $this->units($item),
        ];
    }

    /**
     * Get the units.
     *
     * @return array
     */
    protected function units(Expedition $expedition)
    {
        return $expedition->units->transform(function (Unit $unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->translation('name'),
                'description' => $unit->translation('description'),
                'quantity' => $unit->pivot->quantity,
            ];
        });
    }
}
