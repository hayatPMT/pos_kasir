<?php

namespace App\Services;

class QRISService
{
    /**
     * Generate a simulated QRIS payload for a transaction.
     */
    public function generatePayload($invoice, $amount)
    {
        // In a real scenario, you'd call Midtrans/Xendit API here.
        // For simulation, we return a mock payload.
        return [
            'invoice' => $invoice,
            'amount' => $amount,
            'qr_string' => '00020101021226540014ID.LINKAJA.WWW01189360081100016053350215ID10200216000020303UMI51440014ID.CO.QRIS.WWW0215ID10202111160020303UME5204541153033605802ID5911POS KASIR6008JAKARTA61051234562070703A016304D1B2',
            'expires_at' => now()->addMinutes(15),
            'status' => 'pending',
        ];
    }

    /**
     * Check if the payment has been settled. (Simulated)
     */
    public function checkStatus($invoice)
    {
        // Simulation: randomly succeed after a few seconds or based on session
        // For development, we can use cache to simulate a "Pay" action from another side.
        $status = cache()->get("qris_status_{$invoice}", 'pending');

        return $status;
    }

    /**
     * Set payment status manually (for testing/simulating webhook).
     */
    public function simulateSuccess($invoice)
    {
        cache()->put("qris_status_{$invoice}", 'completed', 60);
    }
}
