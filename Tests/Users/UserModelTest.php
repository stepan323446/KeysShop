<?php
namespace Tests\Users;

use Apps\Users\Models\UserModel;
use Tests\DatabaseTestCase;



class UserModelTest extends DatabaseTestCase {
    const SAVED_USER_USERNAME = "saved_user";
    const SAVED_USER_PASSWORD = "12345678";

    private UserModel $saved_user;

    protected function setUp(): void {
        parent::setUp(); 

        $user = new UserModel();
        $user->field_username = self::SAVED_USER_USERNAME;
        $user->field_email = 'saved@example.com';
        $user->field_password = password_hash(self::SAVED_USER_PASSWORD, PASSWORD_DEFAULT);
        $user->field_fname = 'John';
        $user->field_lname = 'Doe';
        $user->save();

        $this->saved_user = $user;
    }

    public function test_valid_fails_when_username_is_empty(): void {
        $user = new UserModel();
        $user->field_username = '';
        $user->field_email = 'test@example.com';

        $errors = $user->valid();

        $this->assertNotEmpty($errors);
    }

    public function test_valid_passes_with_correct_data(): void {
        $user = new UserModel();
        $user->field_username = 'testuser';
        $user->field_email = 'test@example.com';

        $errors = $user->valid();

        $this->assertTrue($errors);
    }

    public function test_save_persists_user_to_database(): void {
        $found = UserModel::get([
            ['name' => 'obj.username', 'type' => '=', 'value' => self::SAVED_USER_USERNAME]
        ]);

        $this->assertNotFalse($found);
        $this->assertEquals('saved@example.com', $found->field_email);
    }
    public function test_valid_fails_when_email_already_exists(): void {
        $user = new UserModel();
        $user->field_username = 'saved_user';
        $user->field_email = 'saved@example.com';
        $user->field_password = $user->password_hash("12345678");

        $errors = $user->valid();

        $this->assertNotEmpty($errors);
    }
    public function test_login(): void {
        $user = UserModel::get(array(
            [
                'name' => 'obj.username',
                'value' => self::SAVED_USER_USERNAME
            ]
        ));
        $is_loggin_success = $user->valid_password(self::SAVED_USER_PASSWORD);
        $is_loggin_failed = $user->valid_password("failed_password");

        $this->assertTrue($is_loggin_success);
        $this->assertNotEmpty($is_loggin_failed);
    }
}