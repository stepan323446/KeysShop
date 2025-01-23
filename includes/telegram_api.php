<?php
class TelegramBot {
    private $chatId;
    private $telegramBotToken;

    public function __construct($token, $chatId) {
        $this->telegramBotToken = $token;
        $this->chatId = $chatId;
    }
    private function get_api_url() {
        return  "https://api.telegram.org/bot" . $this->telegramBotToken . "/";
    }

    public function sendMessage($message) {
        $url = $this->get_api_url() . "sendMessage";

        $data = [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return false;
        }

        return true;
    }
}
?>