<?php
namespace KeysShop\Apps\Products\Models;

use KeysShop\Includes\Model\BaseModel;

class TaxonomyModel extends BaseModel {
    public string $field_name;
    public string $field_slug;
    public string $field_type;
    public string $field_icon_html;
    public string $field_background_color;
    static protected $search_fields = ['obj.name'];

    static protected $table_name = 'taxonomies';
    static protected array $table_fields = [
        'id'        => 'int', 
        'name'      => 'string',
        'slug'      => 'string', 
        'type'      => 'string',
        'icon_html'      => 'string',
        'background_color'      => 'string',
    ];
    const TYPES = [ ['platform', 'Platform'], ['region', 'Region'] ];
    public static function get_type_values(string $type, $use_id = false) {
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