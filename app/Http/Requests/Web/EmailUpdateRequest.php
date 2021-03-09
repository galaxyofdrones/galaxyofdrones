<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class EmailUpdateRequest extends Request
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
