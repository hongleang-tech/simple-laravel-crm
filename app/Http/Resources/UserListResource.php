<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roleNames,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'address' => $this->whenLoaded('address', fn () => $this->address?->fullAddress),
        ];
    }
}
