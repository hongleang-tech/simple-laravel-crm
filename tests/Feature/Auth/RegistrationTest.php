<?php

namespace Tests\Feature\Auth;

use App\Enums\Role as RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testNewUsersCanRegister(): void
    {
        $response = $this->postJson('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone_number' => '0443998889',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data')
                    ->first(
                        fn(AssertableJson $json) => $json
                            ->where('name', 'Test User')
                            ->where('email', 'test@example.com')
                            ->where('roles', [RoleEnum::User->value])
                            ->etc()
                    )
            )
        ;
    }
}
