<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends StoreClientRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('client'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('clients', 'email')->ignore($this->route('client')),
            ],
            'company' => [
                'required',
                'string',
                'max:100',
                Rule::unique('clients', 'company')->ignore($this->route('client')),
            ],
        ];
    }
}
