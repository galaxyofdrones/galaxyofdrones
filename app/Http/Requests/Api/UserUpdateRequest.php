<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;

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
        $user = $this->user();

        return str_replace(':id', $user->id, [
            'email' => 'required|string|max:255|email|unique:users,email,:id',
            'password' => 'nullable|string|min:8|confirmed',
            'is_notification_enabled' => 'required|boolean',
        ]);
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $attributes = $this->onlyRulesExcept('password');

        if ($this->filled('password')) {
            $attributes['password'] = Hash::make($this->get('password'));
        }

        $this->user()->update(
            $attributes
        );
    }
}
