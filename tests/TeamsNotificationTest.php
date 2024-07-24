<?php

namespace Osama\LaravelTeamsNotification\Tests;

use Osama\LaravelTeamsNotification\TeamsNotification;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class TeamsNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the HTTP client to prevent actual HTTP requests
        Http::fake();
    }

    public function testSetColor()
    {
        $notification = new TeamsNotification();
        $notification->setColor('good');
        $this->assertEquals('good', $notification->color);

        $this->expectException(\InvalidArgumentException::class);
        $notification->setColor('invalid-color');
    }

    public function testSuccessMethod()
    {
        $notification = new TeamsNotification();
        $notification->success();
        $this->assertEquals('good', $notification->color);
    }

    public function testWarningMethod()
    {
        $notification = new TeamsNotification();
        $notification->warning();
        $this->assertEquals('warning', $notification->color);
    }

    public function testErrorMethod()
    {
        $notification = new TeamsNotification();
        $notification->error();
        $this->assertEquals('attention', $notification->color);
    }

    public function testSendMessage()
    {
        $notification = new TeamsNotification();
        $notification->setColor('dark');
        $notification->sendMessage('Test message', ['key1' => 'value1']);

        // Check if HTTP request was made with correct payload
        Http::assertSent(function ($request) {
            $payload = $request->data();
            return isset($payload['attachments'][0]['content']['body']) &&
                $payload['attachments'][0]['content']['body'][0]['text'] === 'Test message' &&
                $payload['attachments'][0]['content']['body'][1]['columns'][0]['items'][0]['text'] === 'key1' &&
                $payload['attachments'][0]['content']['body'][1]['columns'][1]['items'][0]['text'] === 'value1';
        });
    }

    public function testSendException()
    {
        $notification = new TeamsNotification();
        $exception = new \Exception('Test exception message');
        $notification->sendException($exception);

        // Check if HTTP request was made with correct payload
        Http::assertSent(function ($request) use ($exception) {
            $payload = $request->data();
            return isset($payload['attachments'][0]['content']['body']) &&
                $payload['attachments'][0]['content']['body'][0]['text'] === 'Exception Occurred' &&
                $payload['attachments'][0]['content']['body'][1]['columns'][0]['items'][1]['text'] === $exception->getMessage();
        });
    }

    public function testBindTrace()
    {
        $notification = new TeamsNotification();
        $exception = new \Exception('Test exception message');
        $notification->bindTrace()->sendException($exception);

        // Check if HTTP request was made with trace included
        Http::assertSent(function ($request) use ($exception) {
            $payload = $request->data();
            return isset($payload['attachments'][0]['content']['body'][3]['text']) &&
                strpos($payload['attachments'][0]['content']['body'][3]['text'], 'Trace:') !== false;
        });
    }

    public function testSendJsonMessage()
    {
        $notification = new TeamsNotification();
        $notification->sendJsonMessage('Test JSON message', ['key' => 'value']);

        // Check if HTTP request was made with correct payload
        Http::assertSent(function ($request) {
            $payload = $request->data();
            return isset($payload['attachments'][0]['content']['body']) &&
                $payload['attachments'][0]['content']['body'][0]['text'] === 'Test JSON message' &&
                json_decode($payload['attachments'][0]['content']['body'][1]['text'], true) === ['key' => 'value'];
        });
    }
}
