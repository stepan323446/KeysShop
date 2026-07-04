<?php
namespace KeysShop\Apps\Admin\Controllers;

use KeysShop\Apps\Admin\Controllers\Abstract\AdminBaseController;
use KeysShop\Includes\Routing\HttpExceptions\NotFound404;

class AdminDeleteController extends AdminBaseController
{
    const DELETE_MODELS = [
        [
            'url_name'      => 'users',
            'model'         => 'KeysShop\Apps\Users\Models\UserModel',
            'field_display' => 'field_username',
            'back_to'       => 'admin:user',
            'success_to'    => 'admin:user-list'
        ],
        [
            'url_name'      => 'feedbacks',
            'model'         => 'KeysShop\Apps\Contacts\Models\FeedbackModel',
            'field_display' => 'field_created_at',
            'back_to'       => 'admin:feedback',
            'success_to'    => 'admin:feedback-list'
        ],
        [
            'url_name'      => 'taxonomies',
            'model'         => 'KeysShop\Apps\Products\Models\TaxonomyModel',
            'field_display' => 'field_name',
            'back_to'       => 'admin:tax',
            'success_to'    => 'admin:taxonomy-list'
        ],
        [
            'url_name'      => 'products',
            'model'         => 'KeysShop\Apps\Products\Models\ProductModel',
            'field_display' => 'field_title',
            'back_to'       => 'admin:product',
            'success_to'    => 'admin:product-list'
        ],
        [
            'url_name'      => 'orders',
            'model'         => 'KeysShop\Apps\Order\Models\OrderModel',
            'field_display' => 'field_order_number',
            'back_to'       => 'admin:order',
            'success_to'    => 'admin:order-list'
        ],
        [
            'url_name'      => 'product-keys',
            'model'         => 'KeysShop\Apps\Products\Models\KeyModel',
            'field_display' => 'field_created_at',
            'back_to'       => 'admin:product-key',
            'success_to'    => 'admin:key-list'
        ],
    ];

    protected string $template_name = APPS_PATH . '/Admin/Templates/delete.php';

    /**
     * @return null|object
     */
    protected function get_model()
    {
        if (isset($this->__model))
            return $this->__model;

        $id = $this->url_context['url_2'];
        $model_class = '';

        foreach (self::DELETE_MODELS as $delete_model) {
            if($this->url_context['url_1'] == $delete_model['url_name']) {
                $model_class = $delete_model['model'];
                break;
            }
        }
        if(empty($model_class))
            return null;

        return $model_class::get(array(
            [
                'name' => 'obj.id',
                'type' => '=',
                'value' => $id
            ]
        ));
    }
    public function get_context_data()
    {
        $context = parent::get_context_data();

        $model = $this->get_model();
        if (empty($model))
            throw new NotFound404();

        // Display field to show what's model
        $field = '';
        $back_url = '';

        foreach (self::DELETE_MODELS as $delete_model) {
            if($this->url_context['url_1'] == $delete_model['url_name']) {
                $back_url = get_permalink($delete_model['back_to'], [$model->get_id()]);
                $field = $delete_model['field_display'];
            }
        }

        $context['back_url'] = $back_url;
        $context['model'] = $model;
        $context['field'] = $field;

        return $context;
    }
    protected function post()
    {
        $model = $this->get_model();

        if (empty($model))
            throw new NotFound404();

        $model->delete();

        // Redirect after delete
        $link = get_permalink('admin:home');
        foreach (self::DELETE_MODELS as $delete_model) {
            if($this->url_context['url_1'] == $delete_model['url_name']) {
                if(isset($delete_model['success_to'])) {
                    $link = get_permalink($delete_model['success_to']);
                }
                break;
            }
        }
        redirect_to($link);
    }
}