<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
}
