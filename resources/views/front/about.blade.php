@extends('layouts.app')
@section('content')
<section class="relative bg-gradient-to-br from-emerald-700 to-emerald-900 pt-28 pb-20">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 60 60%22><path d=%22M30 5 L55 20 L55 45 L30 55 L5 45 L5 20 Z%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>'); background-size: 60px 60px;"></div>
    <div class="relative max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-emerald-200 text-sm mb-4"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span><span class="text-white">{{ $page->title }}</span></div>
        <h1 class="text-4xl md:text-5xl font-bold text-white">{{ $page->title }}</h1>
        <p class="text-emerald-200 mt-3 max-w-xl">Mengenal lebih dekat UMKM Desa dan misi kami.</p>
    </div>
</section>
<section class="max-w-4xl mx-auto px-4 -mt-8 relative z-10">
    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 prose max-w-none prose-stone prose-headings:text-emerald-800 prose-a:text-emerald-600">
        {!! $page->content !!}
    </div>
</section>
<section class="max-w-4xl mx-auto px-4 py-16 text-center">
    <div class="bg-emerald-50 rounded-2xl p-8 border border-emerald-100">
        <h3 class="text-xl font-bold text-emerald-800 mb-2">Ingin tahu lebih banyak?</h3>
        <p class="text-emerald-600 mb-4">Kunjungi halaman produk kami atau hubungi langsung.</p>
        <div class="flex justify-center gap-3"><a href="{{ route('produk') }}" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition">Lihat Produk</a><a href="{{ route('kontak') }}" class="px-6 py-2 border border-emerald-300 text-emerald-700 rounded-lg font-semibold hover:bg-emerald-50 transition">Hubungi Kami</a></div>
    </div>
</section>
@endsection
