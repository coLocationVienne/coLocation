<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Core\MailService;

class MailIntegrationTest extends TestCase
{
    public function testSendNotification()
    {
        $toEmail = "test@example.com";
        $toName = "Test User";
        $annonceTitle = "Test Annonce";
        $senderName = "Sender Name";

        $result = MailService::sendNotification($toEmail, $toName, $annonceTitle, $senderName);

        $this->assertTrue($result, "Failed to send email notification. Check SMTP configuration and if the mail service is running.");
    }
}
