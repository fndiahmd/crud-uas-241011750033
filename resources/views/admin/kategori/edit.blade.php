@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kategori</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-lg">
<form action="{{ route('kategori.update', $kategori) }}" method="POST">@csrf @method('PUT')
<div class="mb-4"><label class="block text-gray-700 mb-2">Nama Kategori</label><input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('nama_kategori') border-red-500 @enderror">@error('nama_kategori') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Update</button>
<a href="{{ route('kategori.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
</form></div>@endsection
