<?php

namespace Koodilab\Models\Behaviors;

trait Sortable
{
    /**
     * The "booting" method of the trait.
     */
    public static function bootSortable()
    {
        static::creating(function ($model) {
            if (! $model->hasSortOrder()) {
                $model->setSortOrder();
            }
        });
    }

    /**
     * Find all order by sort order.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllOrderBySortOrder($columns = ['*'])
    {
        return $this->sortOrderQuery()
            ->orderBy($this->sortOrderKey())
            ->get($columns);
    }

    /**
     * Update the sort order.
     */
    public function updateSortOrder()
    {
        $this->updateSortOrderByModels(
            $this->findAllOrderBySortOrder()
        );
    }

    /**
     * Update the sort order by ids.
     *
     * @param array $ids
     */
    public function updateSortOrderByIds(array $ids)
    {
        $models = $this->sortOrderQuery()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(function ($model) use ($ids) {
                return array_search($model->getKey(), $ids);
            });

        $this->updateSortOrderByModels(
            $models->values()->all()
        );
    }

    /**
     * Update the sort order by models.
     *
     * @param \Illuminate\Database\Eloquent\Collection|static[] $models
     */
    public function updateSortOrderByModels($models)
    {
        foreach ($models as $i => $model) {
            $model->update([
                $model->sortOrderKey() => $i + 1,
            ]);
        }
    }

    /**
     * Has sort order?
     *
     * @return bool
     */
    protected function hasSortOrder()
    {
        return ! empty($this->sortOrderValue());
    }

    /**
     * Get the next sort order.
     */
    protected function setSortOrder()
    {
        $this->setAttribute(
            $this->sortOrderKey(),
            $this->sortOrderQuery()->max($this->sortOrderKey()) + 1
        );
    }

    /**
     * Get the sort order query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function sortOrderQuery()
    {
        return $this->newQuery();
    }

    /**
     * Get the sort order value.
     *
     * @return int
     */
    protected function sortOrderValue()
    {
        return $this->getAttribute($this->sortOrderKey());
    }

    /**
     * Get the sort order key.
     *
     * @return string
     */
    protected function sortOrderKey()
    {
        return 'sort_order';
    }
}
