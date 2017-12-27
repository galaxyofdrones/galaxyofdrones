<?php

namespace Koodilab\Models\Queries;

use Koodilab\Models\User;

trait FindResearchByUser
{
    /**
     * Find research by user.
     *
     * @param User  $user
     * @param array $columns
     *
     * @return \Koodilab\Models\Research
     */
    public function findResearchByUser(User $user, $columns = ['*'])
    {
        /** @var \Koodilab\Models\Research $research */
        $research = $this->researches()
            ->where('user_id', $user->id)
            ->first($columns);

        if ($research) {
            $research->setRelation('user', $user);
        }

        return $research;
    }
}
