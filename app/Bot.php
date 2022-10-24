<?php

class Bot {

    private $bot_token;
    private $path_to_config;

    public function __construct($path = "json/config.json") {
        $this->path_to_config = $path;
        $config = json_decode(file_get_contents($path));
        $this->bot_token = $config->token;
    }

    public function getToken() {
        return $this->bot_token;
    }

    public function getData() {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        return array(
            "chat_id" => $data['message']['from']['id'],
            "username" => $data['message']['from']['username'],
            "text" => trim($data['message']['text'])
        );
    }

    public function getIsHard() {
        $status = json_decode(file_get_contents($this->path_to_config));
        return $status->is_hard;
    }

    public function setIsHard($value) {
        $status = json_decode(file_get_contents($this->path_to_config));
        $status->is_hard = $value;
        file_put_contents($this->path_to_config, json_encode($status));
    }

    public function sendMessage($text) {
        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $this->bot_token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => "-1001453539385",
                'parse_mode' => 'HTML',
                'text' => $text,
                'reply_markup' => '',
            ]
        ];
        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
    }

}