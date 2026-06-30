# UMKM Product CRUD Application — Design Spec

**Date**: 2026-06-30
**Stack**: Laravel 12, Tailwind CSS, MySQL, barryvdh/laravel-dompdf, yajra/laravel-datatables
**Constraints**: No Filament, Livewire, Jetstream, Breeze, Laravel Nova, Voyager, or similar admin packages. Frontend: HTML, CSS, Tailwind CSS, JavaScript/jQuery only. Backend: vanilla Laravel controllers only.

## Requirements Summary

Web app for UMKM product management:
- Public frontend: Home, Produk, Kategori, About, Kontak (accessible without login)
- Admin backend: CRUD produk, CRUD kategori, edit page content (About/Kontak), PDF export
- Manual login (users table: username + password, no Breeze), single admin seeded
- Responsive Tailwind CSS UI
- Input validation, flash messages, DataTables

## Database Schema

### users
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | auto-increment |
| username | varchar(50) | unique |
| password | varchar(255) | bcrypt hash |
| timestamps | | |

### kategoris
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | auto-increment |
| nama_kategori | varchar(100) | |
| slug | varchar(100) | unique, URL-safe |
| timestamps | | |

### produks
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | auto-increment |
| gambar | varchar(255) | path to stored image |
| nama_produk | varchar(200) | |
| kategori_id | bigint FK | references kategoris(id) |
| harga | decimal(12,2) | |
| deskripsi | text | |
| timestamps | | |

### pages
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | auto-increment |
| slug | varchar(100) | unique, e.g. 'about', 'kontak' |
| title | varchar(200) | |
| content | text | HTML content |
| timestamps | | |

## Routes

### Public
| Method | URI | Controller | View |
|---|---|---|---|
| GET | / | FrontController@home | front.home |
| GET | /produk | FrontController@produk | front.produk |
| GET | /kategori | FrontController@kategori | front.kategori |
| GET | /kategori/{slug} | FrontController@kategoriShow | front.kategori-show |
| GET | /about | FrontController@about | front.about |
| GET | /kontak | FrontController@kontak | front.kontak |

### Auth
| Method | URI | Controller |
|---|---|---|
| GET | /login | AuthController@showLogin |
| POST | /login | AuthController@login |
| POST | /logout | AuthController@logout |

### Admin (protected by auth middleware)
| Method | URI | Controller |
|---|---|---|
| GET | /admin | AdminController@dashboard |
| GET | /admin/produk | ProdukController@index |
| GET | /admin/produk/create | ProdukController@create |
| POST | /admin/produk | ProdukController@store |
| GET | /admin/produk/{id}/edit | ProdukController@edit |
| PUT | /admin/produk/{id} | ProdukController@update |
| DELETE | /admin/produk/{id} | ProdukController@destroy |
| GET | /admin/produk/export | ProdukController@exportPdf |
| GET | /admin/kategori | KategoriController@index |
| GET | /admin/kategori/create | KategoriController@create |
| POST | /admin/kategori | KategoriController@store |
| GET | /admin/kategori/{id}/edit | KategoriController@edit |
| PUT | /admin/kategori/{id} | KategoriController@update |
| DELETE | /admin/kategori/{id} | KategoriController@destroy |
| GET | /admin/pages | PageController@index |
| GET | /admin/pages/{id}/edit | PageController@edit |
| PUT | /admin/pages/{id} | PageController@update |

## Controllers

- **FrontController** — all public page queries
- **AuthController** — manual login/logout with session
- **AdminController** — dashboard stats
- **ProdukController** — CRUD + image upload + PDF export
- **KategoriController** — CRUD
- **PageController** — edit only (about, kontak)

## Validation Rules

### Produk
- nama_produk: required, max:200
- kategori_id: required, exists:kategoris,id
- harga: required, numeric, min:0
- deskripsi: required
- gambar: nullable, image, max:2048

### Kategori
- nama_kategori: required, max:100

### Page
- title: required, max:200
- content: required

### Login
- username: required
- password: required

## Error Handling

- Validation errors → back with errors
- CRUD success → redirect with flash message
- Auth failure → redirect /login with error
- Unauthorized → redirect /login

## Seeder

- 1 admin user: username=admin, password=admin123 (bcrypt)
- 3 sample kategoris: Makanan, Minuman, Kerajinan
- 3 sample produks

## Views Layout

```
resources/views/
├── layouts/
│   ├── app.blade.php          (public — navbar + footer)
│   └── admin.blade.php         (admin — sidebar + header)
├── front/
│   ├── home.blade.php
│   ├── produk.blade.php        (grid + paginate)
│   ├── kategori.blade.php      (list categories)
│   ├── kategori-show.blade.php (products filtered)
│   ├── about.blade.php
│   └── kontak.blade.php
├── admin/
│   ├── login.blade.php
│   ├── dashboard.blade.php
│   ├── produk/
│   │   ├── index.blade.php     (DataTables)
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── kategori/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── pages/
│       ├── index.blade.php
│       └── edit.blade.php
```

## Frontend Design Direction

- Tailwind CSS, responsive mobile-first
- Public: clean landing-page style, product cards grid, category cards with icon circles
- Admin: sidebar layout (dark sidebar, white content area), DataTables styled with Tailwind
- Color: green/earth tones (UMKM feel) — emerald-600 primary, amber-400 accent
- Navbar: transparent on home, solid on scroll

## Implementation Order

1. Laravel project setup + DB config
2. Migrations + Models
3. Routes + AuthMiddleware + AuthController
4. Seeder
5. Admin layout + Dashboard
6. Kategori CRUD
7. Produk CRUD + image upload + DataTables
8. Page edit
9. PDF export
10. Public frontend (FrontController + views)
11. Polish & testing

## Acceptance Criteria

- [ ] All CRUD operations work for produk and kategori
- [ ] Login protects all /admin routes
- [ ] PDF export downloads product data
- [ ] Public pages display data from database
- [ ] About and Kontak content editable from admin
- [ ] Responsive on mobile/tablet/desktop
- [ ] Validation errors show on forms
- [ ] Flash messages after CRUD success
