<?php

namespace App\Enums;

enum Permission: string
{
    // Clients
    case LIST_CLIENTS = 'list clients';
    case READ_CLIENT = 'read client';
    case WRITE_CLIENT = 'write client';
    case DELETE_CLIENT = 'delete client';

    // Projects
    case LIST_PROJECTS = 'list projects';
    case READ_PROJECT = 'read project';
    case WRITE_PROJECT = 'write project';
    case DELETE_PROJECT = 'delete project';

    // Tasks
    case LIST_TASKS = 'list tasks';
    case READ_TASK = 'read task';
    case WRITE_TASK = 'write task';
    case DELETE_TASK = 'delete task';

    public static function all(): array
    {
        return array_column(Permission::cases(), 'value');
    }

    public static function forManager(): array
    {
        return [
            self::LIST_CLIENTS->value,
            self::READ_CLIENT->value,
            self::WRITE_CLIENT->value,
            self::DELETE_CLIENT->value,
            self::LIST_PROJECTS->value,
            self::READ_PROJECT->value,
            self::WRITE_PROJECT->value,
            self::DELETE_PROJECT->value,
            self::LIST_TASKS->value,
            self::WRITE_TASK->value,
            self::DELETE_TASK->value,
        ];
    }

    public static function forUser(): array
    {
        return [
            self::LIST_CLIENTS->value,
            self::READ_CLIENT->value,
            self::LIST_PROJECTS->value,
            self::READ_PROJECT->value,
            self::LIST_TASKS->value,
            self::READ_TASK->value,
            self::LIST_TASKS->value,
            self::READ_TASK->value
        ];
    }
}
