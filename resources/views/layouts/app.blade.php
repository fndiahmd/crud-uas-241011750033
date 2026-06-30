<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Desa — Produk Lokal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50">
    <nav class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur shadow-sm border-b border-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-700 tracking-tight">UMKM<span class="text-amber-500">Desa</span></a>
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-sm rounded-lg {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50 font-semibold' : 'text-stone-600 hover:text-emerald-600 hover:bg-stone-50' }} transition">Home</a>
                    <a href="{{ route('produk') }}" class="px-3 py-2 text-sm rounded-lg {{ request()->routeIs('produk') ? 'text-emerald-700 bg-emerald-50 font-semibold' : 'text-stone-600 hover:text-emerald-600 hover:bg-stone-50' }} transition">Produk</a>
                    <a href="{{ route('kategori') }}" class="px-3 py-2 text-sm rounded-lg {{ request()->routeIs('kategori*') ? 'text-emerald-700 bg-emerald-50 font-semibold' : 'text-stone-600 hover:text-emerald-600 hover:bg-stone-50' }} transition">Kategori</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 text-sm rounded-lg {{ request()->routeIs('about') ? 'text-emerald-700 bg-emerald-50 font-semibold' : 'text-stone-600 hover:text-emerald-600 hover:bg-stone-50' }} transition">Tentang</a>
                    <a href="{{ route('kontak') }}" class="px-3 py-2 text-sm rounded-lg {{ request()->routeIs('kontak') ? 'text-emerald-700 bg-emerald-50 font-semibold' : 'text-stone-600 hover:text-emerald-600 hover:bg-stone-50' }} transition">Kontak</a>
                    <span class="w-px h-5 bg-stone-200 mx-2"></span>
                    <a href="{{ route('login') }}" class="ml-1 px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm shadow-emerald-200 transition">Login</a>
                </div>
                <button id="menu-toggle" class="md:hidden text-stone-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t px-4 pb-4 pt-2 space-y-1">
            <a href="{{ route('home') }}" class="block py-2 px-3 text-stone-600 hover:text-emerald-600 rounded-lg">Home</a>
            <a href="{{ route('produk') }}" class="block py-2 px-3 text-stone-600 hover:text-emerald-600 rounded-lg">Produk</a>
            <a href="{{ route('kategori') }}" class="block py-2 px-3 text-stone-600 hover:text-emerald-600 rounded-lg">Kategori</a>
            <a href="{{ route('about') }}" class="block py-2 px-3 text-stone-600 hover:text-emerald-600 rounded-lg">Tentang</a>
            <a href="{{ route('kontak') }}" class="block py-2 px-3 text-stone-600 hover:text-emerald-600 rounded-lg">Kontak</a>
            <a href="{{ route('login') }}" class="block py-2 px-4 mt-1 text-center text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Login Admin</a>
        </div>
    </nav>
    <main>@yield('content')</main>
    <footer class="bg-stone-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-3">UMKM<span class="text-amber-400">Desa</span></h3>
                <p class="text-stone-400 text-sm">Platform produk lokal berkualitas dari pengusaha kecil desa.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-3 text-stone-300">Navigasi</h4>
                <div class="space-y-1 text-sm text-stone-400"><a href="{{ route('home') }}" class="block hover:text-white">Home</a><a href="{{ route('produk') }}" class="block hover:text-white">Produk</a><a href="{{ route('kategori') }}" class="block hover:text-white">Kategori</a><a href="{{ route('about') }}" class="block hover:text-white">Tentang</a><a href="{{ route('kontak') }}" class="block hover:text-white">Kontak</a></div>
            </div>
            <div>
                <h4 class="font-semibold mb-3 text-stone-300">Kontak</h4>
                <p class="text-stone-400 text-sm">Email: umkm@desa.id<br>Telepon: 0812-3456-7890</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-stone-800 text-center"><p class="text-stone-500 text-sm">&copy; {{ date('Y') }} UMKM Desa.</p></div>
    </footer>
    <script>document.getElementById('menu-toggle')?.addEventListener('click',function(){document.getElementById('mobile-menu').classList.toggle('hidden');});</script>
</body>
</html>
