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

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $profileId = $profile->id;

        if ($user->hasRole(['admin'])) {
            $payment = Payment::with('transaction', 'transaction.profile', 'transaction.livestock', 'transaction.livestock.livestockType', 'transaction.livestock.livestockSpecies', 'transaction.livestock.profile')->get();
        } else if ($user->hasRole(['seller'])) {
            $payment = Payment::with('transaction', 'transaction.profile', 'transaction.livestock', 'transaction.livestock.livestockType', 'transaction.livestock.livestockSpecies', 'transaction.livestock.profile')->whereHas('transaction', function ($query) use ($profileId) {
                $query->whereHas('profile', function ($query) use ($profileId) {
                    $query->where('id', $profileId);
                });
            })->get();
        } else if ($user->hasRole(['buyer'])) {
            $payment = Payment::with('transaction', 'transaction.profile', 'transaction.livestock', 'transaction.livestock.livestockType', 'transaction.livestock.livestockSpecies', 'transaction.livestock.profile')->where('profile_id', $profileId)->get();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        if ($payment->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pembayaran.'
            ], 404);
        }

        return response()->json([
            'payment' => $payment
        ], 200);
    }

    public function postPaymentByIdTransaction(Request $request, string $transactionId)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $findTransaction = Transaction::find($transactionId);

        if (!$findTransaction) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;

        if ($findTransaction->profile->id !== $profileId) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        $existingPayment = Payment::where('transaction_id', $transactionId)->first();

        if ($existingPayment) {
            return response()->json([
                'message' => 'Pembayaran untuk transaksi ini sudah ada.'
            ], 400);
        }

        $validatedData = $request->validate([
            'method' => 'required',
        ]);

        if ($user->hasRole(['admin', 'seller', 'buyer'])) {
            $payment = Payment::create([
                'transaction_id' => $findTransaction->id,
                'method' => $validatedData['method'],
                'date' => Carbon::now(),
                'price' => $findTransaction->livestock->price,
                'status' => false,
            ]);
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        return response()->json([
            'payment' => $payment
        ], 201);
    }

    public function getPaymentById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        if ($user->hasRole(['admin', 'seller', 'buyer'])) {
            $findPayment = Payment::with('transaction', 'transaction.profile', 'transaction.animal', 'transaction.animal.farmAnimal', 'transaction.animal.farmAnimalSpecies', 'transaction.animal.profile')->find($id);
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        return response()->json([
            'payment' => $findPayment
        ], 201);
    }

    public function putPaymentById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $findPayment = Payment::find($id);

        if (!$findPayment) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'status' => 'required',
        ]);

        if ($user->hasRole(['admin', 'seller', 'buyer'])) {
            $findPayment->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        return response()->json([
            'payment' => $findPayment
        ], 200);
    }

    public function deletePaymentById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $findPayment = Payment::find($id);

        if (!$findPayment) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan.'
            ], 404);
        }

        if ($user->hasRole(['admin', 'seller'])) {
            $findPayment->delete();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil dihapus.'
        ], 200);
    }
}
