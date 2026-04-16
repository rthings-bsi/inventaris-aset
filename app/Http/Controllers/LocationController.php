<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('master-data.view'), 403);
        $search = $request->input('search');
        $locations = Location::when($search, function ($query, $search) {
                return $query->where('location_name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        return view('locations.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')->with('success', 'Data Location berhasil ditambahkan!');
    }

    public function edit(Location $location)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('success', 'Data Location berhasil diperbarui!');
    }

    public function destroy(Location $location)
    {
        abort_if(!auth()->user()->hasPermission('master-data.manage'), 403);
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Data Location berhasil dihapus!');
    }
}
