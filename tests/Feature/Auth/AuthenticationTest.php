<?php

namespace Tests\Feature\Auth;

use App\Enums\Permission;
use App\Enums\Role as RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testCanAuthenticateUsingLoginRoute(): void
    {
        $user = User::factory()->user()->create([
            'password' => 'password',
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data')
                    ->first(
                        fn(AssertableJson $json) => $json
                            ->where('name', $user->name)
                            ->where('email', $user->email)
                            ->where('roles', [RoleEnum::User->value])
                            ->where('permissions.0.name', Permission::LIST_CLIENTS->value)
                            ->etc()
                    )
            )
        ;
    }

    public function testUsersCannotAuthenticateWithInvalidPassword(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertJsonValidationErrorFor('email');
    }

    public function testUsersCanLogout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/logout');

        $response->assertNoContent();

        $response->assertSessionMissing('token');
    }
}
