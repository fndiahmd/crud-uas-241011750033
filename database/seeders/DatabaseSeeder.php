<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $k1 = Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);
        $k2 = Kategori::create(['nama_kategori' => 'Minuman', 'slug' => 'minuman']);
        $k3 = Kategori::create(['nama_kategori' => 'Kerajinan', 'slug' => 'kerajinan']);

        Produk::create([
            'nama_produk' => 'Keripik Pisang', 'kategori_id' => $k1->id,
            'harga' => 15000, 'deskripsi' => 'Keripik pisang renyah khas UMKM.',
            'gambar' => 'produk/keripik-pisang.png'
        ]);
        Produk::create([
            'nama_produk' => 'Es Teh Herbal', 'kategori_id' => $k2->id,
            'harga' => 8000, 'deskripsi' => 'Minuman teh herbal menyegarkan.',
            'gambar' => 'produk/es-teh-herbal.png'
        ]);
        Produk::create([
            'nama_produk' => 'Anyaman Bambu', 'kategori_id' => $k3->id,
            'harga' => 50000, 'deskripsi' => 'Kerajinan anyaman bambu tradisional.',
            'gambar' => 'produk/anyaman-bambu.png'
        ]);

        Page::create([
            'slug' => 'about', 'title' => 'Tentang Kami',
            'content' => '<p>UMKM binaan desa. Kami mendukung produk lokal berkualitas.</p>'
        ]);
        Page::create([
            'slug' => 'kontak', 'title' => 'Hubungi Kami',
            'content' => '<p>Email: umkm@desa.id | Telepon: 0812-3456-7890</p>'
        ]);
    }
}
