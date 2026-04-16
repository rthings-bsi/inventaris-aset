@extends('layouts.app')

@section('title', 'Peminjaman Aset - Inventaris')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }
    .glass-header {
        background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(243, 232, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 2px solid white;
    }
</style>

<!-- Page Header -->
<div class="relative glass-header rounded-[2rem] p-5 sm:p-8 mb-6 sm:mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] animate-slide-down">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br from-indigo-200/50 to-purple-200/50 rounded-full mix-blend-multiply filter blur-2xl animate-pulse"></div>
    <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700 flex items-center gap-2">
                <i class="fas fa-handshake text-indigo-500"></i> Peminjaman Aset
            </h1>
            <p class="text-sm font-bold text-gray-500 mt-2">Daftar permintaan peminjaman dan riwayat pengembalian aset.</p>
        </div>
        <div>
            <a href="{{ route('loans.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-[0_4px_15px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 transition-all font-black text-sm gap-2">
                <i class="fas fa-plus"></i> Ajukan Peminjaman
            </a>
        </div>
    </div>
</div>

<!-- List Ruangan / Card View -->
<div class="glass-card rounded-[2rem] overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
    <div class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 px-5 sm:px-6 py-4 sm:py-5 border-b border-indigo-100/50">
        <h2 class="text-sm font-black text-indigo-700 uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-list text-indigo-400"></i> Riwayat Peminjaman
        </h2>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block p-0 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="p-4 text-xs font-black text-indigo-400 uppercase tracking-widest border-b border-gray-100">Aset</th>
                    @if(auth()->user()->hasPermission('loan.manage'))
                        <th class="p-4 text-xs font-black text-indigo-400 uppercase tracking-widest border-b border-gray-100">Peminjam</th>
                    @endif
                    <th class="p-4 text-xs font-black text-indigo-400 uppercase tracking-widest border-b border-gray-100">Tanggal Pengajuan</th>
                    <th class="p-4 text-xs font-black text-indigo-400 uppercase tracking-widest border-b border-gray-100">Status</th>
                    <th class="p-4 text-xs font-black text-indigo-400 uppercase tracking-widest border-b border-gray-100 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($loans as $loan)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-indigo-50 shadow-sm flex items-center justify-center text-indigo-500 overflow-hidden flex-shrink-0">
                                    @if($loan->asset && $loan->asset->photo)
                                        <img src="{{ asset('storage/' . $loan->asset->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-boxes"></i>
                                    @endif
                                </div>
                                <div>
                                    @if($loan->asset)
                                        <a href="{{ route('assets.show', $loan->asset) }}" class="text-sm font-bold text-gray-800 hover:text-indigo-600 transition-colors">{{ $loan->asset->asset_name }}</a>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $loan->asset->asset_code }}</p>
                                    @else
                                        <span class="text-sm font-bold text-gray-400">Aset dihapus permanen</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @if(auth()->user()->hasPermission('loan.manage'))
                            <td class="p-4">
                                <span class="text-sm font-bold text-gray-700">{{ $loan->user->name ?? 'Unknown' }}</span>
                                <p class="text-[10px] font-bold text-gray-400">{{ $loan->notes ? 'Notes: ' . $loan->notes : '-' }}</p>
                            </td>
                        @endif
                        <td class="p-4">
                            <span class="text-sm font-bold text-gray-700">{{ $loan->created_at->format('d M Y') }}</span>
                            <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $loan->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="p-4">
                            @if($loan->status == 'pending')
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase px-3 py-1 rounded-full border border-amber-200">Menunggu</span>
                            @elseif($loan->status == 'borrowed')
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase px-3 py-1 rounded-full border border-indigo-200">Dipinjam</span>
                            @elseif($loan->status == 'returned')
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase px-3 py-1 rounded-full border border-emerald-200">Dikembalikan</span>
                            @elseif($loan->status == 'rejected')
                                <span class="bg-red-100 text-red-700 text-[10px] font-black uppercase px-3 py-1 rounded-full border border-red-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="p-4 flex gap-2 justify-center">
                            @if(auth()->user()->hasPermission('loan.manage') && $loan->status == 'pending')
                                <form action="{{ route('loans.approve', $loan) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="handleLoanAction(this, 'Setujui', 'Persetujuan peminjaman aset ini akan diproses.', 'success')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-100 shadow-sm transition-all flex items-center justify-center text-sm" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('loans.reject', $loan) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="handleLoanAction(this, 'Tolak', 'Permohonan peminjaman ini akan ditolak.', 'error')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-100 shadow-sm transition-all flex items-center justify-center text-sm" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                            
                            @if($loan->status == 'borrowed' && (auth()->user()->hasPermission('loan.manage') || auth()->id() == $loan->user_id))
                                <form action="{{ route('loans.return', $loan) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="handleLoanAction(this, 'Kembalikan', 'Aset akan dikembalikan ke gudang.', 'question')" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-100 shadow-sm transition-all flex items-center justify-center text-sm" title="Kembalikan Aset">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            @endif
                            
                            @if($loan->status != 'pending' && $loan->status != 'borrowed')
                                <span class="text-[10px] font-bold text-gray-400 italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 font-bold">Belum ada data peminjaman aset.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4 px-4 pt-4 pb-4">
        @forelse($loans as $loan)
        <div class="bg-white rounded-[1.25rem] p-4 border border-indigo-50 shadow-[0_5px_15px_-5px_rgba(79,70,229,0.08)] flex flex-col gap-3 relative overflow-hidden group">
            <div class="flex gap-3 items-start justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-indigo-50 shadow-sm flex items-center justify-center text-indigo-500 overflow-hidden flex-shrink-0">
                        @if($loan->asset && $loan->asset->photo)
                            <img src="{{ asset('storage/' . $loan->asset->photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-boxes"></i>
                        @endif
                    </div>
                    <div>
                        @if($loan->asset)
                            <h4 class="text-[14px] font-black text-gray-800 leading-tight truncate w-32">{{ $loan->asset->asset_name }}</h4>
                            <span class="font-mono text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100/50 mt-1 inline-block">{{ $loan->asset->asset_code }}</span>
                        @else
                            <span class="text-[14px] font-black text-gray-400">Aset dihapus</span>
                        @endif
                    </div>
                </div>
                
                <!-- Status Badge -->
                <div class="flex-shrink-0">
                    @if($loan->status == 'pending')
                        <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase px-2 py-1 rounded-lg border border-amber-200"><i class="fas fa-clock mr-1"></i>Menunggu</span>
                    @elseif($loan->status == 'borrowed')
                        <span class="bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase px-2 py-1 rounded-lg border border-indigo-200"><i class="fas fa-handshake mr-1"></i>Dipinjam</span>
                    @elseif($loan->status == 'returned')
                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase px-2 py-1 rounded-lg border border-emerald-200"><i class="fas fa-check mr-1"></i>Kembali</span>
                    @elseif($loan->status == 'rejected')
                        <span class="bg-red-100 text-red-700 text-[10px] font-black uppercase px-2 py-1 rounded-lg border border-red-200"><i class="fas fa-times mr-1"></i>Ditolak</span>
                    @endif
                </div>
            </div>

            <!-- Body -->
            <div class="grid grid-cols-2 gap-y-3 gap-x-2 text-[12px] pt-1">
                @if(auth()->check() && auth()->user()->hasPermission('loan.manage'))
                <div class="flex flex-col col-span-2">
                    <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5">Peminjam</span>
                    <span class="font-bold text-gray-700">{{ $loan->user->name ?? 'Unknown' }}</span>
                    @if($loan->notes)
                        <span class="text-[10px] font-medium text-gray-500 italic mt-0.5 line-clamp-2">"{{ $loan->notes }}"</span>
                    @endif
                </div>
                @endif
                <div class="flex flex-col">
                    <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5">Tgl Pengajuan</span>
                    <span class="font-bold text-gray-700">{{ $loan->created_at->format('d M Y') }}</span>
                    <span class="text-[9px] text-gray-500">{{ $loan->created_at->format('H:i') }} WIB</span>
                </div>
            </div>
            
            <div class="mt-2 flex justify-end gap-2 border-t border-gray-100 pt-3">
                @if(auth()->check() && auth()->user()->hasPermission('loan.manage') && $loan->status == 'pending')
                    <form action="{{ route('loans.reject', $loan) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="handleLoanAction(this, 'Tolak', 'Permohonan peminjaman ini akan ditolak.', 'error')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-100 shadow-sm transition-all flex items-center justify-center text-sm" title="Tolak">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    <form action="{{ route('loans.approve', $loan) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="handleLoanAction(this, 'Setujui', 'Persetujuan peminjaman aset ini akan diproses.', 'success')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-100 shadow-sm transition-all flex items-center justify-center text-sm" title="Setujui">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                @endif
                
                @if($loan->status == 'borrowed' && (auth()->check() && (auth()->user()->hasPermission('loan.manage') || auth()->id() == $loan->user_id)))
                    <form action="{{ route('loans.return', $loan) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="handleLoanAction(this, 'Kembalikan', 'Aset akan dikembalikan ke gudang.', 'question')" class="px-4 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-100 shadow-sm transition-all flex items-center justify-center text-xs font-bold gap-1" title="Kembalikan Aset">
                            <i class="fas fa-undo"></i> Kembalikan
                        </button>
                    </form>
                @endif
                
                @if($loan->status != 'pending' && $loan->status != 'borrowed')
                    <span class="text-[10px] font-bold text-gray-400 italic">Selesai</span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center bg-white rounded-[1.25rem] border border-indigo-50 shadow-sm">
            <h4 class="text-[14px] font-bold text-gray-400">Belum ada data peminjaman.</h4>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($loans->hasPages())
        <div class="bg-gray-50/50 p-4 border-t border-indigo-50/50">
            {{ $loans->links() }}
        </div>
    @endif
</div>
<script>
    function handleLoanAction(btn, title, text, icon) {
        confirmAction({
            title: title + '?',
            text: text,
            confirmText: 'Ya, ' + title,
            icon: icon
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endsection
