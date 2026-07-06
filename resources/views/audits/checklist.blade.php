@extends('layouts.app')

@section('title', 'Checklist Audit - ' . $audit->title)

@section('content')
<x-page-header 
    title="Checklist: {{ $audit->title }}" 
    subtitle="Ceklis aset satu per satu tanpa perlu scanner — kondisi otomatis terhitung dari kriteria." 
    emoji="✅"
>
    <x-slot name="actions">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('audits.show', $audit) }}" class="inline-flex items-center gap-2.5 rounded-2xl bg-white border-2 border-indigo-100 px-5 py-2.5 text-xs font-black text-indigo-600 shadow-sm hover:bg-indigo-50 transition-all">
                <i class="fas fa-qrcode"></i> Mode Scanner
            </a>
            <a href="{{ route('audits.index') }}" class="inline-flex items-center gap-2.5 rounded-2xl bg-white border-2 border-gray-100 px-5 py-2.5 text-xs font-black text-gray-500 shadow-sm hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>
</x-page-header>

<!-- Stats Bar -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="glass-card rounded-2xl p-5 text-center">
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Aset</p>
        <p class="text-3xl font-black text-gray-800 mt-1">{{ $items->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 text-center border-l-4 border-amber-400">
        <p class="text-[10px] font-black uppercase tracking-widest text-amber-500">Pending</p>
        <p class="text-3xl font-black text-amber-600 mt-1">{{ $items->where('checklist_status', 'pending')->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 text-center border-l-4 border-emerald-400">
        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Terceklis</p>
        <p class="text-3xl font-black text-emerald-600 mt-1">{{ $items->whereIn('checklist_status', ['checked', 'scanned', 'verified'])->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 text-center border-l-4 border-indigo-400">
        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500">Terverifikasi</p>
        <p class="text-3xl font-black text-indigo-600 mt-1">{{ $items->where('checklist_status', 'verified')->count() }}</p>
    </div>
    <div class="glass-card rounded-2xl p-5 text-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
        <p class="text-[10px] font-black uppercase tracking-widest text-white/70">Progress</p>
        <p class="text-3xl font-black mt-1">
            {{ $items->count() > 0 ? round(($items->whereIn('checklist_status', ['checked', 'scanned', 'verified'])->count() / $items->count()) * 100) : 0 }}%
        </p>
    </div>
</div>

<!-- Checklist Form -->
<form id="checklist-form" method="POST" action="{{ route('audits.checklist.update', $audit) }}">
    @csrf
    <div class="space-y-8">
        @forelse($groupedAssets as $categoryName => $groupItems)
        <div class="bg-white/80 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between mb-5 px-1">
                <h3 class="font-black text-sm text-indigo-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-folder-open text-indigo-300"></i>
                    {{ $categoryName }}
                    <span class="text-[10px] font-bold bg-indigo-50 text-indigo-500 px-2.5 py-1 rounded-full">{{ $groupItems->count() }} aset</span>
                </h3>
                <button type="button" class="select-all-cat text-[10px] font-black uppercase tracking-widest text-indigo-400 hover:text-indigo-700 transition-colors" data-category="{{ $categoryName }}">
                    <i class="fas fa-check-double mr-1"></i> Pilih Semua
                </button>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3">
                @foreach($groupItems as $item)
                <div class="border border-gray-50 rounded-2xl p-4 hover:shadow-md transition-all {{ $item->checklist_status !== 'pending' ? 'bg-emerald-50/30 border-emerald-100' : '' }}">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="items[{{ $loop->parent->first ? '' : $item->id_asset_audit_items }}][checked]" 
                               value="1" class="asset-checkbox mt-1 h-5 w-5 rounded-lg border-2 border-gray-200 text-indigo-600 focus:ring-indigo-500 transition-all"
                               {{ $item->checklist_status !== 'pending' ? 'checked' : '' }}
                               data-item-id="{{ $item->id_asset_audit_items }}">
                        <input type="hidden" name="items[{{ $item->id_asset_audit_items }}][id]" value="{{ $item->id_asset_audit_items }}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-800 text-sm">{{ $item->asset->asset_name ?? 'Aset Tidak Terdaftar' }}</span>
                                @if($item->condition_grade)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-[10px] font-black {{ $item->condition_grade === 'A' ? 'bg-emerald-100 text-emerald-700' : ($item->condition_grade === 'B' ? 'bg-blue-100 text-blue-700' : ($item->condition_grade === 'C' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')) }}">
                                        {{ $item->condition_grade }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $item->asset->asset_code ?? $item->scanned_code }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] font-bold text-gray-300">{{ $item->asset->location->location_name ?? '-' }}</span>
                                @if($item->checklist_status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-500 rounded-lg text-[8px] font-black uppercase">Terverifikasi</span>
                                @elseif($item->checklist_status !== 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-500 rounded-lg text-[8px] font-black uppercase">Selesai</span>
                                @endif
                            </div>
                        </div>
                    </label>
                    @if($item->criteria_data)
                    <div class="mt-2 ml-8">
                        <span class="text-[9px] font-bold text-gray-400">Skor: {{ $item->condition_score }}% — Grade {{ $item->condition_grade_badge ?? $item->condition_grade }}</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-50">
                            <th class="p-3 w-10">
                                <input type="checkbox" class="category-select-all h-4 w-4 rounded border-2 border-gray-200 text-indigo-600 focus:ring-indigo-500" data-category="{{ $categoryName }}">
                            </th>
                            <th class="p-3">Kode Aset</th>
                            <th class="p-3">Nama Aset</th>
                            <th class="p-3">Lokasi</th>
                            <th class="p-3 text-center">Grade</th>
                            <th class="p-3 text-center">Skor</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($groupItems as $item)
                        <tr class="hover:bg-indigo-50/20 transition-colors {{ $item->checklist_status !== 'pending' ? 'bg-emerald-50/10' : '' }}">
                            <td class="p-3">
                                <input type="checkbox" name="items[{{ $item->id_asset_audit_items }}][checked]" 
                                       value="1" class="asset-checkbox h-4 w-4 rounded border-2 border-gray-200 text-indigo-600 focus:ring-indigo-500 transition-all"
                                       {{ $item->checklist_status !== 'pending' ? 'checked' : '' }}
                                       data-item-id="{{ $item->id_asset_audit_items }}">
                                <input type="hidden" name="items[{{ $item->id_asset_audit_items }}][id]" value="{{ $item->id_asset_audit_items }}">
                            </td>
                            <td class="p-3">
                                <span class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">{{ $item->asset->asset_code ?? $item->scanned_code }}</span>
                            </td>
                            <td class="p-3">
                                <span class="text-sm font-bold text-gray-800">{{ $item->asset->asset_name ?? 'Aset Tidak Terdaftar' }}</span>
                            </td>
                            <td class="p-3">
                                <span class="text-[11px] font-bold text-gray-400">{{ $item->asset->location->location_name ?? '-' }}</span>
                            </td>
                            <td class="p-3 text-center">
                                @if($item->condition_grade)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl text-[11px] font-black {{ $item->condition_grade === 'A' ? 'bg-emerald-100 text-emerald-700' : ($item->condition_grade === 'B' ? 'bg-blue-100 text-blue-700' : ($item->condition_grade === 'C' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')) }}">
                                        {{ $item->condition_grade }}
                                    </span>
                                @else
                                    <span class="text-gray-200">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($item->condition_score !== null)
                                    <div class="inline-flex items-center gap-1.5">
                                        <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $item->condition_score >= 75 ? 'bg-emerald-500' : ($item->condition_score >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $item->condition_score }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-gray-500">{{ round($item->condition_score) }}%</span>
                                    </div>
                                @else
                                    <span class="text-gray-200">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($item->checklist_status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-black uppercase border border-indigo-100">Terverifikasi</span>
                                @elseif($item->checklist_status === 'scanned')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-xl text-[9px] font-black uppercase border border-blue-100">Telah Dipindai</span>
                                @elseif(in_array($item->checklist_status, ['checked']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase border border-emerald-100">Terceklis</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 rounded-xl text-[9px] font-black uppercase border border-amber-100 animate-pulse">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-20">
            <div class="inline-flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-gray-50 border border-gray-100 shadow-inner mb-6">
                <i class="fas fa-clipboard-list text-3xl text-gray-200"></i>
            </div>
            <h4 class="text-lg font-black text-gray-400">Belum Ada Aset dalam Audit</h4>
            <p class="text-[11px] font-bold text-gray-300 uppercase tracking-widest mt-1">Tidak ada aset yang terdaftar untuk sesi audit ini.</p>
        </div>
        @endforelse
    </div>

    @if($items->isNotEmpty())
    <!-- Action Buttons -->
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 p-6 bg-white/90 backdrop-blur-xl border border-white rounded-[2rem] shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            <span class="text-sm font-bold text-gray-600">
                <span id="checked-count">{{ $items->whereIn('checklist_status', ['checked', 'scanned', 'verified'])->count() }}</span> / {{ $items->count() }} aset telah diceklis
            </span>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="group relative inline-flex items-center justify-center px-8 py-3.5 text-[13px] font-black text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-white/20">
                <i class="fas fa-save mr-2"></i> Simpan Checklist
            </button>
            <button type="button" onclick="openGradingPanel()" class="group inline-flex items-center justify-center px-6 py-3.5 text-[13px] font-black text-indigo-600 transition-all duration-300 bg-indigo-50 hover:bg-indigo-100 rounded-xl shadow-sm border border-indigo-100">
                <i class="fas fa-clipboard-check mr-2"></i> Grading Manual
            </button>
        </div>
    </div>
    @endif
</form>

<!-- Grading Modal -->
<div id="grading-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end sm:items-center justify-center min-h-screen px-4 pb-20 pt-4 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeGradingPanel()"></div>
        <div class="relative transform overflow-hidden rounded-[2rem] bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white/20">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-gray-800" id="modal-title">Grading Manual</h3>
                        <p class="text-[11px] font-bold text-gray-400 mt-1">Pilih aset dan tentukan kondisi berdasarkan kriteria</p>
                    </div>
                    <button onclick="closeGradingPanel()" class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <label class="text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 block">Pilih Aset</label>
                    <select id="grade-asset-select" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 transition-all appearance-none outline-none cursor-pointer">
                        <option value="">— Pilih Aset —</option>
                        @foreach($items->where('checklist_status', 'pending') as $item)
                            <option value="{{ $item->id_asset_audit_items }}" data-asset-id="{{ $item->id_assets }}">
                                [{{ $item->asset->asset_code ?? 'N/A' }}] {{ $item->asset->asset_name ?? 'Aset Tidak Terdaftar' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Criteria checkboxes per criteria group -->
                <div id="criteria-container" class="space-y-6 max-h-[400px] overflow-y-auto pr-2">
                    @foreach($criteriaGroups as $group)
                    <div class="criteria-group border border-gray-50 rounded-2xl p-5" data-group-id="{{ $group->id_criteria_groups }}">
                        <h4 class="font-black text-sm text-indigo-600 mb-4 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-indigo-200"></i>
                            {{ $group->name }}
                            @if($group->category_type)
                                <span class="text-[9px] font-bold bg-indigo-50 text-indigo-400 px-2 py-0.5 rounded-full">{{ $group->category_type }}</span>
                            @endif
                        </h4>
                        <div class="space-y-3">
                            @foreach($group->items as $criteria)
                            <label class="criteria-item flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-all cursor-pointer" data-criteria-id="{{ $criteria->id_criteria_items }}" data-weight="{{ $criteria->weight }}">
                                <input type="checkbox" class="criteria-checkbox mt-0.5 h-5 w-5 rounded-lg border-2 border-gray-200 text-indigo-600 focus:ring-indigo-500" data-group="{{ $group->id_criteria_groups }}">
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-700">{{ $criteria->name }}</span>
                                    <p class="text-[10px] font-bold text-gray-400">{{ $criteria->description }}</p>
                                </div>
                                <span class="text-[9px] font-black text-gray-300 bg-gray-50 px-2 py-1 rounded-lg h-fit">bobot {{ $criteria->weight }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <textarea id="grade-notes" rows="2" placeholder="Catatan grading (opsional)..." class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all resize-none"></textarea>
                </div>

                <div class="mt-6 flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Hasil Grade</span>
                        <div id="grade-result" class="flex items-center gap-3 mt-1">
                            <span class="text-2xl font-black text-gray-300">—</span>
                            <span class="text-sm font-bold text-gray-400">Belum dinilai</span>
                        </div>
                    </div>
                    <button onclick="submitGrade()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all text-[12px] uppercase tracking-widest border border-white/20 disabled:opacity-50 disabled:cursor-not-allowed" id="submit-grade-btn" disabled>
                        <i class="fas fa-check-circle"></i> Simpan Grade
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Check all / uncheck all by category (desktop)
    document.querySelectorAll('.category-select-all').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const table = this.closest('.bg-white\\/80');
            table.querySelectorAll('.asset-checkbox').forEach(cb => cb.checked = this.checked);
            updateCount();
        });
    });

    // Select all in category (mobile button)
    document.querySelectorAll('.select-all-cat').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            const card = this.closest('.bg-white\\/80');
            const checkboxes = card.querySelectorAll('.asset-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.innerHTML = allChecked 
                ? '<i class="fas fa-times mr-1"></i> Batal Pilih' 
                : '<i class="fas fa-check-double mr-1"></i> Pilih Semua';
            updateCount();
        });
    });

    // Update checked count
    document.querySelectorAll('.asset-checkbox').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    function updateCount() {
        const checked = document.querySelectorAll('.asset-checkbox:checked').length;
        const total = document.querySelectorAll('.asset-checkbox').length;
        const countEl = document.getElementById('checked-count');
        if (countEl) countEl.textContent = checked;
    }

    // Grading Panel
    function openGradingPanel() {
        document.getElementById('grading-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeGradingPanel() {
        document.getElementById('grading-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    let selectedCriteria = {};

    document.querySelectorAll('.criteria-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const groupId = this.dataset.group;
            const criteriaId = this.closest('.criteria-item').dataset.criteriaId;
            
            if (this.checked) {
                selectedCriteria[criteriaId] = true;
            } else {
                delete selectedCriteria[criteriaId];
            }
            updateGradePreview();
        });
    });

    function updateGradePreview() {
        const selectedCount = Object.keys(selectedCriteria).length;
        const gradeBtn = document.getElementById('submit-grade-btn');
        const gradeResult = document.getElementById('grade-result');

        if (selectedCount === 0) {
            gradeResult.innerHTML = '<span class="text-2xl font-black text-gray-300">—</span><span class="text-sm font-bold text-gray-400">Belum dinilai</span>';
            gradeBtn.disabled = true;
            return;
        }

        // Calculate score from selected criteria
        let totalWeight = 0;
        let earnedWeight = 0;
        
        document.querySelectorAll('.criteria-item').forEach(item => {
            const cb = item.querySelector('.criteria-checkbox');
            const weight = parseInt(item.dataset.weight);
            totalWeight += weight;
            if (cb.checked) earnedWeight += weight;
        });

        const percentage = totalWeight > 0 ? (earnedWeight / totalWeight) * 100 : 0;
        let grade = 'C';
        if (percentage >= 90) grade = 'A';
        else if (percentage >= 75) grade = 'B';
        else if (percentage >= 60) grade = 'C';
        else if (percentage >= 40) grade = 'D';
        else grade = 'E';

        const gradeColors = {
            'A': 'text-emerald-600 bg-emerald-50',
            'B': 'text-blue-600 bg-blue-50',
            'C': 'text-amber-600 bg-amber-50',
            'D': 'text-orange-600 bg-orange-50',
            'E': 'text-red-600 bg-red-50'
        };

        gradeResult.innerHTML = `
            <span class="text-4xl font-black text-gray-800">${grade}</span>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest ${gradeColors[grade] || 'text-gray-600 bg-gray-50'}">${percentage.toFixed(0)}%</span>
                <p class="text-[10px] font-bold text-gray-400 mt-0.5">${earnedWeight}/${totalWeight} bobot terpenuhi</p>
            </div>
        `;
        gradeBtn.disabled = false;
    }

    function submitGrade() {
        const assetSelect = document.getElementById('grade-asset-select');
        const itemId = assetSelect.value;
        if (!itemId) {
            Swal.fire({ icon: 'warning', title: 'Pilih Aset', text: 'Pilih aset yang akan dinilai terlebih dahulu.' });
            return;
        }

        const selectedCount = Object.keys(selectedCriteria).length;
        if (selectedCount === 0) {
            Swal.fire({ icon: 'warning', title: 'Pilih Kriteria', text: 'Pilih minimal satu kriteria penilaian.' });
            return;
        }

        const notes = document.getElementById('grade-notes').value;

        // Build criteria data
        const criteriaData = {};
        document.querySelectorAll('.criteria-item').forEach(item => {
            const cb = item.querySelector('.criteria-checkbox');
            const criteriaId = item.dataset.criteriaId;
            criteriaData[criteriaId] = cb.checked ? true : false;
        });

        fetch('{{ route("audits.grade", [$audit, 0]) }}'.replace('/grade/0', '/grade/' + itemId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ criteria: criteriaData, notes: notes })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Grading Berhasil',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                closeGradingPanel();
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.' });
            }
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan grade. Coba lagi.' });
        });
    }

    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGradingPanel();
    });
</script>
@endsection
