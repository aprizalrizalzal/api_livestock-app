<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPayments(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        $profileId = $profile->id;

        if ($user->hasRole(['admin'])) {
            $payments = Payment::with('transaction', 'transaction.profile', 'transaction.livestock', 'transaction.livestock.livestockType', 'transaction.livestock.livestockSpecies', 'transaction.livestock.profile')->get();
        } else if ($user->hasRole(['seller'])) {
            $payments = Payment::with('transaction', 'transaction.profile', 'transaction.livestock', 'transaction.livestock.livestockType', 'transaction.livestock.livestockSpecies', 'transaction.livestock.profile')->whereHas('transaction', function ($query) use ($profileId) {
                $query->whereHas('livestock', function ($query) use ($profileId) {
                    $query->whereHas('profile', function ($query) use ($profileId) {
                        $query->where('id', $profileId);
                    });
                });
            })->get();
        } else if ($user->hasRole(['buyer'])) {
            $payments = Payment::with('transaction', 'transaction.profile', 'transaction.livestock', 'transaction.livestock.livestockType', 'transaction.livestock.livestockSpecies', 'transaction.livestock.profile')->whereHas('transaction', function ($query) use ($profileId) {
                $query->whereHas('profile', function ($query) use ($profileId) {
                    $query->where('id', $profileId);
                });
            })->get();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        if ($payments->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil diambil!.',
            'payments' => $payments
        ], 200);
    }

    public function postPaymentByIdTransaction(Request $request, string $transactionId)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findTransaction = Transaction::find($transactionId);

        if (!$findTransaction) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;
        $transactionProfileId = $findTransaction->profile->id;

        $existingPayment = Payment::where('transaction_id', $transactionId)->first();

        if ($profileId !== $transactionProfileId || $existingPayment) {
            return response()->json([
                'message' => 'Pembayaran untuk transaksi ini sudah ada.'
            ], 400);
        }

        if ($user->hasRole(['buyer'])) {
            $payment = Payment::create([
                'transaction_id' => $findTransaction->id,
                'date' => Carbon::now(),
                'price' => $findTransaction->livestock->price,
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil dibuat!.',
            'payment' => $payment
        ], 201);
    }

    public function getPaymentById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if ($user->hasRole(['admin', 'seller', 'buyer'])) {
            $findPayment = Payment::with('transaction', 'transaction.profile', 'transaction.animal', 'transaction.animal.farmAnimal', 'transaction.animal.farmAnimalSpecies', 'transaction.animal.profile')->find($id);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        $profileId = $profile->id;
        $paymentProfileId = $findPayment->transaction->livestock->profile->id;
        $paymentBuyerProfileId = $findPayment->transaction->profile_id;

        if ($user->hasRole(['admin']) || ($profileId === $paymentProfileId || $profileId === $paymentBuyerProfileId)) {
            return response()->json([
                'message' => 'Pembayaran berhasil diambil!.',
                'payment' => $findPayment
            ], 201);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }
    }

    public function putPaymentById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findPayment = Payment::find($id);

        if (!$findPayment) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;
        $paymentProfileId = $findPayment->transaction->livestock->profile->id;
        $paymentBuyerProfileId = $findPayment->transaction->profile_id;

        $validatedData = $request->validate([
            'status' => 'required',
        ]);

        if ($user->hasRole(['admin']) || ($profileId === $paymentProfileId || $profileId === $paymentBuyerProfileId)) {
            $findPayment->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil diubah!.',
            'payment' => $findPayment
        ], 200);
    }

    public function deletePaymentById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
            ], 302);
        }

        $findPayment = Payment::find($id);

        if (!$findPayment) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($user->hasRole(['admin', 'seller'])) {
            $findPayment->delete();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil dihapus.'
        ], 200);
    }
}
