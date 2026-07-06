@extends('layouts.app')

@section('title', 'Perbaikan & Perawatan Aset')

@section('content')
<x-page-header 
    title="Perbaikan & Perawatan" 
    subtitle="Workflow perbaikan: Lapor → Perbaiki → QC Approve → Serah Terima." 
    emoji="🔧" 
/>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="glass-card rounded-2xl p-5">
        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Total</p>
        <p class="text-3xl font-black text-gray-800">{{ $repairs->total() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-amber-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-amber-500">Biaya Total</p>
        <p class="text-3xl font-black text-amber-600">Rp{{ number_format($totalCost, 0, ',', '.') }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-red-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-red-500">Menunggu</p>
        <p class="text-3xl font-black text-red-600">{{ $repairs->whereIn('status', ['reported', 'in_progress'])->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-blue-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">QC/Approve</p>
        <p class="text-3xl font-black text-blue-600">{{ $repairs->where('status', 'completed')->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-emerald-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Selesai</p>
        <p class="text-3xl font-black text-emerald-600">{{ $repairs->where('status', 'handed_over')->count() }}</p>
    </div>
</div>

<!-- Filter & Add -->
<div class="mb-5 flex flex-col sm:flex-row items-center justify-between gap-4">
    <form method="GET" action="{{ route('repairs.index') }}" class="flex flex-wrap gap-3">
        <select name="status" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 focus:ring-2 focus:ring-indigo-50 outline-none appearance-none cursor-pointer">
            <option value="">Semua Status</option>
            @foreach(App\Models\AssetRepair::$statusLabels as $val => $label)
            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="repair_type" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 focus:ring-2 focus:ring-indigo-50 outline-none appearance-none cursor-pointer">
            <option value="">Semua Tipe</option>
            <option value="maintenance" {{ request('repair_type') == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
            <option value="damage" {{ request('repair_type') == 'damage' ? 'selected' : '' }}>Kerusakan</option>
            <option value="warranty" {{ request('repair_type') == 'warranty' ? 'selected' : '' }}>Garansi</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-black hover:bg-indigo-100 transition-all border border-indigo-100">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
    </form>
    <button onclick="document.getElementById('new-repair-modal').classList.remove('hidden')" class="group inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl text-xs font-black shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all border border-white/20">
        <i class="fas fa-plus-circle group-hover:rotate-90 transition-transform"></i> Laporkan Kerusakan
    </button>
</div>

<!-- Table -->
<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-50">
                    <th class="p-4">Aset</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4 text-right">Biaya</th>
                    <th class="p-4">Progress</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($repairs as $repair)
                <tr class="hover:bg-indigo-50/20 transition-colors {{ $repair->status === 'handed_over' ? 'opacity-75' : '' }}">
                    <td class="p-4">
                        <div class="flex flex-col">
                            <a href="{{ route('assets.show', $repair->asset) }}" class="text-sm font-bold text-indigo-600 hover:underline">
                                {{ $repair->asset->asset_name ?? 'N/A' }}
                            </a>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $repair->asset->asset_code ?? '' }}</p>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded-lg text-[8px] font-black uppercase w-fit {{ $repair->repair_type === 'damage' ? 'bg-red-50 text-red-600' : ($repair->repair_type === 'warranty' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600') }}">
                                {{ $repair->repair_type_label }}
                            </span>
                            @if($repair->loan)
                            <span class="text-[9px] font-bold text-indigo-400 mt-1">
                                <i class="fas fa-link text-[7px]"></i> Loan #{{ $repair->loan->id_asset_loans }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="text-sm font-bold text-gray-700">{{ $repair->description }}</span>
                        @if($repair->notes)
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $repair->notes }}</p>
                        @endif
                        <p class="text-[9px] font-bold text-gray-400 mt-1">{{ $repair->repair_date->format('d/m/Y') }} · {{ $repair->vendor ?: '-' }}</p>
                        @if($repair->status === 'approved' || $repair->status === 'handed_over')
                            <p class="text-[9px] font-bold text-emerald-600 mt-1">
                                <i class="fas fa-check-circle"></i> QC: {{ $repair->approver?->name ?? '-' }}
                            </p>
                        @endif
                        @if($repair->status === 'handed_over')
                            <p class="text-[9px] font-bold text-indigo-500 mt-0.5">
                                <i class="fas fa-handshake"></i> Serah: {{ $repair->handoverOfficer?->name ?? '-' }}
                                @if($repair->new_condition_grade)
                                    · Grade Baru: {{ $repair->new_condition_grade }}
                                @endif
                            </p>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <span class="font-black text-gray-700 {{ $repair->cost > 0 ? 'text-rose-600' : 'text-gray-300' }}">
                            Rp{{ number_format($repair->cost, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="p-4 min-w-[140px]">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 {{ $repair->status_progress >= 100 ? 'bg-emerald-500' : ($repair->status_progress >= 75 ? 'bg-blue-500' : ($repair->status_progress >= 50 ? 'bg-indigo-500' : ($repair->status_progress >= 25 ? 'bg-amber-500' : 'bg-gray-300'))) }}" style="width: {{ $repair->status_progress }}%"></div>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 w-8 text-right">{{ $repair->status_progress }}%</span>
                        </div>
                        <div class="flex items-center gap-1 mt-1.5">
                            @foreach(App\Models\AssetRepair::$workflowFlow as $i => $step)
                                @php
                                    $currentIdx = array_search($repair->status, App\Models\AssetRepair::$workflowFlow);
                                    $isDone = $i <= $currentIdx;
                                @endphp
                                <div class="flex items-center gap-0.5">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $isDone ? 'bg-indigo-500' : 'bg-gray-200' }}"></div>
                                    @if($i < count(App\Models\AssetRepair::$workflowFlow) - 1)
                                        <div class="w-3 h-px {{ $i < $currentIdx ? 'bg-indigo-300' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                            <span class="text-[8px] font-bold text-gray-400 ml-1">{{ $repair->status_label }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        @php $s = $repair->status; @endphp
                        @if($s === 'reported')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[9px] font-black bg-red-50 text-red-600 border border-red-100 animate-pulse">Lapor</span>
                        @elseif($s === 'in_progress')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[9px] font-black bg-amber-50 text-amber-600 border border-amber-100">Proses</span>
                        @elseif($s === 'completed')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[9px] font-black bg-blue-50 text-blue-600 border border-blue-100">Selesai⚙️</span>
                        @elseif($s === 'approved')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[9px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100">✅ Disetujui</span>
                        @elseif($s === 'handed_over')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[9px] font-black bg-gray-50 text-gray-500 border border-gray-100">🤝 Selesai</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            @if($repair->status === 'reported')
                                <form action="{{ route('repairs.start', $repair) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-[9px] font-black border border-amber-100 transition-all" title="Mulai Perbaikan">
                                        🔧 Mulai
                                    </button>
                                </form>
                            @endif
                            @if($repair->status === 'in_progress')
                                <button onclick="openCompleteModal({{ $repair->id_asset_repairs }})" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-[9px] font-black border border-blue-100 transition-all">
                                    ✅ Selesai
                                </button>
                            @endif
                            @if($repair->status === 'completed' && auth()->user()->hasPermission('loan.manage'))
                                <button onclick="openApproveModal({{ $repair->id_asset_repairs }})" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-[9px] font-black border border-emerald-100 transition-all">
                                    ✅ Approve
                                </button>
                            @endif
                            @if($repair->status === 'approved')
                                <button onclick="openHandoverModal({{ $repair->id_asset_repairs }}, '{{ $repair->suggested_grade }}', {{ $repair->asset->id_assets }})" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-[9px] font-black border border-indigo-100 transition-all">
                                    🤝 Serah Terima
                                </button>
                            @endif
                            @if(in_array($repair->status, ['reported', 'handed_over']))
                            <form action="{{ route('repairs.destroy', $repair) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus?')" class="text-rose-300 hover:text-rose-500 transition-colors px-1">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-20 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tools text-2xl text-gray-200"></i>
                    </div>
                    <p class="text-sm font-black text-gray-400">Belum Ada Catatan Perbaikan</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $repairs->links() }}</div>
</div>

<!-- Modals -->
<div id="new-repair-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-jelly">
        <div class="p-8 border-b border-indigo-50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 relative">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-3"><i class="fas fa-tools text-indigo-600"></i> Laporkan Kerusakan Baru</h3>
            <button onclick="document.getElementById('new-repair-modal').classList.add('hidden')" class="absolute top-8 right-8 text-gray-400 hover:text-rose-500"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="{{ route('repairs.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Aset <span class="text-red-500">*</span></label>
                <select name="id_assets" required class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none appearance-none cursor-pointer">
                    <option value="">— Pilih Aset —</option>
                    @foreach($assets as $asset)
                    <option value="{{ $asset->id_assets }}">[{{ $asset->asset_code }}] {{ $asset->asset_name }} (Grade {{ $asset->condition }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Tanggal</label>
                    <input type="date" name="repair_date" required value="{{ date('Y-m-d') }}" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Tipe</label>
                    <select name="repair_type" required class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-600 focus:ring-2 focus:ring-indigo-100 outline-none appearance-none cursor-pointer">
                        <option value="damage">Perbaikan Kerusakan</option>
                        <option value="maintenance">Perawatan Rutin</option>
                        <option value="warranty">Klaim Garansi</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                <textarea name="description" required rows="2" placeholder="Jelaskan kerusakan..." class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Estimasi Biaya (Rp)</label>
                    <input type="number" name="cost" required value="0" min="0" step="1000" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Vendor/Bengkel</label>
                    <input type="text" name="vendor" placeholder="Nama vendor" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Catatan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan tambahan..." class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all resize-none"></textarea>
            </div>
            <p class="text-[9px] font-bold text-gray-400 -mt-2">Status awal: <strong>Dilaporkan</strong>. Admin akan memproses selanjutnya.</p>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black px-6 py-4 rounded-2xl shadow-xl hover:-translate-y-0.5 transition-all text-sm uppercase tracking-widest">
                <i class="fas fa-paper-plane mr-2"></i> Laporkan Kerusakan
            </button>
        </form>
    </div>
</div>

<!-- Complete Modal -->
<div id="complete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-jelly">
        <div class="p-6 border-b border-gray-50 relative">
            <h3 class="text-lg font-black text-gray-800">✅ Selesaikan Perbaikan</h3>
            <button onclick="document.getElementById('complete-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-rose-500"><i class="fas fa-times"></i></button>
        </div>
        <form id="complete-form" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Biaya Aktual (Rp)</label>
                <input type="number" name="cost" required min="0" step="1000" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-50 outline-none transition-all">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Catatan Penyelesaian</label>
                <textarea name="notes" rows="3" placeholder="Rincian perbaikan yang dilakukan..." class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-50 outline-none transition-all resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black px-5 py-3.5 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all text-xs uppercase tracking-widest">
                <i class="fas fa-check-circle mr-2"></i> Tandai Selesai
            </button>
        </form>
    </div>
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-jelly">
        <div class="p-6 border-b border-gray-50 relative">
            <h3 class="text-lg font-black text-gray-800">✅ Approve Hasil Perbaikan</h3>
            <button onclick="document.getElementById('approve-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-rose-500"><i class="fas fa-times"></i></button>
        </div>
        <form id="approve-form" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-xs font-bold text-amber-700">⚠️ Pastikan perbaikan sudah sesuai standar sebelum menyetujui.</p>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Grade Baru (Opsional)</label>
                <select name="new_condition_grade" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-50 outline-none appearance-none cursor-pointer">
                    <option value="">Auto (rekomendasi sistem)</option>
                    <option value="A">A — Baik Sekali</option>
                    <option value="B">B — Baik</option>
                    <option value="C">C — Cukup</option>
                    <option value="D">D — Rusak Ringan</option>
                    <option value="E">E — Rusak Berat</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-black px-5 py-3.5 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all text-xs uppercase tracking-widest">
                <i class="fas fa-stamp mr-2"></i> Setujui Hasil Perbaikan
            </button>
        </form>
    </div>
</div>

<!-- Handover Modal -->
<div id="handover-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-jelly">
        <div class="p-6 border-b border-gray-50 relative">
            <h3 class="text-lg font-black text-gray-800">🤝 Serah Terima Aset</h3>
            <p class="text-xs font-bold text-gray-400 mt-1">Aset akan dikembalikan ke inventory dengan grade baru</p>
            <button onclick="document.getElementById('handover-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-rose-500"><i class="fas fa-times"></i></button>
        </div>
        <form id="handover-form" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                <p class="text-xs font-bold text-indigo-700">
                    <i class="fas fa-info-circle mr-1"></i> Grade saat ini: <span id="handover-current-grade" class="font-black"></span>
                    → Rekomendasi: <span id="handover-suggested-grade" class="font-black"></span>
                </p>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Grade Baru <span class="text-red-500">*</span></label>
                <select name="new_condition_grade" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-50 outline-none appearance-none cursor-pointer">
                    <option value="A">A — Baik Sekali</option>
                    <option value="B">B — Baik</option>
                    <option value="C">C — Cukup (Rekomendasi Minimal)</option>
                    <option value="D">D — Rusak Ringan</option>
                    <option value="E">E — Rusak Berat</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Catatan Serah Terima</label>
                <textarea name="handover_notes" rows="3" placeholder="Kondisi after repair, kelengkapan..." class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-50 outline-none transition-all resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black px-5 py-3.5 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all text-xs uppercase tracking-widest">
                <i class="fas fa-handshake mr-2"></i> Konfirmasi Serah Terima
            </button>
        </form>
    </div>
</div>

<script>
function openCompleteModal(id) {
    const form = document.getElementById('complete-form');
    form.action = '/repairs/' + id + '/complete';
    document.getElementById('complete-modal').classList.remove('hidden');
}
function openApproveModal(id) {
    const form = document.getElementById('approve-form');
    form.action = '/repairs/' + id + '/approve';
    document.getElementById('approve-modal').classList.remove('hidden');
}
function openHandoverModal(id, suggestedGrade, assetId) {
    const form = document.getElementById('handover-form');
    form.action = '/repairs/' + id + '/handover';
    document.getElementById('handover-current-grade').textContent = '?';
    document.getElementById('handover-suggested-grade').textContent = suggestedGrade;
    // Fetch current grade via asset data attribute
    document.getElementById('handover-modal').classList.remove('hidden');
}
// Close modals on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['complete-modal', 'approve-modal', 'handover-modal', 'new-repair-modal'].forEach(id => {
            document.getElementById(id)?.classList.add('hidden');
        });
    }
});
</script>
@endsection
