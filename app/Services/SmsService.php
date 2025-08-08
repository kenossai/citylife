<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $driver;

    public function __construct()
    {
        $this->driver = config('services.sms.driver', 'log');
    }

    /**
     * Send SMS notification
     * 
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        try {
            switch ($this->driver) {
                case 'twilio':
                    return $this->sendViaTwilio($phone, $message);
                case 'vonage':
                    return $this->sendViaVonage($phone, $message);
                case 'log':
                default:
                    return $this->sendViaLog($phone, $message);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'phone' => $phone,
                'message' => $message,
                'driver' => $this->driver,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send SMS via Twilio (placeholder)
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        // TODO: Implement Twilio integration
        Log::info('SMS would be sent via Twilio', [
            'phone' => $phone,
            'message' => $message,
            'timestamp' => now()
        ]);
        
        return true;
    }

    /**
     * Send SMS via Vonage (placeholder)
     */
    protected function sendViaVonage(string $phone, string $message): bool
    {
        // TODO: Implement Vonage integration
        Log::info('SMS would be sent via Vonage', [
            'phone' => $phone,
            'message' => $message,
            'timestamp' => now()
        ]);
        
        return true;
    }

    /**
     * Send SMS via log (default for development)
     */
    protected function sendViaLog(string $phone, string $message): bool
    {
        Log::info('SMS Notification (Log Driver)', [
            'phone' => $phone,
            'message' => $message,
            'timestamp' => now()
        ]);
        
        return true;
    }

    /**
     * Format phone number for SMS
     * 
     * @param string $phone
     * @return string
     */
    public function formatPhone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present (assuming US/Canada +1)
        if (strlen($phone) === 10) {
            $phone = '1' . $phone;
        }
        
        return '+' . $phone;
    }
}
