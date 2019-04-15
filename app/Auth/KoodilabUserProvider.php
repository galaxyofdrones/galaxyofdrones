<?php

namespace Koodilab\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class KoodilabUserProvider extends EloquentUserProvider
{
    /**
     * {@inheritdoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }

        $query = $this->newModelQuery();

        if (! empty($credentials['username_or_email'])) {
            $query->where(function (Builder $query) use ($credentials) {
                $query->where('username', $credentials['username_or_email'])
                    ->orWhere('email', $credentials['username_or_email']);
            });
        }

        foreach ($credentials as $key => $value) {
            if (Str::contains($key, ['username_or_email', 'is_enabled', 'password'])) {
                continue;
            }

            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * {@inheritdoc}
     */
    protected function newModelQuery($model = null)
    {
        $query = ($model ?: $this->createModel())->newQuery();

        $query->where('is_enabled', true);

        return $query;
    }
}
