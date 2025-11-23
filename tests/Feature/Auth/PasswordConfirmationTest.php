<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function testPasswordCanBeConfirmed(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->postJson('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertJsonMissingValidationErrors();

        $response->assertNoContent();
    }

    public function testPasswordIsNotConfirmedWithInvalidPassword(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertJsonValidationErrorFor('password');
    }
}
