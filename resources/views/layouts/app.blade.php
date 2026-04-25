<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AsetKu Enterprise')</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans for that modern vibe -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        pop: {
                            purple: '#7c3aed',
                            pink: '#ec4899',
                            indigo: '#4f46e5',
                            yellow: '#f59e0b',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'slide-down': 'slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'bounce-sm': 'bounceSmall 2s ease-in-out infinite',
                        'wiggle': 'wiggle 2s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'jelly': 'jelly 0.6s ease-in-out forwards',
                        'gradient-shift': 'gradientShift 3s ease infinite'
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        slideDown: {
                            '0%': { opacity: 0, transform: 'translateY(-20px) scale(0.95)' },
                            '100%': { opacity: 1, transform: 'translateY(0) scale(1)' },
                        },
                        bounceSmall: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        },
                        wiggle: {
                            '0%, 100%': { transform: 'rotate(-3deg)' },
                            '50%': { transform: 'rotate(3deg)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-8px)' },
                        },
                        jelly: {
                            '0%': { transform: 'scale(1, 1)' },
                            '30%': { transform: 'scale(1.25, 0.75)' },
                            '40%': { transform: 'scale(0.75, 1.25)' },
                            '50%': { transform: 'scale(1.15, 0.85)' },
                            '65%': { transform: 'scale(0.95, 1.05)' },
                            '75%': { transform: 'scale(1.05, 0.95)' },
                            '100%': { transform: 'scale(1, 1)' }
                        },
                        gradientShift: {
                            '0%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                            '100%': { backgroundPosition: '0% 50%' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            color: #334155;
            /* Faint glowing background pattern */
            background-image: 
                radial-gradient(at 0% 0%, hsla(234, 100%, 85%, 0.3) 0, transparent 40%), 
                radial-gradient(at 100% 100%, hsla(270, 100%, 85%, 0.3) 0, transparent 40%);
            background-attachment: fixed;
        }

        /* Scrollbar Pop */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #818cf8; }

        .glass-sidebar {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255, 255, 255, 0.6);
        }

        .glass-header {
            background: rgba(255, 255, 0.85);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
        }

        @keyframes gradientBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -30px) scale(1.05); }
            66% { transform: translate(-15px, 15px) scale(0.95); }
        }
        .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }
        
        /* Smooth transitions */
        a, button, .sidebar-text, div {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        .font-tabular { font-variant-numeric: tabular-nums; }
        
        /* Premium SweetAlert Styling */
        .swal2-popup {
            border-radius: 2.5rem !important;
            padding: 2.5rem 2rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border: 2px solid rgba(255,255,255,0.9) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            background: rgba(255, 255, 255, 0.92) !important;
            box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.15) !important;
        }
        .swal2-title { 
            font-weight: 900 !important; 
            color: #1e293b !important; 
            letter-spacing: -0.025em !important;
            font-size: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        .swal2-html-container {
            font-weight: 500 !important;
            color: #64748b !important;
            line-height: 1.6 !important;
        }
        .swal2-confirm { 
            border-radius: 1.25rem !important; 
            font-weight: 800 !important; 
            padding: 1rem 2.5rem !important; 
            font-size: 0.95rem !important; 
            margin: 0.5rem !important;
            transition: all 0.3s ease !important;
            border: none !important;
            color: #ffffff !important;
        }
        .swal2-confirm-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed) !important; 
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3) !important;
        }
        .swal2-confirm-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.4) !important;
            filter: brightness(110%);
        }
        .swal2-confirm-danger {
            background: linear-gradient(135deg, #f43f5e, #e11d48) !important; 
            box-shadow: 0 10px 15px -3px rgba(244, 63, 94, 0.3) !important;
        }
        .swal2-confirm-danger:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 25px -5px rgba(244, 63, 94, 0.4) !important;
            filter: brightness(110%);
        }
        .swal2-confirm-success {
            background: linear-gradient(135deg, #10b981, #059669) !important; 
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3) !important;
        }
        .swal2-confirm-success:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.4) !important;
            filter: brightness(110%);
        }
        .swal2-confirm-warning {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important; 
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3) !important;
        }
        .swal2-confirm-warning:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 25px -5px rgba(245, 158, 11, 0.4) !important;
            filter: brightness(110%);
        }
        .swal2-cancel { 
            border-radius: 1.25rem !important; 
            font-weight: 800 !important; 
            padding: 1rem 2.5rem !important; 
            font-size: 0.95rem !important; 
            background: #f8fafc !important; 
            color: #64748b !important; 
            border: 1px solid #e2e8f0 !important;
            margin: 0.5rem !important;
            transition: all 0.3s ease !important;
        }
        .swal2-cancel:hover {
            background: #f1f5f9 !important;
            color: #334155 !important;
            transform: translateY(-2px) !important;
        }
        .swal2-icon {
            border-width: 3px !important;
            margin-bottom: 1.5rem !important;
        }
        .swal2-backdrop-show {
            backdrop-filter: blur(8px) !important;
            background: rgba(15, 23, 42, 0.4) !important;
        }
        
        /* SweetAlert Responsive */
        @media (max-width: 640px) {
            .swal2-popup {
                padding: 1.5rem 1rem !important;
                border-radius: 1.75rem !important;
                width: 90% !important;
                max-width: 380px !important;
            }
            .swal2-title {
                font-size: 1.25rem !important;
                margin-bottom: 0.25rem !important;
            }
            .swal2-icon {
                transform: scale(0.85);
                margin-bottom: 0.75rem !important;
                margin-top: 0 !important;
            }
            .swal2-actions {
                flex-direction: column !important;
                width: 100% !important;
                margin-top: 1.5rem !important;
            }
            .swal2-confirm, .swal2-cancel {
                width: 100% !important;
                margin: 0.35rem 0 !important;
            }
        }
        /* AsetKu Branding Styles */
        .logo-hexagon {
            clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
        }
        .logo-a-shape {
            clip-path: polygon(50% 0%, 0% 100%, 30% 100%, 50% 40%, 70% 100%, 100% 100%);
        }
    </style>
</head>
<body class="h-screen flex overflow-hidden font-sans antialiased selection:bg-indigo-200 selection:text-indigo-900 group/body">

    <!-- ===== PAGE TRANSITION OVERLAY ===== -->
    <div id="page-loader" class="fixed inset-0 z-[9999] bg-[#f8fafc]/95 backdrop-blur-xl flex transition-all duration-500 ease-in-out opacity-100">
        <!-- Skeleton Sidebar Desktop -->
        <div class="hidden md:flex flex-col w-72 h-screen border-r border-indigo-50/50 bg-white/60 p-6 animate-pulse">
            <!-- Logo Skeleton -->
            <div class="flex items-center gap-4 mb-10 mt-2">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100/60 shadow-inner"></div>
                <div class="flex-1 space-y-2.5">
                    <div class="h-4 bg-indigo-100/70 rounded-md w-3/4"></div>
                    <div class="h-2.5 bg-gray-100 rounded-md w-1/2"></div>
                </div>
            </div>
            <!-- Menu Skeleton -->
            <div class="space-y-4">
                <div class="h-2.5 bg-indigo-50 rounded-md w-1/3 mb-6"></div>
                <div class="h-12 bg-white rounded-2xl w-full border border-gray-100/50 shadow-sm"></div>
                <div class="h-12 bg-white rounded-2xl w-full border border-gray-100/50 shadow-sm"></div>
                <div class="h-2.5 bg-indigo-50 rounded-md w-1/3 mt-8 mb-6"></div>
                <div class="h-12 bg-white rounded-2xl w-full border border-gray-100/50 shadow-sm"></div>
                <div class="h-12 bg-white rounded-2xl w-full border border-gray-100/50 shadow-sm"></div>
            </div>
            <div class="mt-auto h-20 bg-white rounded-t-[2.5rem] w-full border-t border-indigo-50/50 flex items-center px-4 gap-4">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-3 bg-gray-200 rounded-md w-2/3"></div>
                    <div class="h-2 bg-gray-100 rounded-md w-1/3"></div>
                </div>
            </div>
        </div>

        <!-- Skeleton Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header Skeleton -->
            <div class="md:hidden h-20 border-b border-white/50 bg-white/60 flex items-center justify-between px-5 animate-pulse shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl"></div>
                    <div class="h-5 bg-gray-200 rounded-md w-24"></div>
                </div>
                <div class="w-10 h-10 rounded-full bg-white shadow-sm border border-gray-100"></div>
            </div>

            <!-- Content Skeleton -->
            <div class="flex-1 p-4 md:p-8 space-y-6 md:space-y-8 overflow-hidden relative">
                <!-- Top Banner Hero Skeleton -->
                <div class="w-full h-32 md:h-40 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 border-2 border-white rounded-[2rem] animate-pulse p-8 flex flex-col justify-center shadow-sm relative overflow-hidden">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-100/40 rounded-full mix-blend-multiply filter blur-2xl"></div>
                    <div class="h-8 md:h-10 bg-indigo-100/60 rounded-xl w-2/3 md:w-1/3 mb-4 relative z-10"></div>
                    <div class="h-4 bg-gray-200/60 rounded-md w-4/5 md:w-1/2 relative z-10"></div>
                </div>
                
                <!-- Filter & Action Row Skeleton -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-pulse">
                    <div class="h-10 bg-white rounded-xl w-32 border border-white shadow-sm"></div>
                    <div class="flex gap-2.5 w-full sm:w-auto">
                        <div class="h-10 bg-white rounded-xl w-28 border border-white shadow-sm"></div>
                        <div class="h-10 bg-indigo-100 rounded-xl w-32 border border-white shadow-sm"></div>
                    </div>
                </div>
                
                <!-- Table/Grid Skeleton -->
                <div class="bg-white/60 border-2 border-white rounded-[2rem] p-4 md:p-6 space-y-4 animate-pulse shadow-sm flex-1">
                    <div class="hidden md:flex justify-between items-center pb-4 border-b border-indigo-50/50 mb-2 px-2">
                        <div class="h-3 bg-indigo-100 rounded-md w-24"></div>
                        <div class="h-3 bg-indigo-100 rounded-md w-16"></div>
                        <div class="h-3 bg-indigo-100 rounded-md w-20"></div>
                        <div class="h-3 bg-indigo-100 rounded-md w-16"></div>
                    </div>
                    <!-- Rows -->
                    <div class="flex items-center gap-4 p-3 bg-white rounded-2xl border border-gray-50 shadow-[0_2px_10px_rgba(0,0,0,0.01)]">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50/50"></div>
                        <div class="flex flex-col gap-2 flex-1"><div class="h-4 bg-gray-100 rounded-md w-1/3"></div><div class="h-2 bg-gray-50 rounded-md w-1/4"></div></div>
                        <div class="h-6 w-16 bg-emerald-50 rounded-lg hidden md:block"></div>
                        <div class="h-8 w-16 bg-gray-50 rounded-xl hidden md:block lg:ml-auto"></div>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-white rounded-2xl border border-gray-50 shadow-[0_2px_10px_rgba(0,0,0,0.01)]">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50/50"></div>
                        <div class="flex flex-col gap-2 flex-1"><div class="h-4 bg-gray-100 rounded-md w-1/2"></div><div class="h-2 bg-gray-50 rounded-md w-1/3"></div></div>
                        <div class="h-6 w-16 bg-amber-50 rounded-lg hidden md:block"></div>
                        <div class="h-8 w-16 bg-gray-50 rounded-xl hidden md:block lg:ml-auto"></div>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-white rounded-2xl border border-gray-50 shadow-[0_2px_10px_rgba(0,0,0,0.01)]">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50/50"></div>
                        <div class="flex flex-col gap-2 flex-1"><div class="h-4 bg-gray-100 rounded-md w-2/5"></div><div class="h-2 bg-gray-50 rounded-md w-1/4"></div></div>
                        <div class="h-6 w-16 bg-red-50 rounded-lg hidden md:block"></div>
                        <div class="h-8 w-16 bg-gray-50 rounded-xl hidden md:block lg:ml-auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DESKTOP SIDEBAR ===== -->
    <aside id="desktop-sidebar" class="hidden md:flex flex-col w-72 glass-sidebar flex-shrink-0 z-20 shadow-[10px_0_40px_rgba(79,70,229,0.04)] transition-all duration-400 ease-out relative group/sidebar">
        <!-- Toggle Button -->
        <button id="sidebar-toggle" class="absolute -right-4 top-10 bg-white border-2 border-indigo-100 rounded-full w-8 h-8 flex items-center justify-center text-indigo-400 hover:text-indigo-600 z-50 shadow-sm hover:scale-110 hover:shadow-indigo-200 transition-all cursor-pointer focus:outline-none group-hover/sidebar:opacity-100 opacity-0 group-hover/sidebar:visible invisible">
            <i class="fas fa-chevron-left text-[10px] hover:animate-wiggle" id="sidebar-toggle-icon"></i>
        </button>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="h-28 flex items-center pl-0 pr-6 gap-0 group/logo border-b border-indigo-50/50 overflow-hidden whitespace-nowrap mt-2">
            <div class="relative w-40 h-40 -ml-14 flex-shrink-0 group-hover/logo:scale-105 transition-all duration-500 ease-out">
                <img src="{{ asset('logo.png') }}" alt="AsetKu Logo" class="w-full h-full object-contain object-left scale-125 transform origin-left">
            </div>
            <div class="sidebar-text -ml-6 transition-all duration-300">
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter leading-none flex items-baseline">
                    <span class="relative">
                        A
                        <span class="absolute -bottom-1 left-0 w-full h-[3px] bg-sky-400 rounded-full opacity-0 group-hover/logo:opacity-100 transition-opacity duration-300"></span>
                    </span>
                    <span>set</span>
                    <span class="text-indigo-600 transition-colors group-hover/logo:text-violet-600">Ku</span>
                </h1>
                <p class="text-[8px] text-slate-400 font-extrabold mt-1.5 uppercase tracking-[0.2em] group-hover/logo:text-indigo-400 transition-colors">Enterprise System</p>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-8 space-y-3 overflow-x-hidden">
            <p class="sidebar-text px-3 text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-5 transition-opacity duration-200">Navigasi Utama</p>

            @if(auth()->user()->hasPermission('dashboard.view'))
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('dashboard') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-chart-line text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Executive Dashboard
                    @if(request()->routeIs('dashboard'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('asset.view'))
            <a href="{{ route('assets.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('assets.index') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('assets.index') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-boxes text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Direktori Aset
                    @if(request()->routeIs('assets.index'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('loan.view'))
            <a href="{{ route('loans.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('loans.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('loans.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-handshake text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Peminjaman Aset
                    @if(request()->routeIs('loans.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
            @endif

            @if(auth()->check() && auth()->user()->hasPermission('master-data.view'))
            <p class="sidebar-text px-3 text-[10px] font-black text-indigo-300 uppercase tracking-widest mt-6 mb-3 transition-opacity duration-200">Master Data</p>

            <a href="{{ route('categories.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('categories.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('categories.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-tags text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Data Category
                    @if(request()->routeIs('categories.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>

            <a href="{{ route('locations.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('locations.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('locations.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-map-marker-alt text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Data Location
                    @if(request()->routeIs('locations.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
            @endif

            @if(auth()->check() && (auth()->user()->hasPermission('user.manage') || auth()->user()->isAdmin()))
            <p class="sidebar-text px-3 text-[10px] font-black text-indigo-300 uppercase tracking-widest mt-6 mb-3 transition-opacity duration-200">Administration</p>

            @if(auth()->user()->hasPermission('user.manage'))
            <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('users.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-users-cog text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Manajemen User
                    @if(request()->routeIs('users.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>

            <a href="{{ route('roles.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('roles.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('roles.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-shield-alt text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Manajemen Role
                    @if(request()->routeIs('roles.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <a href="{{ route('settings.roles.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('settings.roles.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('settings.roles.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-user-shield text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Pengaturan Role
                    @if(request()->routeIs('settings.roles.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
            @endif
            @endif

            <a href="{{ route('audits.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-[14.5px] font-bold transition-all duration-300 whitespace-nowrap group {{ request()->routeIs('audits.*') ? 'bg-gradient-to-r from-indigo-50/80 via-purple-50/80 to-indigo-50/80 bg-[length:200%_200%] animate-gradient-shift text-indigo-700 shadow-sm border border-indigo-100/60' : 'text-gray-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm hover:translate-x-1.5 border border-transparent hover:border-indigo-50' }}">
                <div class="w-8 flex justify-center transition-transform duration-300 {{ request()->routeIs('audits.*') ? 'text-indigo-600 scale-110' : 'text-gray-400 group-hover:text-indigo-500' }}">
                    <i class="fas fa-barcode text-lg group-hover:animate-jelly"></i>
                </div>
                <span class="sidebar-text transition-all duration-200 w-full flex items-center justify-between">
                    Audit Data Aset
                    @if(request()->routeIs('audits.*'))
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                    @endif
                </span>
            </a>
        </nav>

        <!-- Sidebar Footer / Auth -->
        <div class="p-5 border-t border-indigo-50/50 overflow-hidden bg-white/50 rounded-t-[2.5rem] mt-auto backdrop-blur-md relative">
            <!-- Decorative blur blob inside footer -->
            <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-purple-200 rounded-full mix-blend-multiply filter blur-2xl opacity-50 pointer-events-none"></div>
            
            @auth
                <div class="flex items-center gap-4 mb-5 px-2 whitespace-nowrap group/profile cursor-pointer relative z-10">
                    <div class="w-11 h-11 flex-shrink-0 rounded-2xl bg-gradient-to-br from-white to-indigo-50 border border-indigo-100/80 flex items-center justify-center text-indigo-500 shadow-sm group-hover/profile:scale-110 group-hover/profile:rotate-3 group-hover/profile:shadow-md transition-all duration-300">
                        <i class="fas fa-user-astronaut text-lg group-hover/profile:text-indigo-600 group-hover/profile:animate-jelly"></i>
                    </div>
                    <div class="sidebar-text flex-1 min-w-0 transition-opacity duration-200">
                        <p class="text-[14px] font-black text-gray-800 truncate group-hover/profile:text-indigo-700 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-indigo-400 font-bold truncate mt-0.5 uppercase tracking-wider flex items-center gap-1">
                            <i class="fas {{ auth()->user()->isAdmin() ? 'fa-shield-alt' : 'fa-user-tie mb-0.5' }} text-[9px]"></i> {{ auth()->user()->role_display_name }}
                        </p>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="relative z-10">
                    @csrf
                    <button type="button" onclick="handleLogout()" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 text-[13.5px] font-black text-rose-500 bg-white border border-rose-100/50 hover:bg-gradient-to-r hover:from-rose-50 hover:to-red-50 hover:text-rose-700 hover:border-rose-200 shadow-sm hover:shadow-md rounded-2xl transition-all duration-300 whitespace-nowrap group focus:outline-none focus:ring-4 focus:ring-rose-100">
                        <i class="fas fa-sign-out-alt flex-shrink-0 group-hover:-translate-x-1.5 group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="sidebar-text transition-opacity duration-200">Log Out</span>
                    </button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="relative z-10 w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-[0_4px_15px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-1 hover:scale-[1.02] py-4 rounded-2xl text-[14px] font-black flex items-center justify-center gap-3 whitespace-nowrap transition-all duration-300 group">
                    <span class="sidebar-text transition-opacity duration-200">Otorisasi Akses</span>
                    <i class="fas fa-arrow-right text-sm flex-shrink-0 group-hover:translate-x-2 group-hover:scale-110 transition-transform duration-300"></i>
                </a>
            @endguest
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('desktop-sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const icon = document.getElementById('sidebar-toggle-icon');
            const texts = document.querySelectorAll('.sidebar-text');

            toggleBtn.addEventListener('click', () => {
                const isCollapsed = sidebar.classList.contains('w-24');

                if (isCollapsed) {
                    sidebar.classList.remove('w-24');
                    sidebar.classList.add('w-72');
                    icon.classList.replace('fa-chevron-right', 'fa-chevron-left');

                    texts.forEach(el => {
                        el.style.display = '';
                        setTimeout(() => el.style.opacity = '1', 100);
                    });
                } else {
                    sidebar.classList.remove('w-72');
                    sidebar.classList.add('w-24');
                    icon.classList.replace('fa-chevron-left', 'fa-chevron-right');

                    texts.forEach(el => {
                        el.style.opacity = '0';
                        setTimeout(() => el.style.display = 'none', 150);
                    });
                }
            });
        });
    </script>

    <!-- ===== MAIN CONTENT WRAPPER ===== -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-transparent relative">

        <!-- ===== MOBILE HEADER ===== -->
        <header class="md:hidden glass-header sticky top-0 z-30 shadow-sm border-b border-white/50">
            <div class="px-5 h-20 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition-transform">
                        <i class="fas fa-layer-group text-sm group-hover:animate-jelly"></i>
                    </div>
                    <div>
                        <span class="font-black text-gray-800 text-xl tracking-tight">Aset<span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Ku</span></span>
                    </div>
                </a>
                <button id="mobile-menu-btn" class="w-10 h-10 bg-white rounded-full shadow-sm text-indigo-500 hover:text-indigo-600 hover:bg-gray-50 hover:scale-105 active:scale-95 transition-all flex justify-center items-center border border-indigo-50">
                    <i class="fas fa-bars text-lg" id="mobile-icon"></i>
                </button>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="mobile-menu" class="hidden absolute top-20 left-0 w-full bg-white/95 backdrop-blur-xl shadow-lg border-b border-indigo-100 py-4 animate-slide-down z-40 rounded-b-[2rem] max-h-[calc(100vh-6rem)] overflow-y-auto custom-scrollbar">
                <nav class="px-4 space-y-2 pb-6">
                    @if(auth()->user()->hasPermission('dashboard.view'))
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-chart-line w-6 text-center {{ request()->routeIs('dashboard') ? 'text-indigo-500' : '' }}"></i> Executive Dashboard
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('asset.view'))
                    <a href="{{ route('assets.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('assets.index') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-boxes w-6 text-center {{ request()->routeIs('assets.index') ? 'text-indigo-500' : '' }}"></i> Direktori Aset
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('loan.view'))
                    <a href="{{ route('loans.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('loans.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-handshake w-6 text-center {{ request()->routeIs('loans.*') ? 'text-indigo-500' : '' }}"></i> Peminjaman Aset
                    </a>
                    @endif
                    @if(auth()->check() && auth()->user()->hasPermission('master-data.view'))
                    <div class="px-3 pt-3 pb-1">
                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest px-4">Master Data</p>
                    </div>
                    <a href="{{ route('categories.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-tags w-6 text-center {{ request()->routeIs('categories.*') ? 'text-indigo-500' : '' }}"></i> Data Category
                    </a>
                    <a href="{{ route('locations.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('locations.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-map-marker-alt w-6 text-center {{ request()->routeIs('locations.*') ? 'text-indigo-500' : '' }}"></i> Data Location
                    </a>
                    @endif

                    @if(auth()->check() && (auth()->user()->hasPermission('user.manage') || auth()->user()->isAdmin()))
                    <div class="px-3 pt-3 pb-1">
                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest px-4">Administration</p>
                    </div>
                    @if(auth()->user()->hasPermission('user.manage'))
                    <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-users-cog w-6 text-center {{ request()->routeIs('users.*') ? 'text-indigo-500' : '' }}"></i> Manajemen User
                    </a>
                    <a href="{{ route('roles.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('roles.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-shield-alt w-6 text-center {{ request()->routeIs('roles.*') ? 'text-indigo-500' : '' }}"></i> Manajemen Role
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('settings.roles.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('settings.roles.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-user-shield w-6 text-center {{ request()->routeIs('settings.roles.*') ? 'text-indigo-500' : '' }}"></i> Pengaturan Role
                    </a>
                    @endif
                    @endif
                    <a href="{{ route('audits.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-[15px] font-bold transition-all {{ request()->routeIs('audits.*') ? 'bg-indigo-50 text-indigo-700 border border-indigo-100/50' : 'text-gray-600 hover:bg-gray-50 border border-transparent' }}">
                        <i class="fas fa-barcode w-6 text-center {{ request()->routeIs('audits.*') ? 'text-indigo-500' : '' }}"></i> Audit Data Aset
                    </a>
                </nav>
                <div class="border-t border-indigo-100/50 pt-6 px-6 pb-4 bg-gray-50/50 mx-2 rounded-2xl relative overflow-hidden">
                    <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-purple-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40 pointer-events-none"></div>
                    @auth
                        <div class="flex items-center gap-4 mb-6 relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-indigo-100 flex items-center justify-center text-indigo-500 shadow-sm">
                                <i class="fas fa-user-astronaut text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-[15px] font-black text-gray-800">{{ auth()->user()->name }}</h4>
                                <span class="text-[11px] font-black uppercase tracking-wide text-indigo-500 flex items-center gap-1">
                        <i class="fas {{ auth()->user()->isAdmin() ? 'fa-shield-alt' : 'fa-user-tie mb-0.5' }} text-[9px]"></i> {{ auth()->user()->role_display_name }}
                    </span>
                            </div>
                        </div>
                        <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="relative z-10">
                            @csrf
                            <button type="button" onclick="handleLogout()" class="w-full flex items-center justify-center gap-2 px-4 py-4 text-rose-600 bg-white border border-rose-100 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 rounded-xl font-bold text-sm shadow-sm transition-all active:scale-95 group">
                                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> Akhiri Sesi Akses
                            </button>
                        </form>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="relative z-10 w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black py-4 text-center flex items-center justify-center gap-2 shadow-[0_4px_15px_rgba(79,70,229,0.3)] active:scale-95 transition-transform group">
                            Otorisasi Akses <i class="fas fa-arrow-right text-[12px] group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <!-- ===== SCROLLABLE AREA ===== -->
        <div class="flex-1 overflow-y-auto flex flex-col">

            <!-- Default Content Container -->
            <div class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-8 md:py-8 animate-fade-in-up">

                <!-- ===== FLASH MESSAGES ===== -->
                <div id="flash-container" class="space-y-4 {{ (session('success') || session('error')) ? 'mb-6 md:mb-8' : 'mb-0' }}">
                    @if(session('success'))
                        <div class="flash-alert flex items-center justify-between bg-white/90 backdrop-blur-md border border-emerald-200 p-5 rounded-2xl shadow-sm animate-slide-down transform transition-all cursor-pointer overflow-hidden group/alert" onclick="this.classList.add('opacity-0', 'scale-95'); setTimeout(() => this.remove(), 300)">
                            <div class="absolute left-0 top-0 w-1 h-full bg-emerald-500"></div>
                            <div class="flex items-center gap-5 z-10 w-full pl-2">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0 text-emerald-500 group-hover/alert:animate-jelly">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-emerald-800 uppercase tracking-wide">Terkonfirmasi Sukses</h4>
                                    <p class="text-xs font-bold text-gray-500 mt-0.5">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-emerald-600 hover:bg-emerald-50 transition-all w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="flash-alert flex items-center justify-between bg-white/90 backdrop-blur-md border border-red-200 p-5 rounded-2xl shadow-sm animate-slide-down transform transition-all cursor-pointer overflow-hidden group/alert" onclick="this.classList.add('opacity-0', 'scale-95'); setTimeout(() => this.remove(), 300)">
                            <div class="absolute left-0 top-0 w-1 h-full bg-red-500"></div>
                            <div class="flex items-center gap-5 z-10 w-full pl-2">
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 text-red-500 group-hover/alert:animate-wiggle">
                                    <i class="fas fa-exclamation-triangle text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-red-800 uppercase tracking-wide">Kesalahan Terdeteksi</h4>
                                    <p class="text-xs font-bold text-gray-500 mt-0.5">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-red-600 hover:bg-red-50 transition-all w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- ===== PAGE CONTENT ===== -->
                @yield('content')

            </div>

            <!-- ===== FOOTER ===== -->
            <footer class="mt-auto py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[11px] font-black uppercase tracking-widest text-gray-400 bg-white/40 backdrop-blur-md px-6 py-5 rounded-2xl border border-indigo-50 shadow-sm">
                        <div class="flex items-center gap-3 transition-colors cursor-default group hover:text-indigo-400">
                            <i class="fas fa-building text-indigo-400 group-hover:animate-jelly"></i>
                            <span>Sistem Manajemen Aset Korporat Terpadu</span>
                        </div>
                        <span class="hover:text-indigo-500 transition-colors cursor-pointer text-gray-500 font-bold tracking-normal normal-case text-xs">&copy; {{ date('Y') }} AsetKu Enterprise. All rights reserved.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('mobile-icon');

        btn.addEventListener('click', () => {
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.className = 'fas fa-times text-lg';
            } else {
                menu.classList.add('hidden');
                icon.className = 'fas fa-bars text-lg';
            }
        });

        // Auto dismiss alerts smoothly
        setTimeout(() => {
            document.querySelectorAll('.flash-alert').forEach(alert => {
                alert.classList.add('opacity-0', 'scale-95', '-translate-x-4');
                setTimeout(() => alert.remove(), 400);
            });
        }, 5000);

        function handleLogout() {
            confirmAction({
                title: 'Akhiri Sesi?',
                text: 'Masa sesi Anda akan diakhiri dan Anda harus masuk kembali untuk mengakses sistem.',
                confirmText: 'Ya, Log Out',
                icon: 'question'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.querySelector('#logout-form') || document.querySelector('#mobile-logout-form');
                    if (form) form.submit();
                }
            });
        }

        // Global SweetAlert Confirmation Helper
        window.confirmAction = function(options) {
            const iconColors = {
                'success': '#10b981',
                'error': '#f43f5e',
                'warning': '#f59e0b',
                'info': '#3b82f6',
                'question': '#7c3aed'
            };

            let btnClass = 'swal2-confirm-primary';
            if (options.icon === 'error' || options.variant === 'danger') btnClass = 'swal2-confirm-danger';
            else if (options.icon === 'success') btnClass = 'swal2-confirm-success';
            else if (options.icon === 'warning') btnClass = 'swal2-confirm-warning';
            else if (options.icon === 'question') btnClass = 'swal2-confirm-danger'; // Usually used for destructive confirmation

            return Swal.fire({
                title: options.title || 'Konfirmasi Tindakan',
                text: options.text || 'Apakah Anda yakin ingin melanjutkan?',
                icon: options.icon || 'warning',
                iconColor: iconColors[options.icon] || '#4f46e5',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: `swal2-confirm font-sans ${btnClass}`,
                    cancelButton: 'swal2-cancel font-sans',
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    htmlContainer: 'swal2-html-container'
                },
                buttonsStyling: false,
                padding: window.innerWidth < 640 ? '1.5rem 1rem' : '2.5rem'
            });

        };
    </script>
    <script>
        // Smooth Page Transitions
        const loader = document.getElementById('page-loader');

        function hideLoader() {
            if(!loader) return;
            loader.style.opacity = '0';
            loader.style.transform = 'scale(1.05)';
            setTimeout(() => {
                loader.style.display = 'none';
                loader.classList.add('pointer-events-none');
            }, 500);
        }

        // Auto hide on load independently
        window.addEventListener('load', hideLoader);
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(hideLoader, 100);
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(hideLoader, 100);

            // Intercept clicks to show loader
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;
                
                // Exclude new tabs, javascript links, hashes, downloads
                if (link.target === '_blank' || e.ctrlKey || e.metaKey || link.hasAttribute('download')) return;
                if (!link.href || link.href.startsWith('javascript:') || link.href.includes('#')) return;
                if (link.classList && link.classList.contains('no-loader')) return;
                
                // Allow only same origin
                try {
                    if (new URL(link.href).origin !== window.location.origin) return;
                } catch(err) { return; }

                if (e.defaultPrevented) return;

                e.preventDefault();
                showLoader();
                setTimeout(() => {
                    window.location.href = link.href;
                }, 300);
            });

            // Intercept form submissions
            document.addEventListener('submit', (e) => {
                const form = e.target;
                // Exclude forms with target="_blank", no-loader class, or specific IDs
                if (form.hasAttribute('target') && form.target === '_blank') return;
                if (form.classList && form.classList.contains('no-loader')) return;
                if (form.id === 'form-export' || form.id === 'bulk-delete-form') return;
                
                showLoader();
            });
        });

        function showLoader() {
            if(!loader) return;
            loader.style.display = 'flex';
            loader.offsetHeight; // trigger reflow
            loader.classList.remove('pointer-events-none');
            loader.style.opacity = '1';
            loader.style.transform = 'scale(1)';
        }

        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                hideLoader();
            }
        });
        
        // Failsafe: always hide after maximum 3.5 seconds
        setTimeout(hideLoader, 3500);
    </script>

    <!-- Sticky Notification Bell -->
    @auth
        @php
            $unreadNotifications = auth()->user()->unreadNotifications;
        @endphp
        <div class="fixed bottom-6 right-6 z-[100] group/notif">
            <button class="w-14 h-14 bg-white/90 backdrop-blur-md rounded-full shadow-[0_10px_25px_rgba(79,70,229,0.25)] border border-indigo-100 flex items-center justify-center text-indigo-500 hover:text-white hover:bg-gradient-to-r hover:from-indigo-600 hover:to-purple-600 transition-all duration-300 hover:scale-110">
                <i class="fas fa-bell text-xl"></i>
                @if($unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full animate-bounce"></span>
                @endif
            </button>

            <!-- Dropdown -->
            <div class="absolute bottom-16 right-0 mb-4 w-80 sm:w-96 bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_-10px_rgba(0,0,0,0.2)] border border-indigo-100 overflow-hidden transform origin-bottom-right transition-all duration-300 z-[101] opacity-0 invisible group-hover/notif:opacity-100 group-hover/notif:visible translate-y-4 group-hover/notif:translate-y-0">
                <div class="flex justify-between items-center px-5 py-4 border-b border-indigo-50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                    <h3 class="font-black text-indigo-800 text-[15px] flex items-center gap-2">
                        <i class="fas fa-bell text-indigo-400"></i> Pusat Notifikasi
                    </h3>
                    @if($unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.read') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="text-[10px] uppercase font-black text-indigo-500 hover:text-indigo-700 tracking-wider bg-white px-3 py-1.5 rounded-lg border border-indigo-100 shadow-sm hover:shadow active:scale-95 transition-all">Tandai Dibaca <i class="fas fa-check-double ml-1"></i></button>
                        </form>
                    @endif
                </div>
                <div class="max-h-[50vh] overflow-y-auto">
                    @forelse($unreadNotifications as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-4 border-b border-gray-50/80 hover:bg-indigo-50/60 transition-colors {{ $loop->last ? 'border-0' : '' }} group/item">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <i class="fas {{ ($notification->data['type'] ?? '') == 'loan_requested' ? 'fa-envelope-open-text' : (($notification->data['type'] ?? '') == 'loan_approved' ? 'fa-check' : (($notification->data['type'] ?? '') == 'loan_rejected' ? 'fa-times' : 'fa-undo')) }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[13px] text-gray-700 font-semibold leading-snug">
                                        {!! str_replace($notification->data['asset_name'] ?? '', '<span class="font-black text-indigo-600">'.($notification->data['asset_name'] ?? '').'</span>', $notification->data['message']) !!}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-1.5 font-bold flex items-center gap-1"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-10 text-center flex flex-col items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-purple-50 text-indigo-300 rounded-full flex items-center justify-center mb-4 shadow-inner border border-indigo-100/50">
                                <i class="fas fa-bell-slash text-2xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-indigo-900 mb-1">Sudah Membaca Semua!</h4>
                            <p class="text-[11px] font-bold text-gray-500">Belum ada pembaruan aktivitas peminjaman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endauth
</body>
</html>