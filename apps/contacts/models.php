<?php
require_once INCLUDES_PATH . '/base_model.php';

class FeedbackModel extends BaseModel {
    public $field_name;
    public $field_email;
    public $field_is_read = false;
    public $field_content;
    public  $field_created_at = null;
    static protected $table_name = 'feedbacks';
    static protected $table_fields = [
        'id'        => 'int', 
        'name'      => 'string',
        'email'     => 'string', 
        'content'   => 'string',
        'is_read'   => 'bool',
        'created_at' => 'DateTime'
    ];
    static protected $search_fields = ['obj.name', 'obj.email'];

    public static function init_table() {
        $result = db_query('CREATE TABLE ' . static::$table_name . ' (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            content TEXT NOT NULL,
            is_read TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );');
        return $result;
    }
    public function valid() {
        $errors = array();

        $name_len = strlen($this->field_name);
        $email_len = strlen($this->field_email);
        $content_len = strlen($this->field_content);

        if($name_len > 50 || $name_len < 2)
            $errors[] = 'The name field must contain from 2 to 50 characters.';

        if($email_len > 50 || $email_len < 2)
            $errors[] = 'The email field must contain from 2 to 50 characters.';

        if($content_len < 10)
            $errors[] = 'The content field must contain 10 characters and more.';

        if(empty($errors))
            return true;

        return $errors;
    }
}
?>