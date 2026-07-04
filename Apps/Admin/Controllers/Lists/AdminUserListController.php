<?php
namespace Apps\Admin\Controllers\Lists;

use Apps\Admin\Controllers\Abstract\AdminListController;

class AdminUserListController extends AdminListController {
    protected string $model_сlass_name = "Apps\Users\Models\UserModel";
    protected array $table_fields = array(
        'Username' => 'field_username',
        'Public Name	' => 'get_public_name()',
        'E-mail	' => 'field_email',
        'Status	' => 'get_role()',
    );
    protected string $single_router_name = 'admin:user';
    protected ?string $create_router_name = 'admin:user-new';
    protected string $verbose_name = "user";
    protected string $verbose_name_multiply = "users";
}