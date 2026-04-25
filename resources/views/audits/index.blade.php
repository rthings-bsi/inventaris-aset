@extends('layouts.app')

@section('title', 'Audit Data Aset - Inventaris')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }
    @keyframes gradientBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(20px, -30px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
    }
    .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }
</style>

<x-page-header 
    title="Data Audit Aset" 
    subtitle="Monitoring kepatuhan dan validasi fisik aset secara berkala." 
    emoji="📊" 
/>

<!-- Action Row -->
<div class="mb-5 relative z-30 flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
    <!-- Search Section -->
    <div class="w-full sm:w-80">
        <form method="GET" action="{{ route('audits.index') }}" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-300 group-focus-within:text-indigo-400 transition-colors"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari sesi audit..." 
                   class="w-full pl-11 pr-4 py-3 bg-white/60 backdrop-blur-md border border-white hover:border-indigo-100 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all shadow-sm">
        </form>
    </div>

    <!-- Add Audit Action Button -->
    <button onclick="document.getElementById('new-audit-modal').classList.remove('hidden')" class="w-full sm:w-auto group relative inline-flex items-center justify-center px-8 py-3.5 text-[14px] font-black text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-[0_10px_25px_rgba(79,70,229,0.35)] hover:shadow-[0_15px_35px_rgba(79,70,229,0.45)] hover:-translate-y-1 border border-white/20 whitespace-nowrap">
        <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-500 text-lg"></i>
        Mulai Audit Baru
    </button>
</div>

<!-- Main Content Area -->
<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm mb-8 relative z-20 overflow-hidden min-h-[500px]">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-sm font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-layer-group"></i> Riwayat Sesi Audit
        </h3>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-500 font-black">
                    <th class="p-4 rounded-tl-2xl">Informasi Sesi</th>
                    <th class="p-4">Auditor</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right rounded-tr-2xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($audits as $audit)
                <tr class="hover:bg-indigo-50/30 transition-colors group">
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                                <i class="fas fa-barcode text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-800 text-[15px] group-hover:text-indigo-700 transition-colors">{{ $audit->title }}</h4>
                                <p class="text-[11px] font-bold text-gray-400 mt-0.5 line-clamp-1 truncate max-w-[200px]">{{ $audit->description ?: 'Tanpa deskripsi' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-[10px] font-black border border-white shadow-sm">
                                {{ strtoupper(substr($audit->creator->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-bold text-gray-600">{{ $audit->creator->name }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-700">{{ $audit->audit_date->format('d M Y') }}</span>
                            <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">{{ $audit->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        @if($audit->status == 'open')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Open
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 opacity-60">
                                <i class="fas fa-lock text-[8px]"></i> Locked
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2 transition-all duration-300">
                            @if($audit->status == 'open')
                                <a href="{{ route('audits.show', $audit) }}" class="w-9 h-9 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-100 flex items-center justify-center hover:scale-110 hover:-rotate-3 transition-all" title="Lanjut Scanning">
                                    <i class="fas fa-bolt text-sm"></i>
                                </a>
                            @else
                                <a href="{{ route('audits.report', $audit) }}" class="w-9 h-9 bg-white border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-50 hover:scale-110 transition-all shadow-sm" title="Lihat Laporan">
                                    <i class="fas fa-file-alt text-sm"></i>
                                </a>
                            @endif
                            <form action="{{ route('audits.destroy', $audit) }}" method="POST" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="button" onclick="handleDelete(this)" class="w-9 h-9 bg-white border border-rose-100 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-50 hover:text-rose-700 hover:scale-110 transition-all shadow-sm" title="Hapus Sesi">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-24 text-center">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                                <i class="fas fa-search text-3xl"></i>
                            </div>
                            <h4 class="text-lg font-black text-gray-800 mb-1">Belum Ada Sesi Audit</h4>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Silakan buat sesi audit baru untuk mulai memindai aset.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4 mt-4">
            @forelse($audits as $audit)
            <div class="bg-white rounded-[1.5rem] p-5 border border-indigo-50 shadow-sm relative group overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 -z-10"></div>
                
                <div class="flex gap-4 items-start pb-4 border-b border-gray-100">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                        <i class="fas fa-barcode text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-gray-800 text-[15px]">{{ $audit->title }}</h4>
                        <p class="text-[11px] font-bold text-gray-400 mt-0.5 line-clamp-2">{{ $audit->description ?: 'Tanpa deskripsi' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 py-4 border-b border-gray-100">
                    <div>
                        <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest block mb-1">Auditor</span>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-[9px] font-black border border-white shadow-sm">
                                {{ strtoupper(substr($audit->creator->name, 0, 2)) }}
                            </div>
                            <span class="text-xs font-bold text-gray-600 truncate">{{ $audit->creator->name }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest block mb-1">Tanggal & Waktu</span>
                        <span class="block text-xs font-black text-gray-700">{{ $audit->audit_date->format('d M Y') }}</span>
                        <span class="block text-[10px] font-bold text-gray-400">{{ $audit->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <div>
                        @if($audit->status == 'open')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Open
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 opacity-60">
                                <i class="fas fa-lock text-[8px]"></i> Locked
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($audit->status == 'open')
                            <a href="{{ route('audits.show', $audit) }}" class="w-10 h-10 bg-indigo-600 text-white rounded-xl shadow-md flex items-center justify-center" title="Lanjut Scanning">
                                <i class="fas fa-bolt"></i>
                            </a>
                        @else
                            <a href="{{ route('audits.report', $audit) }}" class="w-10 h-10 bg-white border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center" title="Lihat Laporan">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        @endif
                        <form action="{{ route('audits.destroy', $audit) }}" method="POST" class="inline delete-form">
                            @csrf @method('DELETE')
                            <button type="button" onclick="handleDelete(this)" class="w-10 h-10 bg-white border border-rose-100 text-rose-500 rounded-xl flex items-center justify-center" title="Hapus Sesi">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 text-center bg-indigo-50/30 rounded-[1.5rem] border border-indigo-50 border-dashed">
                <i class="fas fa-search text-3xl text-indigo-200 mb-3"></i>
                <h4 class="text-sm font-black text-gray-600">Belum Ada Sesi Audit</h4>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $audits->links() }}
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="new-audit-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-indigo-50 animate-jelly">
        <div class="p-8 border-b border-indigo-50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 relative">
            <h3 class="text-2xl font-black text-gray-800 flex items-center gap-3">
                <i class="fas fa-clipboard-check text-indigo-600"></i> Sesi Audit Baru
            </h3>
            <button onclick="document.getElementById('new-audit-modal').classList.add('hidden')" class="absolute top-8 right-8 text-gray-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('audits.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">Judul Sesi</label>
                <input type="text" name="title" required placeholder="E.g. Audit Triwulan I 2024" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">Tanggal Pelaksanaan</label>
                    <input type="date" name="audit_date" required value="{{ date('Y-m-d') }}" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" rows="3" placeholder="Tambahkan catatan audit di sini..." class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black px-6 py-4 rounded-2xl shadow-xl shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-1 active:scale-95 transition-all text-sm uppercase tracking-widest">
                    Buat Sesi & Mulai
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleDelete(btn) {
        confirmAction({
            title: 'Hapus Sesi Audit?',
            text: 'Seluruh data pemindaian pada sesi ini akan dihapus secara permanen.',
            confirmText: 'Ya, Hapus Sesi',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endsection
