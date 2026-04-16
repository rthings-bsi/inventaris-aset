<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $roles = \App\Models\Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('roles.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        \App\Models\Role::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->route('roles.index')->with('success', 'Role baru berhasil ditambahkan!');
    }

    public function edit(\App\Models\Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, \App\Models\Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        // Prevent renaming admin/staff slugs if they are critical
        $slug = $role->slug;
        if (!in_array($role->slug, ['admin', 'staff'])) {
            $slug = \Illuminate\Support\Str::slug($request->name);
        }

        $role->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy(\App\Models\Role $role)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        if (in_array($role->slug, ['admin', 'staff'])) {
            return redirect()->back()->with('error', 'Role sistem (admin/staff) tidak dapat dihapus!');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}
