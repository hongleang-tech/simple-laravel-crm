<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('user'));
    }

    public function rules(): array
    {
        return [
            ...parent::rules(),
            'email' => ['required', 'string', 'email'],
        ];
    }
}
