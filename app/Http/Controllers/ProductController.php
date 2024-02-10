<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductRequest;
use App\Services\Product\ProductService;

class ProductController extends Controller
{

    /**
     * Initial related services for this controller
     */
    public function __construct(
        public ProductService $productService,
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {   
        return \Cache::remember('products', 100000, function ()  {
            return $this->productService->all()->toJson();
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
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
