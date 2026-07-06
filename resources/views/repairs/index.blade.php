@extends('layouts.app')

@section('title', 'Perbaikan & Perawatan Aset')

@section('content')
<x-page-header 
    title="Perbaikan & Perawatan" 
    subtitle="Catat riwayat perbaikan, biaya perawatan, dan klaim garansi aset." 
    emoji="🔧" 
/>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="glass-card rounded-2xl p-5">
        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Total Catatan</p>
        <p class="text-3xl font-black text-gray-800">{{ $repairs->total() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-amber-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-amber-500">Biaya Total</p>
        <p class="text-3xl font-black text-amber-600">Rp{{ number_format($totalCost, 0, ',', '.') }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-emerald-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Perawatan</p>
        <p class="text-3xl font-black text-emerald-600">{{ $repairs->where('repair_type', 'maintenance')->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 border-l-4 border-red-400">
        <p class="text-[9px] font-black uppercase tracking-widest text-red-500">Kerusakan</p>
        <p class="text-3xl font-black text-red-600">{{ $repairs->where('repair_type', 'damage')->count() }}</p>
    </div>
</div>

<!-- Filter & Add -->
<div class="mb-5 flex flex-col sm:flex-row items-center justify-between gap-4">
    <form method="GET" action="{{ route('repairs.index') }}" class="flex flex-wrap gap-3">
        <select name="repair_type" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 focus:ring-2 focus:ring-indigo-50 outline-none appearance-none cursor-pointer">
            <option value="">Semua Tipe</option>
            <option value="maintenance" {{ request('repair_type') == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
            <option value="damage" {{ request('repair_type') == 'damage' ? 'selected' : '' }}>Kerusakan</option>
            <option value="warranty" {{ request('repair_type') == 'warranty' ? 'selected' : '' }}>Garansi</option>
        </select>
        <select name="asset_id" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 focus:ring-2 focus:ring-indigo-50 outline-none appearance-none cursor-pointer">
            <option value="">Semua Aset</option>
            @foreach($assets as $asset)
            <option value="{{ $asset->id_assets }}" {{ request('asset_id') == $asset->id_assets ? 'selected' : '' }}>[{{ $asset->asset_code }}] {{ $asset->asset_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-black hover:bg-indigo-100 transition-all border border-indigo-100">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
    </form>
    <button onclick="document.getElementById('new-repair-modal').classList.remove('hidden')" class="group inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl text-xs font-black shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all border border-white/20">
        <i class="fas fa-plus-circle group-hover:rotate-90 transition-transform"></i> Catat Perbaikan
    </button>
</div>

<!-- Table -->
<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-50">
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Aset</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4 text-right">Biaya</th>
                    <th class="p-4">Vendor</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($repairs as $repair)
                <tr class="hover:bg-indigo-50/20 transition-colors">
                    <td class="p-4 text-sm font-black text-gray-700">{{ $repair->repair_date->format('d/m/Y') }}</td>
                    <td class="p-4">
                        <a href="{{ route('assets.show', $repair->asset) }}" class="text-sm font-bold text-indigo-600 hover:underline">
                            {{ $repair->asset->asset_name ?? 'N/A' }}
                        </a>
                        <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $repair->asset->asset_code ?? '' }}</p>
                    </td>
                    <td class="p-4">
                        <span class="text-sm font-bold text-gray-700">{{ $repair->description }}</span>
                        @if($repair->notes)
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $repair->notes }}</p>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase {{ $repair->repair_type === 'damage' ? 'bg-red-50 text-red-600' : ($repair->repair_type === 'warranty' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600') }}">
                            {{ $repair->repair_type_label }}
                        </span>
                    </td>
                    <td class="p-4 text-right font-black text-gray-700">Rp{{ number_format($repair->cost, 0, ',', '.') }}</td>
                    <td class="p-4 text-sm font-bold text-gray-500">{{ $repair->vendor ?: '-' }}</td>
                    <td class="p-4 text-center">
                        @if($repair->status === 'completed')
                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-100">Selesai</span>
                        @elseif($repair->status === 'in_progress')
                            <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-100 animate-pulse">Proses</span>
                        @else
                            <span class="text-[10px] font-black text-gray-400 bg-gray-50 px-2.5 py-1 rounded-xl border border-gray-100">Pending</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('repairs.destroy', $repair) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus data perbaikan ini?')" class="text-rose-400 hover:text-rose-600 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-20 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tools text-2xl text-gray-200"></i>
                        </div>
                        <p class="text-sm font-black text-gray-400">Belum Ada Catatan Perbaikan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $repairs->links() }}</div>
</div>

<!-- Create Modal -->
<div id="new-repair-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-jelly">
        <div class="p-8 border-b border-indigo-50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 relative">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-3"><i class="fas fa-tools text-indigo-600"></i> Catat Perbaikan Baru</h3>
            <button onclick="document.getElementById('new-repair-modal').classList.add('hidden')" class="absolute top-8 right-8 text-gray-400 hover:text-rose-500 transition-colors"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="{{ route('repairs.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Aset</label>
                <select name="id_assets" required class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">— Pilih Aset —</option>
                    @foreach($assets as $asset)
                    <option value="{{ $asset->id_assets }}">[{{ $asset->asset_code }}] {{ $asset->asset_name }}</option>
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
                    <select name="repair_type" required class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all appearance-none cursor-pointer">
                        <option value="maintenance">Perawatan Rutin</option>
                        <option value="damage">Perbaikan Kerusakan</option>
                        <option value="warranty">Klaim Garansi</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Deskripsi</label>
                <textarea name="description" required rows="2" placeholder="Jelaskan kerusakan/perbaikan..." class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Biaya (Rp)</label>
                    <input type="number" name="cost" required value="0" min="0" step="1000" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                </div>
                <div>
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Vendor</label>
                    <input type="text" name="vendor" placeholder="Nama vendor/bengkel" class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Status</label>
                <select name="status" required class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all appearance-none cursor-pointer">
                    <option value="pending">Pending</option>
                    <option value="in_progress">Dalam Proses</option>
                    <option value="completed" selected>Selesai</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Catatan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan tambahan..." class="w-full px-5 py-3.5 bg-gray-50 border border-indigo-100 rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"></textarea>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black px-6 py-4 rounded-2xl shadow-xl hover:-translate-y-0.5 transition-all text-sm uppercase tracking-widest">Simpan Data Perbaikan</button>
        </form>
    </div>
</div>
@endsection
