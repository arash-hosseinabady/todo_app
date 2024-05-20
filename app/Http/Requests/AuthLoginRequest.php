<?php

namespace App\Http\Requests;

class AuthLoginRequest extends CustomRequest
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
            'username' => 'required_without:email',
            'email' => 'required_without:username',
            'password' => 'required|min:8|max:16',
        ];
    }
}
