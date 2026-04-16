<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $assets = Asset::with(['category', 'location'])
            ->filter(request(['search', 'category', 'status']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = \App\Models\Category::all();
        
        return view('assets.index', compact('assets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $locations = \App\Models\Location::all();
        $users = \App\Models\User::all();
        return view('assets.create', compact('categories', 'locations', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('assets', 'public');
        }

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $users = \App\Models\User::all();
        return view('assets.show', compact('asset', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $categories = \App\Models\Category::all();
        $locations = \App\Models\Location::all();
        $users = \App\Models\User::all();
        return view('assets.edit', compact('asset', 'categories', 'locations', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($asset->photo) {
                Storage::disk('public')->delete($asset->photo);
            }
            $validated['photo'] = $request->file('photo')->store('assets', 'public');
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Aset berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        // Delete photo if exists
        if ($asset->photo) {
            Storage::disk('public')->delete($asset->photo);
        }

        // Hard delete from database as per user request
        $asset->forceDelete();

        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus permanen!');
    }

    /**
     * Remove multiple assets from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:assets,id'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $assets = Asset::whereIn('id', $request->ids)->get();

                foreach ($assets as $asset) {
                    if ($asset->photo) {
                        Storage::disk('public')->delete($asset->photo);
                    }
                    // Use forceDelete to remove from database physically
                    $asset->forceDelete();
                }
            });

            return redirect()->route('assets.index')->with('success', count($request->ids) . ' aset berhasil dihapus permanen secara massal!');
        } catch (\Exception $e) {
            \Log::error('Bulk Delete Error: ' . $e->getMessage(), [
                'ids' => $request->ids,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('assets.index')->with('error', 'Gagal menghapus aset terpilih secara massal. Silakan periksa apakah aset masih digunakan dalam transaksi lain.');
        }
    }
}
