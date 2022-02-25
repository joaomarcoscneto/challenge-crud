<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        User::factory()->count(2)->create();

        $this->getJson('/users')
            ->assertStatus(200)
            ->assertJsonStructure([
                'users' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at',
                        'city',
                        'state'
                    ]
                ]
            ]);

        $this->assertDatabaseCount('users', 2);
    }

    public function testStore()
    {
        $user = User::factory()->make();

        $userArray = $user->toArray();
        $userArray['password'] = '123456';
        $userArray['password_confirmation'] = '123456';

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'name',
                    'email',
                    'city',
                    'state',
                    'created_at',
                    'updated_at',
                    'id'
                ]
            ]);

        $this->assertDatabaseCount('users', 1);
    }

    public function testStoreValidationRequired()
    {
        $user = User::factory()->make([
            'name' => '',
            'email' => '',
            'city' => '',
            'state' => '',
        ]);

        $userArray = $user->toArray();
        $userArray['password'] = '';
        $userArray['password_confirmation'] = '';

        $response = $this->postJson('/users', $userArray);

        $response->assertInvalid(['name', 'email', 'city', 'state', 'password']);
    }

    public function testStoreValidationTypeFields()
    {
        $user = User::factory()->make([
            'name' => 1010,
            'email' => 1010,
            'city' => 1010,
            'state' => 1010,
        ]);

        $userArray = $user->toArray();
        $userArray['password'] = 010101;
        $userArray['password_confirmation'] = 010101;

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(422)
            ->assertInvalid(['name', 'email', 'city', 'state', 'password']);
    }

    public function testStoreValidationEmail()
    {
        $user = User::factory()->make([
            'email' => 'sdafsdf'
        ]);

        $userArray = $user->toArray();
        $userArray['password'] = '123456';
        $userArray['password_confirmation'] = '123456';

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(422)
            ->assertInvalid(['email']);
    }

    public function testStoreValidationMinLength()
    {
        $user = User::factory()->make();

        $userArray = $user->toArray();
        $userArray['password'] = '0101';
        $userArray['password_confirmation'] = '0101';

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(422)
            ->assertInvalid(['password']);
    }

    public function testStoreValidationWithoutPasswordConfirmation()
    {
        $user = User::factory()->make();

        $userArray = $user->toArray();
        $userArray['password'] = '123456';

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(422)
            ->assertInvalid(['password']);
    }

    public function testStoreValidationWithDiferentPasswordConfirmation()
    {
        $user = User::factory()->make();

        $userArray = $user->toArray();
        $userArray['password'] = '123456';
        $userArray['password_confirmation'] = '010101';

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(422)
            ->assertInvalid(['password']);
    }

    public function testStoreUniqueEmailValidation()
    {
        $user1 = User::factory()->create();

        $user2 = User::factory()->make([
            'email' => $user1->email
        ]);

        $userArray = $user2->toArray();
        $userArray['password'] = '123456';
        $userArray['password_confirmation'] = '010101';

        $response = $this->postJson('/users', $userArray);

        $response->assertStatus(422)
            ->assertInvalid(['email']);
    }
}
