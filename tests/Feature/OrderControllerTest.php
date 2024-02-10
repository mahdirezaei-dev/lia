<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class OrderControllerTest extends TestCase
{
     use DatabaseMigrations;

    protected $data = [
        'name' => 'mahdi',
        'email' => 'mahdi@mail.com',
        'password' => '12345678'
    ];

    /** @test */
    public function unauthenticated_user_cant_accsees_to_protected_routes(): void
    {
        $order = Order::factory()->create();

        $this->assertGuest($guard = null);

        $this->getJson(route('orders.index'))->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->getJson(route('orders.show', ['order' => $order->id]))->assertStatus(Response::HTTP_UNAUTHORIZED);    
        $this->postJson(route('orders.store', []))->assertStatus(Response::HTTP_UNAUTHORIZED);    
        $this->putJson(route('orders.update', ['order' => $order->id]))->assertStatus(Response::HTTP_UNAUTHORIZED);    
        $this->deleteJson(route('orders.destroy', ['order' => $order->id]))->assertStatus(Response::HTTP_UNAUTHORIZED);    
    }
}
