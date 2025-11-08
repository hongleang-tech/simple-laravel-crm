<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Project::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'numeric', Rule::exists('clients', 'id')],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required', 'string', Rule::in(ProjectStatus::getAllByKey('value'))],
            'start_date' => ['required', 'date_format:d/m/Y', 'before:end_date'],
            'end_date' => ['required', 'date_format:d/m/Y', 'after:start_date'],
            'budget' => ['required', 'numeric', 'min:1'],
            'address_1' => ['required', 'string', 'max:100'],
            'address_2' => ['nullable', 'string', 'max:100'],
            'suburb' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:4'],
            'country' => ['required', 'string', Rule::in(['Australia', 'New Zealand'])],
        ];
    }
}
