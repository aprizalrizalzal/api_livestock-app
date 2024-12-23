<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\LivestockSpecies;
use App\Models\LivestockType;
use Illuminate\Http\Request;

class LivestockSpeciesController extends Controller
{
    public function getLivestockSpecies()
    {

        $livestockSpecies = LivestockSpecies::get();

        if (!$livestockSpecies) {
            return response()->json([
                'message' => 'Tidak ada spesies dari jenis hewan ternak.'
            ], 404);
        }

        return response()->json([
            'message' => 'Spesies jenis Hewan ternak berhasil diambil!.',
            'livestockSpecies' => $livestockSpecies
        ], 201);
    }

    public function getLivestockSpeciesByIdLivestockType(string $livestockTypeId)
    {
        $findLivestockType = LivestockType::find($livestockTypeId);

        if (!$findLivestockType) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $livestockSpecies = LivestockSpecies::where('livestock_type_id', $livestockTypeId)->get();

        return response()->json([
            'message' => 'Spesies jenis Hewan ternak berhasil diambil!.',
            'livestockSpecies' => $livestockSpecies
        ], 201);
    }

    public function postLivestockSpeciesByIdLivestockType(Request $request, string $livestockTypeId)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findLivestockType = LivestockType::find($livestockTypeId);

        if (!$findLivestockType) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if ($user->hasRole(['admin'])) {
            $livestockSpecies = LivestockSpecies::create([
                'livestock_type_id' => $findLivestockType->id,
                'name' => $validatedData['name'],
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Spesies jenis Hewan ternak berhasil dibuat!.',
            'livestockSpecies' => $livestockSpecies
        ], 201);
    }

    public function getLivestockSpeciesById(string $id)
    {
        $findLivestockSpecies = LivestockSpecies::find($id);

        if (!$findLivestockSpecies) {
            return response()->json([
                'message' => 'Sepesies dari jenis hewan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Spesies jenis Hewan ternak berhasil diambil!.',
            'livestockSpecies' => $findLivestockSpecies
        ], 201);
    }

    public function putLivestockSpeciesById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findLivestockSpecies = LivestockSpecies::find($id);

        if (!$findLivestockSpecies) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if ($user->hasRole(['admin'])) {
            $findLivestockSpecies->update($validatedData);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Spesies jenis Hewan ternak berhasil diubah!.',
            'livestockSpecies' => $findLivestockSpecies
        ], 200);
    }

    public function deleteLivestockSpeciesById(Request $request, string $id)
    {
        $user = $request->user();
        $profile = $user->profile;

       if (!$profile->phone_number_verified_at) {
            return response()->json([
                'message' => 'Silahkan verifikasi nomor telpon Anda terlebih dahulu.'
            ], 302);
        }

        $findLivestockSpecies = LivestockSpecies::find($id);

        if (!$findLivestockSpecies) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }

        if ($user->hasRole(['admin'])) {
            $findLivestockSpecies->delete();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Spesies dari jenis hewan berhasil dihapus.'
        ], 200);
    }
}
