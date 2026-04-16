@extends('layouts.app')

@section('title', 'Tambah Aset - Inventaris')

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
        -webkit-backdrop-filter: blur(20px);
    }
    @keyframes gradientBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(20px, -30px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
    }
    .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }
</style>

<!-- Header Section -->
<div class="relative glass-header rounded-[2rem] p-8 md:p-10 mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/60 hover:bg-white text-indigo-600 rounded-xl text-sm font-black transition-all shadow-sm border border-indigo-50 mb-4 group/back">
                <i class="fas fa-arrow-left mr-2 group-hover/back:-translate-x-1 transition-transform"></i> Kembali ke Inventaris
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700 tracking-tight flex items-center gap-3">
                Tambah Entri Baru ✨
            </h1>
            <p class="text-gray-600 mt-2 font-semibold text-lg max-w-xl">Silakan lengkapi formulir di bawah ini untuk mencatat aset baru ke dalam sistem.</p>
        </div>
        
        <div class="hidden md:flex">
             <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center border-2 border-indigo-50 text-indigo-500 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                  <i class="fas fa-box-open text-2xl group-hover:animate-bounce-sm"></i>
             </div>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="glass-card rounded-[2rem] p-8 md:p-10 mb-10 relative overflow-hidden group/form border-t-2 border-l-2 border-white/80">
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/20 to-purple-50/20 opacity-0 group-hover/form:opacity-100 transition-opacity duration-1000 -z-10"></div>
    
    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <!-- Kode Aset -->
            <div class="group/input relative pb-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Referensi / Kode Aset <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-qrcode text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <input type="text" name="asset_code" value="{{ old('asset_code') }}" 
                           class="w-full pl-12 pr-5 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 placeholder-gray-400 outline-none @error('asset_code') border-red-300 bg-red-50 focus:border-red-500 @enderror"
                           placeholder="Contoh: AST-2023-001" required>
                </div>
                @error('asset_code')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Aset -->
            <div class="group/input relative pb-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Nama Lengkap Aset <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-tag text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <input type="text" name="asset_name" value="{{ old('asset_name') }}" 
                           class="w-full pl-12 pr-5 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 placeholder-gray-400 outline-none @error('asset_name') border-red-300 bg-red-50 focus:border-red-500 @enderror"
                           placeholder="Contoh: Apple MacBook Pro 16\ 2023" required>
                </div>
                @error('asset_name')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="group/input relative pb-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Category Aset <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-layer-group text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <select name="category_id" required
                            class="w-full pl-12 pr-10 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 appearance-none cursor-pointer outline-none @error('category_id') border-red-300 bg-red-50 focus:border-red-500 @enderror">
                        <option value="">Pilihan Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-indigo-400">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                </div>
                @error('category_id')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Nilai Perolehan -->
            <div class="group/input relative pb-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Nilai Kapital (Harga) <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <span class="text-indigo-400 font-black">Rp</span>
                    </div>
                    <input type="number" name="acquisition_cost" value="{{ old('acquisition_cost') }}" 
                           class="w-full pl-14 pr-5 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 placeholder-gray-400 outline-none @error('acquisition_cost') border-red-300 bg-red-50 focus:border-red-500 @enderror"
                           placeholder="Contoh: 15600000" required min="0" step="0.01">
                </div>
                <p class="text-[10px] font-black text-gray-400 mt-2 px-1 uppercase tracking-wider absolute -bottom-5 left-0">* Nominal tanpa format koma / titik</p>
                @error('acquisition_cost')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Perolehan -->
            <div class="group/input pt-2">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Tanggal Pembelian <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <input type="date" name="acquisition_date" value="{{ old('acquisition_date') }}" 
                           class="w-full px-5 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 outline-none cursor-pointer @error('acquisition_date') border-red-300 bg-red-50 focus:border-red-500 @enderror"
                           required>
                </div>
                @error('acquisition_date')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Condition -->
            <div class="group/input pt-2">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Condition Fisik <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-stethoscope text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <select name="condition" required
                            class="w-full pl-12 pr-10 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 appearance-none cursor-pointer outline-none @error('condition') border-red-300 bg-red-50 focus:border-red-500 @enderror">
                        <option value="">Pilihan Condition</option>
                        <option value="Baik Sekali" {{ old('condition') == 'Baik Sekali' ? 'selected' : '' }}>Grade A - Masih Baru / Sangat Mulus</option>
                        <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Grade B - Condition Baik Kelayakan 100%</option>
                        <option value="Cukup" {{ old('condition') == 'Cukup' ? 'selected' : '' }}>Grade C - Layak Pakai dengan Defect Minor</option>
                        <option value="Kurang Baik" {{ old('condition') == 'Kurang Baik' ? 'selected' : '' }}>Grade D - Kurang Baik / Perlu Inspeksi</option>
                        <option value="Rusak" {{ old('condition') == 'Rusak' ? 'selected' : '' }}>Grade E - Rusak Parah / Unserviceable</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-indigo-400">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                </div>
                @error('condition')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div class="group/input md:col-span-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Penempatan Aset <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <select name="location_id" required
                            class="w-full pl-12 pr-10 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 appearance-none cursor-pointer outline-none @error('location_id') border-red-300 bg-red-50 focus:border-red-500 @enderror">
                        <option value="">Pilihan Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->location_name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-indigo-400">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                </div>
                @error('location_id')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="group/input md:col-span-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Status Penggunaan <span class="text-pink-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-shield-alt text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <select name="status" required
                            class="w-full pl-12 pr-10 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 appearance-none cursor-pointer outline-none @error('status') border-red-300 bg-red-50 focus:border-red-500 @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif - Siap Digunakan</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance - Dalam Perbaikan / Servis</option>
                        <option value="broken" {{ old('status') == 'broken' ? 'selected' : '' }}>Rusak - Tidak Dapat Diberdayakan</option>
                        <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>Dihapuskan - Proses Penghancuran / Lelang</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-indigo-400">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                </div>
                @error('status')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Penanggung Jawab -->
            <div class="group/input md:col-span-2">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Penanggung Jawab (PIC) Opsional</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-user-tie text-indigo-300 group-hover/input:text-indigo-500 transition-colors"></i>
                    </div>
                    <select name="user_id"
                            class="w-full pl-12 pr-10 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 appearance-none cursor-pointer outline-none @error('user_id') border-red-300 bg-red-50 focus:border-red-500 @enderror">
                        <option value="">Pilih Penanggung Jawab (Kosongkan jika divisi kolektif)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-indigo-400">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                </div>
                @error('user_id')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Photo -->
            <div class="group/input md:col-span-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Unggah Dokumen / Photo Benda</label>
                <div class="border-2 border-dashed border-indigo-200 bg-indigo-50/30 rounded-3xl p-6 text-center hover:bg-indigo-50 hover:border-indigo-400 transition-all cursor-pointer relative group/upload" onclick="document.getElementById('photo-input').click()">
                    <input type="file" name="photo" id="photo-input" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="previewText(this)">
                    <div class="flex flex-col items-center gap-3 mt-2 text-indigo-400 pointer-events-none transition-transform group-hover/upload:-translate-y-2">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-3xl border border-indigo-100 group-hover/upload:shadow-md transition-shadow text-indigo-500">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <span class="text-[15px] font-black text-indigo-600" id="file-chosen">Pilih Direktori Berkas...</span>
                    </div>
                    <p class="text-[11px] font-black tracking-widest uppercase text-gray-400 mt-4 pointer-events-none relative z-20">Ekstensi Maks: JPG/PNG • 2MB</p>
                </div>
                @error('photo')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Script to show filename -->
            <script>
                function previewText(input) {
                    const fileChosen = document.getElementById('file-chosen');
                    if (input.files && input.files[0]) {
                        fileChosen.textContent = 'Berkas Terpilih: ' + input.files[0].name;
                        fileChosen.classList.remove('text-indigo-600');
                        fileChosen.classList.add('text-pink-500');
                    } else {
                        fileChosen.textContent = 'Pilih Direktori Berkas...';
                        fileChosen.classList.remove('text-pink-500');
                        fileChosen.classList.add('text-indigo-600');
                    }
                }
            </script>

            <!-- Description -->
            <div class="group/input md:col-span-1">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest group-hover/input:text-indigo-600 transition-colors">Notes Operasional (Description)</label>
                <div class="relative h-full">
                    <textarea name="description" rows="6"
                              class="w-full h-full min-h-[142px] px-5 py-4 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:ring-0 focus:bg-white focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all text-[15px] font-bold text-gray-800 placeholder-gray-400 outline-none resize-none @error('description') border-red-300 bg-red-50 focus:border-red-500 @enderror"
                              placeholder="Dokumentasikan spesifikasi, riwayat garansi atau info unik lainnya..."></textarea>
                </div>
                <!-- Retain old content -->
                <script>document.querySelector('textarea[name="description"]').value = `{!! addslashes(old('description')) !!}`;</script>
                @error('description')
                    <p class="text-red-500 text-xs font-bold mt-2 animate-bounce-sm px-1"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-10 pt-8 border-t-2 border-dashed border-indigo-100">
            <a href="{{ route('assets.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white border-2 border-indigo-100 text-gray-500 rounded-xl font-black hover:bg-gray-50 hover:text-gray-700 hover:border-gray-300 transition-all hover:-translate-y-1 block text-center shadow-sm">
                Batalkan
            </a>
            <button type="submit" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black shadow-[0_10px_20px_-10px_rgba(79,70,229,0.5)] hover:shadow-[0_15px_30px_-10px_rgba(79,70,229,0.7)] hover:-translate-y-1 transition-all group border border-transparent">
                <span class="flex items-center justify-center">
                    Simpan ke Sistem <i class="fas fa-save ml-3 group-hover:scale-125 transition-transform"></i>
                </span>
            </button>
        </div>
    </form>
</div>
@endsection
