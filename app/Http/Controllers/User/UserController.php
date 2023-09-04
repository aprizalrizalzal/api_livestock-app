<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole(['admin'])) {
            $users = User::with('profile', 'roles')->get();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        return response()->json([
            'users' => $users
        ], 200);
    }

    // public function putUserEmail(Request $request)
    // {
    //     $user = $request->user();

    //     $validatedData = $request->validate([
    //         'email' => 'required|string|email|unique:users',
    //     ]);

    //     $emailChanged = $user->email !== $validatedData['email'];

    //     $user->update($validatedData);

    //     if ($emailChanged) {
    //         if ($user->hasVerifiedEmail()) {
    //             $user->email_verified_at = null;
    //             $user->save();
    //         }

    //         $user->sendEmailVerificationNotification();

    //         return response()->json(['message' => 'Email berhasil diperbarui, silakan verifikasi email baru'], 200);
    //     }

    //     return response()->json(['message' => 'Email berhasil diperbarui'], 200);
    // }

    // public function putUserPassword(Request $request)
    // {
    //     $user = $request->user();

    //     $validatedData = $request->validate([
    //         'password_old' => 'required',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     if (!Hash::check($validatedData['password_old'], $user->password)) {
    //         throw ValidationException::withMessages([
    //             'password_old' => ['Password lama yang dimasukkan tidak cocok.'],
    //         ]);
    //     }

    //     $user->password = Hash::make($validatedData['password']);
    //     $user->save();

    //     return response()->json(['message' => 'Password berhasil diperbarui.'], 200);
    // }

    public function getuserById(Request $request, string $id)
    {
        $user = $request->user();

        if ($user->hasRole(['admin'])) {
            $findUser = User::with('profile', 'roles', 'permissions')->find($id);
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        if (!$findUser) {
            return response()->json([
                'message' => 'Pengguna tidak ditemukan.'
            ], 404);
        }
        
        return response()->json([
            'user' => $findUser
        ], 200);
    }

    public function deleteUserById(Request $request, string $id)
    {
        $user = $request->user();

        if ($user->hasRole(['admin'])) {
            $findUser = User::find($id);

            if (!$findUser) {
                return response()->json([
                    'message' => 'Pengguna tidak ditemukan.'
                ], 404);
            }

            $findUser->delete();
        } else {
            return response()->json([
                'message' => 'Anda tidak memiliki izin.'
            ], 203);
        }

        return response()->json([
            'message' => 'Pengguna berhasil dihapus.'
        ], 200);
    }
}
