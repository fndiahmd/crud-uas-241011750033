@extends('layouts.app')
@section('content')
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-emerald-200 text-sm mb-3"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span><span class="text-white">Produk</span></div>
        <h1 class="text-4xl md:text-5xl font-bold text-white">Semua Produk</h1>
        <p class="text-emerald-200 mt-2 text-lg">Jelajahi produk UMKM lokal berkualitas.</p>
    </div>
</section>
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($produks as $p)
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-stone-100 hover:border-emerald-200 flex flex-col">
            <div class="relative overflow-hidden">
                @if($p->gambar)
                    <img src="{{ asset('storage/' . $p->gambar) }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-52 bg-stone-100 flex items-center justify-center text-stone-300"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                @endif
                <span class="absolute top-3 left-3 text-xs font-semibold bg-white/90 backdrop-blur text-emerald-700 px-2.5 py-1 rounded-full shadow">{{ $p->kategori->nama_kategori }}</span>
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-bold text-stone-800 group-hover:text-emerald-700 transition-colors">{{ $p->nama_produk }}</h3>
                <p class="text-emerald-600 font-bold text-lg mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                <p class="text-stone-400 text-sm mt-2 leading-relaxed flex-1">{{ \Illuminate\Support\Str::limit($p->deskripsi, 80) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-10">{{ $produks->links() }}</div>
</div>
@endsection
