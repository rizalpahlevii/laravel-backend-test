<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $structure = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'enable' => $this->enable,
        ];

        if ($this->relationLoaded('category')) {
            $structure['category'] = new CategoryResource($this->category);
        }

        if ($this->relationLoaded('image')) {
            $structure['image'] = new ImageResource($this->image);
        }

        return $structure;
    }
}
