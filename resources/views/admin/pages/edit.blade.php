@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Halaman: {{ $page->title }}</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
<form action="{{ route('pages.update', $page) }}" method="POST">@csrf @method('PUT')
<div class="mb-4"><label class="block text-gray-700 mb-2">Judul</label><input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('title') border-red-500 @enderror">@error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<div class="mb-4"><label class="block text-gray-700 mb-2">Konten (HTML)</label><textarea name="content" rows="12" class="w-full border border-gray-300 rounded-lg px-4 py-2 font-mono text-sm focus:ring-2 focus:ring-emerald-500 @error('content') border-red-500 @enderror">{{ old('content', $page->content) }}</textarea>@error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror</div>
<button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Update</button>
<a href="{{ route('pages.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
</form></div>@endsection
