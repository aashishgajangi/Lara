<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function createOrder($amount, $receiptId, $currency = 'INR')
    {
        try {
            $orderData = [
                'receipt'         => (string) $receiptId,
                'amount'          => $amount * 100, // Amount in paise
                'currency'        => $currency,
                'payment_capture' => 1 // Auto capture
            ];
            
            $razorpayOrder = $this->api->order->create($orderData);
            
            return $razorpayOrder;
        } catch (Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifySignature($attributes)
    {
        try {
            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (Exception $e) {
            Log::error('Razorpay Signature Verification Failed: ' . $e->getMessage());
            return false;
        }
    }
}
