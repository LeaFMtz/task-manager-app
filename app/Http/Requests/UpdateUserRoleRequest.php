<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRoleRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('task-view-all');
    }

    /**
     * Reglas de validación que aplican a la petición.
     */
    public function rules(): array
    {
        return [
            'role_name' => [
                'required', 
                'string',
                Rule::exists('roles', 'name')->where(function ($query) {
                   
                    return $query;
                }),
            ],
        ];
    }
}