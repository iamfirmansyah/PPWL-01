<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Categories', 'url' => '']
        ];

        return view('categories.index', compact('categories', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Categories', 'url' => route('categories.index')],
            ['title' => 'Create', 'url' => '']
        ];

        return view('categories.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|min:3|max:191',
        ]);

        Category::create(['nama' => $request->nama]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Categories', 'url' => route('categories.index')],
            ['title' => 'Edit', 'url' => '']
        ];

        return view('categories.edit', compact('category', 'breadcrumbs'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama' => 'required|string|min:3|max:191',
        ]);

        $category->update(['nama' => $request->nama]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
