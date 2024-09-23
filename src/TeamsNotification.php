<?php

namespace Osama\LaravelTeamsNotification;

use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;

class TeamsNotification
{
    protected $webhookUrl;
    protected $exception;
    protected $includeTrace = false;
    protected $additionalDetails = [];
    protected $color = 'default'; // Default color

    // Valid color options
    protected $validColors = [
        "default", "dark", "light", "accent", "good", "warning", "attention"
    ];

    protected $client;

    public function __construct($webhookUrl = null)
    {
        $this->webhookUrl = $webhookUrl ?: config('teams.webhook_url');
        $this->client = new Client(); // Initialize Guzzle client
    }

    // Method to set the color and allow chaining
    public function setColor(string $color)
    {
        if (!in_array($color, $this->validColors)) {
            throw new InvalidArgumentException('Invalid color specified.');
        }
        $this->color = $color;
        return $this;
    }

    // Method to set success color and allow chaining
    public function success()
    {
        $this->color = 'good';
        return $this;
    }

    // Method to set warning color and allow chaining
    public function warning()
    {
        $this->color = 'warning';
        return $this;
    }

    // Method to set error color and allow chaining
    public function error()
    {
        $this->color = 'attention';
        return $this;
    }

    // Method to send a normal message with additional details
    public function sendMessage($message, array $details = [])
    {
        $this->additionalDetails = $details;
        $card = $this->buildNormalMessageCard($message);
        return $this->sendToTeams($card);
    }

    // Method to send an exception message with optional trace
    public function sendException(\Exception $exception)
    {
        $this->exception = $exception;
        $this->error(); // Default color for exceptions
        $card = $this->buildExceptionCard();
        return $this->sendToTeams($card);
    }

    // Method to include the trace in the exception message
    public function bindTrace()
    {
        $this->includeTrace = true;
        return $this;
    }

    // Method to send a message with an array as a JSON block
    public function sendJsonMessage($message, array $data)
    {
        $card = $this->buildJsonMessageCard($message, $data);
        return $this->sendToTeams($card);
    }

    // Method to build the adaptive card for a normal message with additional details
    protected function buildNormalMessageCard($message)
    {
        $body = [
            [
                "type" => "TextBlock",
                "size" => "Medium",
                "weight" => "Bolder",
                "color" => $this->color,
                "text" => $message
            ]
        ];

        if (!empty($this->additionalDetails)) {
            foreach ($this->additionalDetails as $key => $value) {
                $body[] = [
                    "type" => "ColumnSet",
                    "columns" => [
                        [
                            "type" => "Column",
                            "items" => [
                                [
                                    "type" => "TextBlock",
                                    "text" => $key,
                                    "weight" => "Bolder"
                                ]
                            ],
                            "width" => "stretch"
                        ],
                        [
                            "type" => "Column",
                            "items" => [
                                [
                                    "type" => "TextBlock",
                                    "text" => is_array($value)?json_encode($value,JSON_PRETTY_PRINT):$value,
                                    "wrap" => true
                                ]
                            ],
                            "width" => "stretch"
                        ]
                    ]
                ];
            }
        }

        return [
            "type" => "message",
            "attachments" => [
                [
                    "contentType" => "application/vnd.microsoft.card.adaptive",
                    "content" => [
                        "\$schema" => "http://adaptivecards.io/schemas/adaptive-card.json",
                        "type" => "AdaptiveCard",
                        "version" => "1.2",
                        "msteams" => [
                            "width" => "Full"
                        ],
                        "body" => $body
                    ]
                ]
            ]
        ];
    }

    // Method to build the adaptive card for an exception message
    protected function buildExceptionCard()
    {
        $body = [
            [
                "type" => "TextBlock",
                "size" => "Large",
                "weight" => "Bolder",
                "text" => "Exception Occurred",
                "color" => $this->color
            ],
            [
                "type" => "ColumnSet",
                "columns" => [
                    [
                        "type" => "Column",
                        "items" => [
                            [
                                "type" => "TextBlock",
                                "text" => "Message:",
                                "weight" => "Bolder"
                            ],
                            [
                                "type" => "TextBlock",
                                "text" => $this->exception->getMessage(),
                                "wrap" => true
                            ]
                        ],
                        "width" => "stretch"
                    ],
                    [
                        "type" => "Column",
                        "items" => [
                            [
                                "type" => "TextBlock",
                                "text" => "File:",
                                "weight" => "Bolder"
                            ],
                            [
                                "type" => "TextBlock",
                                "text" => $this->exception->getFile(),
                                "wrap" => true
                            ]
                        ],
                        "width" => "stretch"
                    ],
                    [
                        "type" => "Column",
                        "items" => [
                            [
                                "type" => "TextBlock",
                                "text" => "Line:",
                                "weight" => "Bolder"
                            ],
                            [
                                "type" => "TextBlock",
                                "text" => $this->exception->getLine(),
                                "wrap" => true
                            ]
                        ],
                        "width" => "stretch"
                    ]
                ]
            ]
        ];

        if ($this->includeTrace) {
            $body[] = [
                "type" => "TextBlock",
                "text" => "Trace:",
                "weight" => "Bolder"
            ];
            $body[] = [
                "type" => "TextBlock",
                "text" => nl2br($this->exception->getTraceAsString()),
                "wrap" => true
            ];
        }

        return [
            "type" => "message",
            "attachments" => [
                [
                    "contentType" => "application/vnd.microsoft.card.adaptive",
                    "content" => [
                        "\$schema" => "http://adaptivecards.io/schemas/adaptive-card.json",
                        "type" => "AdaptiveCard",
                        "version" => "1.2",
                        "msteams" => [
                            "width" => "Full"
                        ],
                        "body" => $body
                    ]
                ]
            ]
        ];
    }

    // Method to build the adaptive card for a message with a JSON block
    protected function buildJsonMessageCard($message, array $data)
    {
        $body = [
            [
                "type" => "TextBlock",
                "size" => "Medium",
                "weight" => "Bolder",
                "color" => $this->color,
                "text" => $message
            ],
            [
                "type" => "TextBlock",
                "text" => json_encode($data, JSON_PRETTY_PRINT),
                "wrap" => true
            ]
        ];

        return [
            "type" => "message",
            "attachments" => [
                [
                    "contentType" => "application/vnd.microsoft.card.adaptive",
                    "content" => [
                        "\$schema" => "http://adaptivecards.io/schemas/adaptive-card.json",
                        "type" => "AdaptiveCard",
                        "version" => "1.2",
                        "msteams" => [
                            "width" => "Full"
                        ],
                        "body" => $body
                    ]
                ]
            ]
        ];
    }

    // Method to send the constructed adaptive card message to Teams
    protected function sendToTeams(array $card)
    {
        try {
            $response = $this->client->post($this->webhookUrl, [
                'json' => $card
            ]);
            return $response->getStatusCode(); // Return the HTTP status code
        } catch (\Exception $e) {
            // Handle the exception, e.g., log it or rethrow it
            throw new RuntimeException('Failed to send message to Teams: ' . $e->getMessage());
        }
    }
}
