<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivestockController extends Controller
{
    public function getLivestocks()
    {
        $livestocks = Livestock::with('profile', 'livestockType', 'livestockSpecies')->get();

        if (!$livestocks) {
            return response()->json([
                'message' => 'Tidak ada hewan ternak.'
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
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }
        
        if ($user->hasRole(['admin', 'seller'])) {
            $livestocks = Livestock::with('profile', 'livestockType', 'livestockSpecies')->where('profile_id', $profileId)->get();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 403);
        }

        if (!$livestocks) {
            return response()->json([
                'message' => 'Pengguna tidak memiliki hewan ternak.'
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
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $validatedData = $request->validate([
            'livestock_type_id' => 'required',
            'livestock_species_id' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'price' => 'required',
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
                'sold' => false,
                'detail' => $validatedData['detail'],
            ]);
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
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
                'message' => 'Silahkan atur profil Anda terlebih dahulu.'
            ], 404);
        }

        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Foto hewan ternak tidak ditemukan.'
            ], 404);
        }

        if ($findLivestock->photo_url) {
            Storage::delete($findLivestock->photo_url);
        }

        $path = $validatedData['photo']->store('photos/livestock');

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
                'message' => 'Silahkan atur profil Anda terlebih dahulu.'
            ], 404);
        }
        
        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Foto hewan ternak tidak ditemukan.'
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
                'message' => 'Hewan ternak tidak ditemukan.'
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
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Hewan ternak tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'livestock_type_id' => 'required',
            'livestock_species_id' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'price' => 'required',
            'detail' => 'required',
        ]);

        $profileId = $profile->id;

        if ($user->hasRole(['admin', 'seller']) || $findLivestock->profile_id === $profileId) {
            $findLivestock->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
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
                'message' => 'Silahkan atur Profil Anda terlebih dahulu.'
            ], 404);
        }

        $findLivestock = Livestock::find($id);

        if (!$findLivestock) {
            return response()->json([
                'message' => 'Hewan ternak tidak ditemukan.'
            ], 404);
        }

        $profileId = $profile->id;

        if ($user->hasRole(['admin', 'seller']) || $findLivestock->profile_id === $profileId) {
            $findLivestock->delete();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Hewan ternak berhasil dihapus.'
        ], 200);
    }
}
