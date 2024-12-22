<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     * Validates input data, creates a new user, assigns a role, and generates an API token.
     */
    public function register(Request $request)
    {
        // Validasi data yang dikirim oleh pengguna
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
        ]);

        // Cari role yang dipilih oleh pengguna
        $selectedRole = Role::findByName($validatedData['role']);

        // Buat user baru dan berikan role serta izin awal
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ])->assignRole($selectedRole)->givePermissionTo('user');

        // Buat profil default untuk user yang baru dibuat
        Profile::create([
            'user_id' => $user->id,
            'name' => $user->name,
        ]);

        // Hapus semua token sebelumnya jika ada dan buat token API baru
        $user->tokens()->delete();
        $token = $user->createToken('api_token')->plainTextToken;

        // Kembalikan respon sukses dengan data user dan token
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Handle user login.
     * Validates credentials, authenticates the user, and generates an API token.
     */
    public function login(Request $request)
    {
        // Validasi data login yang dikirim oleh pengguna
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Cek apakah kredensial valid
        if (!Auth::attempt($validatedData)) {
            // Respon gagal jika kredensial salah
            return response()->json([
                'message' => 'Maaf, email atau kata sandi yang Anda masukkan tidak valid.'
            ], 401);
        }

        // Ambil data user yang berhasil login
        $user = $request->user();
        $user->getRoleNames();

        // Hapus token API sebelumnya jika ada
        $user->tokens()->delete();

        // Buat token API baru
        $token = $user->createToken('api_token')->plainTextToken;

        // Kembalikan respon sukses dengan data user dan token
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Handle user logout.
     * Revokes API tokens and clears the session.
     */
    public function logout(Request $request)
    {
        // Hapus semua token API milik user yang sedang logout
        $request->user()->tokens()->delete();

        // Invalidate session untuk keamanan tambahan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Kembalikan respon sukses
        return response()->json([
            'message' => 'Berhasil keluar',
        ], 200);
    }
}
