<?php
namespace Apps\Order\Controllers;

use Apps\Order\Models\OrderModel;
use Apps\Products\Models\KeyModel;
use Includes\Model\CustomDateTime;
use Includes\RestController;
use Includes\Routing\HttpExceptions\BadRequest400;

class OrderStripeWebhook extends RestController {
    protected function post(): ?array
    {   
        global $pdo;

        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        $payload = file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, STRIPE_WEBHOOK_SECRET
            );
        } catch (\Exception $e) {
            http_response_code(400);
            return ['error' => 'Invalid signature'];
        }

        if ($event->type !== 'checkout.session.completed') {
            return ['received' => true];
        }

        $session = $event->data->object;
        $payment_intent_id = $session->payment_intent;

        $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        $user_id = $payment_intent->metadata->user_id;
        $key_ids = explode(',', $payment_intent->metadata->key_ids);

        $pdo->beginTransaction();
        try {
            $now = new CustomDateTime();

            $order = new OrderModel(array(
                'method'       => 'stripe',
                'order_number' => generate_uuid(),
                'user_id'      => $user_id,
                'created_at'   => $now
            ));
            $order->save();

            $keys = KeyModel::filter(array(
                [
                    'name'  => 'obj.id',
                    'type'  => 'IN',
                    'value' => $key_ids
                ]
            ), count: count($key_ids), block_for_update: true);

            foreach ($keys as $key) {
                if (!$key->is_available()) {
                    throw new BadRequest400('Key is already bought');
                }
                $key->field_order_id = $order->get_id();
                $key->field_bought_at = $now;
                $key->save();
            }

            // Capture payment to stripe
            $payment_intent->capture();

            $pdo->commit();

            return ['received' => true, 'order_id' => $order->get_id()];

        } catch (\Exception $ex) {
            $pdo->rollBack();

            // Cancel authorization and order in stripe
            try {
                $payment_intent->cancel();
            } catch (\Exception $cancel_ex) {
                
            }

            if (DEBUG_MODE) {
                return ['error' => $ex->getMessage()];
            }

            http_response_code(200);
            return ['received' => true, 'error' => 'processing_failed'];
        }
    }
}