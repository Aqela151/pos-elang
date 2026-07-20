<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        return User::with('cabang')->get();
    }

    // Menambah user
    public function store(Request $request)
    {
        //
    }

    // Menampilkan satu user
    public function show(User $user)
    {
        return $user->load('cabang');
    }

    // Update user
    public function update(Request $request, User $user)
    {
        //
    }

    // Hapus user
    public function destroy(User $user)
    {
        //
    }
}