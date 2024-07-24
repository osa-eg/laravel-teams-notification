<?php

namespace Osama\LaravelTeamsNotification\Tests;

use Osama\LaravelTeamsNotification\TeamsNotification;
use PHPUnit\Framework\TestCase;

class TeamsNotificationTest extends TestCase
{
    protected $notification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notification = new TeamsNotification();
    }

    public function testSendNormalMessage()
    {
        $message = "This is a normal message.";
        $result = $this->notification->sendNormalMessage($message);

        $this->assertTrue($result);
    }

    public function testSendExceptionMessage()
    {
        $exception = new \Exception("Test Exception", 123);
        $result = $this->notification->sendException($exception);

        $this->assertTrue($result);
    }

    public function testSendJsonBlockMessage()
    {
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $result = $this->notification->sendJsonBlock($data);

        $this->assertTrue($result);
    }

    public function testSendMessageWithCustomColor()
    {
        $message = "This is a message with a custom color.";
        $result = $this->notification->setColor('accent')->sendNormalMessage($message);

        $this->assertTrue($result);
    }

    public function testSendSuccessMessage()
    {
        $message = "This is a success message.";
        $result = $this->notification->success()->sendNormalMessage($message);

        $this->assertTrue($result);
    }

    public function testSendWarningMessage()
    {
        $message = "This is a warning message.";
        $result = $this->notification->warning()->sendNormalMessage($message);

        $this->assertTrue($result);
    }

    public function testSendErrorMessage()
    {
        $message = "This is an error message.";
        $result = $this->notification->error()->sendNormalMessage($message);

        $this->assertTrue($result);
    }

    public function testSenderNameAndImage()
    {
        $this->notification->senderName("Test Sender");
        $this->notification->senderImageUrl("https://example.com/image.jpg");

        $message = "Message with sender details.";
        $result = $this->notification->sendNormalMessage($message);

        $this->assertTrue($result);
    }

    public function testBindTrace()
    {
        $exception = new \Exception("Test Exception with trace");
        $this->notification->bindTrace($exception->getTrace());

        $result = $this->notification->sendException($exception);

        $this->assertTrue($result);
    }
}
