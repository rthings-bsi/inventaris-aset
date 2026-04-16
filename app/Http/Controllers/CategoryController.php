<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('master-data.view'), 403);
        $search = $request->input('search');
        $categories = Category::when($search, function ($query, $search) {
                return $query->where('category_name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        return view('categories.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Data Category berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Data Category berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        // Add check if category is used by assets
        // if ($category->assets()->exists()) {
        //     return redirect()->back()->with('error', 'Category tidak dapat dihapus karena masih digunakan oleh aset.');
        // }
        // For now, simple delete
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Data Category berhasil dihapus!');
    }
}
