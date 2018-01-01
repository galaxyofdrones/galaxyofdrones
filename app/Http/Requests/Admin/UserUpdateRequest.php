<?php

namespace Koodilab\Http\Requests\Admin;

use Koodilab\Http\Requests\Request;

class UserUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user');

        return str_replace([':id', ':role_max'], [$user->id, $this->user()->role], [
            'username' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9\.\-_]+$/u|unique:users,username,:id',
            'email' => 'required|max:255|email|unique:users,email,:id',
            'password' => 'nullable|min:6|max:255',
            'is_enabled' => 'required|boolean',
            'role' => 'required|integer|min:0|max::role_max',
        ]);
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $this->route('user')->update(
            $this->onlyRules()
        );
    }
}
