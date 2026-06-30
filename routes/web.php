<?php

use Illuminate\Support\Facades\Route;
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
