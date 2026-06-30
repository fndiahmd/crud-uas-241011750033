<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - UMKM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-4 text-xl font-bold border-b border-gray-700">Admin UMKM</div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">Dashboard</a>
                <a href="{{ route('produk.index') }}" class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('produk.*') ? 'bg-gray-700' : '' }}">Produk</a>
                <a href="{{ route('kategori.index') }}" class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('kategori.*') ? 'bg-gray-700' : '' }}">Kategori</a>
                <a href="{{ route('pages.index') }}" class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('pages.*') ? 'bg-gray-700' : '' }}">Halaman</a>
                <hr class="my-2 border-gray-700">
                <a href="{{ route('produk.export') }}" class="block px-3 py-2 rounded hover:bg-gray-700 text-amber-400">Export PDF</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button class="w-full text-left px-3 py-2 rounded hover:bg-gray-700 text-red-400">Logout</button>
                </form>
            </nav>
        </aside>
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    @stack('scripts')
</body>
</html>
