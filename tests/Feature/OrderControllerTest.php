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
    public function authenticated_user_can_get_all_orders(): void
    {
        $user = User::create($this->data);

        Order::factory()->count(20)->create();

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->getJson(route('orders.index'));
        
        $this->assertAuthenticatedAs($user, $guard = null);
        
        $response->assertJsonCount(20, 'data')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function authenticated_user_can_get_an_order(): void
    {
        $user = User::create($this->data);

        $order = Order::factory()->create();

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . auth()->tokenById($user->id),
            ])
            ->getJson(route('orders.show', ['order' => $order->id]));
        
        $this->assertAuthenticatedAs($user, $guard = null);

        $response->assertJsonStructure([
            'success',
            'code',
            'message',
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function user_can_create_a_new_order(){

        $products = Product::factory()->count(3)->create();

        $order = [
            'products' => [],
            'total_price' => 100.22
        ];
        foreach ($products as $key => $value) {
           $order['products'][] = [
                'id' => $value->id,
                'quantity' => 1,
           ];
        }

        $user = User::create($this->data);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . auth()->tokenById($user->id)])
                ->postJson(
                    route('orders.store'),
                    $order
                )
                ->assertJsonStructure(['data' => ['products', 'total_price']])
                ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('orders', $order);
    }

        /** @test */
    public function user_can_update_an_existing_order(){

        $products = Product::factory()->count(3)->create();
        $existing_order = Order::factory()->create();

        $order = [
            'products' => [],
            'total_price' => 100.22
        ];
        foreach ($products as $key => $value) {
           $order['products'][] = [
                'id' => $value->id,
                'quantity' => 1,
           ];
        }

        $user = User::create($this->data);

        $this->withHeaders(['Authorization' => 'Bearer ' . auth()->tokenById($user->id)])
                ->patchJson(route('orders.update', ['order' => $existing_order->id]), $order)
                // ->assertJsonStructure(['data' => ['products', 'total_price']])
                ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('orders', $existing_order->toArray());
        $this->assertDatabaseHas('orders', $order);
    }

        /** @test */
    public function user_can_delete_an_order(){
        $order = Order::factory()->create();

        $user = User::create($this->data);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . auth()->tokenById($user->id)])->delete(route('orders.destroy', ['order' => $order->id]));

        $response->assertJsonStructure(['message'])
                ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('orders', $order->toArray());
    }

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
