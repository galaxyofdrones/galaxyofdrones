<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\Star;
use Koodilab\Models\User;

trait FindByStarAndUser
{
    /**
     * Find by star and user.
     *
     * @param Star  $star
     * @param User  $user
     * @param array $columns
     *
     * @return static
     */
    public static function findByStarAndUser(Star $star, User $user, $columns = ['*'])
    {
        $model = static::where('star_id', $star->id)
            ->where('user_id', $user->id)
            ->first($columns);

        if ($model) {
            $model->setRelations([
                'star' => $star,
                'user' => $user,
            ]);
        }

        return $model;
    }
}
