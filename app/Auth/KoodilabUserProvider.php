<?php

namespace Koodilab\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class KoodilabUserProvider extends EloquentUserProvider
{
    /**
     * {@inheritdoc}
     */
    public function retrieveById($identifier)
    {
        /** @var \Koodilab\Models\User $model */
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getKeyName(), $identifier)
            ->where('is_enabled', true)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByToken($identifier, $token)
    {
        /** @var \Koodilab\Models\User $model */
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->where($model->getRememberTokenName(), $token)
            ->where('is_enabled', true)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        $query = $this->createModel()->newQuery();

        if (!empty($credentials['username_or_email'])) {
            $query->where(function (Builder $query) use ($credentials) {
                $query->where('username', $credentials['username_or_email'])
                    ->orWhere('email', $credentials['username_or_email']);
            });
        }

        $query->where('is_enabled', true);

        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, ['username_or_email', 'is_enabled', 'password', 'ability'])) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\User $user
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        if (!$this->hasher->check($plain, $user->getAuthPassword())) {
            return false;
        }

        if (!empty($credentials['ability']) && $user->cannot($credentials['ability'])) {
            return false;
        }

        return true;
    }
}
