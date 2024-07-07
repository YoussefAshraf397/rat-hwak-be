<?php

namespace App\Notify;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class TelegramNotifier
{
    protected string $token;

    protected int $chatId;

    protected Client $client;

    public function __construct()
    {
        $this->token = getenv('TELEGRAM_TOKEN');
        $this->chatId = (int)getenv('TELEGRAM_CHAT_ID');
        $this->client = new Client([
            "base_uri" => "https://api.telegram.org",
        ]);
    }

    public function getBotUpdates(): array
    {
        $response = $this->client->request("GET", "/bot$this->token/getUpdates");

        return json_decode($response->getBody()->getContents(), true);
    }

    public function notify($text): void
    {
        try {
            $url = sprintf('/bot%s/sendMessage', $this->token);

            $this->client->post($url, [
                RequestOptions::JSON => [
                    'chat_id' => $this->chatId,
                    'text' => $text,
                ]
            ]);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
