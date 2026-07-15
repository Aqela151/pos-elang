<?php

namespace Tests\Feature;

use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProdukUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_uploaded_image_and_returns_product_data(): void
    {
        $file = UploadedFile::fake()->create('produk.jpg', 100, 'image/jpeg');

        $response = $this->postJson('/api/produk', [
            'kategori_id' => 1,
            'supplier_id' => 1,
            'kode_produk' => 'P001',
            'nama_produk' => 'Produk Test',
            'harga_beli' => 10000,
            'harga_eceran' => 15000,
            'harga_grosir' => 12000,
            'stok' => 10,
            'gambar' => $file,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'kategori_id',
                'supplier_id',
                'kode_produk',
                'nama_produk',
                'harga_beli',
                'harga_eceran',
                'harga_grosir',
                'stok',
                'gambar',
                'gambar_url',
            ],
        ]);

        $product = Produk::where('kode_produk', 'P001')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->gambar);
        $this->assertFileExists(public_path('uploads/' . $product->gambar));
    }
}
