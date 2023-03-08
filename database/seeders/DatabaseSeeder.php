<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::factory()->count(5)->create();

        $categories->each(function ($category) {
            $products = Product::factory()->count(5)->create();
            $products->each(function ($product) use ($category) {
                CategoryProduct::factory()->create([
                    'category_id' => $category->id,
                    'product_id' => $product->id,
                ]);
                ProductImage::factory()->count(5)
                    ->for(Image::factory()->create())
                    ->create([
                    'product_id' => $product->id,
                ]);
            });
        });
    }
}
