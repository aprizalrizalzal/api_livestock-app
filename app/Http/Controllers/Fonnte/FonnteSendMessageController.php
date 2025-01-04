<?php

namespace App\Http\Controllers\Fonnte;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use App\Models\LivestockSpecies;
use App\Models\LivestockType;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Transaction;
use App\Service\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FonnteSendMessageController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function send_transaction_message_to_buyer($request, $transaction)
    {
        $user = $request->user();
        $profile = Profile::where('user_id', $user->id)->first();
        $livestock = Livestock::where('id', $transaction->livestock_id)->first();
        $livestockType = LivestockType::where('id', $livestock->livestock_type_id)->first();
        $livestockSpecies = LivestockSpecies::where('id', $livestock->livestock_species_id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Nomor telepon tidak ditemukan.'], 404);
        }

        $to = $profile->phone_number;
        $message = "Halo, _{$profile->name}_. Terima kasih atas pemesanan _{$livestockSpecies->name} ({$livestockType->name})_. Pesanan Anda sedang kami proses, mohon menunggu hingga selesai diproses oleh penjual.";

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

    public function send_transaction_message_to_seller($transaction)
    {
        $profile = Profile::where('id', $transaction->profile_id)->first();
        $livestock = Livestock::where('id', $transaction->livestock_id)->first();
        $livestockType = LivestockType::where('id', $livestock->livestock_type_id)->first();
        $livestockSpecies = LivestockSpecies::where('id', $livestock->livestock_species_id)->first();
        $livestockProfile = Profile::where('id', $livestock->profile_id)->first();

        if (!$livestockProfile) {
            return response()->json(['message' => 'Nomor telepon tidak ditemukan.'], 404);
        }

        $to = $livestockProfile->phone_number;
        $message = "Halo, _{$livestockProfile->name}_. Anda telah menerima pesanan baru untuk _{$livestockSpecies->name} ({$livestockType->name})_ dari _{$profile->name}_. Mohon segera proses pesanan tersebut dan pastikan pembeli mendapatkan informasi yang diperlukan.";

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

    public function send_payment_message_to_seller($payment)
    {
        $transaction = Transaction::where('id', $payment->transaction_id)->first();
        $profile = Profile::where('id', $transaction->profile_id)->first();
        $livestock = Livestock::where('id', $transaction->livestock_id)->first();
        $livestockType = LivestockType::where('id', $livestock->livestock_type_id)->first();
        $livestockSpecies = LivestockSpecies::where('id', $livestock->livestock_species_id)->first();
        $livestockProfile = Profile::where('id', $livestock->profile_id)->first();

        if (!$livestockProfile) {
            return response()->json(['message' => 'Nomor telepon tidak ditemukan.'], 404);
        }

        $to = $livestockProfile->phone_number;
        $message = "Halo, _{$livestockProfile->name}_. Anda telah menerima notifikasi pembayaran untuk pesanan _{$livestockSpecies->name} ({$livestockType->name})_ dari _{$profile->name}_. Mohon segera verifikasi pembayaran tersebut dan pastikan pembeli mendapatkan konfirmasi secepatnya.";

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
}
