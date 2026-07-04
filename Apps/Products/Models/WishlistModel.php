<?php
namespace Apps\Products;

use Includes\Model\BaseModel;

class WishlistModel extends BaseModel {
    public int $field_product_id;
    public int $field_user_id;

    static protected $table_name = 'wishlist';
    static protected array $table_fields = [
        'id'         => 'int', 
        'product_id' => 'int',
        'user_id'    => 'int',
    ];
}