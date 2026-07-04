<?php
namespace Tests\Products;

use Apps\Products\Models\KeyModel;
use Apps\Products\Models\ProductModel;
use Apps\Products\Models\TaxonomyModel;
use Includes\Model\CustomDateTime;
use Tests\DatabaseTestCase;

class KeyModelTest extends DatabaseTestCase {

    private ProductModel $product;

    protected function setUp(): void {
        parent::setUp();

        $taxonomy = $this->create_test_taxonomy();
        $this->product = $this->create_test_product($taxonomy->get_id());
    }

    private function create_test_taxonomy(): TaxonomyModel {
        $taxonomy = new TaxonomyModel();
        $taxonomy->field_name = 'Test Platform';
        $taxonomy->field_slug = 'test-platform';
        $taxonomy->field_type = 'platform';
        $taxonomy->save();

        return $taxonomy;
    }

    private function create_test_product(int $platform_id): ProductModel {
        $product = new ProductModel();
        $product->field_title = 'Test Product';
        $product->field_slug = 'test-product-' . uniqid();
        $product->field_excerpt = 'Test excerpt';
        $product->field_description = 'Test description';
        $product->field_poster_url = 'test.jpg';
        $product->field_original_url = 'https://example.com';
        $product->field_original_price = 19.99;
        $product->field_platform_id = $platform_id;
        $product->field_region_id = $platform_id;
        $product->field_created_at = new CustomDateTime();
        $product->field_updated_at = new CustomDateTime();
        $product->save();

        return $product;
    }

    public function test_valid_fails_when_key_code_is_empty(): void {
        $key = new KeyModel();
        $key->field_key_code = '';
        $key->field_product_id = $this->product->get_id();

        $errors = $key->valid();

        $this->assertIsArray($errors);
        $this->assertContains('The key is empty', $errors);
    }

    public function test_valid_fails_when_product_id_is_empty(): void {
        $key = new KeyModel();
        $key->field_key_code = 'SOME-KEY-CODE';

        $errors = $key->valid();

        $this->assertIsArray($errors);
        $this->assertContains('The product id is empty', $errors);
    }

    public function test_valid_passes_with_correct_data(): void {
        $key = new KeyModel();
        $key->field_key_code = 'VALID-KEY-CODE';
        $key->field_product_id = $this->product->get_id();

        $result = $key->valid();

        $this->assertTrue($result);
    }

    public function test_is_available_returns_true_when_no_order(): void {
        $key = new KeyModel();
        $key->field_key_code = 'AVAILABLE-KEY';
        $key->field_product_id = $this->product->get_id();
        $key->field_price = 9.99;
        $key->field_original_price = 19.99;
        $key->field_created_at = new CustomDateTime();
        $key->save();

        $this->assertTrue($key->is_available());
    }

    public function test_is_available_returns_false_when_order_is_set(): void {
        $key = new KeyModel();
        $key->field_key_code = 'SOLD-KEY';
        $key->field_product_id = $this->product->get_id();
        $key->field_price = 9.99;
        $key->field_original_price = 19.99;
        $key->field_order_id = 1;
        $key->field_created_at = new CustomDateTime();
        $key->save();

        $this->assertFalse($key->is_available());
    }

    public function test_show_secret_key_masks_code_after_first_six_chars(): void {
        $key = new KeyModel();
        $key->field_key_code = 'ABCDEF-1234-5678';
        $key->field_product_id = $this->product->get_id();

        $this->assertEquals('ABCDEF****', $key->show_secret_key());
    }

    public function test_save_persists_key_to_database(): void {
        $key = new KeyModel();
        $key->field_key_code = 'PERSIST-KEY-001';
        $key->field_product_id = $this->product->get_id();
        $key->field_price = 5.5;
        $key->field_original_price = 10;
        $key->field_created_at = new CustomDateTime();
        $key->save();

        $found = KeyModel::get([
            ['name' => 'obj.key_code', 'type' => '=', 'value' => 'PERSIST-KEY-001']
        ]);

        $this->assertNotFalse($found);
        $this->assertEquals($this->product->get_id(), $found->field_product_id);
    }
}