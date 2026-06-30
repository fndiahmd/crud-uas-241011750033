@extends('layouts.app')
@section('content')
<section class="relative bg-gradient-to-br from-emerald-700 to-emerald-900 pt-28 pb-20">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 60 60%22><path d=%22M30 5 L55 20 L55 45 L30 55 L5 45 L5 20 Z%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>'); background-size: 60px 60px;"></div>
    <div class="relative max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-emerald-200 text-sm mb-4"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span>/</span><span class="text-white">{{ $page->title }}</span></div>
        <h1 class="text-4xl md:text-5xl font-bold text-white">{{ $page->title }}</h1>
        <p class="text-emerald-200 mt-3 max-w-xl">Jangan ragu untuk menghubungi kami.</p>
    </div>
</section>
<section class="max-w-4xl mx-auto px-4 -mt-8 relative z-10">
    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
        <div class="prose max-w-none prose-stone prose-headings:text-emerald-800 prose-a:text-emerald-600">
            {!! $page->content !!}
        </div>
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-6 pt-8 border-t border-stone-100">
            <div class="text-center p-4 rounded-xl bg-emerald-50"><div class="text-2xl mb-2">📧</div><h4 class="font-semibold text-stone-800 text-sm">Email</h4><p class="text-emerald-600 text-sm">umkm@desa.id</p></div>
            <div class="text-center p-4 rounded-xl bg-amber-50"><div class="text-2xl mb-2">📞</div><h4 class="font-semibold text-stone-800 text-sm">Telepon</h4><p class="text-amber-600 text-sm">0812-3456-7890</p></div>
            <div class="text-center p-4 rounded-xl bg-blue-50"><div class="text-2xl mb-2">📍</div><h4 class="font-semibold text-stone-800 text-sm">Lokasi</h4><p class="text-blue-600 text-sm">Desa Binaan, Indonesia</p></div>
        </div>
    </div>
</section>
@endsection
