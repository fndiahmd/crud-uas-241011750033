@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-800">Daftar Kategori</h1><a href="{{ route('kategori.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">+ Tambah Kategori</a></div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-left"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-gray-600">#</th><th class="px-6 py-3 text-gray-600">Nama Kategori</th><th class="px-6 py-3 text-gray-600">Slug</th><th class="px-6 py-3 text-gray-600">Aksi</th></tr></thead>
        <tbody class="divide-y">@foreach($kategoris as $k)<tr class="hover:bg-gray-50"><td class="px-6 py-4">{{ $loop->iteration }}</td><td class="px-6 py-4">{{ $k->nama_kategori }}</td><td class="px-6 py-4 text-gray-500">{{ $k->slug }}</td><td class="px-6 py-4 space-x-2"><a href="{{ route('kategori.edit', $k) }}" class="text-amber-500 hover:text-amber-700">Edit</a><form action="{{ route('kategori.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-700">Hapus</button></form></td></tr>@endforeach</tbody>
    </table></div>@endsection
