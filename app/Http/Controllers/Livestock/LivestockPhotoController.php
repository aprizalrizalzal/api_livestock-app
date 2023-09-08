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
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu.'
            ], 404);
        }

        $findLivestock = Livestock::find($livestockId);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Hewan ternak tidak ditemukan.'
            ], 404);
        }

        $livestockPhotos = LivestockPhoto::where('livestock_id', $livestockId)->get();

        if (!$livestockPhotos) {
            return response()->json([
                'message' => 'Anda tidak memiliki foto hewan ternak.'
            ], 404);
        }

        return response()->json(['livestockPhotos' => $livestockPhotos], 200);
    }

    public function postLivestockPhotoByIdLivestock(Request $request, string $livestockId)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu.'
            ], 404);
        }

        $findLivestock = Livestock::find($livestockId);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Hewan ternak tidak ditemukan.'
            ], 404);
        }

        $existingPhotosCount = $findLivestock->livestockPhotos()->count();
        $maxPhotoCount = 6;

        if ($existingPhotosCount >= $maxPhotoCount) {
            return response()->json([
                'message' => 'Anda telah mencapai batas maksimum foto hewan.'
            ], 403);
        }

        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $path = $validatedData['photo']->store('photos/livestock');

        $createLivestockPhoto = LivestockPhoto::create([
            'livestock_id' => $findLivestock->id,
            'photo_url' => $path,
        ]);

        return response()->json(['livestockPhoto' => $createLivestockPhoto], 201);
    }

    public function deleteLivestockPhotoById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu.'
            ], 404);
        }

        $findLivestockPhoto = LivestockPhoto::find($id);

        if (!$findLivestockPhoto) {
            return response()->json([
                'message' => 'Foto hewan ternak tidak ditemukan.'
            ], 404);
        }

        if ($findLivestockPhoto->photo_url) {
            Storage::delete($findLivestockPhoto->photo_url);
        }

        if ($user->hasRole(['admin', 'seller'])) {
            $findLivestockPhoto->delete();
        } else {
            return response()->json([
                
                'message' => 'Anda tidak memiliki izin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Foto hewan ternak dihapus.'
        ], 200);
    }
}
