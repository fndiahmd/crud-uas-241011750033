<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Page;

class AdminController extends Controller
{
    public function dashboard()
    {
        $produkCount = Produk::count();
        $kategoriCount = Kategori::count();
        $pageCount = Page::count();
        return view('admin.dashboard', compact('produkCount', 'kategoriCount', 'pageCount'));
    }
}
