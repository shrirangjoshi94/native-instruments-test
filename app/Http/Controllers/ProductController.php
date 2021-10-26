<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Product\ProductIndexResource;

class ProductController extends Controller
{
    /**
     * Returns list of products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(['products' => ProductIndexResource::collection(Product::all())]);
    }
}
