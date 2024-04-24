<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (request()->getMethod() === 'POST') {
            return [
                'name' => 'required|unique:' . config('permission.table_names')['roles']
            ];
        }

        return [
            'name' => ['required', function ($attribute, $value, $fail) {
                $role = Role::where('id', '<>', request()->route()->parameters()['id'])
                    ->where('name', request()->get('name'))
                    ->first();

                if ($role) {
                    $fail(__('validation.unique'));
                }
            }]
        ];
    }
}
