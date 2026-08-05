<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Core\MailService;

class MailIntegrationTest extends TestCase
{
    /**
     * @group mail
     * @group integration
     */
    public function testSendNotification()
    {
        // We use a dummy email address for testing
        $toEmail = "test@example.com";
        $toName = "Test User";
        $annonceTitle = "Test Annonce";
        $senderName = "Sender Name";

        // Attempt to send the notification
        $result = MailService::sendNotification($toEmail, $toName, $annonceTitle, $senderName);

        // In a real environment with Mailpit running, this should return true
        // If it fails, it will log the error to error_log
        $this->assertTrue($result, "Failed to send email notification. Check SMTP configuration and if the mail service is running.");
    }
}
