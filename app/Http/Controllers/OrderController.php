<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use App\Services\Order\OrderService;

class OrderController extends Controller
{
    /**
     * Initial related services for this controller
     */
    public function __construct(
        public OrderService $orderService,
    ) {}

    
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {   
        return \Cache::remember('orders', 100000, function ()  {
            return $this->orderService->all()->toJson();
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
