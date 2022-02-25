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

        $this->getJson('/api/users')
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

        $userData = $user->toArray();
        $userData['password'] = '123456';
        $userData['password_confirmation'] = '123456';

        $this->postJson('/api/users', $userData)
            ->assertStatus(201)
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

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
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

        $userData = $user->toArray();
        $userData['password'] = '';
        $userData['password_confirmation'] = '';

        $this->postJson('/api/users', $userData)
            ->assertInvalid(['name', 'email', 'city', 'state', 'password']);
    }

    public function testStoreValidationTypeFields()
    {
        $user = User::factory()->make([
            'name' => 1010,
            'email' => 1010,
            'city' => 1010,
            'state' => 1010,
        ]);

        $userData = $user->toArray();
        $userData['password'] = 010101;
        $userData['password_confirmation'] = 010101;

        $this->postJson('/api/users', $userData)
            ->assertStatus(422)
            ->assertInvalid(['name', 'email', 'city', 'state', 'password']);
    }

    public function testStoreValidationEmail()
    {
        $user = User::factory()->make([
            'email' => 'sdafsdf'
        ]);

        $userData = $user->toArray();
        $userData['password'] = '123456';
        $userData['password_confirmation'] = '123456';

        $this->postJson('/api/users', $userData)
            ->assertStatus(422)
            ->assertInvalid(['email']);
    }

    public function testStoreValidationMinLength()
    {
        $user = User::factory()->make();

        $userData = $user->toArray();
        $userData['password'] = '0101';
        $userData['password_confirmation'] = '0101';

        $this->postJson('/api/users', $userData)
            ->assertStatus(422)
            ->assertInvalid(['password']);
    }

    public function testStoreValidationWithoutPasswordConfirmation()
    {
        $user = User::factory()->make();

        $userData = $user->toArray();
        $userData['password'] = '123456';

        $this->postJson('/api/users', $userData)
            ->assertStatus(422)
            ->assertInvalid(['password']);
    }

    public function testStoreValidationWithDiferentPasswordConfirmation()
    {
        $user = User::factory()->make();

        $userData = $user->toArray();
        $userData['password'] = '123456';
        $userData['password_confirmation'] = '010101';

        $this->postJson('/api/users', $userData)
            ->assertStatus(422)
            ->assertInvalid(['password']);
    }

    public function testStoreUniqueEmailValidation()
    {
        $user1 = User::factory()->create();

        $user2 = User::factory()->make([
            'email' => $user1->email
        ]);

        $userData = $user2->toArray();
        $userData['password'] = '123456';
        $userData['password_confirmation'] = '010101';

        $this->postJson('/api/users', $userData)
            ->assertStatus(422)
            ->assertInvalid(['email']);
    }

    public function testShow()
    {
        $user = User::factory()->create();

        $this->getJson('api/users/' . $user->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'city',
                    'state'
                ]
            ]);
    }

    public function testShowWhenNotExistsUserId()
    {
        User::factory()->create();

        $this->getJson('/users/999')->assertStatus(404);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();

        $userData = $user->toArray();
        $userData['name'] = "Name User Update";
        $userData['email'] = "emailupdate@test.com";

        $this->putJson('api/users/' . $user->id, $userData)
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'name' => "Name User Update",
            'email' => "emailupdate@test.com"
        ]);
    }

    public function testUpdateWhenNotExistsUserId()
    {
        $user = User::factory()->create();

        $userData = $user->toArray();
        $userData['name'] = "Name User Update";
        $userData['email'] = "emailupdate@test.com";

        $this->putJson('api/users/' . '99999', $userData)
            ->assertStatus(404);
    }

    public function testUpdateValidations()
    {
        $user = User::factory()->create();

        $userData['name'] = '';
        $userData['email'] = '';
        $userData['city'] = '';
        $userData['state'] = '';
        $userData['password'] = '';
        $userData['password_confirmation'] = '';

        $this->putJson('api/users/' . $user->id, $userData)
            ->assertInvalid(['name', 'email', 'city', 'state', 'password']);
    }

    public function testUpdateUniqueEmailValidation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1Data = $user1->toArray();
        $user1Data['name'] = 'Name Update';
        $user1Data['email'] = $user2->email;

        $this->putJson('api/users/' . $user1->id, $user1Data)
            ->assertStatus(422)
            ->assertInvalid(['email']);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->deleteJson('api/users/' . $user->id)
            ->assertOk()
            ->assertJson(["delete" => true]);

        $this->assertDatabaseCount('users', 0);
    }

    public function testDestroyWhenNotExistsUserId()
    {
        $user = User::factory()->create();

        $this->deleteJson('/api/users/999')
            ->assertStatus(404);
    }
}
