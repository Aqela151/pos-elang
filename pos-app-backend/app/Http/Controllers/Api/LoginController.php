<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * POST /api/login
     */
    public function login(Request $request)
    {
        // 1. Validasi input dasar
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 422);
        }

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cek user & password menggunakan Hash::check()
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // 4. (Opsional tapi disarankan) Buat token menggunakan Sanctum
        // Pastikan model User memakai trait HasApiTokens (Laravel Sanctum)
        $token = null;
        if (method_exists($user, 'createToken')) {
            $token = $user->createToken('pos-token')->plainTextToken;
        }

        // 5. Response sukses sesuai format yang diminta
        return response()->json([
            'success' => true,
            'user' => [
                'id'    => $user->id,
                'nama'  => $user->nama,
                'email' => $user->email,
                'role'  => $user->role,
                'cabang_id' => $user->cabang_id,
            ],
            // token dikirim terpisah, tidak wajib dipakai di frontend
            'token' => $token,
        ]);
    }

    /**
     * POST /api/logout
     * (opsional, jika menggunakan Sanctum token)
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}