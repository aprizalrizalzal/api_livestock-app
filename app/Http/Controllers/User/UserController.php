<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole(['admin'])) {
            $users = User::with('profile', 'roles', 'permissions')->get();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function getRoles()
    {
        $roles = Role::all();

        return response()->json([
            'roles' => $roles
        ], 200);
    }

    public function getPermissions()
    {
        $permissions = Permission::all();
        

        return response()->json([
            'permissions' => $permissions
        ], 200);
    }

    public function getuserById(Request $request, string $id)
    {
        $user = $request->user();

        if ($user->hasRole(['admin', 'seller', 'buyer'])) {
            $findUser = User::with('profile', 'roles', 'permissions')->find($id);
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        if (!$findUser) {
            return response()->json([
                'message' => 'Tidak ditemukan.'
            ], 404);
        }
        
        return response()->json([
            'user' => $findUser
        ], 200);
    }

    public function deleteUserById(Request $request, string $id)
    {
        $user = $request->user();

        $findUser = User::find($id);

            if (!$findUser) {
                return response()->json([
                    'message' => 'Tidak ditemukan.'
                ], 404);
            }

        if ($user->hasRole(['admin'])) {
            if ($user->id === $findUser->id) {
                return response()->json([
                    'message' => 'Anda tidak dapat menghapus akun sendiri.'
                ], 403);
            }
            $findUser->delete();
        } else {
            return response()->json([
                'message' => 'Maaf, Anda tidak diizinkan, Silahkan hubungi Admin.'
            ], 403);
        }

        return response()->json([
            'message' => 'Pengguna berhasil dihapus.'
        ], 200);
    }
}
