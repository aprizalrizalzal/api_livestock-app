<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getTransactions(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        $profileId = $profile->id;

        if ($user->hasRole(['admin'])) {
            $transactions = Transaction::with('profile', 'livestock', 'livestock.livestockType', 'livestock.livestockSpecies', 'livestock.profile')->get();
        } else if ($user->hasRole(['seller'])) {
            $transactions = Transaction::with('profile', 'livestock', 'livestock.livestockType', 'livestock.livestockSpecies', 'livestock.profile')->whereHas('livestock', function ($query) use ($profileId) {
                $query->where('profile_id', $profileId);
            })->get();
        } else if ($user->hasRole(['buyer'])) {
            $transactions = Transaction::with('profile', 'livestock', 'livestock.livestockType', 'livestock.livestockSpecies', 'livestock.profile')->where('profile_id', $profileId)->get();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        if ($transactions->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Transaksi berhasil diambil!',
            'transactions' => $transactions
        ], 200);
    }

    public function postTransactionByIdLivestock(Request $request, string $livestockId)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findLivestock = Livestock::find($livestockId);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;
        $livestockProfileId = $findLivestock->profile->id;

        if ($profileId === $livestockProfileId) {
            return response()->json([
                'message' => 'Anda tidak bisa melakukan transaksi pada hewan ternak ini (produk).'
            ], 400);
        }

        $existingTransaction = Transaction::where('profile_id', $profileId)->where('livestock_id', $findLivestock->id)->first();

        if ($existingTransaction) {
            return response()->json([
                'message' => 'Anda sudah melakukan transaksi pada hewan ini.'
            ], 400);
        }

        if ($user->hasRole(['buyer'])) {
            $transaction = Transaction::create([
                'profile_id' => $profile->id,
                'livestock_id' => $findLivestock->id,
                'date' => Carbon::now(),
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Transaksi berhasil dibuat!',
            'transaction' => $transaction
        ], 201);
    }

    public function getTransactionById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if ($user->hasRole(['admin', 'seller', 'buyer'])) {
            $findTransaction = Transaction::with('profile', 'livestock', 'livestock.livestockType', 'livestock.livestockSpecies', 'livestock.profile')->find($id);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        if (!$findTransaction) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;
        $transactionProfileId = $findTransaction->livestock->profile->id;
        $transactionBuyerProfileId = $findTransaction->profile_id;

        if ($user->hasRole(['admin']) || ($profileId === $transactionProfileId || $profileId === $transactionBuyerProfileId)) {
            return response()->json([
                'message' => 'Transaksi berhasil diambil!',
                'transaction' => $findTransaction
            ], 201);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }
    }

    public function putTransactionById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findTransaction = Transaction::find($id);

        if (!$findTransaction) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;
        $transactionProfileId = $findTransaction->livestock->profile->id;
        $transactionBuyerProfileId = $findTransaction->profile_id;

        $validatedData = $request->validate([
            'status' => 'required|boolean',
        ]);

        if ($user->hasRole(['admin']) || ($profileId === $transactionProfileId || $profileId === $transactionBuyerProfileId)) {
            $findTransaction->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Transaksi berhasil diubah!',
            'transaction' => $findTransaction
        ], 200);
    }

    public function deleteTransactionById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findTransaction = Transaction::find($id);

        if (!$findTransaction) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($user->hasRole(['admin'])) {
            $findTransaction->delete();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Transaksi berhasil dihapus.'
        ], 200);
    }
}
