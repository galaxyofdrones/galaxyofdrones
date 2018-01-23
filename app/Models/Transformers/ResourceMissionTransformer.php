<?php

namespace Koodilab\Models\Transformers;

use Illuminate\Contracts\Auth\Factory as Auth;

class ResourceMissionTransformer extends Transformer
{
    /**
     * The auth instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Resource $item
     */
    public function transform($item)
    {
        /** @var \Koodilab\Models\User $user */
        $user = $this->auth->guard()->user();

        /** @var \Koodilab\Models\Resource $resource */
        $resource = $user->resources->firstWhere('id', $item->id);

        return [
            'id' => $item->id,
            'name' => $item->translation('name'),
            'description' => $item->translation('description'),
            'quantity' => $resource
                ? $resource->pivot->quantity
                : 0,
        ];
    }
}
