<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private string $endpoint = '/api/products';

    /**
     * A basic feature test example.
     */
    public function test_populate_products(): void
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);

        $this->assertNotEmpty($response->json());
    }

    /**
     * Test the endpoint returns a 404 status code when the endpoint is invalid.
     *
     * @return void
     */
    public function test_populate_products_with_invalid_endpoint(): void
    {
        $response = $this->getJson('/api/products/invalid');

        $response->assertStatus(404);
    }

    /**
     * Test create product.
     *
     * @return void
     */
    public function test_create_product(): void
    {
        $data = [
            'name' => 'Test Product ' . rand(1, 1000),
            'description' => 'Test Product Description',
            'category_id' => Category::factory()->create()->id,
            'image' => UploadedFile::fake()->image('product.jpg'),
            'enable' => true
        ];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertCreated();

        $product = $response->json('product');
        $productId = $product['id'];

        $this->assertNotEmpty($product);
        $this->assertDatabaseHas('products', Arr::only($data, ['name', 'description', 'enable']));
        $this->assertDatabaseHas('category_products', ['product_id' => $productId, 'category_id' => $data['category_id']]);
        $this->assertDatabaseHas('product_images', ['product_id' => $productId]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('product.id');
        });
    }

    /**
     * Test create product with invalid data.
     *
     * @return void
     */
    public function test_create_product_with_invalid_data(): void
    {
        $data = [
            'name' => 'Test Product ' . rand(1, 1000),
            'description' => 'Test Product Description',
            'category_id' => 'invalid',
            'image' => 'invalid',
            'enable' => true
        ];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(422);
    }

    /**
     * Test update product.
     *
     * @return void
     */
    public function test_update_product(): void
    {
        $product = $this->prepareProduct();
        $data = [
            'name' => 'Test Product ' . rand(1, 1000),
            'description' => 'Test Product Description',
            'category_id' => Category::factory()->create()->id,
            'image' => UploadedFile::fake()->image('product.jpg'),
            'enable' => true
        ];
        $response = $this->putJson($this->endpoint . '/' . $product->id, $data);

        $response->assertOk();

        $product = $response->json('product');
        $productId = $product['id'];

        $this->assertNotEmpty($product);
        $this->assertDatabaseHas('products', Arr::only($data, ['name', 'description', 'enable']));
        $this->assertDatabaseHas('category_products', ['product_id' => $productId, 'category_id' => $data['category_id']]);
        $this->assertDatabaseHas('product_images', ['product_id' => $productId]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('product.id');
        });
    }

    private function prepareProduct(): Product
    {
        $product = Product::factory()->create();
        $image = Image::create([
            'file' => 'test.jpg',
            'name' => 'test.jpg',
            'enable' => true
        ]);
        ProductImage::factory()->create([
            'product_id' => $product->id,
            'image_id' => $image->id,
        ]);
        CategoryProduct::factory()->create([
            'product_id' => $product->id,
            'category_id' => Category::factory()->create()->id,
        ]);
        return $product;
    }

    /**
     * Test update product with invalid data.
     *
     * @return void
     */
    public function test_update_product_with_invalid_data(): void
    {
        $product = Product::factory()->create();
        $data = [
            'name' => 'Test Product ' . rand(1, 1000),
            'description' => 'Test Product Description',
            'category_id' => 'invalid',
            'image' => 'invalid',
            'enable' => true
        ];
        $response = $this->putJson($this->endpoint . '/' . $product->id, $data);

        $response->assertStatus(422);
    }

    /**
     * Test delete product.
     *
     * @return void
     */
    public function test_delete_product(): void
    {
        $product = $this->prepareProduct();
        $response = $this->deleteJson($this->endpoint . '/' . $product->id);

        $response->assertOk();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('category_products', ['product_id' => $product->id]);
        $this->assertDatabaseMissing('product_images', ['product_id' => $product->id]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

    /**
     * Test delete product with invalid id.
     *
     * @return void
     */
    public function test_delete_product_with_invalid_id(): void
    {
        $response = $this->deleteJson($this->endpoint . '/invalid');

        $response->assertStatus(404);
    }

    /**
     * Test show product.
     *
     * @return void
     */
    public function test_show_product(): void
    {
        $product = $this->prepareProduct();
        $response = $this->getJson($this->endpoint . '/' . $product->id);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('id');
            $json->has('name');
            $json->has('description');
            $json->has('enable');

            $json->has('category.id');
            $json->has('category.name');
            $json->has('category.enable');

            $json->has('image.id');
            $json->has('image.file');
            $json->has('image.name');
            $json->has('image.enable');
        });
    }

    /**
     * Test show product with invalid id.
     *
     * @return void
     */
    public function test_show_product_with_invalid_id(): void
    {
        $response = $this->getJson($this->endpoint . '/invalid');

        $response->assertStatus(404);
    }
}
