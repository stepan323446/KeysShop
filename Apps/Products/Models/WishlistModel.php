<?php
namespace KeysShop\Apps\Products;

use KeysShop\Includes\Model\BaseModel;

class WishlistModel extends BaseModel {
    public int $field_product_id;
    public int $field_user_id;

    static protected $table_name = 'wishlist';
    static protected array $table_fields = [
        'id'         => 'int', 
        'product_id' => 'int',
        'user_id'    => 'int',
    ];

    public static function init_table() {
        $result = db_query('CREATE TABLE ' . static::$table_name . ' (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            product_id  INT NOT NULL,
            user_id     INT NOT NULL,

            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );');
        return $result;
    }
}