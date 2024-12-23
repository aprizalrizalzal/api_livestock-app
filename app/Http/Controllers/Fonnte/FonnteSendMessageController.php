<?php

namespace App\Http\Controllers\Fonnte;

use App\Http\Controllers\Controller;
use App\Service\FonnteService;
use Illuminate\Support\Facades\Log;

class FonnteSendMessageController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function send_verification_message($service)
    {
        $to = '087765889202';

        if ($to) {
            $message = $service;
            try {
                // Kirim pesan
                $response = $this->fonnte->sendMessage($to, $message);
                // Mencatat log respon
                Log::info('Fonnte Response for Service Message:', ['response' => $response]);
            } catch (\Exception $e) {
                // Log pesan error atau lakukan tindakan lainnya
                Log::error('Failed to send message: ' . $e->getMessage());

                // Anda juga bisa menambahkan flash message atau indikasi lainnya untuk pengguna
                session()->flash('error', 'Gagal mengirim pesan, silakan coba lagi nanti.');
            }
        } else {
            Log::warning('Error');
        }

        // return redirect()->route('show.services')->with('messages', 'Pesan Terkirim');
    }

    // public function send_service_detail_message($service_code)
    // {
    //     $company = Company::first();
    //     $service = Service::with([
    //         'customer',
    //         'customer.user',
    //         'serviceDetail',
    //         'serviceDetail.user'
    //     ])->where('service_code', $service_code)->first();

    //     $to = $service->customer->phone;

    //     if ($service) {
    //         $message = "*" . $company->name . "*\n"
    //             . "" . $company->address . "\n\n"
    //             . "Teknisi *" . $service->serviceDetail->user->name . "*\n"
    //             . "Kode Layanan *" . $service->service_code . "*\n"
    //             . "Status *" . ucfirst(strtolower($service->serviceDetail->status)) . "*\n\n"
    //             . "Untuk informasi lebih lengkap, Anda bisa mengunjungi tautan "
    //             . "*_" . url("/service-code-" . $service->service_code . "") . "_*\n\n"
    //             . "Atau masuk menggunakan Email *" . $service->customer->user->email . "* dan Kata Sandi *@amitech* "
    //             . "*pada tautan _" . url("/login") . "_*\n\n"
    //             . "Terima kasih atas kepercayaan Anda terhadap layanan kami. Salam hangat dari Teknisi 😊. Sehat selalu kak *" . $service->customer->user->name . "*\n";

    //         try {
    //             // Kirim pesan
    //             $response = $this->fonnte->sendMessage($to, $message);
    //             // Mencatat log respon
    //             Log::info('Fonnte Response for Service  Message:', ['response' => $response]);
    //         } catch (\Exception $e) {
    //             // Log pesan error atau lakukan tindakan lainnya
    //             Log::error('Failed to send message: ' . $e->getMessage());

    //             // Anda juga bisa menambahkan flash message atau indikasi lainnya untuk pengguna
    //             session()->flash('error', 'Gagal mengirim pesan, silakan coba lagi nanti.');
    //         }
    //     } else {
    //         Log::warning('Service not found for service code: ' . $service_code);
    //     }

    //     return redirect()->route('show.service.details')->with('messages', 'Pesan Terkirim');
    // }

    // public function send_payment_message($payment_code)
    // {
    //     $company = Company::first();
    //     $payment = Payment::with([
    //         'serviceDetail',
    //         'serviceDetail.service',
    //         'serviceDetail.service.customer',
    //         'serviceDetail.service.customer.user'
    //     ])->where('payment_code', $payment_code)->first();

    //     $to = $payment->serviceDetail->service->customer->phone;

    //     if ($payment) {
    //         $message = "*" . $company->name . "*\n"
    //             . "" . $company->address . "\n\n"
    //             . "Kode Pembayaran *" . $payment->payment_code . "*\n"
    //             . "Metode Pembayaran *" . $payment->payment_method . "*\n"
    //             . "Status *" . ucfirst(strtolower($payment->status)) . "*\n\n"
    //             . "Untuk informasi lebih lengkap, Anda bisa masuk menggunakan Email *" . $payment->serviceDetail->service->customer->user->email . "* dan Kata Sandi *@amitech* "
    //             . "pada tautan *_" . url("/login") . "_*\n\n"
    //             . "Terima kasih atas kepercayaan Anda terhadap layanan kami. Salam hangat dari Admin 😊. Sehat selalu kak *" . $payment->serviceDetail->service->customer->user->name . "*\n";

    //         try {
    //             // Kirim pesan
    //             $response = $this->fonnte->sendMessage($to, $message);
    //             // Mencatat log respon
    //             Log::info('Fonnte Response for Payment  Message:', ['response' => $response]);
    //         } catch (\Exception $e) {
    //             // Log pesan error atau lakukan tindakan lainnya
    //             Log::error('Failed to send message: ' . $e->getMessage());

    //             // Anda juga bisa menambahkan flash message atau indikasi lainnya untuk pengguna
    //             session()->flash('error', 'Gagal mengirim pesan, silakan coba lagi nanti.');
    //         }
    //     } else {
    //         Log::warning('Payment not found for payment code: ' . $payment_code);
    //     }

    //     return redirect()->route('show.payments')->with('messages', 'Pesan Terkirim');
    // }
}
