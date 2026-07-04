<?php
namespace KeysShop\Apps\Contacts\Controllers;

use Exception;
use KeysShop\Apps\Contacts\Models\FeedbackModel;
use KeysShop\Includes\BaseController;
use KeysShop\Includes\Model\ValidationError;
use KeysShop\Includes\TelegramBot;

class ContactController extends BaseController {
    protected string $template_name = APPS_PATH . '/Contacts/Templates/contacts.php';

    function post() {
        $context = array();

        $message_name = $_POST['name'];
        $message_email = $_POST['email'];
        $message_content = $_POST['content'];
        
        if(!empty($message_name) && !empty($message_email) && !empty($message_content)) {
            // Save message to db
            $feedback = new FeedbackModel(array(
                'name' => $message_name,
                'email' => $message_email,
                'content' => $message_content
            ));
            try {
                $feedback->save();
            }
            catch (ValidationError $ex) {
                $this->context['error_form'] = $ex;
                return;
            }
            catch(Exception $ex) {
                $this->context['error_message'] = 'Unexpected error';
                return;
            }

            // Send message to telegram bot
            $tg_bot = new TelegramBot(TELEGRAM_BOT_TOKEN, TELEGRAM_BOT_CHATID);
            $is_tg_sent = $tg_bot->sendMessage(<<<EOT
<b>Name:</b>
$message_name

<b>Email:</b>
$message_email

<b>Content:</b>
$message_content
EOT);
            $context['success_message'] = 'Your message has been sent successfully';
        }
        else {
            $context['error_message'] = 'You didn\'t fill in all the fields.';
        }
        $this->context = $context;
    }
}