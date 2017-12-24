<?php

namespace Koodilab\Http\Requests\Api;

use Koodilab\Http\Requests\Request;

class ProfileUpdateRequest extends Request
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
            'email' => 'required|max:255|email|unique:users,email,:id',
            'password' => 'nullable|min:6|max:255|confirmed',
        ]);
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $this->user()->update(
            $this->onlyRules()
        );
    }
}
