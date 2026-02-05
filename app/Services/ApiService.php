<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected string $apiUrl = 'https://recruitment.fastprint.co.id/tes/api_tes_programmer';

    protected function generateUsername(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        return 'tesprogrammer' . $now->format('dmy') . 'C' . $now->format('H');
    }


    protected function generatePassword(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $day = $now->format('d');
        $month = $now->format('m');
        $year = $now->format('y');
        
        return md5("bisacoding-{$day}-{$month}-{$year}");
    }

    public function fetchProduk(): array
    {
        $username = $this->generateUsername();
        $password = $this->generatePassword();

        $response = Http::asForm()->post($this->apiUrl, [
            'username' => $username,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['error']) && $data['error'] == 0) {
                return [
                    'success' => true,
                    'data' => $data['data'] ?? [],
                ];
            }
        }

        return [
            'success' => false,
            'message' => $response->json()['ket'] ?? 'Gagal mengambil data dari API',
            'username' => $username,
            'password' => $password,
        ];
    }
}
