<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Product;

class OrderTest extends TestCase
{
     use DatabaseMigrations;

    protected $data = [
        'name' => 'mahdi',
        'email' => 'mahdi@mail.com',
        'password' => '12345678'
    ];
}
