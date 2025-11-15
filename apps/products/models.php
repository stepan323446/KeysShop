<?php

require_once INCLUDES_PATH . '/base_model.php';

class TaxonomyModel extends BaseModel {
    public $field_name;
    public $field_slug;
    public $field_type;
    public $field_icon_html;
    public $field_background_color;
    static protected $search_fields = ['obj.name'];

    static protected $table_name = 'taxonomies';
    static protected $table_fields = [
        'id'        => 'int', 
        'name'      => 'string',
        'slug'      => 'string', 
        'type'      => 'string',
        'icon_html'      => 'string',
        'background_color'      => 'string',
    ];
    const TYPES = [ ['platform', 'Platform'], ['region', 'Region'] ];
    public static function get_type_values($type, $use_id = false) {
        $tax_list = TaxonomyModel::filter(
            array(
                [
                    'name' => 'obj.type',
                    'type' => '=',
                    'value' => $type
                ]
            )
        );
        $result = array();
        foreach($tax_list as $tax) {
            if($use_id)
                $result[] = [ $tax->get_id(), $tax->field_name ];
            else
                $result[] = [ $tax->field_slug, $tax->field_name ];
        }
        return $result;
    }
    
    public static function init_table() {
        $result = db_query('CREATE TABLE ' . static::$table_name . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            type VARCHAR(50) NOT NULL,
            slug VARCHAR(50) NOT NULL,

            icon_html TEXT NULL,
            background_color VARCHAR(20) NULL
        );');
        return $result;
    }

    public function valid() {
        $errors = array();

        $name_len = strlen($this->field_name);
        $slug_len = strlen($this->field_slug);
        $type_len = strlen($this->field_type);

        if($name_len > 50 || $name_len < 2)
            $errors[] = 'The name field must contain from 2 to 50 characters.';

        if($slug_len > 50 || $slug_len < 2)
            $errors[] = 'The slug field must contain from 2 to 50 characters.';

        if($type_len > 50 || $type_len < 2)
            $errors[] = 'The type field must contain from 2 to 50 characters.';

        if(empty($errors))
            return true;

        return $errors;
    }
}

class ProductModel extends BaseModel {
    public $field_title;
    public $field_slug;
    public $field_excerpt;
    public $field_description;
    public $field_poster_url;
    public $field_image_url;
    public $field_original_url;
    public $field_original_price = 0;
    public $field_edition = 'Standart edition';
    public $field_created_at;
    public $field_updated_at;
    public $field_platform_id;
    public $field_region_id;
    public $field_sales = 0;
    
    // Join product_keys table
    public $keys_count;
    public $current_price;
    public $min_price;
    public $result_price;

    // Join taxonomies table
    public $platform_title;
    public $platform_icon;
    public $platform_background;

    // Join wishlist
    public $is_in_wishlist = 0;

    static protected $search_fields = ['obj.title'];
    static protected $table_name = 'products';
    static protected $table_fields = [
        'id'         => 'int', 
        'title'      => 'string',
        'slug'       => 'string', 
        'excerpt'    => 'string',
        'description' => 'string',
        'poster_url' => 'string',
        'image_url'  => 'string',
        'original_url' => 'string',
        'original_price' => 'float',
        'edition'   =>  'string',
        'sales'     => 'int',

        'created_at' => 'DateTime',
        'updated_at' => 'DateTime',
        'platform_id' => 'int',
        'region_id' => 'int'
    ];
    static protected $additional_fields = array(
        [
            "field"         => [
                "COUNT(CASE WHEN tb1.order_id IS NULL THEN tb1.id ELSE NULL END) AS keys_count",
                "ROUND(MIN(CASE WHEN tb1.order_id IS NULL THEN tb1.price ELSE NULL END), 2) AS current_price",
                "MIN(tb1.price) AS min_price",
                "CASE
                    WHEN COUNT(CASE WHEN tb1.order_id IS NULL THEN tb1.id ELSE NULL END) > 0 THEN
                        ROUND(MIN(CASE WHEN tb1.order_id IS NULL THEN tb1.price ELSE NULL END), 2)
                    ELSE
                        CASE
                            WHEN MIN(tb1.price) IS NULL THEN obj.original_price
                            ELSE MIN(tb1.price)
                        END
                END AS result_price"
            ],
            "join_table"    => "product_keys tb1 ON tb1.product_id = obj.id"
        ],
        [
            "field"         => [
                "tb2.name AS platform_title",
                "tb2.icon_html AS platform_icon",
                "tb2.background_color AS platform_background"
            ],
            "join_table"    => "taxonomies tb2 ON tb2.id = obj.platform_id"
        ]
    );
    protected static function get_additional_fields(){
        $add_fields = parent::get_additional_fields();

        // If user is authorized, we also check product in thw wishlist
        if(CURRENT_USER) {
            $add_fields = array_merge($add_fields, array(
                [
                    "field" => [
                        "CASE WHEN wish.user_id = ". CURRENT_USER->get_id() ." THEN 1 ELSE 0 END AS is_in_wishlist"
                    ],
                    "join_table" => "wishlist wish ON wish.product_id = obj.id"
                ]
            ));
        }

        return $add_fields;
    }
    public function get_price() {
        return round($this->result_price, 2);
    }
    public function is_available() {
        if($this->keys_count > 0)
            return true;
        else
            return false;
    }
    public function get_discount() {
        if($this->get_price() == $this->field_original_price)
            return 0;

        return (int)(100 - $this->get_price() * 100 / $this->field_original_price);
    }
    public function get_description() {
        $descpr = $this->field_description;

        $descpr = str_replace("\n", "<br>", $descpr);

        return $descpr;
    }
    public function get_price_format() {
        return number_format($this->get_price(), 2);
    }
    public function get_poster_url() {
        return MEDIA_URL . $this->field_poster_url;
    }
    public function get_image_url() {
        if($this->field_image_url)
            return MEDIA_URL . $this->field_image_url;
        else
            return $this->get_poster_url();
    }
    public function get_absolute_url() {
        return get_permalink('products:single', [$this->field_slug]);
    }

    public static function init_table() {
        $result = db_query('CREATE TABLE ' . static::$table_name . ' (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            title       VARCHAR(50) NOT NULL,
            slug        VARCHAR(50) NOT NULL,
            excerpt     VARCHAR(250) NOT NULL,
            poster_url   VARCHAR(255) NULL,
            image_url   VARCHAR(255) NULL,

            description TEXT NOT NULL,
            original_url VARCHAR(255) NOT NULL,
            original_price FLOAT NOT NULL,
            edition     VARCHAR(50) DEFAULT "Standart edition",
            sales       INT DEFAULT 0,

            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            platform_id INT NOT NULL,
            region_id   INT NOT NULL,

            FOREIGN KEY (platform_id) REFERENCES taxonomies(id) ON DELETE RESTRICT,
            FOREIGN KEY (region_id) REFERENCES taxonomies(id) ON DELETE RESTRICT
        );');
        return $result;
    }

    public function valid() {
        $errors = array();

        $product_exists = ProductModel::get(
            array(
                [
                    'name' => 'obj.slug',
                    'type' => '=',
                    'value' => $this->field_slug,
                ]
            )
        );
        if(!empty($product_exists)) {
            if($product_exists->get_id() !== $this->id) {
                $errors[] = 'A product with this slug already exists in the database';
            }
        }
        if(empty($this->field_title))
            $errors[] = 'The title is empty';

        if(empty($this->field_slug))
            $errors[] = 'The slug is empty';

        if(empty($this->field_excerpt))
            $errors[] = 'The excerpt is empty';

        if(empty($this->field_description))
            $errors[] = 'The description is empty';

        if(empty($errors))
            return true;

        return $errors;
    }
}
class KeyModel extends BaseModel {
    public $field_product_id;
    public $field_key_code;
    public $field_price;
    public $field_original_price;
    public $field_order_id;
    public $field_created_at;
    public $field_bought_at;

    // join products table
    public $product_name;

    static protected $search_fields = ['obj.key_code'];
    static protected $table_name = 'product_keys';
    static protected $table_fields = [
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

class WishlistModel extends BaseModel {
    public $field_product_id;
    public $field_user_id;

    static protected $table_name = 'wishlist';
    static protected $table_fields = [
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