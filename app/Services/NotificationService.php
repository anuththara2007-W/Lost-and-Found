<?php
namespace App\Services;
class NotificationService
{
 public static function sendEmail($to, $subject, $body)
    {
       
        error_log("NotificationService: Sending EMAIL to $to | Subject: $subject");
        return true;
    }

}