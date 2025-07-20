<?php

namespace App\Http\Controllers\Api\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\GetProductsRequest;
use Illuminate\Http\JsonResponse;

final class IndexProductController extends Controller
{
    public function getProducts(GetProductsRequest $request, ProductServiceInterface $productService): JsonResponse
    {
        $products = $productService->getProductsWithStocks($request->toDTO());

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
