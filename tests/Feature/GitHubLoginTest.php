<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Mockery;

class GitHubLoginTest extends TestCase
{
    use RefreshDatabase;

    protected $socialite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockSocialiteFacade();
    }
    protected function tearDown(): void
    {
        Mockery::close();
    }
    protected function mockSocialiteFacade(): void
    {
        $this->socialite = Mockery::mock('alias:Laravel\Socialite\Facades\Socialite');
    }
    public function testRedirectToGitHub()
    {
        $this->withoutExceptionHandling();

        $this->socialite->shouldReceive('driver->stateless->redirect->getTargetUrl')
            ->andReturn('https://github.com/login/oauth/authorize?client_id=83491591d8924ec58ce4');

        $response = $this->get('api/auth/redirect/github');

        $response->assertStatus(200)
            ->assertJson(['redirect_uri' => 'https://github.com/login/oauth/authorize?client_id=83491591d8924ec58ce4']);
    }
    public function testHandleGitHubCallback()
    {
        $mockedUser = [
            'id' => 123,
            'username' => 'John Doe',
            'email' => 'sara@test.com',
        ];

        $socialiteUser = Mockery::mock('Laravel\Socialite\Two\User');
        $socialiteUser->shouldReceive('getEmail')->andReturn($mockedUser['email']);
        $socialiteUser->shouldReceive('getNickname')->andReturn($mockedUser['username']);

        $this->socialite->shouldReceive('driver->stateless->user')->andReturn($socialiteUser);

        $response = $this->get('api/auth/callback/github');

        $response->assertStatus(302)
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'sara@test.com',
        ]);
    }
}
