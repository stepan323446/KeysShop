<?php
require_once INCLUDES_PATH . '/base_model.php';

class OrderModel extends BaseModel {
    public $field_method;
    public $field_order_number;
    public $field_created_at;
    public $field_user_id;

    // product_keys table
    public $total_price;

    // users table
    public $buyer;

    static protected $table_name = 'orders';
    static protected $table_fields = [
        'id'        => 'int', 
        'method'    => 'string',
        'order_number' => 'string', 
        'created_at' => 'DateTime',
        'user_id'   => 'int'
    ];
    static protected $search_fields = ['obj.order_number'];
    
    static protected $additional_fields = array (
        [
            "field"         => [
                "SUM(tb1.price) AS total_price"
            ],
            "join_table"    => "product_keys tb1 ON tb1.order_id = obj.id"
        ],
        [
            "field"         => [
                "db2.username AS 'buyer'"
            ],
            "join_table"    => "users db2 ON db2.id = obj.user_id"
        ]
    );
    public function get_total_price() {
        return number_format($this->total_price, 2);
    }
    public function get_buyer_username() {
        return $this->buyer;
    }
    public static function init_table() {
        $result = db_query('CREATE TABLE ' . static::$table_name . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_number VARCHAR(255) NOT NULL,
            method VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id    INT NOT NULL,

            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
        );');
        return $result;
    }
}