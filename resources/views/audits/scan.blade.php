@extends('layouts.app')

@section('title', 'Pemindaian Audit - ' . $audit->title)

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }
    .glass-header {
        background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(243, 232, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
    }
    @keyframes gradientBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(20px, -30px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
    }
    .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }
    
    #reader video {
        object-fit: cover !important;
        border-radius: 2rem;
    }
    #reader__scan_region { border: none !important; }
    #reader img { display: none !important; }
    #reader__dashboard_section_csr button {
        background: #4F46E5 !important;
        color: white !important;
        border-radius: 1rem !important;
        padding: 10px 20px !important;
        font-family: inherit !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        border: none !important;
        font-size: 11px !important;
        letter-spacing: 1px !important;
    }
</style>

<!-- Page Header -->
<div class="relative glass-header rounded-[2rem] p-8 md:p-10 mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('audits.index') }}" class="group inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-400 hover:text-indigo-600 transition-colors mb-4">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                Sesi Audit: <span class="text-indigo-600 font-black">{{ $audit->title }}</span>
            </h1>
            <p class="text-gray-600 mt-2 font-semibold text-lg max-w-xl flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Pindai barcode pada aset fisik untuk validasi stok lapangan.
            </p>
        </div>
        <button onclick="confirmComplete()" class="group relative inline-flex items-center justify-center px-8 py-4 text-[14px] font-black text-white transition-all duration-300 bg-emerald-600 rounded-2xl shadow-[0_10px_25px_rgba(16,185,129,0.35)] hover:shadow-[0_15px_35px_rgba(16,185,129,0.45)] hover:-translate-y-1 active:scale-95 border border-transparent">
            <i class="fas fa-check-double mr-2 group-hover:scale-110 transition-transform"></i>
            Selesaikan Audit
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
    <!-- Scanner Side -->
    <div class="lg:col-span-5 space-y-6">
        <div class="glass-card rounded-[2.5rem] p-8 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 flex items-center gap-2">
                        <i class="fas fa-camera text-indigo-400"></i> Scanner Lapangan
                    </h3>
                    <div id="scanner-status" class="flex gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-emerald-600 border border-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Kamera Aktif
                        </span>
                    </div>
                </div>

                <div class="relative aspect-square w-full overflow-hidden rounded-[2rem] bg-slate-900 shadow-inner border-4 border-white/50 group">
                    <div id="reader" style="width: 100%; height: 100%;"></div>
                    <!-- Scanner UI Decorative Overlays -->
                    <div class="absolute inset-x-12 top-1/2 -translate-y-1/2 h-0.5 bg-indigo-500/50 shadow-[0_0_15px_rgba(99,102,241,0.8)] animate-bounce pointer-events-none"></div>
                    <div class="absolute inset-0 border-[30px] border-black/10 pointer-events-none"></div>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-100"></div>
                        </div>
                        <div class="relative flex justify-center text-[10px] font-black uppercase tracking-widest">
                            <span class="bg-white px-4 text-gray-300">Atau Input Manual</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <input type="text" id="manual-code" placeholder="Ketik kode aset..." class="flex-1 rounded-2xl border-2 border-gray-100 bg-gray-50 px-6 py-4 font-bold text-gray-700 outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50 transition-all shadow-sm">
                        <button onclick="manualScan()" class="rounded-2xl bg-indigo-100 px-8 py-4 font-black text-indigo-700 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-time Scan Result Card -->
        <div id="last-result" class="hidden glass-card rounded-[2rem] p-6 border-l-4 border-indigo-500 animate-jelly">
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 shadow-inner">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div>
                    <div id="res-name" class="text-sm font-black text-gray-800">Nama Aset Terdeteksi</div>
                    <div id="res-code" class="text-[11px] font-black text-indigo-500 uppercase tracking-widest mt-1">CODE-001</div>
                </div>
                <div id="res-time" class="ml-auto text-[10px] font-black text-gray-300">12:30:00</div>
            </div>
        </div>
    </div>

    <!-- Stats & History Side -->
    <div class="lg:col-span-7 space-y-6">
        <div class="glass-card rounded-[2.5rem] p-8 h-full flex flex-col">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Antrean Pemindaian</h3>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mt-1">Status audit real-time lapangan</p>
                </div>
                <div id="scan-count-container" class="flex flex-col items-end">
                    <div id="scan-count" class="flex items-center gap-3 rounded-2xl bg-indigo-600 px-6 py-3 text-white shadow-xl shadow-indigo-100">
                        <span class="text-2xl font-black">{{ $items->count() }}</span>
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-80 leading-tight text-right">Aset<br>Terpindai</span>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-3 custom-scrollbar" style="max-height: 550px;" id="scan-history">
                @forelse($items as $item)
                <div class="flex items-center gap-5 rounded-[1.5rem] bg-white p-5 shadow-sm border border-gray-50 group hover:shadow-md hover:translate-x-1 transition-all">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $item->status === 'unexpected' ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500' }}">
                        <i class="fas {{ $item->status === 'unexpected' ? 'fa-exclamation-triangle' : 'fa-check' }} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[15px] font-black text-gray-800 truncate">{{ $item->asset ? $item->asset->asset_name : 'Aset Tidak Terdaftar' }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $item->scanned_code }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] font-black text-indigo-400">{{ $item->scanned_at->format('H:i:s') }}</div>
                        @if($item->status === 'unexpected')
                            <span class="text-[9px] font-black uppercase text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100 mt-1 inline-block">Surplus</span>
                        @endif
                    </div>
                </div>
                @empty
                <div id="no-history" class="py-32 text-center">
                    <div class="inline-flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-gray-50 border border-gray-100 shadow-inner mb-6 animate-float">
                        <i class="fas fa-barcode text-3xl text-gray-200"></i>
                    </div>
                    <h4 class="text-lg font-black text-gray-400">Belum Ada Data</h4>
                    <p class="text-[11px] font-bold text-gray-300 uppercase tracking-widest mt-1">Mulailah memindai kode aset di lapangan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;
    const scannerConfig = { fps: 15, qrbox: { width: 250, height: 250 } };

    document.addEventListener('DOMContentLoaded', () => {
        startScanner();
    });

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, 
            scannerConfig,
            onScanSuccess
        ).catch(err => {
            console.error(err);
            document.getElementById('scanner-status').innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-rose-600 border border-rose-100">
                    <i class="fas fa-times-circle"></i> Eror Kamera
                </span>
            `;
            Swal.fire({
                icon: 'error',
                title: 'Akses Kamera Gagal',
                text: 'Pastikan Anda memberikan izin kamera atau gunakan input manual.'
            });
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        html5QrCode.pause(true);
        processScan(decodedText);
    }

    function manualScan() {
        const code = document.getElementById('manual-code').value;
        if (!code) return;
        processScan(code);
        document.getElementById('manual-code').value = '';
    }

    function processScan(code) {
        // Show loading state
        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang memvalidasi kode aset...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); },
            timer: 2000, // Safety timer
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });

        // Haptic feedback if available
        if (window.navigator.vibrate) window.navigator.vibrate(100);
        
        // Success audio
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2218/2218-preview.mp3');
        audio.play().catch(() => {});

        fetch("{{ route('audits.scan', $audit) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            if (data.success) {
                updateUI(data.item);
                setTimeout(() => { if (html5QrCode.getState() === Html5QrcodeScannerState.PAUSED) html5QrCode.resume(); }, 1000);
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                setTimeout(() => { if (html5QrCode.getState() === Html5QrcodeScannerState.PAUSED) html5QrCode.resume(); }, 1000);
            }
        })
        .catch(err => {
            Swal.close();
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Eror Sistem',
                text: err.message || 'Terjadi kesalahan saat menghubungkan ke server. Pastikan koneksi internet stabil.',
                confirmButtonColor: '#4F46E5'
            });
            setTimeout(() => { if (html5QrCode.getState() === Html5QrcodeScannerState.PAUSED) html5QrCode.resume(); }, 2000);
        });
    }

    function updateUI(item) {
        const noHist = document.getElementById('no-history');
        if (noHist) noHist.remove();

        const countSpan = document.querySelector('#scan-count span');
        countSpan.textContent = parseInt(countSpan.textContent) + 1;

        const lastRes = document.getElementById('last-result');
        lastRes.classList.remove('hidden');
        document.getElementById('res-name').textContent = item.name;
        document.getElementById('res-code').textContent = item.code;
        document.getElementById('res-time').textContent = item.time;

        const history = document.getElementById('scan-history');
        const newItem = `
            <div class="flex items-center gap-5 rounded-[1.5rem] bg-white p-5 shadow-sm border border-gray-50 group hover:shadow-md hover:translate-x-1 transition-all animate-fade-in-up">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl ${item.status === 'unexpected' ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500'}">
                    <i class="fas ${item.status === 'unexpected' ? 'fa-exclamation-triangle' : 'fa-check'} text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[15px] font-black text-gray-800 truncate">${item.name}</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">${item.code}</div>
                </div>
                <div class="text-right">
                    <div class="text-[11px] font-black text-indigo-400">${item.time}</div>
                    ${item.status === 'unexpected' ? '<span class="text-[9px] font-black uppercase text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100 mt-1 inline-block">Surplus</span>' : ''}
                </div>
            </div>
        `;
        history.insertAdjacentHTML('afterbegin', newItem);
    }

    function confirmComplete() {
        confirmAction({
            title: 'Selesaikan Audit?',
            text: 'Setelah diselesaikan, data hasil pemindaian tidak dapat diubah lagi.',
            confirmText: 'Ya, Selesaikan',
            icon: 'info'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('audits.complete', $audit) }}";
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E1; }
</style>
@endsection
