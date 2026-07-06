@extends('layouts.app')

@section('title', 'Buat Pengajuan Peminjaman - Inventaris')

@section('content')
<!-- Page Header -->
<x-page-header 
    title="Buat Pengajuan Peminjaman" 
    subtitle="Pilih aset yang tersedia dari gudang dan isi form pengajuan operasional Anda." 
    emoji="✨"
>
    <x-slot name="actions">
        <a href="{{ route('loans.index') }}" class="inline-flex items-center text-xs font-black tracking-widest uppercase text-indigo-400 hover:text-indigo-600 transition-colors group bg-white/60 px-4 py-2 rounded-xl border border-indigo-50 shadow-sm">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>
    </x-slot>
</x-page-header>

<!-- Form Card -->
<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-sm mb-6 relative z-20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-sm font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-edit"></i> Form Pengajuan Aset
        </h2>
    </div>

    <form action="{{ route('loans.store') }}" method="POST" class="p-5 sm:p-8">
        @csrf
        <div class="space-y-6 max-w-3xl">
            <!-- Asset Select -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-3 uppercase flex items-center gap-2">
                    <i class="fas fa-boxes text-indigo-300"></i> Pilih Aset Operasional <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="id_assets" required class="w-full pl-5 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100/50 text-sm font-bold text-gray-700 outline-none transition-all appearance-none cursor-pointer shadow-sm hover:border-indigo-300"
                            onchange="updateAssetInfo(this)">
                        <option value="">Pilih Aset yang Tersedia...</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id_assets }}" 
                                data-condition="{{ $asset->condition }}"
                                data-category="{{ $asset->category->category_name ?? '-' }}"
                                data-location="{{ $asset->location->location_name ?? '-' }}"
                                data-book-value="{{ $asset->book_value }}"
                                data-depreciation="{{ $asset->annual_depreciation }}"
                                data-depr-status="{{ $asset->depreciation_status }}">
                                {{ $asset->asset_code }} - {{ $asset->asset_name }} [{{ $asset->condition }}]
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <!-- Selected Asset Info Panel -->
                <div id="asset-info-panel" class="hidden mt-4 p-5 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100/50 transition-all animate-fade-in-up">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Grade Kondisi</p>
                            <span id="info-grade" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black"></span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Kategori</p>
                            <p id="info-category" class="text-sm font-bold text-gray-700">—</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Nilai Buku</p>
                            <p id="info-book-value" class="text-sm font-bold text-gray-700">—</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Depresiasi/Tahun</p>
                            <p id="info-depreciation" class="text-sm font-bold text-gray-700">—</p>
                        </div>
                    </div>
                    <div id="info-warning" class="hidden mt-3 p-3 bg-amber-50 border border-amber-100 rounded-xl text-xs font-bold text-amber-700 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="info-warning-text"></span>
                    </div>
                </div>

                @error('id_assets')
                    <p class="mt-2 text-xs font-bold text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-3 uppercase flex items-center gap-2">
                    <i class="fas fa-align-left text-indigo-300"></i> Keperluan Peminjaman (Opsional)
                </label>
                <textarea name="notes" rows="4" placeholder="Jelaskan kebutuhan operasional Anda secara singkat..." class="w-full p-5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100/50 text-sm font-semibold text-gray-700 outline-none transition-all placeholder:text-gray-400 shadow-sm hover:border-indigo-300 resize-none"></textarea>
                @error('notes')
                    <p class="mt-2 text-xs font-bold text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-indigo-50/50 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                <button type="reset" class="px-6 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-gray-50 hover:text-gray-800 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-redo-alt"></i> Reset Form
                </button>
                <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black text-sm uppercase tracking-wider shadow-[0_4px_15px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group">
                    Kirim Pengajuan <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function updateAssetInfo(select) {
    const panel = document.getElementById('asset-info-panel');
    const option = select.options[select.selectedIndex];
    
    if (!option || !option.value) {
        panel.classList.add('hidden');
        return;
    }

    panel.classList.remove('hidden');

    // Grade
    const grade = option.dataset.condition || '?';
    const gradeColors = {
        'A': 'bg-emerald-100 text-emerald-700 border border-emerald-200',
        'B': 'bg-blue-100 text-blue-700 border border-blue-200',
        'C': 'bg-amber-100 text-amber-700 border border-amber-200',
        'D': 'bg-orange-100 text-orange-700 border border-orange-200',
        'E': 'bg-red-100 text-red-700 border border-red-200'
    };
    const gradeEl = document.getElementById('info-grade');
    gradeEl.textContent = 'Grade ' + grade;
    gradeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black ' + (gradeColors[grade] || 'bg-gray-100 text-gray-600');

    // Category
    document.getElementById('info-category').textContent = option.dataset.category || '-';

    // Book Value
    const bv = parseFloat(option.dataset.bookValue) || 0;
    document.getElementById('info-book-value').textContent = 'Rp' + bv.toLocaleString('id-ID');

    // Depreciation
    const depr = parseFloat(option.dataset.depreciation) || 0;
    document.getElementById('info-depreciation').textContent = 'Rp' + depr.toLocaleString('id-ID') + '/thn';

    // Warning for C/D/E grades
    const warnEl = document.getElementById('info-warning');
    const warnText = document.getElementById('info-warning-text');
    if (grade === 'C') {
        warnEl.classList.remove('hidden');
        warnText.textContent = 'Aset grade C memerlukan persetujuan atasan untuk peminjaman.';
    } else if (grade === 'D' || grade === 'E') {
        warnEl.classList.remove('hidden');
        warnText.textContent = 'Aset sedang dalam kondisi rusak. Hubungi admin untuk informasi lebih lanjut.';
    } else {
        warnEl.classList.add('hidden');
    }
}
</script>
@endsection
