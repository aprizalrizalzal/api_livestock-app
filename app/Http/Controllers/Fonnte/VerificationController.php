<?php

namespace App\Http\Controllers\Fonnte;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Service\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function send_verification_message(Request $request)
    {
        $user = $request->user();
        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Nomor telepon tidak ditemukan.'], 404);
        }

        // Generate tautan verifikasi unik
        $token = Str::random(40);
        $verificationLink = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['token' => $token, 'profile' => $profile->id]
        );

        // Simpan token sementara
        $profile->update(['verification_token' => $token]);

        $to = $profile->phone_number;
        $message = "Halo, {$profile->name}! Klik tautan ini untuk memverifikasi nomor telepon Anda \n*{$verificationLink}* \nTautan berlaku selama 30 menit.";

        try {
            // Kirim pesan
            $response = $this->fonnte->sendMessage($to, $message);

            // Sesuaikan pengecekan status respons sesuai dengan struktur data yang dikembalikan Fonnte
            if (is_array($response) && isset($response['status']) && $response['status'] === true) {
                Log::info('Pesan berhasil dikirim ke WhatsApp melalui Fonnte: ', $response);
                return response()->json(['message' => 'Pesan verifikasi berhasil dikirim.'], 200);
            } else {
                Log::error('Gagal mengirim pesan via Fonnte: ' . json_encode($response));
                return response()->json(['message' => 'Gagal mengirim pesan verifikasi.'], 500);
            }
        } catch (\Exception $e) {
            // Log pesan error atau lakukan tindakan lainnya
            Log::error('Gagal mengirim pesan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim pesan verifikasi.'], 500);
        }
    }

    public function verify(Request $request)
    {
        // Validasi token
        $profile = Profile::where('id', $request->profile)
                        ->where('verification_token', $request->token)
                        ->first();

        if (!$profile || !$request->hasValidSignature()) {
            return $this->generateHtmlResponse('Tautan tidak valid atau telah kadaluarsa.', 'error');
        }

        // Update phone_number_verified_at
        $profile->update([
            'phone_number_verified_at' => Carbon::now(),
            'verification_token' => null // Hapus token setelah verifikasi
        ]);

        return $this->generateHtmlResponse('Nomor telepon berhasil diverifikasi.', 'success');
    }

    private function generateHtmlResponse($message, $status)
    {
        // Tentukan class CSS untuk gaya
        $statusClass = $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

        return response()->make("
            <!DOCTYPE html>
            <html lang='id'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Verifikasi</title>
                    <style>
                        body {
                            font-family: 'Arial', sans-serif;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            background-color: #f0f4f8;
                        }
                        .container {
                            text-align: center;
                            padding: 20px;
                            border-radius: 8px;
                            width: 400px;
                            background-color: white;
                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        }
                        .message {
                            font-size: 18px;
                            margin-bottom: 20px;
                        }
                        .button {
                            background-color: #007bff;
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 4px;
                            cursor: pointer;
                            text-decoration: none;
                        }
                        .button:hover {
                            background-color: #0056b3;
                        }
                        .${statusClass} {
                            padding: 10px;
                            margin: 10px 0;
                            border-radius: 8px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='${statusClass}'>
                            <p class='message'>$message</p>
                        </div>
                        <a href='http://localhost:3000' class='button'>Kembali ke Beranda</a>
                    </div>
                </body>
            </html>
        ");
    }
}
