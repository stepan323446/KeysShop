<?php
namespace KeysShop\Apps\Admin\Controllers;

use DateTime;
use KeysShop\Apps\Admin\Controllers\Abstract\AdminBaseController;
use KeysShop\Apps\Order\Models\OrderModel;
use KeysShop\Apps\Products\Models\KeyModel;
use KeysShop\Apps\Users\Models\UserModel;

class AdminHomeController extends AdminBaseController {
    protected string $template_name = APPS_PATH . '/Admin/Templates/home.php';
    
    public function get_context_data() {
        global $pdo;

        $context = parent::get_context_data();

        $datetime_month_ago = new DateTime();
        $datetime_month_ago->modify("-1 month");
        $dateStr = $datetime_month_ago->format('Y-m-d H:i:s');
        
        // Stats
        $context['new_user_count'] = UserModel::count(array(
            [
                'name'      =>      'obj.register_at',
                'type'      =>      '>=',
                'value'     =>      $dateStr
            ]
        ));
        
       
        $stmt = $pdo->prepare('SELECT SUM(price) FROM ' . KeyModel::get_table_name() . ' WHERE bought_at > :date');
        $stmt->execute(['date' => $dateStr]);
        $context['total_sales'] = $stmt->fetchColumn();

        $stmt = $pdo->prepare('SELECT SUM(original_price) FROM ' . KeyModel::get_table_name() . ' WHERE created_at > :date');
        $stmt->execute(['date' => $dateStr]);
        $context['total_loss'] = $stmt->fetchColumn();

        $context['total_profit'] = $context['total_sales'] - $context['total_loss'];

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ' . OrderModel::get_table_name() . ' WHERE created_at > :date');
        $stmt->execute(['date' => $dateStr]);
        $context['total_orders'] = $stmt->fetchColumn();

        $context['last_orders'] = OrderModel::filter(
            sort_by: ['-obj.created_at'],
        );
        

        return $context;
    }
}