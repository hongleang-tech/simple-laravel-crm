<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeListOfUsers(): void
    {
        $authUser = $this->createAdminUser([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->actingAs($authUser)->get(route('users.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn($page) => $page
                    ->component('Users/Index')
                    ->has('users')
                    ->has('users', 1) // Should have exactly 1 user
                    ->where('users.0.name', 'John Doe')
                    ->where('users.0.email', $authUser->email)
            );
    }
}
