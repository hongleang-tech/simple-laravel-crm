<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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

        $response = $this->actingAs($authUser)->getJson('/users');

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', 1, fn(AssertableJson $json) => $json
                    ->where('name', $authUser->name)
                    ->where('email', $authUser->email)
                    ->where('phone_number', $authUser->phone_number)
                    ->where('roles', $authUser->roleNames)
                    ->where('address', $authUser->address->fullAddress))
                ->has('meta')
                ->has('links'))
        ;
    }

    public function testCanSeeUser(): void
    {
        $authUser = $this->createAdminUser([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($authUser)->getJson("/users/{$user->id}");

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', fn(AssertableJson $json) => $json
                    ->where('id', $user->id)
                    ->where('first_name', $user->first_name)
                    ->where('last_name', $user->last_name)
                    ->where('email', $user->email)
                    ->where('phone_number', $user->phone_number)
                    ->where('address.address_1', $user->address->address_1)
                    ->where('address.address_2', $user->address->address_2)
                    ->where('address.suburb', $user->address->suburb)
                    ->where('address.state', $user->address->state)
                    ->where('address.postcode', $user->address->postcode)
                    ->where('address.country', $user->address->country)));
    }

    public function testCanCreateUser(): void
    {
        $authUser = $this->createAdminUser();

        $userData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '0412345678',
        ];

        $addressData = [
            'address_1' => '123 Main St',
            'address_2' => 'Apt 4B',
            'suburb' => 'Melbourne',
            'state' => 'VIC',
            'postcode' => '3000',
            'country' => 'Australia',
        ];

        $response = $this->actingAs($authUser)->postJson('/users', [
            ...$userData,
            ...$addressData,
        ]);

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has(
                    'data',
                    fn(AssertableJson $json) => $json
                        ->where('first_name', $userData['first_name'])
                        ->where('last_name', $userData['last_name'])
                        ->where('email', $userData['email'])
                        ->where('phone_number', $userData['phone_number'])
                        ->where('address.address_1', $addressData['address_1'])
                        ->where('address.suburb', $addressData['suburb'])
                ));

        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
        $this->assertDatabaseHas('addresses', ['suburb' => $addressData['suburb']]);
    }

    public function testCanUpdateUser(): void
    {
        $authUser = $this->createAdminUser();
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $updatedData = [
            'first_name' => 'Jonathan',
            'last_name' => 'Smith',
            'phone_number' => '0499999999',
        ];

        $response = $this->actingAs($authUser)->patchJson("/users/{$user->id}", $updatedData);

        $response->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has(
                        'data',
                        fn(AssertableJson $json) => $json
                            ->where('id', $user->id)
                            ->where('first_name', $updatedData['first_name'])
                            ->where('last_name', $updatedData['last_name'])
                            ->where('phone_number', $updatedData['phone_number'])
                    )
            )
        ;

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => $updatedData['first_name'],
        ]);
    }

    public function testCanUpdateUserAddress(): void
    {
        $authUser = $this->createAdminUser();
        $user = User::factory()->create();

        $updatedAddress = [
            'address_1' => '456 New St',
            'address_2' => 'Suite 100',
            'suburb' => 'Sydney',
            'state' => 'NSW',
            'postcode' => '2000',
            'country' => 'Australia',
        ];

        $response = $this->actingAs($authUser)->patchJson("/users/{$user->id}", $updatedAddress);

        $response->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has(
                        'data',
                        fn(AssertableJson $json) => $json
                            ->where('address.address_1', $updatedAddress['address_1'])
                            ->where('address.suburb', $updatedAddress['suburb'])
                            ->where('address.state', $updatedAddress['state'])
                    )
            )
        ;

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'suburb' => $updatedAddress['suburb'],
        ]);
    }

    public function testCanDeleteUser(): void
    {
        $authUser = $this->createAdminUser();
        $user = User::factory()->create();

        $response = $this->actingAs($authUser)->deleteJson("/users/{$user->id}");

        $response->assertSuccessful();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('addresses', ['user_id' => $user->id]);
    }

    public function testCannotCreateUserWithoutRequiredFields(): void
    {
        $authUser = $this->createAdminUser();

        $response = $this->actingAs($authUser)->postJson('/users', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone_number'])
        ;
    }

    public function testCannotCreateUserWithInvalidEmail(): void
    {
        $authUser = $this->createAdminUser();

        $response = $this->actingAs($authUser)->postJson('/users', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
            'phone_number' => '0412345678',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('email')
        ;
    }

    public function testCannotAccessUsersWithoutAuthentication(): void
    {
        $response = $this->getJson('/users');

        $response->assertUnauthorized();
    }

    public function testCannotAccessUserDetailWithoutAuthentication(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/users/{$user->id}");

        $response->assertUnauthorized();
    }

    public function testCannotCreateUserWithoutAuthorization(): void
    {
        $regularUser = User::factory()->create();

        $response = $this->actingAs($regularUser)->postJson('/users', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '0412345678',
        ]);

        $response->assertForbidden();
    }
}
