<?php
namespace KeysShop\Apps\Products\Models;

use KeysShop\Apps\Products\Models\ProductModel;
use KeysShop\Includes\Model\BaseModel;
use KeysShop\Includes\Model\CustomDateTime;

class KeyModel extends BaseModel {
    public int $field_product_id;
    public string $field_key_code;
    public float $field_price;
    public float $field_original_price;
    public int $field_order_id;
    public CustomDateTime $field_created_at;
    public CustomDateTime $field_bought_at;

    // join products table
    public string $product_name;

    static protected $search_fields = ['obj.key_code'];
    static protected $table_name = 'product_keys';
    static protected array $table_fields = [
        'id'         => 'int', 
        'product_id' => 'int',
        'key_code'   => 'string', 
        'price'      => 'float',
        'original_price' => 'float',
        'order_id'   => 'int',
        'created_at' => 'DateTime',
        'bought_at'  => 'DateTime',
    ];
    public static function init_table() {
        $result = db_query('CREATE TABLE ' . static::$table_name . ' (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            product_id  INT NOT NULL,
            key_code    VARCHAR(255) NOT NULL,
            price       FLOAT NOT NULL,
            original_price FLOAT NOT NULL,
            order_id    INT NULL,

            bought_at   TIMESTAMP NULL,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        );');
        return $result;
    }
    static protected $additional_fields = array(
        [
            'field' => [
                'tb1.title AS product_name'
            ],
            'join_table' => 'products tb1 ON tb1.id = obj.product_id'
        ],
        [
            'field' => [
                'tb2.user_id AS buyer_id'
            ],
            'join_table' => 'orders tb2 ON tb2.id = obj.order_id'
        ]
    );

    // Update product after new key
    protected function after_save() {
        if($this->is_saved())
           return; 

        $product = ProductModel::get(array(
            [
                'name'      => 'obj.id',
                'value'     => $this->field_product_id
            ]
        ));
        $product->field_updated_at = new CustomDateTime();
        $product->save();
    }

    public function is_available() {
        if(isset($this->field_order_id))
            return false;
        else
            return true;
    }
    public function show_secret_key() {
        $secret_key = substr($this->field_key_code, 0, 6);

        return $secret_key . '****';
    }
    public function valid() {
        $errors = [];

        if(empty($this->field_key_code))
            $errors[] = 'The key is empty';

        if(empty($this->field_product_id))
            $errors[] = 'The product id is empty';

        if(empty($errors))
            return true;

        return $errors;
    }
}