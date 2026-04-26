<?php
namespace App\Services;

/**
 * NotificationService
 * 
 * Handles all outward bound communication (Email, SMS) scaffolds.
 * In a production environment, this would integrate with Twilio/SendGrid.
 */
class NotificationService
{
    /**
     * Send an email notification
     */
    public static function sendEmail($to, $subject, $body)
    {
        // Scaffold implementation
        // e.g. mail($to, $subject, $body, $headers);
        error_log("NotificationService: Sending EMAIL to $to | Subject: $subject");
        return true;
    }

    /**
     * Send an SMS notification
     */
    public static function sendSMS($phoneNumber, $message)
    {
        // Scaffold implementation
        // e.g. Twilio API call here
        error_log("NotificationService: Sending SMS to $phoneNumber | Message: $message");
        return true;
    }
}


//havent time to impliment