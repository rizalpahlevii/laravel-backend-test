<?php

namespace App\Observers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductImage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $image = $product->image;
        $category = $product->category;

        $image->delete();
        $category->delete();

        ProductImage::where('product_id', $product->id)->delete();
        CategoryProduct::where('product_id', $product->id)->delete();
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
