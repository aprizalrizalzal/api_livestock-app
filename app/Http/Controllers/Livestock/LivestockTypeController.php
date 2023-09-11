<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\LivestockType;
use Illuminate\Http\Request;

class LivestockTypeController extends Controller
{
    public function getlivestockTypes()
    {
        $livestockTypes = LivestockType::with('livestockSpecies')->get();

        if (!$livestockTypes) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'livestockTypes' => $livestockTypes
        ], 201);
    }

    public function postLivestockType(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $existingLivestockType = LivestockType::where('name', $validatedData['name'])->first();

        if ($existingLivestockType) {
            return response()->json([
                'message' => 'Jenis hewan ternak sudah ada.'
            ], 400);
        }

        if ($user->hasRole(['admin'])) {
            $LivestockType = LivestockType::create([
                'name' => $validatedData['name'],
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'livestockType' => $LivestockType
        ], 201);
    }

    public function getLivestockTypeById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $findLivestockType = LivestockType::with('livestockSpecies')->find($id);

        if (!$findLivestockType) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'livestockType' => $findLivestockType
        ], 201);
    }

    public function putLivestockTypeById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $findLivestockType = LivestockType::find($id);

        if (!$findLivestockType) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if ($user->hasRole(['admin'])) {
            $findLivestockType->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'livestockType' => $findLivestockType
        ], 200);
    }

    public function deleteLivestockTypeById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $findLivestockType = LivestockType::find($id);

        if (!$findLivestockType) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($user->hasRole(['admin'])) {
            $findLivestockType->delete();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Jenis hewan ternak berhasil dihapus.'
        ], 200);
    }
}
