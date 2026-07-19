<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $profil = DB::table('pengaturan_toko')->where('id', 1)->first();
        $pajak = DB::table('pengaturan_pajak')->get();
        $notifikasi = DB::table('pengaturan_notifikasi')->get();
        $user = Auth::user();

        return response()->json([
            'profil' => $profil ? [
                'nama_toko'    => $profil->nama_toko,
                'telepon'      => $profil->telepon,
                'email'        => $profil->email,
                'kota'         => $profil->kota,
                'alamat'       => $profil->alamat,
                'footer_struk' => $profil->footer_struk,
            ] : null,
            'pajak' => $pajak->map(fn($p) => [
                'id' => $p->id,
                'label' => $p->label,
                'desc' => $p->deskripsi,
                'value' => $p->nilai,
                'enabled' => (bool) $p->aktif,
                'hasInput' => (bool) $p->punya_input,
            ]),
            'akun' => [
                'nama_lengkap' => $user->name ?? $user->nama ?? '',
                'email' => $user->email ?? '',
            ],
            'notifikasi' => $notifikasi->map(fn($n) => [
                'id' => $n->id,
                'label' => $n->label,
                'desc' => $n->deskripsi,
                'enabled' => (bool) $n->aktif,
            ]),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'profil' => 'array',
            'pajak' => 'array',
            'akun' => 'array',
            'notifikasi' => 'array',
        ]);

        DB::transaction(function () use ($data) {
            if (!empty($data['profil'])) {
                DB::table('pengaturan_toko')->where('id', 1)->update([
                    'nama_toko'    => $data['profil']['nama_toko'] ?? '',
                    'telepon'      => $data['profil']['telepon'] ?? '',
                    'email'        => $data['profil']['email'] ?? '',
                    'kota'         => $data['profil']['kota'] ?? '',
                    'alamat'       => $data['profil']['alamat'] ?? '',
                    'footer_struk' => $data['profil']['footer_struk'] ?? '',
                    'updated_at'   => now(),
                ]);
            }

            foreach ($data['pajak'] ?? [] as $item) {
                DB::table('pengaturan_pajak')->where('id', $item['id'])->update([
                    'nilai' => $item['value'] ?? null,
                    'aktif' => $item['enabled'] ?? true,
                    'updated_at' => now(),
                ]);
            }

            if (!empty($data['akun']) && ($user = Auth::user())) {
    DB::table('users')->where('id', $user->id)->update([
        'nama' => $data['akun']['nama_lengkap'] ?? $user->nama,
        'email' => $data['akun']['email'] ?? $user->email,
    ]);
}

            foreach ($data['notifikasi'] ?? [] as $item) {
                DB::table('pengaturan_notifikasi')->where('id', $item['id'])->update([
                    'aktif' => $item['enabled'] ?? true,
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json(['message' => 'Settings berhasil disimpan']);
    }
}