<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Product extends Model
{
    use HasFactory;

    public function image(): HasOneThrough
    {
        return $this->hasOneThrough(Image::class, ProductImage::class, 'product_id', 'id', 'id', 'image_id');
    }

    public function category(): HasOneThrough
    {
        return $this->hasOneThrough(Category::class, CategoryProduct::class, 'product_id', 'id', 'id', 'category_id');
    }
}
