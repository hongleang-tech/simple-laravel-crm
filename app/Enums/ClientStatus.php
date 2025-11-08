<?php

namespace App\Enums;

enum ClientStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Prospect = 'prospect';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Prospect => 'Prospect',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn(ClientStatus $status) => [
            'value' => $status->value,
            'label' => $status->label()
        ])->toArray();
    }

    public static function getAllByKey(string $key): array
    {
        return array_column(self::cases(), $key);
    }
}
