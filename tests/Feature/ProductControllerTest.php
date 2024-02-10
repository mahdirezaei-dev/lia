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
