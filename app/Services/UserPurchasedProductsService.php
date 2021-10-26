<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class UserPurchasedProductsService
{
    /**
     * Add the product to the user purchased list.
     *
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        try {
            $product = Product::where('sku', $data['sku'])->first();
            Auth::user()->products()->attach([$product->id => ['sku' => $product->sku]]);

            return true;
        } catch (Exception $exception) {
            report($exception);

            return false;
        }
    }

    /**
     * Remove a product from user purchased list.
     *
     * @param string $sku
     * @return bool
     */
    public function delete(string $sku): bool
    {
        try {
            $product = Product::where('sku', $sku)->first();
            Auth::user()->products()->detach($product->id);

            return true;
        } catch (Exception $exception) {
            report($exception);

            return false;
        }
    }
}
