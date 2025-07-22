<?php

namespace App\Http\Controllers\Api\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetWithFiltersRequest;
use Illuminate\Http\JsonResponse;

final class IndexProductController extends Controller
{
    public function getProducts(GetWithFiltersRequest $request, ProductServiceInterface $productService): JsonResponse
    {
        $dto = $request->toDTO();

        $products = $productService->getProductsWithStocks($dto);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
