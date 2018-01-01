<?php

namespace Koodilab\Http\Requests\Admin;

use Koodilab\Http\Requests\Request;
use Koodilab\Models\User;

class UserStoreRequest extends Request
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
        return str_replace(':role_max', $this->user()->role, [
            'username' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9\.\-_]+$/u|unique:users',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|min:6|max:255',
            'is_enabled' => 'required|boolean',
            'role' => 'required|integer|min:0|max::role_max',
        ]);
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        User::create(
            $this->onlyRules()
        );
    }
}
