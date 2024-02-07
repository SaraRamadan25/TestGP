<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_anyone_can_access_the_homepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_that_anyone_can_register()
    {
        $this->post('/api/register' , [
            'username' => 'Sara',
            'email' => 'sara@test.com',
            'password' => 'password',
            'confirm_password' => 'password'
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'Sara',
            'email' => 'sara@test.com']);
    }
    public function test_that_only_users_with_valid_credentials_can_login()
    {
        $user = User::factory()->create([
            'email' => 'sara@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('api/login', [
            'email' => 'sara@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'username',
                'email',
            ],
            'token',
        ]);
    }
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response = $this->get('/api/logout');

        $response->assertStatus(200);

        $this->assertCount(0, $user->tokens);
    }
    public function test_user_can_get_own_info_when_authenticated()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getUserInfo($user->username);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
        ]);
    }
    public function test_user_cannot_get_other_user_info_when_not_authenticated()
    {
        $user = User::factory()->create();

        $this->withoutMiddleware();

        $response = $this->getUserInfo($user->username);

        $response->assertStatus(401);

        $response->assertJson([
            'error' => 'Unauthorized',
        ]);
    }
    private function getUserInfo($username): TestResponse
    {
        return $this->get('/api/user/' . $username);
    }

}




