<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class CheckIfUserHasPurchasedProductRule implements Rule
{
    /** @var bool $deleteUserPurchasedProduct */
    private bool $deleteUserPurchasedProduct;

    /** @var string $message */
    private string $message;

    /**
     * To initialize class objects/variables.
     *
     * @param bool $deleteUserPurchasedProduct
     */
    public function __construct(?bool $deleteUserPurchasedProduct = false)
    {
        $this->deleteUserPurchasedProduct = $deleteUserPurchasedProduct;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $product = Product::where('sku', $value)->first();

        if (!$product) {
            return true;
        }

        $this->message = $this->deleteUserPurchasedProduct
            ? __('validation_messages.user_purchased_products.user_did_not_purchase_product')
            : __('validation_messages.user_purchased_products.user_has_already_purchased_product');
        $userPurchasedProduct = Auth::user()->products()->where('product_id', $product->id)->first();

        return $this->deleteUserPurchasedProduct
            ? (bool)$userPurchasedProduct
            : !(bool)$userPurchasedProduct;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
