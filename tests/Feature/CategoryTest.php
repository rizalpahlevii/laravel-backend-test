<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    private string $endpoint = '/api/categories';

    /**
     * A basic feature test example.
     */
    public function test_populate_categories(): void
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
    public function test_populate_categories_with_invalid_endpoint(): void
    {
        $response = $this->getJson('/api/categories/invalid');

        $response->assertStatus(404);
    }


    /**
     * Test create category.
     *
     * @return void
     */
    public function test_create_category(): void
    {
        $data = [
            'name' => 'Test Category ' . rand(1, 1000),
            'enable' => true
        ];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertCreated();

        $this->assertDatabaseHas('categories', $data);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('category.id');
        });
    }

    /**
     * Test create category with invalid data.
     *
     * @return void
     */
    public function test_create_category_with_invalid_data(): void
    {
        $data = [
            'name' => 'Test Category ' . rand(1, 1000),
            'enable' => 'invalid'
        ];
        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.enable');
        });
    }

    /**
     * Test update category.
     *
     * @return void
     */
    public function test_update_category(): void
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Test Category ' . rand(1, 1000),
            'enable' => false
        ];
        $response = $this->putJson($this->endpoint . '/' . $category->id, $data);

        $response->assertOk();

        $this->assertDatabaseHas('categories', $data);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('category.id');
        });
    }

    /**
     * Test update category with invalid data.
     *
     * @return void
     */
    public function test_update_category_with_invalid_data(): void
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Test Category ' . rand(1, 1000),
            'enable' => 'invalid'
        ];
        $response = $this->putJson($this->endpoint . '/' . $category->id, $data);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.enable');
        });
    }

    /**
     * Test update category with invalid id.
     *
     * @return void
     */
    public function test_update_category_with_invalid_id(): void
    {
        $data = [
            'name' => 'Test Category ' . rand(1, 1000),
            'enable' => false
        ];
        $response = $this->putJson($this->endpoint . '/invalid', $data);

        $response->assertNotFound();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

    /**
     * Test delete category.
     *
     * @return void
     */
    public function test_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson($this->endpoint . '/' . $category->id);

        $response->assertOk();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

    /**
     * Test delete category with invalid id.
     *
     * @return void
     */
    public function test_delete_category_with_invalid_id(): void
    {
        $response = $this->deleteJson($this->endpoint . '/invalid');

        $response->assertNotFound();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

    /**
     * Test get category.
     *
     * @return void
     */
    public function test_get_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson($this->endpoint . '/' . $category->id);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('id');
            $json->has('name');
            $json->has('enable');
        });
    }

    /**
     * Test get category with invalid id.
     *
     * @return void
     */
    public function test_get_category_with_invalid_id(): void
    {
        $response = $this->getJson($this->endpoint . '/invalid');

        $response->assertNotFound();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

}
