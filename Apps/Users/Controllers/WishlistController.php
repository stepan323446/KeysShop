<?php
namespace Apps\Users\Controllers;

use Apps\Products\Models\ProductModel;
use Includes\BaseController;



class WishlistController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/wishlist.php';
    protected ?string $allow_role = 'user';

    public function get_context_data() {
        $context = parent::get_context_data();

        $context['products'] = ProductModel::filter(
            array(
                [
                    'name'      => 'is_in_wishlist',
                    'value'     => 1,
                    'is_having' => true
                ]
            )
        );

        return $context;
    }
}