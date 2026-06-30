<?php

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
