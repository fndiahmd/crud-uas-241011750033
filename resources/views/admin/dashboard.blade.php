@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-gray-500">Total Produk</p>
        <p class="text-3xl font-bold text-emerald-600">{{ $produkCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-gray-500">Total Kategori</p>
        <p class="text-3xl font-bold text-amber-500">{{ $kategoriCount }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-gray-500">Total Halaman</p>
        <p class="text-3xl font-bold text-blue-600">{{ $pageCount }}</p>
    </div>
</div>
@endsection
