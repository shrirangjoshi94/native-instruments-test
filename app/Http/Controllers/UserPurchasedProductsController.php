<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\{JsonResponse, Response};
use App\Services\UserPurchasedProductsService;
use App\Http\Resources\Product\UserPurchasedProductsIndexResource;
use App\Http\Requests\UserPurchasedProducts\{CreateRequest, DeleteRequest};

class UserPurchasedProductsController extends Controller
{
    /** @var UserPurchasedProductsService $userPurchasedProductsService */
    private UserPurchasedProductsService $userPurchasedProductsService;

    /**
     * To initialize class objects/variables.
     *
     * @param UserPurchasedProductsService $userPurchasedProductsService
     */
    public function __construct(UserPurchasedProductsService $userPurchasedProductsService)
    {
        $this->userPurchasedProductsService = $userPurchasedProductsService;
    }

    /**
     * Returns list of user purchased products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(['products' => UserPurchasedProductsIndexResource::collection(Auth::user()->products)]);
    }

    /**
     * Add new product to the user purchased list.
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        return $this->userPurchasedProductsService->store($request->validated())
            ? response()->json(['message' => __('messages.user_purchased_products.create.success')])
            : response()->json(
                ['message' => __('messages.user_purchased_products.create.failure')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
    }

    /**
     * Remove user purchased product.
     *
     * @param DeleteRequest $request
     * @param string $sku
     * @return JsonResponse
     */
    public function destroy(DeleteRequest $request, string $sku): JsonResponse
    {
        return $this->userPurchasedProductsService->delete($sku)
            ? response()->json(['message' => __('messages.user_purchased_products.delete.success')])
            : response()->json(
                ['message' => __('messages.user_purchased_products.delete.failure')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
    }
}
