<?php
namespace Apps\Products\Models;

use Includes\Model\BaseModel;
use Includes\Model\CustomDateTime;

#[\AllowDynamicProperties]
class ProductModel extends BaseModel {
    public string $field_title;
    public string $field_slug;
    public string $field_excerpt;
    public string $field_description;
    public string $field_poster_url;
    public ?string $field_image_url = null;
    public string $field_original_url;
    public float $field_original_price = 0;
    public string $field_edition = 'Standart edition';
    public CustomDateTime $field_created_at;
    public CustomDateTime $field_updated_at;
    public int $field_platform_id;
    public int $field_region_id;
    public int $field_sales = 0;
    
    // Join product_keys table
    public int $keys_count;
    public ?float $current_price;
    public ?float $min_price;
    public ?float $result_price;

    // Join taxonomies table
    public string $platform_title;
    public string $platform_icon;
    public string $platform_background;

    // Join wishlist
    public int $is_in_wishlist = 0;

    static protected $search_fields = ['obj.title'];
    static protected $table_name = 'products';
    static protected array $table_fields = [
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
                        "MAX(CASE WHEN wish.user_id = ". CURRENT_USER->get_id() ." THEN 1 ELSE 0 END) AS is_in_wishlist"
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