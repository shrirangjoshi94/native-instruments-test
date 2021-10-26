<?php

declare(strict_types=1);

namespace App\Http\Requests\UserPurchasedProducts;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckIfUserHasPurchasedProductRule;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'exists:products,sku', new CheckIfUserHasPurchasedProductRule(true)],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['sku' => $this->route('sku')]);
    }
}
