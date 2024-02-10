<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

        protected $data = [
            'name' => 'mahdi',
            'email' => 'mahdi@mail.com',
            'password' => '12345678'
    ];

    /** @test */
    public function authenticated_user_can_get_all_products(): void
    {
        $user = User::create($this->data);

        Product::factory()->count(20)->create();

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->getJson(route('products.index'));
        
        $this->assertAuthenticatedAs($user, $guard = null);
        
        $response->assertJsonCount(20, 'data')
            ->assertStatus(Response::HTTP_OK);
    }
    
    /** @test */
    public function authenticated_user_can_get_a_product(): void
    {
        $user = User::create($this->data);

        $product = Product::factory()->create();

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->getJson(route('products.show', ['product' => $product->id]));
        
        $this->assertAuthenticatedAs($user, $guard = null);

        $response->assertJsonStructure([
            'success',
            'code',
            'message',
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function unauthenticated_user_cant_accsees_to_protected_routes(): void
    {
        $product = Product::factory()->create();

        $this->assertGuest($guard = null);

        $this->getJson(route('products.index'))->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->getJson(route('products.show', ['product' => $product->id]))->assertStatus(Response::HTTP_UNAUTHORIZED);    
        $this->postJson(route('products.store', []))->assertStatus(Response::HTTP_UNAUTHORIZED);    
        $this->putJson(route('products.update', ['product' => $product->id]))->assertStatus(Response::HTTP_UNAUTHORIZED);    
        $this->deleteJson(route('products.destroy', ['product' => $product->id]))->assertStatus(Response::HTTP_UNAUTHORIZED);    
    }
}
