<?php

namespace Tests;

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setup(): void
    {
        parent::setup();

        $this->seed(RoleSeeder::class);
    }

    public function createUser(array $attributes = []): User
    {
        return once(function () use ($attributes) {
            $user = User::factory()->create($attributes);

            $user->assignRole(RoleEnum::User);

            return $user;
        });
    }

    public function createAdminUser(array $attributes = []): User
    {
        return once(function () use ($attributes) {
            $user = User::factory()->create($attributes);

            $user->assignRole(RoleEnum::Admin);

            return $user;
        });
    }
}
