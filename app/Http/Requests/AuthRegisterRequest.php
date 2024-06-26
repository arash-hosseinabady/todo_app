<?php

namespace App\Http\Requests;

class AuthRegisterRequest extends CustomRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:32',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:16',
            'password_confirm' => 'required|same:password',
        ];
    }
}
