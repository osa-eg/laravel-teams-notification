<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
use Osama\LaravelTeamsNotification\TeamsNotification;
use Tests\TestCase;
use Mockery;

class TeamsNotificationTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // Mocking HTTP requests to avoid actual API calls
        Http::fake();
    }

    public function testSendMessage()
    {
        $notification = new TeamsNotification();
        $response = $notification->setColor('good')->sendMessage('Test Message', ['Key1' => 'Value1', 'Key2' => 'Value2']);

        $expected = [
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
                        "body" => [
                            [
                                "type" => "TextBlock",
                                "size" => "Medium",
                                "weight" => "Bolder",
                                "color" => "good",
                                "text" => 'Test Message'
                            ],
                            [
                                "type" => "ColumnSet",
                                "columns" => [
                                    [
                                        "type" => "Column",
                                        "items" => [
                                            [
                                                "type" => "TextBlock",
                                                "text" => 'Key1',
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
                                                "text" => 'Value1',
                                                "wrap" => true
                                            ]
                                        ],
                                        "width" => "stretch"
                                    ]
                                ]
                            ],
                            [
                                "type" => "ColumnSet",
                                "columns" => [
                                    [
                                        "type" => "Column",
                                        "items" => [
                                            [
                                                "type" => "TextBlock",
                                                "text" => 'Key2',
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
                                                "text" => 'Value2',
                                                "wrap" => true
                                            ]
                                        ],
                                        "width" => "stretch"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        Http::assertSent(function ($request) use ($expected) {
            return $request->data() === $expected;
        });
    }

    public function testSendException()
    {
        $exception = new \Exception('Test Exception', 1, new \ErrorException('Previous exception'));

        $notification = new TeamsNotification();
        $notification->bindTrace(); // To include the trace
        $response = $notification->sendException($exception);

        $expected = [
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
                        "body" => [
                            [
                                "type" => "TextBlock",
                                "size" => "Large",
                                "weight" => "Bolder",
                                "text" => "Exception Occurred",
                                "color" => "attention"
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
                                                "text" => 'Test Exception',
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
                                                "text" => '',
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
                                                "text" => '',
                                                "wrap" => true
                                            ]
                                        ],
                                        "width" => "stretch"
                                    ]
                                ]
                            ],
                            [
                                "type" => "TextBlock",
                                "text" => "Trace:",
                                "weight" => "Bolder"
                            ],
                            [
                                "type" => "TextBlock",
                                "text" => nl2br($exception->getTraceAsString()),
                                "wrap" => true
                            ]
                        ]
                    ]
                ]
            ]
        ];

        Http::assertSent(function ($request) use ($expected) {
            return $request->data() === $expected;
        });
    }

    public function testSendJsonMessage()
    {
        $notification = new TeamsNotification();
        $response = $notification->sendJsonMessage('JSON Message', ['key1' => 'value1', 'key2' => 'value2']);

        $expected = [
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
                        "body" => [
                            [
                                "type" => "TextBlock",
                                "size" => "Medium",
                                "weight" => "Bolder",
                                "color" => "default",
                                "text" => 'JSON Message'
                            ],
                            [
                                "type" => "TextBlock",
                                "text" => json_encode(['key1' => 'value1', 'key2' => 'value2'], JSON_PRETTY_PRINT),
                                "wrap" => true
                            ]
                        ]
                    ]
                ]
            ]
        ];

        Http::assertSent(function ($request) use ($expected) {
            return $request->data() === $expected;
        });
    }

    public function testSetColor()
    {
        $notification = new TeamsNotification();
        $notification->setColor('dark');

        $this->assertEquals('dark', $notification->color);
    }

    public function testInvalidColor()
    {
        $this->expectException(\InvalidArgumentException::class);

        $notification = new TeamsNotification();
        $notification->setColor('invalid_color');
    }
}
