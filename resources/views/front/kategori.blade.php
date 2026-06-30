@extends('layouts.app')
@section('content')
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-emerald-200 text-sm mb-3"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span><span class="text-white">Kategori</span></div>
        <h1 class="text-4xl md:text-5xl font-bold text-white">Kategori Produk</h1>
        <p class="text-emerald-200 mt-2 text-lg">Temukan produk berdasarkan kategori favorit Anda.</p>
    </div>
</section>
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
        $icons = ['🍜','🥤','🧶','🎨','🌾','🍪','🪴','🕯️'];
        $bgColors = ['bg-rose-50 group-hover:bg-rose-100','bg-sky-50 group-hover:bg-sky-100','bg-amber-50 group-hover:bg-amber-100','bg-purple-50 group-hover:bg-purple-100','bg-lime-50 group-hover:bg-lime-100','bg-pink-50 group-hover:bg-pink-100','bg-teal-50 group-hover:bg-teal-100','bg-indigo-50 group-hover:bg-indigo-100'];
        @endphp
        @foreach($kategoris as $k)
        @php $idx = $loop->index % count($icons); @endphp
        <a href="{{ route('kategori.show', $k->slug) }}" class="group bg-white border border-stone-100 rounded-2xl p-6 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl {{ $bgColors[$idx] }} flex items-center justify-center text-3xl transition-colors duration-300 flex-shrink-0">{{ $icons[$idx] }}</div>
            <div>
                <h3 class="text-lg font-bold text-stone-800 group-hover:text-emerald-700 transition-colors">{{ $k->nama_kategori }}</h3>
                <p class="text-stone-400 text-sm mt-1">{{ $k->produks_count }} produk tersedia</p>
            </div>
            <div class="ml-auto text-stone-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
        </a>
        @endforeach
    </div>
</div>
@endsection
