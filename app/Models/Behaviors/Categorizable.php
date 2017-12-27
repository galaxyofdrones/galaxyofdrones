<?php

namespace Koodilab\Models\Behaviors;

use Kalnoy\Nestedset\NodeTrait;

trait Categorizable
{
    use NodeTrait;

    /**
     * Find all roots.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllRoots($columns = ['*'])
    {
        return $this->newScopedQuery()
            ->whereIsRoot()
            ->defaultOrder()
            ->get($columns);
    }

    /**
     * Find all with depth.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllWithDepth($columns = ['*'])
    {
        return $this->newScopedQuery()
            ->withDepth()
            ->defaultOrder()
            ->get($columns);
    }

    /**
     * Find all with siblings.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findSelfAndSiblings($columns = ['*'])
    {
        return $this->newScopedQuery()
            ->where($this->getParentIdName(), $this->getParentId())
            ->defaultOrder()
            ->get($columns);
    }

    /**
     * Find by parent id and id.
     *
     * @param int $id
     *
     * @return mixed|static
     */
    public function findByParentIdAndId($id)
    {
        return $this->newScopedQuery()
            ->where($this->getParentIdName(), $this->getParentId())
            ->where('id', $id)
            ->first();
    }

    /**
     * Find all with depth except self and descendants.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAllWithDepthExceptSelfAndDescendants($columns = ['*'])
    {
        $except = $this->descendants()->pluck('id');
        $except[] = $this->getKey();

        return $this->newScopedQuery()
            ->whereNotIn('id', $except)
            ->withDepth()
            ->defaultOrder()
            ->get($columns);
    }
}
