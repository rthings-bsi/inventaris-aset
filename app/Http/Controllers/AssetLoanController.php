<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\AssetLoanNotification;
use Illuminate\Support\Facades\Notification;

class AssetLoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!auth()->user()->hasPermission('loan.view')) abort(403);
        
        if ($user->hasPermission('loan.manage')) {
            $loans = AssetLoan::with(['asset', 'user'])->latest()->paginate(10);
        } else {
            $loans = AssetLoan::with(['asset'])->where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('loans.index', compact('loans'));
    }
    public function create()
    {
        if (!auth()->user()->hasPermission('loan.create')) abort(403);

        $query = Asset::whereNull('user_id')
            ->whereDoesntHave('loans', function ($query) {
                $query->whereIn('status', ['pending', 'borrowed']);
            })
            ->latest();

        // Regular users can only borrow assets with condition 'Baik Sekali' (Grade A) or 'Baik' (Grade B)
        if (!Auth::user()->isAdmin()) {
            $query->whereIn('condition', ['Baik Sekali', 'Baik']);
        }

        $assets = $query->get();
            
        return view('loans.create', compact('assets'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('loan.create')) abort(403);

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        // Regular users (without manage permission) can only borrow Grade A/B
        if (!Auth::user()->hasPermission('loan.manage') && !in_array($asset->condition, ['Baik Sekali', 'Baik'])) {
            return back()->with('error', 'Anda hanya diizinkan untuk meminjam aset dengan kondisi Grade A atau B.');
        }

        // Check if asset is already borrowed physically
        if ($asset->user_id) {
            return back()->with('error', 'Aset sedang dipinjam oleh pengguna lain.');
        }

        // Check if asset has pending requests
        $hasPending = AssetLoan::where('asset_id', $asset->id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->exists();
            
        if ($hasPending) {
            return back()->with('error', 'Aset ini sedang dalam proses pengajuan oleh pengguna lain.');
        }

        $loan = AssetLoan::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'loan_date' => now(),
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AssetLoanNotification($loan, 'requested', 'Pengajuan peminjaman baru unit ' . $asset->asset_name . ' dari ' . Auth::user()->name));

        return redirect()->route('loans.index')->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan.');
    }

    public function approve(AssetLoan $loan)
    {
        if (!Auth::user()->hasPermission('loan.manage')) {
            return abort(403);
        }

        if ($loan->asset->user_id) {
            return back()->with('error', 'Aset sudah dipinjam pengguna lain. Harap tolak pengajuan ini atau kembalikan aset terlebih dahulu.');
        }

        $loan->update([
            'status' => 'borrowed',
            'loan_date' => now() // Reset loan date to approval time
        ]);

        $loan->asset->update([
            'user_id' => $loan->user_id
        ]);

        $loan->user->notify(new \App\Notifications\AssetLoanNotification($loan, 'approved', 'Pengajuan peminjaman unit ' . $loan->asset->asset_name . ' Anda telah disetujui Administrator.'));

        return back()->with('success', 'Pengajuan peminjaman disetujui.');
    }

    public function reject(AssetLoan $loan)
    {
        if (!Auth::user()->hasPermission('loan.manage')) {
            return abort(403);
        }

        $loan->update(['status' => 'rejected']);

        $loan->user->notify(new \App\Notifications\AssetLoanNotification($loan, 'rejected', 'Maaf, pengajuan peminjaman unit ' . $loan->asset->asset_name . ' Anda ditolak.'));

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function return(AssetLoan $loan)
    {
        // Manager or the user who borrowed it
        if (!Auth::user()->hasPermission('loan.manage') && Auth::id() !== $loan->user_id) {
            return abort(403);
        }

        if ($loan->status !== 'borrowed') {
            return back()->with('error', 'Aset tidak dalam status dipinjam.');
        }

        $loan->update([
            'status' => 'returned',
            'return_date' => now()
        ]);

        $loan->asset->update([
            'user_id' => null
        ]);

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new AssetLoanNotification($loan, 'returned', 'Unit aset ' . $loan->asset->asset_name . ' telah dikembalikan oleh ' . Auth::user()->name));

        return back()->with('success', 'Aset berhasil dikembalikan.');
    }

    /**
     * Pinjamkan Aset (Direct Assignment)
     */
    public function pinjam(Request $request, Asset $asset)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $asset->loans()->create([
            'user_id' => $request->user_id,
            'loan_date' => now(),
            'status' => 'borrowed',
            'notes' => $request->notes
        ]);

        $asset->update(['user_id' => $request->user_id]);

        $user = \App\Models\User::find($request->user_id);
        return redirect()->route('assets.show', $asset)->with('success', 'Proses serah terima berhasil. Aset dipinjamkan ke ' . $user->name);
    }

    /**
     * Kembalikan Aset (Direct Return)
     */
    public function kembalikan(Request $request, Asset $asset)
    {
        $loan = $asset->loans()->where('status', 'borrowed')->latest()->first();
        if ($loan) {
            $loan->update([
                'return_date' => now(),
                'status' => 'returned'
            ]);
        }
        
        $asset->update(['user_id' => null]);

        return redirect()->route('assets.show', $asset)->with('success', 'Aset telah dikembalikan ke inventory.');
    }
}
