@extends('layouts.app')
@section('content')
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 text-white pt-32 pb-24">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-5">Produk <span class="text-amber-400">UMKM</span> Unggulan</h1>
        <p class="text-lg text-emerald-100 mb-10 max-w-2xl mx-auto leading-relaxed">Dukung produk lokal berkualitas dari pengusaha kecil di desa kita. Setiap pembelian berarti bagi mereka.</p>
        <div class="flex justify-center gap-3 flex-wrap"><a href="{{ route('produk') }}" class="px-8 py-3.5 bg-amber-400 text-stone-900 rounded-full font-bold hover:bg-amber-300 shadow-lg shadow-amber-500/25 transition">Lihat Produk</a><a href="{{ route('kategori') }}" class="px-8 py-3.5 border-2 border-white/30 text-white rounded-full font-semibold hover:bg-white/10 transition">Jelajahi Kategori</a></div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 -mt-12 relative z-10">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4"><div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-xl">🛍️</div><div><p class="text-2xl font-bold text-stone-800">{{ $produks->count() }}+</p><p class="text-stone-400 text-sm">Produk Lokal</p></div></div>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4"><div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-xl">📦</div><div><p class="text-2xl font-bold text-stone-800">{{ $kategoris->count() }}</p><p class="text-stone-400 text-sm">Kategori</p></div></div>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4"><div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-xl">💚</div><div><p class="text-2xl font-bold text-stone-800">100%</p><p class="text-stone-400 text-sm">Produk Desa</p></div></div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-center justify-between mb-8"><h2 class="text-3xl font-bold text-stone-800">Kategori</h2><a href="{{ route('kategori') }}" class="text-emerald-600 font-semibold text-sm hover:underline">Lihat semua →</a></div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @php $icons = ['🍜','🥤','🧶','🎨','🌾','🍪','🪴','🕯️']; @endphp
        @foreach($kategoris as $k)
        <a href="{{ route('kategori.show', $k->slug) }}" class="group bg-white border border-stone-100 rounded-2xl p-5 text-center hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl group-hover:bg-emerald-100 transition">{{ $icons[$loop->index % count($icons)] }}</div>
            <h3 class="font-bold text-stone-700 group-hover:text-emerald-700 transition">{{ $k->nama_kategori }}</h3>
        </a>
        @endforeach
    </div>
</section>

<section class="bg-stone-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8"><h2 class="text-3xl font-bold text-stone-800">Produk Terbaru</h2><a href="{{ route('produk') }}" class="text-emerald-600 font-semibold text-sm hover:underline">Lihat semua →</a></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($produks as $p)
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-stone-100 hover:border-emerald-200 flex flex-col">
                <div class="relative overflow-hidden">
                    @if($p->gambar)<img src="{{ asset('storage/' . $p->gambar) }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                    @else<div class="w-full h-52 bg-stone-100 flex items-center justify-center text-stone-300"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>@endif
                    <span class="absolute top-3 left-3 text-xs font-semibold bg-white/90 backdrop-blur text-emerald-700 px-2.5 py-1 rounded-full shadow">{{ $p->kategori->nama_kategori }}</span>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-bold text-stone-800 group-hover:text-emerald-700 transition-colors">{{ $p->nama_produk }}</h3>
                    <p class="text-emerald-600 font-bold text-lg mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    <p class="text-stone-400 text-sm mt-2 leading-relaxed flex-1">{{ \Illuminate\Support\Str::limit($p->deskripsi, 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
