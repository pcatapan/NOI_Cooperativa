<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (UserRoleEnum::ADMIN->value === Auth::user()->role) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => 'required|string|in:' . implode(',', UserRoleEnum::getValues()),
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',

            'fiscal_code' => 'string|max:20|unique:employees|nullable',
            'phone' => 'string|max:12|nullable',
            'number_serial' => 'string|max:100|nullable',
            'inps_number' => 'string|max:50|nullable',
            'address' => 'string|max:255|nullable',
            'city' => 'string|max:255|nullable',
            'province' => 'string|max:3|nullable',
            'zip_code' => 'string|max:6|nullable',
            'notes' => 'string|nullable',
            'date_of_hiring' => 'date|nullable',
            'date_of_resignation' => 'date|nullable',
            'job' => 'string|max:255|nullable',
            'active' => 'nullable',
        ];
    }
}
