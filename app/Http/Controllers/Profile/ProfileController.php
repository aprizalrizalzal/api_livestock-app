<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        return response()->json([
            'message' => 'Profil berhasil diambil!',
            'profile' => $profile
        ], 200);
    }

    public function postProfile(Request $request)
    {
        $user = $request->user();

        // Cek apakah profil sudah diatur
        if ($user->profile) {
            return response()->json([
                'message' => 'Profil Anda sudah diatur. Silakan tambahkan atau ubah Foto Profil Anda.'
            ], 400);
        }

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:100',
        ], [
            'name.max' => 'Nama tidak boleh lebih dari 50 karakter!',
            'phone_number.max' => 'Nomor Telepon tidak boleh lebih dari 15 karakter!',
            'address.max' => 'Alamat tidak boleh lebih dari 100 karakter!',
        ]);

        // Simpan profil baru
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone_number' => $validatedData['phone_number'],
            'address' => $validatedData['address'],
        ]);

        return response()->json([
            'message' => 'Profil berhasil dibuat!',
            'profile' => $profile
        ], 201);
    }


    public function postProfilePhoto(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        $validatedData = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($profile->photo_url) {
            Storage::delete($profile->photo_url);
        }

        $uniqueName = time();
        $path = $validatedData['photo']->storeAs('photos/profile', $uniqueName, 'public');

        $profile->update([
            'photo_url' => $path,
        ]);

        return response()->json([
            'message' => 'Foto Profil berhasil dibuat!',
            'profile' => $profile
        ], 200);
    }

    public function putProfilePhoto(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if ($profile->photo_url) {
            Storage::delete($profile->photo_url);
        }

        $profile->update([
            'photo_url' => null,
        ]);

        return response()->json([
            'message' => 'Foto Profil berhasil diubah!',
            'profile' => $profile
        ], 200);
    }

    public function putProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

       $validatedData = $request->validate([
            'name' => 'nullable|string|max:50',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:100',
        ], [
            'name.max' => 'Nama tidak boleh lebih dari 50 karakter!',
            'phone_number.max' => 'Nomor Telepon tidak boleh lebih dari 15 karakter!',
            'address.max' => 'Alamat tidak boleh lebih dari 100 karakter!',
        ]);

        $profile->update($validatedData);

        return response()->json([
            'message' => 'Profil berhasil diubah!',
            'profile' => $profile
        ], 200);
    }

    public function deleteProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        $profile->delete();

        return response()->json([
            'message' => 'Profil berhasil dihapus.'
        ], 200);
    }
}
