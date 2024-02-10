<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    private array $data = [
        'name' => 'mahdi',
        'email' => 'mahdi@mail.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ];

    /** @test */
    public function unauthenticated_user_can_register(): void
    {

        $response = $this->postJson(
            route('register'),
            $this->data
        );
 
        $this->assertAuthenticated($guard = null);
        
        $response->assertJsonStructure([
                'access_token',
                'token_type',
                'token_type'
            ])
            ->assertStatus(200);
    }

        /** @test */
    public function unauthenticated_user_can_login(): void
    {
        $user = User::create($this->data);

        $response = $this->postJson(route('login'), [
            'email' => $this->data['email'],
            'password' => $this->data['password'],
        ]);
 
        $this->assertAuthenticatedAs($user, $guard = null);

        $response->assertJsonStructure([
                'access_token',
                'token_type',
                'token_type'
            ])
            ->assertStatus(200);
    }

        /** @test */
    public function authenticated_user_can_refresh_her_token(): void
    {
        $user = User::create($this->data);

        $reponse = $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->postJson(route('refresh'));

        $reponse->assertJsonStructure([
                'access_token',
                'token_type',
                'token_type'
            ])
            ->assertStatus(200);

        $this->assertAuthenticatedAs($user, $guard = null);
    }

        /** @test */
    public function authenticated_user_can_logout(): void
    {
        $user = User::create($this->data);

        $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->postJson(route('logout'))
            ->assertStatus(200);

        $this->assertGuest($guard = null);
    }

        /** @test */
    public function authenticated_user_can_see_her_information(): void
    {
        $user = User::create($this->data);

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->postJson(route('me'));

        $response->assertJson($user->toArray(), $strict = false)
            ->assertStatus(200);

        $this->assertAuthenticatedAs($user, $guard = null);
    }
}
