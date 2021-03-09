<?php

namespace App\Models\Queries;

use App\Models\User;

trait FindResearchByUser
{
    /**
     * Find research by user.
     *
     * @param array $columns
     *
     * @return \App\Models\Research
     */
    public function findResearchByUser(User $user, $columns = ['*'])
    {
        /** @var \App\Models\Research $research */
        $research = $this->researches()
            ->where('user_id', $user->id)
            ->first($columns);

        if ($research) {
            $research->setRelation('user', $user);
        }

        return $research;
    }
}
