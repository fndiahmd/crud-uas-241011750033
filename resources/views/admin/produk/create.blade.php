@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Produk</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
<form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">@csrf
<div class="mb-4"><label class="block text-gray-700 mb-2">Nama Produk</label><input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('nama_produk') border-red-500 @enderror">@error('nama_produk') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<div class="mb-4"><label class="block text-gray-700 mb-2">Kategori</label><select name="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('kategori_id') border-red-500 @enderror"><option value="">-- Pilih Kategori --</option>@foreach($kategoris as $k)<option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>@endforeach</select>@error('kategori_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<div class="mb-4"><label class="block text-gray-700 mb-2">Harga</label><input type="number" name="harga" value="{{ old('harga') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('harga') border-red-500 @enderror">@error('harga') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<div class="mb-4"><label class="block text-gray-700 mb-2">Deskripsi</label><textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>@error('deskripsi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<div class="mb-4"><label class="block text-gray-700 mb-2">Gambar</label><input type="file" name="gambar" class="w-full @error('gambar') border-red-500 @enderror">@error('gambar') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Simpan</button>
<a href="{{ route('produk.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
</form></div>@endsection
