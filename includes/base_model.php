<?php
class ValidationError extends Exception {
    public $errors;

    public function __construct($errors) {
        $this->errors = $errors;
        $this->message = 'One or more fields did not pass the validation check: ' . join(" | ", $errors);
    }
    public function display_error() {
        ?>
        <div class="form-error">
            <p>The fields have incorrect values:</p>
            <ul>
                <?php foreach($this->errors as $error): ?>
                <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}
class CustomDateTime extends DateTime {
    public function __toString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }
}

class BaseModel {
    protected $id = null;
    static protected $table_name = 'none';
    
    /**
     * Field and format. Format for every field. Can be string, bool, int, DateTime
     * ['id' => 'int', 'name' => 'string']
     * @var array
     */
    static protected $table_fields = ['id' => 'int'];
    /**
     * Join some fields from other tables to variables in model
     * Example:
     * [
     *  [
     *      "field" => ["COUNT(*) AS keys_count"], // where keys_count is variable in the class
     *      "join_table"     => "table_name tb1 ON tb1.prod_id = obj.id"
     *  ]
     * ]
     * @var array
     */
    static protected $additional_fields = [];
    /**
     * Search fields with prefix (example obj.)
     * ['obj.name', 'obj.email']
     * @var array
     */
    static protected $search_fields = array();

    static public $last_query = '';
    static public $last_args_query = array();

    public function __construct($args = array(), $has_prefix = false) {
        foreach ($args as $key => $value) {
            // Default SELECT which used in filter() or get() with field_{key}
            if($has_prefix)
                if(str_starts_with($key, 'field_'))
                    $key = substr($key, strlen('field_'));

            // If not exists in standart table field names, this field is not for this model (it's needed for LEFT JOIN with other models)
            if(!key_exists(
                $key, 
                static::$table_fields))
            {
                $this->{$key} = $value;
                continue;
            }
                

            // Exception only for id field
            if($key == 'id') {
                $this->id = $value;
                continue;
            }
            if(gettype($value) == 'string')
                $value = trim($value);

            // Set type for variable
            if($value === null) {
                // If $value has null, skip
                continue; 
            }

            switch (static::$table_fields[$key]) {
                case 'int':
                    $this->{'field_' . $key} = (int)$value;
                    break;
                case 'float':
                    $this->{'field_' . $key} = (float)$value;
                    break;
                case 'DateTime':
                    $this->{'field_' . $key} = new CustomDateTime($value);
                    break;
                case 'bool';
                    $this->{'field_' . $key} = (bool)$value;
                    break;
                case 'string':
                default:
                    $this->{'field_' . $key} = (string)$value;
                    break;
            }
        }     
    }
    public function get_id() {
        return $this->id;
    }
    public function is_saved() {
        if(isset($this->id))
            return true;
        else
            return false;
    }

    public static function init_table() {
        $result = db_query('');
        return $result;
    }
    public static function get_table_name() {
        return static::$table_name;
    }
    protected static function get_additional_fields() {
        return static::$additional_fields;
    }

    private static function get_fields_for_select($additional_fields = array()) {
        $fields = array_keys(static::$table_fields);
        $result = '';

        // Standart fields
        for ($i=0; $i < count($fields); $i++) { 
            $result .= 'obj.' . $fields[$i] . ' AS ' . 'field_' . $fields[$i];

            // Place ', ' for every field excerpt last
            if($i + 1 != count($fields))
                $result .= ', ';
        }

        // Additionl fields
        $additional_fields += static::get_additional_fields();
        if(!empty($additional_fields)) {
            $fields = array();
            foreach($additional_fields as $ad_field) {
                $fields = array_merge($ad_field['field'], $fields);
            }
            
            $add_fields_str = implode(', ', $fields);
            $result .= ', ' . $add_fields_str;
        }

        return $result;
    }
    /**
     * Collect fields from model as in the database
     * @return array
     */
    protected function get_db_fields() {
        $result_fields = [];
        // Getting all the public properties of the object
        $class_fields = array_keys(get_object_vars($this));

        foreach ($class_fields as $field) {
            // Checking for the 'field_' prefix
            if(str_starts_with($field, 'field_'))  {
                // Check if $field has value
                if(!isset($this->{$field}))
                    continue;

                // Removing the prefix and adding as a result
                $result_fields[] = substr($field, strlen('field_'));
            }
        }
        return $result_fields;
    }
    /**
     * Collect values from model as in the database
     * @return array
     */
    protected function get_db_values() {
        $fields = $this->get_db_fields();
        $values = [];
        foreach ($fields as $field) {
            $val = $this->{"field_$field"};
            
            switch (gettype($val)) {
                case 'boolean':
                    $val = (int)$val;
                    break;
            }

            $values[] = $val;
        }
        return $values;
    }

    /**
     * Return some list by filter and sort
     * @param array $fields
     * @param array $sort_by
     * @param int $count
     * @param string $field_relation
     * @param int $offset
     * @param string $search
     * @param array $additional_fields like static::$additional_fields
     * @return mysqli_result|array(self)
     */
    static function filter(
        $fields = array(), 
        $sort_by = array(), 
        $count = 10, 
        $field_relation = 'AND',
        $offset = 0,
        $search = '',
        $additional_fields = array()
        ) {
        /**
         * $fields = array(
         *      [
         *          'name' => 'obj.price'
         *          'type' => '>=' // default =
         *          'value' => 10
         *      ]
         * )
         * $sort_by = ['-price', 'comments']
         */

        // Filter part
        $where = [];
        $params = [];
        $whereSql = '';

        $having = [];
        $havingSql = '';
        if(!empty($fields)) {
            foreach ($fields as $field) {
                // Having or Where
                $is_having = $field['is_having'] ?? false;
                

                // For null values
                if($field['value'] === null) {
                    $where[] = "{$field['name']} {$field['type']} NULL";
                    continue;
                }
                // For array and IN
                if($field['type'] == 'IN') {
                    if(!is_array($field['value']))
                        continue;
                    
                    $placeholders = implode(', ', 
                        array_fill(0, count($field['value']), '?')
                    );
                    $where[] = "{$field['name']} IN ({$placeholders})";

                    $params = array_merge($params, $field['value']);
                    continue;
                }

                $condition = isset($field['type']) ? $field['type'] : '='; // use '=' as default
                
                if($is_having)
                    $having[] = "{$field['name']} {$condition} ?";
                else
                    $where[] = "{$field['name']} {$condition} ?";

                if($field['type'] != 'IN')
                    $params[] = $field['value'];
            }
            $whereSql = implode(' ' . $field_relation . ' ', $where);

            if(!empty($having))
                $havingSql = 'HAVING ' . implode(' ' . $field_relation . ' ', $having);
        }

        // Addition JOIN fields from other tables
        $join_table = '';
        $additional_fields = array_merge($additional_fields, static::get_additional_fields());
        if(!empty($additional_fields)) {
            $tables = array();
            foreach ($additional_fields as $val) {
                if(!isset($val['join_table']))
                    continue;

                $tables[] = 'LEFT JOIN '. $val['join_table'];
            }
            $join_table = implode(' ', $tables);
        }
        
        // Search part
        $whereSearchSql = [];
        if(!empty($search)) {
            foreach (static::$search_fields as $field_name) {
                $whereSearchSql[] = "{$field_name} LIKE ?";
                $params[] = "%" . $search . "%";
            }
            $whereSearchSql = implode(' OR ', $whereSearchSql);
        };

        // Search and filter in WHERE
        if(!empty($whereSql) && !empty($whereSearchSql))
            $whereSql = 'WHERE (' . $whereSql . ') AND (' . $whereSearchSql . ')';
        elseif(!empty($whereSql))
            $whereSql = 'WHERE (' . $whereSql . ')';
        elseif(!empty($whereSearchSql))
            $whereSql = 'WHERE (' . $whereSearchSql . ')';

        // Sort part
        $sortSql = '';
        $sort_by[] = 'obj.id';  // Sort by id on the end
        if (!empty($sort_by)) {
            $sortSql = 'ORDER BY ' . implode(', ', array_map(function($sort) {
                return ltrim($sort, '-') . ' ' . (strpos($sort, '-') === 0 ? 'DESC' : 'ASC');
            }, $sort_by));
        }

        // Limit part
        $limit = "LIMIT " . (int) $count;
        $offsetSql = "OFFSET " . (int)$offset;

        // Fields to select from db
        $select_fields = static::get_fields_for_select($additional_fields);

        // Create full sql query
        $sql = "SELECT " . $select_fields . " FROM " . static::$table_name . " obj " . $join_table . " " . $whereSql . " GROUP BY obj.id " . $havingSql . " " . $sortSql . " " . $limit . " " . $offsetSql;

        static::$last_query = $sql;
        static::$last_args_query = $params;
        $result = db_prepare($sql, $params);
        
        // Return result as objects or mysqli_result
        return static::createObjectsFromQuery($result);
    }
    /**
     * Return first row from db
     * @param array $fields
     * @return static|false
     */
    static function get($fields, $sort_by = array(), $field_relation = 'AND') {
        $result = static::filter(
            $fields, 
            $sort_by, 
            1, 
            $field_relation
        );
        if(count($result) > 0)
            return $result[0];
        else
            return false;
    }
    static function count($fields, $search) {
        $filter_result = static::filter(
            $fields,
            array(),
            1,
            'AND',
            0,
            $search,
            array(
                [
                    'field' => ['COUNT(*) OVER () AS func_total_count']
                ]
            )
        );
        if(empty($filter_result))
            return 0;
        else
            return $filter_result[0]->func_total_count;
    }
    
    public function delete() {
        // Object is not in the table        
        if(!isset($this->id))
            return false;

        $sql = "DELETE FROM " . static::$table_name . " WHERE id = ?";
        db_prepare($sql, [$this->id]);
    }
    protected function after_save() { }
    public function save() {
        global $db;

        // Check is valid fields or not
        if($this->valid() !== true)
            throw new ValidationError($this->valid());

        // Collect field and valus from fields in the class
        $fields = $this->get_db_fields();
        $values = $this->get_db_values();

        // Create SQL Prepare
        if (isset($this->id)) {
            // If we have id, then model is already in database

            // SQL for UPDATE .. SET ...
            $set_clause = implode(
                ', ', 
                array_map(
                    fn($field) => "$field = ?", 
                    $fields
                )
            );

            // Query and values
            $query = 'UPDATE ' . static::$table_name . ' SET '. $set_clause .' WHERE id = ?';
            $query_values = $values;
            $query_values[] = $this->id;
        } else {
            // if we don't have id (not in db), we create a new row

            // placeholder for SQL: ?, ?, ?, ?,...
            $placeholders = implode(', ', array_fill(0, count($fields), '?'));
            // list for SQL Insert
            $field_list = implode(', ', $fields);

            // Query and values
            $query = 'INSERT INTO ' . static::$table_name . ' ('.$field_list.') VALUES ('. $placeholders .')';
            $query_values = $values;
        }
        db_prepare($query, $query_values);

        if($this->id === null) {
            $this->id = $db->insert_id;
        }

        $this->after_save();
    }
    /**
     * Check fields. Return true or array with string errors
     * @return array|bool
     */
    public function valid() {
        return true;
    }
    
    /**
     * Return model from Mysql result
     * @param mysqli_result $mysqli_result
     * @return array
     */
    protected static function createObjectsFromQuery($mysqli_result) {
        $arr = $mysqli_result->fetch_all(MYSQLI_ASSOC);
        $models = [];
        
        foreach ($arr as $row) {
            $models[] = new static($row, true);
        }

        return $models;
    }
}