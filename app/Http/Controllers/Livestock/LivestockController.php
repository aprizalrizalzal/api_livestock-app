<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivestockController extends Controller
{
    public function getLivestocksAnonymous()
    {
        $livestocks = Livestock::with('profile', 'livestockType', 'livestockSpecies')->get();

        if (!$livestocks) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'livestocks' => $livestocks
        ], 200);
    }

    public function getLivestocks(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $profileId = $profile->id;

        if ($user->hasRole(['seller'])) {
            $livestocks = Livestock::with('profile', 'livestockType', 'livestockSpecies')->where('profile_id', $profileId)->get();
        } else {
            $livestocks = Livestock::with('profile', 'livestockType', 'livestockSpecies')->get();
        }

        if (!$livestocks) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'livestocks' => $livestocks
        ], 200);
    }

    public function getLivestockByIdProfile(Request $request, string $profileId)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        if ($user->hasRole(['admin'])) {
            $livestocks = Livestock::with('profile', 'livestockType', 'livestockSpecies')->where('profile_id', $profileId)->get();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        if (!$livestocks) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'livestocks' => $livestocks
        ], 200);
    }

    public function postLivestock(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $validatedData = $request->validate([
            'livestock_type_id' => 'required',
            'livestock_species_id' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'price' => 'required|numeric',
            'detail' => 'required',
        ]);

        if ($user->hasRole(['admin', 'seller'])) {
            $livestocks = Livestock::create([
                'profile_id' => $profile->id,
                'livestock_type_id' => $validatedData['livestock_type_id'],
                'livestock_species_id' => $validatedData['livestock_species_id'],
                'age' => $validatedData['age'],
                'gender' => $validatedData['gender'],
                'price' => $validatedData['price'],
                'status' => false,
                'detail' => $validatedData['detail'],
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'livestock' => $livestocks
        ], 201);
    }

    public function postLivestockPhotoById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($findLivestock->photo_url) {
            Storage::delete($findLivestock->photo_url);
        }

        $uniqueName = time();
        $path = $validatedData['photo']->storeAs('photos/livestock', $uniqueName, 'public');

        $findLivestock->update([
            'photo_url' => $path,
        ]);

        return response()->json([
            'livestock' => $findLivestock
        ], 200);
    }

    public function putLivestockPhotoById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($findLivestock->photo_url) {
            Storage::delete($findLivestock->photo_url);
        }

        $findLivestock->update([
            'photo_url' => null,
        ]);

        return response()->json([
            'livestock' => $findLivestock
        ], 200);
    }

    public function getLivestockById(string $id)
    {
        $findLivestock = Livestock::with('livestockPhotos', 'profile', 'livestockType', 'livestockSpecies')->find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'livestock' => $findLivestock
        ], 200);
    }

    public function putLivestockById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'livestock_type_id' => 'required',
            'livestock_species_id' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'price' => 'required|numeric',
            'status' => 'boolean',
            'detail' => 'required',
        ]);

        $profileId = $profile->id;

        if ($user->hasRole(['admin', 'seller']) || $findLivestock->profile_id === $profileId) {
            $findLivestock->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'livestock' => $findLivestock
        ], 200);
    }

    public function deleteLivestockById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;

        if ($user->hasRole(['admin']) || ($user->hasRole(['seller']) && $findLivestock->profile_id === $profileId)) {
            $findLivestock->delete();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Hewan ternak berhasil dihapus.'
        ], 200);
    }
}
