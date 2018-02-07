<?php

namespace Koodilab\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Koodilab\Models\Building;

trait HasBuilding
{
    /**
     * Get the current building.
     *
     * @return Building
     */
    public function currentBuilding()
    {
        if (! $this->building_id) {
            return null;
        }

        return $this->building->applyModifiers([
            'level' => $this->level,
            'defense_bonus' => $this->planet->defense_bonus,
            'construction_time_bonus' => $this->planet->construction_time_bonus,
        ]);
    }

    /**
     * Get the construction buildings.
     *
     * @return Collection|Building[]
     */
    public function constructionBuildings()
    {
        $buildings = new Collection();

        if ($this->building_id) {
            return $buildings;
        }

        $modifiers = [
            'level' => 1,
            'defense_bonus' => $this->planet->defense_bonus,
            'construction_time_bonus' => $this->planet->construction_time_bonus,
        ];

        if ($this->construction) {
            return $buildings->add(
                $this->construction->building->applyModifiers($modifiers)
            );
        }

        $buildings = Building::defaultOrder()->whereIn(
            'parent_id',
            $this->planet->findNotEmptyGrids()->pluck('building_id')
        );

        if ($this->type == static::TYPE_RESOURCE) {
            $buildings->where('type', Building::TYPE_MINER);
        } else {
            $buildings->whereNotIn('type', [
                Building::TYPE_CENTRAL, Building::TYPE_MINER,
            ]);
        }

        return $buildings->get()
            ->filter(function (Building $building) {
                if ($building->limit) {
                    $count = $this->planet->grids()
                        ->where('building_id', $building->id)
                        ->count();

                    $count += $this->planet->constructions()
                        ->where('constructions.building_id', $building->id)
                        ->count();

                    return $building->limit > $count;
                }

                return true;
            })
            ->transform(function (Building $building) use ($modifiers) {
                return $building->applyModifiers($modifiers);
            })->values();
    }

    /**
     * Get the upgrade building.
     *
     * @return Building
     */
    public function upgradeBuilding()
    {
        $building = $this->currentBuilding();

        if (! $building->hasLowerLevel()) {
            return null;
        }

        return $building->replicate()->applyModifiers([
            'level' => $this->level + 1,
            'defense_bonus' => $this->planet->defense_bonus,
            'construction_time_bonus' => $this->planet->construction_time_bonus,
        ]);
    }

    /**
     * Demolish the building.
     *
     * @param int $level
     */
    public function demolishBuilding($level = null)
    {
        $level = $level ?: $this->level;

        if (empty($level) || ! $this->building_id) {
            return;
        }

        if ($this->upgrade) {
            $this->upgrade->delete();
        }

        if ($this->training) {
            $this->training->delete();
        }

        $minLevel = $this->planet->isCapital() && $this->building->type == Building::TYPE_CENTRAL
            ? 1
            : 0;

        $this->level = max(
            $minLevel,
            $this->level - $level
        );

        if (! $this->level && $this->building->type == Building::TYPE_CENTRAL) {
            $this->planet->update([
                'user_id' => null,
            ]);
        } else {
            if (! $this->level) {
                $this->level = null;
                $this->building_id = null;
            }

            $this->save();
        }
    }
}
