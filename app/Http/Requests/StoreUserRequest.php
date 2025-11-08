<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'alpha', 'string', 'max:100'],
            'last_name' => ['required', 'alpha', 'string', 'max:100'],
            'phone_number' => ['required', 'string', new PhoneNumber()],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'address_1' => ['required', 'string', 'max:100'],
            'address_2' => ['nullable', 'string', 'max:100'],
            'suburb' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:4'],
            'country' => ['required', 'string', Rule::in(['Australia', 'New Zealand'])],
        ];
    }
}
