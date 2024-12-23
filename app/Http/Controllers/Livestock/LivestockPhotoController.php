<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use App\Models\LivestockPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivestockPhotoController extends Controller
{
    public function getLivestockPhotosByIdLivestock(Request $request, string $livestockId)
    {
        $user = $request->user();

        $findLivestock = Livestock::find($livestockId);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $livestockPhotos = LivestockPhoto::where('livestock_id', $livestockId)->get();

        if (!$livestockPhotos) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Foto Hewan ternak berhasil diambil!.',
            'livestockPhotos' => $livestockPhotos
        ], 200);
    }

    public function postLivestockPhotoByIdLivestock(Request $request, string $livestockId)
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

        $existingPhotosCount = $findLivestock->livestockPhotos()->count();
        $maxPhotoCount = 6;

        if ($existingPhotosCount >= $maxPhotoCount) {
            return response()->json([
                'message' => 'Anda telah mencapai batas maksimum jumlah foto hewan ternak.'
            ], 403);
        }

        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $uniqueName = time();
        $path = $validatedData['photo']->storeAs('photos/livestock', $uniqueName, 'public');

        if ($user->hasRole(['seller']) && $profileId === $livestockProfileId) {
            $createLivestockPhoto = LivestockPhoto::create([
                'livestock_id' => $findLivestock->id,
                'photo_url' => $path,
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Foto Hewan ternak berhasil dibuat!.',
            'livestockPhoto' => $createLivestockPhoto
        ], 201);
    }

    public function deleteLivestockPhotoById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findLivestockPhoto = LivestockPhoto::find($id);

        $profileId = $profile->id;
        $livestockProfileId = $findLivestockPhoto->livestock->profile->id;

        if (!$findLivestockPhoto) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($findLivestockPhoto->photo_url) {
            Storage::delete($findLivestockPhoto->photo_url);
        }

        if ($user->hasRole(['seller']) && $profileId === $livestockProfileId) {
            $findLivestockPhoto->delete();
        } else {
            return response()->json([

                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Foto hewan ternak dihapus.'
        ], 200);
    }
}
