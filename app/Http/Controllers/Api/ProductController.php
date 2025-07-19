<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetProductsRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
    )
    {
    }

    public function __invoke(GetProductsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $products = $this->productService->getProductsWithStocks(
            $validated['per_page'] ?? 10,
            ['name' => $validated['name'] ?? null],
        );

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
