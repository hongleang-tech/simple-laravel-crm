<?php

namespace Tests;

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

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

    protected function formatApiUri(string $uri): string
    {
        if (Str::startsWith($uri, ['/api', 'http://', 'https://'])) {
            return $uri;
        }

        return '/api'.Str::start($uri, '/');
    }

    public function getJson($uri, array $headers = [], $options = 0): TestResponse
    {
        return parent::getJson($this->formatApiUri($uri), $headers, $options);
    }

    public function postJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::postJson($this->formatApiUri($uri), $data, $headers, $options);
    }

    public function putJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::putJson($this->formatApiUri($uri), $data, $headers, $options);
    }

    public function patchJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::patchJson($this->formatApiUri($uri), $data, $headers, $options);
    }

    public function deleteJson($uri, array $data = [], array $headers = [], $options = 0): TestResponse
    {
        return parent::deleteJson($this->formatApiUri($uri), $data, $headers, $options);
    }
}
