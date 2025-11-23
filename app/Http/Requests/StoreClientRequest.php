<?php

namespace App\Http\Requests;

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Client::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'unique:clients,email'],
            'phone' => ['required', 'string', new PhoneNumber],
            'company' => ['required', 'string', 'max:100', 'unique:clients,company'],
            'status' => ['required', 'string', Rule::in(ClientStatus::cases())],
            'address_1' => ['required', 'string', 'max:100'],
            'address_2' => ['nullable', 'string', 'max:100'],
            'suburb' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:4'],
            'country' => ['required', 'string', Rule::in(['Australia', 'New Zealand'])],
        ];
    }
}
