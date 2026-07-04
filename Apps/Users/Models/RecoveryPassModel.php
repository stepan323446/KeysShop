<?php

use Includes\Model\BaseModel;
use Includes\Model\CustomDateTime;

class RecoveryPassModel extends BaseModel {
    public int $field_user_id;
    public string $field_recovery_slug;
    public CustomDateTime|null $field_created_at = null;
    public bool $field_is_used = false;

    static protected $cooldownModifier = '10 minutes';
    static protected $table_name = 'recovery_password';
    static protected array $table_fields = [
        'id'            => 'int', 
        'user_id'       => 'int',
        'recovery_slug' => 'string',
        'created_at'    => 'DateTime',
        'is_used'       => 'bool'
    ];

    public function is_available() {
        // Recovery is already used
        if($this->field_is_used)
            return false;

        // Invalid after 10 minutes
        $datetime_recovery = new DateTime($this->field_created_at);
        $datetime_recovery->modify('+' . static::$cooldownModifier);

        $current_datetime = new DateTime();
        if ($datetime_recovery < $current_datetime) {
            return false;
        } else {
            return true;
        }
    }
    public static function get_cooldown_modifier() {
        return static::$cooldownModifier;
    }
    /**
     * Check if the email was sent 10 minutes earlier. If so, the user needs to wait for the cooldown.
     * @return bool
     */
    public static function is_cooldown_available($user_id) {
        $cooldownTime = new DateTime();
        $cooldownTime->modify( '-' . static::$cooldownModifier);

        $lastRecoveryModel = RecoveryPassModel::get(
            array(
                [
                    'name' => 'obj.created_at',
                    'type' => '>=',
                    'value' => $cooldownTime->format('Y-m-d H:i:s')
                ],
                [
                    'name' => 'obj.user_id',
                    'type' => '=',
                    'value' => $user_id
                ]
            )
        );

        if($lastRecoveryModel)
            return false;
        else
            return true;
    }
}