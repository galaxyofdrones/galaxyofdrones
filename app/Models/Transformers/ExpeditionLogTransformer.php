<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\ExpeditionLog;
use Koodilab\Models\Unit;

class ExpeditionLogTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param ExpeditionLog $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'star' => $item->star->name,
            'solarion' => $item->solarion,
            'experience' => $item->experience,
            'created_at' => $item->created_at->toDateTimeString(),
            'units' => $this->units($item),
        ];
    }

    /**
     * Get the units.
     *
     * @param ExpeditionLog $expeditionLog
     *
     * @return array
     */
    protected function units(ExpeditionLog $expeditionLog)
    {
        return $expeditionLog->units->transform(function (Unit $unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->translation('name'),
                'description' => $unit->translation('description'),
                'quantity' => $unit->pivot->quantity,
            ];
        });
    }
}
