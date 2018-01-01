<?php

namespace Koodilab\Models\Providers;

use Koodilab\Models\User;

class UserProvider extends Provider
{
    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'username' => $this->translator->trans('validation.attributes.username'),
            'email' => $this->translator->trans('validation.attributes.email'),
            'role' => $this->translator->trans('validation.attributes.role'),
            'is_enabled' => $this->translator->trans('validation.attributes.is_enabled'),
            'last_login' => $this->translator->trans('validation.attributes.last_login'),
            'created_at' => $this->translator->trans('validation.attributes.created_at'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $query = User::orderBy(
            $this->sort(), $this->direction()
        );

        if ($this->hasKeyword()) {
            $query->where('username', 'LIKE', $this->likeKeyword())
                ->orWhere('email', 'LIKE', $this->likeKeyword());
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     *
     * @param User $item
     */
    public function transform($item)
    {
        $roleOptions = User::roleOptions();

        return [
            'id' => $item->id,
            'gravatar' => $item->gravatar([
                's' => 64,
            ]),
            'username' => $item->username,
            'email' => $item->email,
            'role' => $this->translator->trans($roleOptions[$item->role]),
            'is_enabled' => $this->translator->trans($item->is_enabled
                ? 'messages.yes'
                : 'messages.no'),
            'last_login' => $item->last_login
                ? $item->last_login->toDateTimeString()
                : $this->translator->trans('messages.not_set'),
            'created_at' => $item->created_at->toDateTimeString(),
            'edit_url' => $this->gate->check('edit', $item)
                ? $this->url->route('admin_user_edit', $item)
                : null,
        ];
    }
}
