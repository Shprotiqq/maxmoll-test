<?php

namespace App\Http\Controllers\Api\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\GetProductsRequest;
use Illuminate\Http\JsonResponse;

final class IndexProductController extends Controller
{
    public function __invoke(GetProductsRequest $request, ProductServiceInterface $productService): JsonResponse
    {
        $validated = $request->validated();

        $products = $productService->getProductsWithStocks(
            $validated['per_page'] ?? 10,
            ['name' => $validated['name'] ?? null],
        );

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
