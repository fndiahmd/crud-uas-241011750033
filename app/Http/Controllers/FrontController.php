<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Page;

class FrontController extends Controller
{
    public function home()
    {
        $produks = Produk::with('kategori')->latest()->take(6)->get();
        $kategoris = Kategori::all();
        return view('front.home', compact('produks', 'kategoris'));
    }

    public function produk()
    {
        $produks = Produk::with('kategori')->latest()->paginate(12);
        $kategoris = Kategori::all();
        return view('front.produk', compact('produks', 'kategoris'));
    }

    public function kategori()
    {
        $kategoris = Kategori::withCount('produks')->get();
        return view('front.kategori', compact('kategoris'));
    }

    public function kategoriShow($slug)
    {
        $kategori = Kategori::where('slug', $slug)->firstOrFail();
        $produks = Produk::where('kategori_id', $kategori->id)->latest()->paginate(12);
        return view('front.kategori-show', compact('kategori', 'produks'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        return view('front.about', compact('page'));
    }

    public function kontak()
    {
        $page = Page::where('slug', 'kontak')->firstOrFail();
        return view('front.kontak', compact('page'));
    }
}
