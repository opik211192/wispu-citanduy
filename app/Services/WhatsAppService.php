<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $apiKey;
    protected $numberKey;
    protected $bbwsNumber;
    protected $adminNumber;

    public function __construct()
    {
        $this->apiKey = env('WATZAP_API_KEY');
        $this->numberKey = env('WATZAP_NUMBER_KEY');
        $this->bbwsNumber = env('WATZAP_BBWS_NUMBER');
        $this->adminNumber = env('WATZAP_ADMIN_NUMBER');
    }

    /**
     * Mengirim pesan WhatsApp menggunakan nomor BBWS Citanduy
     * yang terhubung dengan Number Key WatZap.
     */
public function sendMessage($phone, $message)
{
    $response = Http::asJson()->post(
        'https://api.watzap.id/v1/send_message',
        [
            'api_key' => $this->apiKey,
            'number_key' => $this->numberKey,
            'phone_no' => $phone,
            'message' => $message,
        ]
    );

    \Log::info('WATZAP REQUEST', [
        'phone' => $phone,
        'api_key_terisi' => !empty($this->apiKey),
        'number_key_terisi' => !empty($this->numberKey),
        'http_status' => $response->status(),
        'response' => $response->json(),
        'raw_response' => $response->body(),
    ]);

    return $response;
}

    /**
     * Mengirim notifikasi kepada pelapor.
     */
    public function sendToPelapor($phone, $message)
    {
        return $this->sendMessage($phone, $message);
    }

    /**
     * Mengirim notifikasi kepada admin.
     */
    public function sendToAdmin($message)
    {
        if (!$this->adminNumber) {
            return null;
        }

        return $this->sendMessage(
            $this->adminNumber,
            $message
        );
    }

    /**
     * Mendapatkan nomor WhatsApp BBWS Citanduy.
     */
    public function getBbwsNumber()
    {
        return $this->bbwsNumber;
    }

    /**
     * Mendapatkan nomor admin.
     */
    public function getAdminNumber()
    {
        return $this->adminNumber;
    }
}