<?php

namespace Osama\LaravelTeamsNotification;

use GuzzleHttp\Client;

class TeamsNotification
{
    protected $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('teams.webhook_url');
    }

    public function send($message)
    {
        $client = new Client();

        $body = [
            "type" => "message",
            "attachments" => [
                [
                    "contentType" => "application/vnd.microsoft.card.adaptive",
                    "contentUrl" => null,
                    "content" => [
                        "\$schema" => "http://adaptivecards.io/schemas/adaptive-card.json",
                        "type" => "AdaptiveCard",
                        "version" => "1.2",
                        "body" => [
                            [
                                "type" => "TextBlock",
                                "text" => $message,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $client->post($this->webhookUrl, [
            'json' => $body,
        ]);

        return $response->getStatusCode();
    }
}
