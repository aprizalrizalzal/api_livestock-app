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

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        return response()->json([
            'profile' => $profile
        ], 200);
    }

    public function postProfile(Request $request)
    {
        $user = $request->user();

        if ($user->profile) {
            return response()->json([
                'message' => 'Anda sudah mengatur Profil, silahkan atur Foto Profil Anda.'
            ], 400);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'required|numeric',
            'address' => 'required|string|max:100',
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone_number' => $validatedData['phone_number'],
            'address' => $validatedData['address'],
        ]);

        return response()->json([
            'profile' => $profile
        ], 201);
    }

    public function postProfilePhoto(Request $request)
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

        if ($profile->photo_url) {
            Storage::delete($profile->photo_url);
        }

        $uniqueName = time();
        $path = $validatedData['photo']->storeAs('photos/profile', $uniqueName, 'public');

        $profile->update([
            'photo_url' => $path,
        ]);

        return response()->json(['profile' => $profile], 200);
    }

    public function putProfilePhoto(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        if ($profile->photo_url) {
            Storage::delete($profile->photo_url);
        }

        $profile->update([
            'photo_url' => null,
        ]);

        return response()->json([
            'profile' => $profile
        ], 200);
    }

    public function putProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:50',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'required|numeric',
            'address' => 'required|string|max:100',
        ]);

        $profile->update($validatedData);

        return response()->json([
            'profile' => $profile
        ], 200);
    }

    public function deleteProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Silahkan atur profil Anda terlebih dahulu, untuk bisa menggunakan fitur yang ada pada aplikasi.'
            ], 302);
        }

        $profile->delete();

        return response()->json([
            'message' => 'Profil berhasil dihapus.'
        ], 200);
    }
}
