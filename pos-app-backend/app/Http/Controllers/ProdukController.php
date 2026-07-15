<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    public function index()
    {
        return Produk::all()->map(fn (Produk $produk) => $this->withImageData($produk));
    }

    public function store(Request $request)
    {
        Log::info('Produk store - files:', $request->allFiles());
        Log::info('Produk store - has gambar:', ['hasFile' => $request->hasFile('gambar')]);

        $data = $request->only([
            'kategori_id',
            'supplier_id',
            'kode_produk',
            'nama_produk',
            'harga_beli',
            'harga_eceran',
            'harga_grosir',
            'stok',
        ]);

        $data['gambar'] = $this->handleImageUpload($request);

        $produk = Produk::create($data);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $this->withImageData($produk),
        ], 201);
    }

    public function show(Produk $produk)
    {
        return $this->withImageData($produk);
    }

    public function update(Request $request, Produk $produk)
    {
        Log::info('Produk update - files:', $request->allFiles());
        Log::info('Produk update - has gambar:', ['hasFile' => $request->hasFile('gambar')]);

        $data = $request->only([
            'kategori_id',
            'supplier_id',
            'kode_produk',
            'nama_produk',
            'harga_beli',
            'harga_eceran',
            'harga_grosir',
            'stok',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->handleImageUpload($request, $produk->gambar);
        }

        $produk->update($data);

        return response()->json([
            'message' => 'Produk berhasil diupdate',
            'data' => $this->withImageData($produk),
        ]);
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
        ]);
    }

    private function handleImageUpload(Request $request, ?string $previousImage = null): ?string
    {
        if (! $request->hasFile('gambar')) {
            Log::info('Tidak ada file gambar di request, pakai gambar lama.');
            return $previousImage;
        }

        $file = $request->file('gambar');

        if (! $file->isValid()) {
            Log::warning('File gambar tidak valid.', ['error' => $file->getErrorMessage()]);
            return $previousImage;
        }

        if ($previousImage) {
            $this->deleteImageFile($previousImage);
        }

        $destination = public_path('uploads');
        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $filename = $this->buildFilename($file);
        $file->move($destination, $filename);

        Log::info('Gambar berhasil disimpan.', ['filename' => $filename]);

        return $filename;
    }

    private function buildFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        return time() . '_' . Str::slug($originalName) . '.' . $extension;
    }

    private function deleteImageFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = public_path('uploads/' . $filename);
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    private function withImageData(Produk $produk): array
    {
        $data = $produk->toArray();
        $data['gambar_url'] = $produk->gambar ? asset('uploads/' . $produk->gambar) : null;

        return $data;
    }
}