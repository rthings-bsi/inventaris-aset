@extends('layouts.app')

@section('title', 'Buat Pengajuan Peminjaman - Inventaris')

@section('content')
<x-page-header 
    title="Buat Pengajuan Peminjaman" 
    subtitle="Pilih aset berdasarkan kategori, lihat informasi lengkap sebelum mengajukan." 
    emoji="✨"
>
    <x-slot name="actions">
        <a href="{{ route('loans.index') }}" class="inline-flex items-center text-xs font-black tracking-widest uppercase text-indigo-400 hover:text-indigo-600 transition-colors group bg-white/60 px-4 py-2 rounded-xl border border-indigo-50 shadow-sm">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>
    </x-slot>
</x-page-header>

<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-sm mb-6 relative z-20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
    <form action="{{ route('loans.store') }}" method="POST">
        @csrf
        <div class="space-y-8 max-w-4xl">
            <!-- Category Filter Pills -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-4 uppercase flex items-center gap-2">
                    <i class="fas fa-layer-group text-indigo-300"></i> Pilih Kategori Aset
                </label>
                <div class="flex flex-wrap gap-2" id="category-filters">
                    <button type="button" class="cat-filter active px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 border-2 bg-indigo-600 text-white border-indigo-600 shadow-md" data-category="all">
                        <i class="fas fa-th-large mr-1.5"></i> Semua Aset
                        <span class="ml-1.5 px-2 py-0.5 bg-white/20 rounded-lg text-[10px]">{{ $assets->count() }}</span>
                    </button>
                    @foreach($categories as $cat)
                    @php $catCount = $groupedAssets->get($cat->category_name, collect())->count(); @endphp
                    @if($catCount > 0)
                    <button type="button" class="cat-filter px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 border-2 border-gray-100 bg-white text-gray-600 hover:border-indigo-200 hover:text-indigo-600 shadow-sm" data-category="{{ $cat->category_name }}">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 inline-flex items-center justify-center text-[10px] font-black mr-1.5">{{ $cat->code_prefix }}</span>
                        {{ $cat->category_name }}
                        <span class="ml-1.5 px-2 py-0.5 bg-gray-100 rounded-lg text-[10px]">{{ $catCount }}</span>
                    </button>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Asset Cards Grid -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-4 uppercase flex items-center gap-2">
                    <i class="fas fa-boxes text-indigo-300"></i> Pilih Aset <span class="text-red-500">*</span>
                </label>

                <div id="assets-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($groupedAssets as $catName => $catAssets)
                        @foreach($catAssets as $asset)
                        <div class="asset-card rounded-2xl border-2 border-gray-50 bg-white p-5 hover:shadow-md hover:border-indigo-200 transition-all duration-200 cursor-pointer group relative {{ $loop->first ? '' : '' }}" 
                             data-category="{{ $catName }}"
                             data-asset-id="{{ $asset->id_assets }}"
                             onclick="selectAsset(this, {{ $asset->id_assets }})">
                            <input type="radio" name="id_assets" value="{{ $asset->id_assets }}" class="hidden peer" required>
                            
                            <!-- Card Top -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center text-indigo-500 border border-indigo-50 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-microchip text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800 leading-tight">{{ $asset->asset_name }}</p>
                                        <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mt-0.5">{{ $asset->asset_code }}</p>
                                    </div>
                                </div>
                                @php $g = $asset->condition; @endphp
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[10px] font-black flex-shrink-0 {{ $g === 'A' ? 'bg-emerald-100 text-emerald-700' : ($g === 'B' ? 'bg-blue-100 text-blue-700' : ($g === 'C' ? 'bg-amber-100 text-amber-700' : ($g === 'D' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700'))) }}">
                                    {{ $g }}
                                </span>
                            </div>

                            <!-- Card Meta -->
                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <i class="fas fa-folder-open text-gray-300 text-[8px]"></i>
                                    <span class="font-bold">{{ $catName }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <i class="fas fa-map-pin text-gray-300 text-[8px]"></i>
                                    <span class="font-bold">{{ $asset->location->location_name ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- Depreciation bar -->
                            @if($asset->annual_depreciation > 0)
                            <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between">
                                <span class="text-[9px] font-bold text-gray-400">Depr: Rp{{ number_format($asset->annual_depreciation, 0, ',', '.') }}/thn</span>
                                <span class="text-[9px] font-bold text-gray-400">Nilai Buku: Rp{{ number_format($asset->book_value, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <!-- Check indicator -->
                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-100 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="absolute inset-0 rounded-2xl border-2 border-indigo-500 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                        @endforeach
                    @empty
                    <div class="col-span-3 py-16 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-3xl text-gray-200"></i>
                        </div>
                        <h4 class="text-lg font-black text-gray-400">Tidak Ada Aset Tersedia</h4>
                        <p class="text-[11px] font-bold text-gray-300 mt-1">Semua aset sedang dipinjam atau dalam perawatan</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Selected Asset Info Panel -->
            <div id="selected-asset-panel" class="hidden p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100/50 transition-all animate-fade-in-up">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-800">Aset Terpilih</p>
                        <p id="selected-name" class="text-sm font-bold text-gray-600">—</p>
                    </div>
                    <button type="button" onclick="clearSelection()" class="ml-auto text-xs font-black text-rose-500 hover:text-rose-700 bg-white px-4 py-2 rounded-xl border border-rose-100 hover:bg-rose-50 transition-all">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Kode Aset</p>
                        <p id="selected-code" class="text-sm font-black text-indigo-600 tracking-wider">—</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Grade</p>
                        <span id="selected-grade" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black"></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Kategori</p>
                        <p id="selected-category" class="text-sm font-bold text-gray-700">—</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Nilai Buku</p>
                        <p id="selected-book-value" class="text-sm font-bold text-gray-700">—</p>
                    </div>
                </div>
                <div id="selected-warning" class="hidden mt-3 p-3 bg-amber-50 border border-amber-100 rounded-xl text-xs font-bold text-amber-700 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="selected-warning-text"></span>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-3 uppercase flex items-center gap-2">
                    <i class="fas fa-align-left text-indigo-300"></i> Keperluan Peminjaman (Opsional)
                </label>
                <textarea name="notes" rows="3" placeholder="Jelaskan kebutuhan operasional Anda..." class="w-full p-5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100/50 text-sm font-semibold text-gray-700 outline-none transition-all placeholder:text-gray-400 shadow-sm hover:border-indigo-300 resize-none"></textarea>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-indigo-50/50 flex justify-end">
                <button type="submit" id="submit-btn" disabled class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-black text-sm uppercase tracking-wider shadow-[0_4px_15px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-1 transition-all flex items-center gap-3 opacity-50 cursor-not-allowed">
                    <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Category filter
document.querySelectorAll('.cat-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update active pill
        document.querySelectorAll('.cat-filter').forEach(b => {
            b.className = b.className.replace(/active.*?(?= |$)/, '').trim();
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');
            b.classList.add('bg-white', 'text-gray-600', 'border-gray-100', 'shadow-sm');
        });
        this.classList.remove('bg-white', 'text-gray-600', 'border-gray-100', 'shadow-sm');
        this.classList.add('active', 'bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');

        // Filter cards
        const category = this.dataset.category;
        document.querySelectorAll('.asset-card').forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        // Update visible count
        const visible = document.querySelectorAll('.asset-card:not(.hidden)').length;
    });
});

// Asset selection
function selectAsset(card, assetId) {
    // Unselect all
    document.querySelectorAll('.asset-card').forEach(c => {
        c.classList.remove('border-indigo-500', 'bg-indigo-50/20');
        c.classList.add('border-gray-50');
    });
    // Select this one
    card.classList.remove('border-gray-50');
    card.classList.add('border-indigo-500', 'bg-indigo-50/20');
    card.querySelector('input[type="radio"]').checked = true;

    // Enable submit
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = false;
    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

    // Show info panel
    const panel = document.getElementById('selected-asset-panel');
    panel.classList.remove('hidden');

    // Get data from hidden attributes in the card's radio
    const allAssets = @json($assetsJson);

    const data = allAssets[assetId];
    if (!data) return;

    document.getElementById('selected-name').textContent = data.name;
    document.getElementById('selected-code').textContent = data.code;

    // Grade
    const grade = data.condition;
    const gradeColors = {
        'A': 'bg-emerald-100 text-emerald-700 border border-emerald-200',
        'B': 'bg-blue-100 text-blue-700 border border-blue-200',
        'C': 'bg-amber-100 text-amber-700 border border-amber-200',
        'D': 'bg-orange-100 text-orange-700 border border-orange-200',
        'E': 'bg-red-100 text-red-700 border border-red-200'
    };
    const gradeEl = document.getElementById('selected-grade');
    gradeEl.textContent = 'Grade ' + grade;
    gradeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black ' + (gradeColors[grade] || 'bg-gray-100 text-gray-600');

    document.getElementById('selected-category').textContent = data.category;
    document.getElementById('selected-book-value').textContent = 'Rp' + (data.book_value || 0).toLocaleString('id-ID');

    // Warning
    const warnEl = document.getElementById('selected-warning');
    const warnText = document.getElementById('selected-warning-text');
    if (grade === 'C') {
        warnEl.classList.remove('hidden');
        warnText.textContent = 'Aset grade C memerlukan persetujuan atasan untuk peminjaman.';
    } else if (grade === 'D' || grade === 'E') {
        warnEl.classList.remove('hidden');
        warnText.textContent = 'Aset sedang dalam kondisi rusak. Hubungi admin.';
    } else {
        warnEl.classList.add('hidden');
    }
}

function clearSelection() {
    document.querySelectorAll('.asset-card').forEach(c => {
        c.classList.remove('border-indigo-500', 'bg-indigo-50/20');
        c.classList.add('border-gray-50');
        c.querySelector('input[type="radio"]').checked = false;
    });
    document.getElementById('selected-asset-panel').classList.add('hidden');
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
}
</script>
@endsection
