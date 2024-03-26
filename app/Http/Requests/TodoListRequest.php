<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class TodoListRequest extends CustomRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string|max:1024',
        ];
    }

    public function prepareForValidation()
    {
        if (request()->getMethod() === 'POST') {
            $this->merge([
                'user_id' => auth()->id(),
            ]);
        }
    }
}
