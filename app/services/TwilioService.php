<?php

namespace App\services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        // Initialize Twilio client with credentials
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );
    }

    public function sendOTP($phoneNumber, $otp)
    {
        try {
            $message = $this->twilio->messages->create(
                $phoneNumber, // Destination phone number
                [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' => 'Your OTP code is: ' . $otp
                ]
            );
            return $message;
        } catch (\Exception $e) {
            // Handle exceptions
            return $e->getMessage();
        }
    }

}
