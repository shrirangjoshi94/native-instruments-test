<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPurchasedProductsIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'bought_at' => Carbon::parse($this->pivot->created_at)->format('d-m-Y H:i:s'),
        ];
    }
}
