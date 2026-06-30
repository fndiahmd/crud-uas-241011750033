@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Halaman</h1>
<div class="bg-white rounded-xl shadow overflow-hidden">
<table class="w-full text-left"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-gray-600">#</th><th class="px-6 py-3 text-gray-600">Slug</th><th class="px-6 py-3 text-gray-600">Judul</th><th class="px-6 py-3 text-gray-600">Aksi</th></tr></thead>
<tbody class="divide-y">@foreach($pages as $p)<tr class="hover:bg-gray-50"><td class="px-6 py-4">{{ $loop->iteration }}</td><td class="px-6 py-4">{{ $p->slug }}</td><td class="px-6 py-4">{{ $p->title }}</td><td class="px-6 py-4"><a href="{{ route('pages.edit', $p) }}" class="text-amber-500 hover:text-amber-700">Edit</a></td></tr>@endforeach</tbody></table></div>
@endsection
