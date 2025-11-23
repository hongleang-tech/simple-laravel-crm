<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Planned = 'planned';
    case InProgress = 'in_progress';
    case OnHold = 'on_hold';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Planned => 'Planned',
            self::InProgress => 'In Progress',
            self::OnHold => 'On Hold',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn (ProjectStatus $status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ])->toArray();
    }

    public static function getAllByKey(string $key): array
    {
        return array_column(self::cases(), $key);
    }
}
