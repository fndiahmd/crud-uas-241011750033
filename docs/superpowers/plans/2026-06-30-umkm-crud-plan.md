# UMKM Product CRUD — Implementation Plan

**Goal:** Build a Laravel 12 web app for UMKM product management with public frontend, admin CRUD backend, manual login, and PDF export.

**Architecture:** Vanilla Laravel 12 MVC — Controllers handle all logic, Blade + Tailwind for views, MySQL for data, session-based auth via custom middleware.

**Tech Stack:** Laravel 12, Tailwind CSS (Vite), MySQL, barryvdh/laravel-dompdf, yajra/laravel-datatables, jQuery

## Global Constraints

- Laravel 12 (exact)
- Database name: db_uas_241011750033
- No Filament, Livewire, Jetstream, Breeze, Laravel Nova, Voyager, or similar admin packages
- Frontend: HTML, CSS, Tailwind CSS, JavaScript/jQuery only
- Backend: vanilla Laravel controllers only
- Manual login: custom users table with username and password columns, bcrypt hashed
- Single admin seeded: username=admin, password=admin123
- Responsive design: mobile, tablet, desktop
- Color scheme: emerald-600 primary, amber-400 accent

---

### Task 1: Laravel 12 Project Setup and Database Config

**Files:**
- Create: Laravel project files (via composer create-project)
- Modify: .env
- Create: 4 migration files in database/migrations/
- Create: app/Models/User.php, Kategori.php, Produk.php, Page.php

- [ ] **Step 1: Create Laravel 12 project**
```
composer create-project laravel/laravel:^12.0 .
```

- [ ] **Step 2: Configure .env database**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_uas_241011750033
DB_USERNAME=root
DB_PASSWORD=
```

- [ ] **Step 3: Create database**
```sql
CREATE DATABASE db_uas_241011750033;
```

- [ ] **Step 4: Create users migration**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('username', 50)->unique();
    $table->string('password', 255);
    $table->timestamps();
});
```

- [ ] **Step 5: Create kategoris migration**
```php
Schema::create('kategoris', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kategori', 100);
    $table->string('slug', 100)->unique();
    $table->timestamps();
});
```

- [ ] **Step 6: Create produks migration**
```php
Schema::create('produks', function (Blueprint $table) {
    $table->id();
    $table->string('gambar', 255)->nullable();
    $table->string('nama_produk', 200);
    $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
    $table->decimal('harga', 12, 2);
    $table->text('deskripsi');
    $table->timestamps();
});
```

- [ ] **Step 7: Create pages migration**
```php
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->string('slug', 100)->unique();
    $table->string('title', 200);
    $table->text('content');
    $table->timestamps();
});
```

- [ ] **Step 8: Run migrations**
```bash
php artisan migrate
```

- [ ] **Step 9: Install packages**
```bash
composer require barryvdh/laravel-dompdf yajra/laravel-datatables
```

- [ ] **Step 10: Create Models**

app/Models/Produk.php:
```php
class Produk extends Model
{
    protected $fillable = ['gambar', 'nama_produk', 'kategori_id', 'harga', 'deskripsi'];
    public function kategori() { return $this->belongsTo(Kategori::class); }
}
```

app/Models/Kategori.php:
```php
class Kategori extends Model
{
    protected $fillable = ['nama_kategori', 'slug'];
    public function produks() { return $this->hasMany(Produk::class); }
}
```

app/Models/Page.php:
```php
class Page extends Model
{
    protected $fillable = ['slug', 'title', 'content'];
}
```

- [ ] **Step 11: Enable storage link**
```bash
php artisan storage:link
```

---

### Task 2: Auth Middleware + AuthController + Seeder

**Files:**
- Create: app/Http/Middleware/AuthMiddleware.php
- Create: app/Http/Controllers/AuthController.php
- Modify: bootstrap/app.php (register middleware)
- Modify: database/seeders/DatabaseSeeder.php

- [ ] **Step 1: Create AuthMiddleware**

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return $next($request);
    }
}
```

- [ ] **Step 2: Create AuthController**

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['admin_id' => $user->id, 'admin_username' => $user->username]);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_username']);
        return redirect()->route('login');
    }
}
```

- [ ] **Step 3: Register middleware in bootstrap/app.php**

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin.auth' => \App\Http\Middleware\AuthMiddleware::class,
    ]);
})
```

- [ ] **Step 4: Create DatabaseSeeder**

```php
use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $k1 = Kategori::create(['nama_kategori' => 'Makanan', 'slug' => 'makanan']);
        $k2 = Kategori::create(['nama_kategori' => 'Minuman', 'slug' => 'minuman']);
        $k3 = Kategori::create(['nama_kategori' => 'Kerajinan', 'slug' => 'kerajinan']);

        Produk::create(['nama_produk' => 'Keripik Pisang', 'kategori_id' => $k1->id, 'harga' => 15000, 'deskripsi' => 'Keripik pisang renyah khas UMKM.']);
        Produk::create(['nama_produk' => 'Es Teh Herbal', 'kategori_id' => $k2->id, 'harga' => 8000, 'deskripsi' => 'Minuman teh herbal menyegarkan.']);
        Produk::create(['nama_produk' => 'Anyaman Bambu', 'kategori_id' => $k3->id, 'harga' => 50000, 'deskripsi' => 'Kerajinan anyaman bambu tradisional.']);

        Page::create(['slug' => 'about', 'title' => 'Tentang Kami', 'content' => '<p>UMKM binaan desa.</p>']);
        Page::create(['slug' => 'kontak', 'title' => 'Hubungi Kami', 'content' => '<p>Email: umkm@desa.id</p>']);
    }
}
```

- [ ] **Step 5: Run seeder**
```bash
php artisan db:seed
```

---

### Task 3: Routes + Admin Layout + Login View + Dashboard

**Files:**
- Modify: routes/web.php
- Create: resources/views/layouts/admin.blade.php
- Create: resources/views/admin/login.blade.php
- Create: resources/views/admin/dashboard.blade.php
- Create: app/Http/Controllers/AdminController.php

- [ ] **Step 1: Create AdminController**
```php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Page;

class AdminController extends Controller
{
    public function dashboard()
    {
        $produkCount = Produk::count();
        $kategoriCount = Kategori::count();
        $pageCount = Page::count();
        return view('admin.dashboard', compact('produkCount', 'kategoriCount', 'pageCount'));
    }
}
```

- [ ] **Step 2: Define all routes in web.php**
```php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FrontController;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/produk', [FrontController::class, 'produk'])->name('produk');
Route::get('/kategori', [FrontController::class, 'kategori'])->name('kategori');
Route::get('/kategori/{slug}', [FrontController::class, 'kategoriShow'])->name('kategori.show');
Route::get('/about', [FrontController::class, 'about'])->name('about');
Route::get('/kontak', [FrontController::class, 'kontak'])->name('kontak');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('admin.auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/produk/export', [ProdukController::class, 'exportPdf'])->name('produk.export');
    Route::resource('/produk', ProdukController::class)->except(['show']);
    Route::resource('/kategori', KategoriController::class)->except(['show']);
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
});
```

- [ ] **Step 3: Create admin layout**

resources/views/layouts/admin.blade.php:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - UMKM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
</body>
</html>
```

- [ ] **Step 4: Create login view**

resources/views/admin/login.blade.php:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin UMKM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-emerald-600 to-emerald-800 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-emerald-800 mb-6">Admin Login</h1>
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 font-semibold">Login</button>
        </form>
    </div>
</body>
</html>
```

- [ ] **Step 5: Create dashboard view**

resources/views/admin/dashboard.blade.php:
```html
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
```

---

### Task 4: Kategori CRUD

**Files:**
- Create: app/Http/Controllers/KategoriController.php
- Create: resources/views/admin/kategori/index.blade.php
- Create: resources/views/admin/kategori/create.blade.php
- Create: resources/views/admin/kategori/edit.blade.php

- [ ] **Step 1: Create KategoriController**
```php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('id', 'desc')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|max:100']);
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
        ]);
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate(['nama_kategori' => 'required|max:100']);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
        ]);
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
```

- [ ] **Step 2: Create kategori index view**

resources/views/admin/kategori/index.blade.php:
```html
@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Kategori</h1>
    <a href="{{ route('kategori.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">+ Tambah Kategori</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-gray-600">#</th>
                <th class="px-6 py-3 text-gray-600">Nama Kategori</th>
                <th class="px-6 py-3 text-gray-600">Slug</th>
                <th class="px-6 py-3 text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($kategoris as $k)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $k->nama_kategori }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $k->slug }}</td>
                <td class="px-6 py-4 space-x-2">
                    <a href="{{ route('kategori.edit', $k) }}" class="text-amber-500 hover:text-amber-700">Edit</a>
                    <form action="{{ route('kategori.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

- [ ] **Step 3: Create kategori create view**

resources/views/admin/kategori/create.blade.php:
```html
@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kategori</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-lg">
    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('nama_kategori') border-red-500 @enderror">
            @error('nama_kategori') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Simpan</button>
        <a href="{{ route('kategori.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
    </form>
</div>
@endsection
```

- [ ] **Step 4: Create kategori edit view**

resources/views/admin/kategori/edit.blade.php:
```html
@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kategori</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-lg">
    <form action="{{ route('kategori.update', $kategori) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('nama_kategori') border-red-500 @enderror">
            @error('nama_kategori') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Update</button>
        <a href="{{ route('kategori.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
    </form>
</div>
@endsection
```

---

### Task 5: Produk CRUD + Image Upload + DataTables

**Files:**
- Create: app/Http/Controllers/ProdukController.php
- Create: resources/views/admin/produk/index.blade.php
- Create: resources/views/admin/produk/create.blade.php
- Create: resources/views/admin/produk/edit.blade.php

- [ ] **Step 1: Create ProdukController**
```php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->orderBy('id', 'desc')->get();
        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|max:200',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama_produk' => 'required|max:200',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
```

- [ ] **Step 2: Create produk index with DataTables**

resources/views/admin/produk/index.blade.php:
```html
@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
    <a href="{{ route('produk.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">+ Tambah Produk</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table id="produk-table" class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-gray-600">Gambar</th>
                <th class="px-6 py-3 text-gray-600">Nama Produk</th>
                <th class="px-6 py-3 text-gray-600">Kategori</th>
                <th class="px-6 py-3 text-gray-600">Harga</th>
                <th class="px-6 py-3 text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($produks as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    @if($p->gambar)
                        <img src="{{ asset('storage/' . $p->gambar) }}" class="w-16 h-16 object-cover rounded">
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4">{{ $p->nama_produk }}</td>
                <td class="px-6 py-4">{{ $p->kategori->nama_kategori }}</td>
                <td class="px-6 py-4">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td class="px-6 py-4 space-x-2">
                    <a href="{{ route('produk.edit', $p) }}" class="text-amber-500 hover:text-amber-700">Edit</a>
                    <form action="{{ route('produk.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $('#produk-table').DataTable({ responsive: true });
});
</script>
@endpush
```

- [ ] **Step 3: Update admin layout to support @stack('scripts')**

Add before `</body>` in resources/views/layouts/admin.blade.php:
```html
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
@stack('scripts')
```

- [ ] **Step 4: Create produk create view**

resources/views/admin/produk/create.blade.php:
```html
@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Produk</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('nama_produk') border-red-500 @enderror">
            @error('nama_produk') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Kategori</label>
            <select name="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('kategori_id') border-red-500 @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            @error('kategori_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Harga</label>
            <input type="number" name="harga" value="{{ old('harga') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('harga') border-red-500 @enderror">
            @error('harga') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Gambar</label>
            <input type="file" name="gambar" class="w-full @error('gambar') border-red-500 @enderror">
            @error('gambar') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Simpan</button>
        <a href="{{ route('produk.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
    </form>
</div>
@endsection
```

- [ ] **Step 5: Create produk edit view**

resources/views/admin/produk/edit.blade.php:
```html
@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Produk</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form action="{{ route('produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('nama_produk') border-red-500 @enderror">
            @error('nama_produk') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Kategori</label>
            <select name="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('kategori_id') border-red-500 @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            @error('kategori_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Harga</label>
            <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('harga') border-red-500 @enderror">
            @error('harga') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            @error('deskripsi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Gambar</label>
            @if($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}" class="w-24 h-24 object-cover rounded mb-2">
            @endif
            <input type="file" name="gambar" class="w-full @error('gambar') border-red-500 @enderror">
            @error('gambar') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Update</button>
        <a href="{{ route('produk.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
    </form>
</div>
@endsection
```

---

### Task 6: Page Edit (About and Kontak)

**Files:**
- Create: app/Http/Controllers/PageController.php
- Create: resources/views/admin/pages/index.blade.php
- Create: resources/views/admin/pages/edit.blade.php

- [ ] **Step 1: Create PageController**
```php
namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|max:200',
            'content' => 'required',
        ]);
        $page->update($request->only('title', 'content'));
        return redirect()->route('pages.index')->with('success', 'Halaman berhasil diupdate.');
    }
}
```

- [ ] **Step 2: Create pages index view**

resources/views/admin/pages/index.blade.php:
```html
@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Halaman</h1>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-gray-600">#</th>
                <th class="px-6 py-3 text-gray-600">Slug</th>
                <th class="px-6 py-3 text-gray-600">Judul</th>
                <th class="px-6 py-3 text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($pages as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $p->slug }}</td>
                <td class="px-6 py-4">{{ $p->title }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('pages.edit', $p) }}" class="text-amber-500 hover:text-amber-700">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

- [ ] **Step 3: Create pages edit view**

resources/views/admin/pages/edit.blade.php:
```html
@extends('layouts.admin')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Halaman: {{ $page->title }}</h1>
<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form action="{{ route('pages.update', $page) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Judul</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Konten (HTML)</label>
            <textarea name="content" rows="12" class="w-full border border-gray-300 rounded-lg px-4 py-2 font-mono text-sm focus:ring-2 focus:ring-emerald-500 @error('content') border-red-500 @enderror">{{ old('content', $page->content) }}</textarea>
            @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700">Update</button>
        <a href="{{ route('pages.index') }}" class="text-gray-500 ml-2 hover:underline">Batal</a>
    </form>
</div>
@endsection
```

---

### Task 7: PDF Export

**Files:**
- Modify: app/Http/Controllers/ProdukController.php (add exportPdf method)

- [ ] **Step 1: Add exportPdf method to ProdukController**

Add this method inside ProdukController class:
```php
public function exportPdf()
{
    $produks = Produk::with('kategori')->orderBy('id', 'desc')->get();
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.produk.pdf', compact('produks'));
    return $pdf->download('laporan-produk-umkm.pdf');
}
```

- [ ] **Step 2: Create PDF blade view**

resources/views/admin/produk/pdf.blade.php:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk UMKM</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px 8px; font-size: 12px; text-align: left; }
        th { background-color: #059669; color: white; }
    </style>
</head>
<body>
    <h1>Laporan Produk UMKM</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->nama_produk }}</td>
                <td>{{ $p->kategori->nama_kategori }}</td>
                <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td>{{ Str::limit($p->deskripsi, 80) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```

- [ ] **Step 3: Test PDF export**
```bash
php artisan route:list
```
Visit `/admin/produk/export` after login — should download PDF.

---

### Task 8: Public Frontend (FrontController + Views + Tailwind Config)

**Files:**
- Create: app/Http/Controllers/FrontController.php
- Create: resources/views/layouts/app.blade.php
- Create: resources/views/front/home.blade.php
- Create: resources/views/front/produk.blade.php
- Create: resources/views/front/kategori.blade.php
- Create: resources/views/front/kategori-show.blade.php
- Create: resources/views/front/about.blade.php
- Create: resources/views/front/kontak.blade.php
- Modify: tailwind.config.js (add custom colors)

- [ ] **Step 1: Update Tailwind config**

resources/css/app.css:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

Modify the existing tailwind setup — Laravel 12 already has Vite configured with Tailwind.

- [ ] **Step 2: Create FrontController**
```php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Page;

class FrontController extends Controller
{
    public function home()
    {
        $produks = Produk::with('kategori')->latest()->take(6)->get();
        $kategoris = Kategori::all();
        return view('front.home', compact('produks', 'kategoris'));
    }

    public function produk()
    {
        $produks = Produk::with('kategori')->latest()->paginate(12);
        $kategoris = Kategori::all();
        return view('front.produk', compact('produks', 'kategoris'));
    }

    public function kategori()
    {
        $kategoris = Kategori::withCount('produks')->get();
        return view('front.kategori', compact('kategoris'));
    }

    public function kategoriShow($slug)
    {
        $kategori = Kategori::where('slug', $slug)->firstOrFail();
        $produks = Produk::where('kategori_id', $kategori->id)->latest()->paginate(12);
        return view('front.kategori-show', compact('kategori', 'produks'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        return view('front.about', compact('page'));
    }

    public function kontak()
    {
        $page = Page::where('slug', 'kontak')->firstOrFail();
        return view('front.kontak', compact('page'));
    }
}
```

- [ ] **Step 3: Create public layout**

resources/views/layouts/app.blade.php:
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Desa — Produk Lokal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-colors duration-300 bg-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-emerald-600">UMKM Desa</a>
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 font-medium {{ request()->routeIs('home') ? 'text-emerald-600' : '' }}">Home</a>
                    <a href="{{ route('produk') }}" class="text-gray-700 hover:text-emerald-600 font-medium {{ request()->routeIs('produk') ? 'text-emerald-600' : '' }}">Produk</a>
                    <a href="{{ route('kategori') }}" class="text-gray-700 hover:text-emerald-600 font-medium {{ request()->routeIs('kategori*') ? 'text-emerald-600' : '' }}">Kategori</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-emerald-600 font-medium {{ request()->routeIs('about') ? 'text-emerald-600' : '' }}">About</a>
                    <a href="{{ route('kontak') }}" class="text-gray-700 hover:text-emerald-600 font-medium {{ request()->routeIs('kontak') ? 'text-emerald-600' : '' }}">Kontak</a>
                </div>
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t px-4 pb-4 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-emerald-600">Home</a>
            <a href="{{ route('produk') }}" class="block py-2 text-gray-700 hover:text-emerald-600">Produk</a>
            <a href="{{ route('kategori') }}" class="block py-2 text-gray-700 hover:text-emerald-600">Kategori</a>
            <a href="{{ route('about') }}" class="block py-2 text-gray-700 hover:text-emerald-600">About</a>
            <a href="{{ route('kontak') }}" class="block py-2 text-gray-700 hover:text-emerald-600">Kontak</a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} UMKM Desa. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('menu-toggle')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        window.addEventListener('scroll', function() {
            var nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('bg-white', 'shadow-md');
                nav.classList.remove('bg-transparent');
            } else {
                nav.classList.remove('bg-white', 'shadow-md');
                nav.classList.add('bg-transparent');
            }
        });
    </script>
</body>
</html>
```

- [ ] **Step 4: Create home view**

resources/views/front/home.blade.php:
```html
@extends('layouts.app')
@section('content')
<section class="bg-gradient-to-br from-emerald-600 to-emerald-800 text-white pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Produk UMKM Unggulan</h1>
        <p class="text-lg text-emerald-100 mb-8 max-w-2xl mx-auto">Dukung produk lokal berkualitas dari pengusaha kecil di desa kita.</p>
        <a href="{{ route('produk') }}" class="inline-block bg-amber-400 text-gray-900 px-8 py-3 rounded-full font-bold hover:bg-amber-300">Lihat Produk</a>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Kategori Produk</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($kategoris as $k)
        <a href="{{ route('kategori.show', $k->slug) }}" class="bg-white border border-gray-200 rounded-xl p-6 text-center hover:shadow-lg hover:border-emerald-300 transition">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-2xl">📦</span>
            </div>
            <h3 class="font-semibold text-gray-800">{{ $k->nama_kategori }}</h3>
        </a>
        @endforeach
    </div>
</section>

<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Produk Terbaru</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($produks as $p)
            <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition">
                @if($p->gambar)
                    <img src="{{ asset('storage/' . $p->gambar) }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                @endif
                <div class="p-4">
                    <span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded">{{ $p->kategori->nama_kategori }}</span>
                    <h3 class="font-bold text-gray-800 mt-2">{{ $p->nama_produk }}</h3>
                    <p class="text-emerald-600 font-bold mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ Str::limit($p->deskripsi, 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('produk') }}" class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 font-semibold">Semua Produk</a>
        </div>
    </div>
</section>
@endsection
```

- [ ] **Step 5: Create produk view**

resources/views/front/produk.blade.php:
```html
@extends('layouts.app')
@section('content')
<section class="bg-emerald-600 text-white pt-28 pb-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold">Semua Produk</h1>
        <p class="text-emerald-100 mt-2">Jelajahi produk UMKM lokal.</p>
    </div>
</section>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($produks as $p)
        <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition">
            @if($p->gambar)
                <img src="{{ asset('storage/' . $p->gambar) }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
            @endif
            <div class="p-4">
                <span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded">{{ $p->kategori->nama_kategori }}</span>
                <h3 class="font-bold text-gray-800 mt-2">{{ $p->nama_produk }}</h3>
                <p class="text-emerald-600 font-bold mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                <p class="text-gray-500 text-sm mt-2">{{ Str::limit($p->deskripsi, 80) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $produks->links() }}
    </div>
</div>
@endsection
```

- [ ] **Step 6: Create kategori list view**

resources/views/front/kategori.blade.php:
```html
@extends('layouts.app')
@section('content')
<section class="bg-emerald-600 text-white pt-28 pb-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold">Kategori Produk</h1>
        <p class="text-emerald-100 mt-2">Pilih berdasarkan kategori.</p>
    </div>
</section>
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($kategoris as $k)
        <a href="{{ route('kategori.show', $k->slug) }}" class="bg-white border border-gray-200 rounded-xl p-8 text-center hover:shadow-lg hover:border-emerald-300 transition group">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-200 transition">
                <span class="text-3xl">📦</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $k->nama_kategori }}</h3>
            <p class="text-gray-500 mt-1">{{ $k->produks_count }} produk</p>
        </a>
        @endforeach
    </div>
</div>
@endsection
```

- [ ] **Step 7: Create kategori show view**

resources/views/front/kategori-show.blade.php:
```html
@extends('layouts.app')
@section('content')
<section class="bg-emerald-600 text-white pt-28 pb-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold">Kategori: {{ $kategori->nama_kategori }}</h1>
        <p class="text-emerald-100 mt-2">{{ $produks->total() }} produk ditemukan.</p>
    </div>
</section>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($produks as $p)
        <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition">
            @if($p->gambar)
                <img src="{{ asset('storage/' . $p->gambar) }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
            @endif
            <div class="p-4">
                <h3 class="font-bold text-gray-800">{{ $p->nama_produk }}</h3>
                <p class="text-emerald-600 font-bold mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                <p class="text-gray-500 text-sm mt-2">{{ Str::limit($p->deskripsi, 80) }}</p>
            </div>
        </div>
        @empty
        <p class="text-gray-500 col-span-full text-center py-12">Belum ada produk di kategori ini.</p>
        @endforelse
    </div>
    <div class="mt-8">
        {{ $produks->links() }}
    </div>
</div>
@endsection
```

- [ ] **Step 8: Create about view**

resources/views/front/about.blade.php:
```html
@extends('layouts.app')
@section('content')
<section class="bg-emerald-600 text-white pt-28 pb-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
    </div>
</section>
<div class="max-w-4xl mx-auto px-4 py-12 prose max-w-none">
    {!! $page->content !!}
</div>
@endsection
```

- [ ] **Step 9: Create kontak view**

resources/views/front/kontak.blade.php:
```html
@extends('layouts.app')
@section('content')
<section class="bg-emerald-600 text-white pt-28 pb-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
    </div>
</section>
<div class="max-w-4xl mx-auto px-4 py-12 prose max-w-none">
    {!! $page->content !!}
</div>
@endsection
```

---

### Task 9: Final Polish — Vite Build + Testing

**Files:**
- Modify: resources/css/app.css (Tailwind directives)

- [ ] **Step 1: Ensure Tailwind is configured**

resources/css/app.css:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

- [ ] **Step 2: Build assets**
```bash
npm install
npm run build
```

- [ ] **Step 3: Start dev server and test**
```bash
php artisan serve
```

- [ ] **Step 4: Manual test checklist**
1. Visit http://localhost:8000 → home page with products and categories
2. Visit /produk → product listing with pagination
3. Visit /kategori → category list
4. Visit /kategori/makanan → filtered products
5. Visit /about → about page content from DB
6. Visit /kontak → contact page content from DB
7. Visit /login → login form
8. Login as admin/admin123 → redirected to /admin dashboard
9. Admin: CRUD produk (create/edit/delete) with image upload
10. Admin: CRUD kategori (create/edit/delete)
11. Admin: Edit halaman (about/kontak content)
12. Admin: Export PDF → downloads laporan-produk-umkm.pdf
13. Visit /admin without login → redirect to /login
14. Test mobile responsive (resize browser)
15. Test validation: submit empty forms → error messages appear

## Validation Commands
```bash
php artisan serve
npm run dev
```
