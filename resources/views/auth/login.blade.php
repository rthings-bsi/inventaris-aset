<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Basecamp Aset</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        primary: '#4f46e5', // Indigo 600
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Background Pattern */
        .bg-grid {
            background-size: 30px 30px;
            background-image: 
                linear-gradient(to right, rgba(99, 102, 241, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
        }

        /* Interactive Robot Character */
        .robot {
            width: 120px;
            height: 100px;
            position: relative;
            cursor: pointer;
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .robot-antenna {
            position: absolute;
            top: -24px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 24px;
            background: #cbd5e1;
            border-radius: 4px 4px 0 0;
            z-index: 5;
        }

        .robot-antenna::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -4px;
            width: 14px;
            height: 14px;
            background: #60a5fa;
            border-radius: 50%;
            box-shadow: 0 0 12px #60a5fa;
            animation: pulse-glow 2s infinite alternate;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 8px #60a5fa, 0 0 15px #60a5fa; opacity: 0.8; }
            100% { box-shadow: 0 0 15px #3b82f6, 0 0 25px #3b82f6; opacity: 1; }
        }

        .robot-ear {
            position: absolute;
            top: 40px;
            width: 12px;
            height: 24px;
            background: #94a3b8;
            z-index: 5;
        }
        .robot-ear-left { left: -12px; border-radius: 6px 0 0 6px; }
        .robot-ear-right { right: -12px; border-radius: 0 6px 6px 0; }

        .robot-head {
            width: 100%;
            height: 100%;
            background: #ffffff;
            border-radius: 35px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1), inset 0 -6px 20px rgba(226, 232, 240, 0.8);
            border: 3px solid #f8fafc;
            position: relative;
            z-index: 10;
        }

        .robot-screen {
            position: absolute;
            top: 18px;
            left: 12px;
            right: 12px;
            height: 60px;
            background: #0f172a;
            border-radius: 20px;
            overflow: hidden;
            border: 3px solid #334155;
            box-shadow: inset 0 0 20px rgba(0,0,0,1);
        }

        .robot-eyes {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            gap: 16px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .robot-eye {
            width: 16px;
            height: 24px;
            background: #38bdf8;
            border-radius: 10px;
            box-shadow: 0 0 15px #38bdf8;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .robot-blush {
            position: absolute;
            top: 60%;
            width: 18px;
            height: 8px;
            background: #f472b6;
            border-radius: 50%;
            filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .robot-blush-left { left: 8px; }
        .robot-blush-right { right: 8px; }

        /* Mascot States */
        body.is-typing .robot-eyes {
            transform: translate(-50%, -15%);
        }
        body.is-typing .robot-eye {
            height: 18px;
        }

        body.is-peeking .robot-blush { opacity: 0.95; }
        body.is-peeking .robot-eye {
            height: 6px;
            margin-top: 10px;
            background: #f472b6;
            box-shadow: 0 0 16px #f472b6;
            border-radius: 10px;
        }
        body.is-peeking .robot-eyes {
            transform: translate(-50%, -50%) scale(1.1);
        }

        /* Hands */
        .robot-hand {
            position: absolute;
            bottom: -15px;
            width: 32px;
            height: 40px;
            background: #ffffff;
            border-radius: 16px;
            border: 3px solid #f8fafc;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 20;
        }
        .robot-hand-left { left: 16px; }
        .robot-hand-right { right: 16px; }

        /* Hand Animations */
        body.is-peeking .robot-hand-left {
            transform: translateY(-56px) translateX(24px) rotate(50deg);
        }
        body.is-peeking .robot-hand-right {
            transform: translateY(-56px) translateX(-24px) rotate(-50deg);
        }
        body.is-typing .robot-hand-left {
            transform: translateY(-10px) translateX(-5px) rotate(-15deg);
        }
        body.is-typing .robot-hand-right {
            transform: translateY(-10px) translateX(5px) rotate(15deg);
        }

    </style>
</head>
<body class="bg-slate-50 min-h-screen relative text-slate-800 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <!-- Ambient / Abstract Background Patterns -->
    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-primary opacity-20 blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-0 right-10 -z-10 h-[250px] w-[250px] rounded-full bg-pink-500 opacity-15 blur-[120px]"></div>
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 pt-16">
        
        <!-- Interactive Robot Mascot -->
        <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20">
            <div class="robot" id="robot" title="Boop!">
                <div class="robot-antenna"></div>
                <div class="robot-ear robot-ear-left"></div>
                <div class="robot-ear robot-ear-right"></div>
                
                <div class="robot-head" id="robot-head">
                    <div class="robot-screen">
                        <div class="robot-blush robot-blush-left"></div>
                        <div class="robot-blush robot-blush-right"></div>
                        <div class="robot-eyes" id="robot-eyes">
                            <div class="robot-eye"></div>
                            <div class="robot-eye"></div>
                        </div>
                    </div>
                </div>
                
                <div class="robot-hand robot-hand-left"></div>
                <div class="robot-hand robot-hand-right"></div>
            </div>
        </div>

        <!-- Glassmorphism Card -->
        <div class="bg-white/70 backdrop-blur-xl py-10 px-6 sm:px-10 shadow-2xl sm:rounded-[2.5rem] border border-white/60 relative">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 mb-4">
                    <i class="ri-box-3-fill text-2xl"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Basecamp Aset</h2>
                <p class="mt-2 text-sm text-slate-500 font-medium">Masuk untuk mengelola inventaris cerdas Anda.</p>
            </div>

            <!-- Error handling from Laravel -->
            @error('email')
                <div class="p-4 mb-6 text-sm text-red-600 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3 shadow-sm animate-[pulse_0.5s_ease-in-out]">
                    <i class="ri-error-warning-fill text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold">Gagal Masuk</p>
                        <p class="font-medium mt-0.5">Kredensial tidak valid. Silakan periksa email dan kata sandi.</p>
                    </div>
                </div>
            @enderror

            <form class="space-y-5 relative z-20" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Input Email -->
                <div class="group">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-indigo-600">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ri-mail-line text-lg"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}"
                            class="block w-full pl-11 pr-4 py-3.5 bg-white/80 border-2 border-slate-200 rounded-xl text-sm text-slate-800 font-semibold placeholder-slate-400 focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 focus:bg-white transition-all outline-none @error('email') border-red-300 focus:border-red-500 @enderror" 
                            placeholder="nama@perusahaan.com">
                    </div>
                </div>

                <!-- Input Password -->
                <div class="group">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-widest transition-colors group-focus-within:text-indigo-600">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ri-lock-password-line text-lg"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full pl-11 pr-12 py-3.5 bg-white/80 border-2 border-slate-200 rounded-xl text-sm text-slate-800 font-semibold placeholder-slate-400 focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 focus:bg-white transition-all outline-none tracking-widest placeholder:tracking-normal focus:tracking-normal @error('password') border-red-300 focus:border-red-500 @enderror" 
                            placeholder="Malu-malu Kucing">
                        
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                            <i class="ri-eye-off-line text-lg" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pb-2 pt-1">
                    <label for="remember" class="flex items-center gap-2 cursor-pointer group/chk select-none">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="peer sr-only" {{ old('remember') ? 'checked' : '' }}>
                            <div class="w-5 h-5 rounded border-2 border-slate-300 bg-white peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all flex items-center justify-center group-hover/chk:border-indigo-400">
                                <i class="ri-check-line text-white text-[10px] font-bold opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-500 group-hover/chk:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="relative w-full group overflow-hidden rounded-xl bg-indigo-600 text-white font-bold py-3.5 sm:py-4 transition-all hover:shadow-[0_10px_20px_-10px_rgba(79,70,229,0.7)] hover:-translate-y-0.5 mt-2 border-2 border-transparent focus:ring-4 focus:ring-indigo-600/20 active:scale-[0.98]">
                        <span class="relative z-10 flex items-center justify-center gap-2 text-sm sm:text-base">
                            Masuk ke Sistem <i class="ri-arrow-right-line group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-slate-200/60 flex items-center justify-center">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                    <i class="ri-shield-check-fill text-emerald-500 text-sm"></i> Keamanan Enkripsi 256-bit
                </p>
            </div>
            
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const body = document.body;
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyes = document.getElementById('robot-eyes');
            const eyeElements = document.querySelectorAll('.robot-eye');
            const robot = document.getElementById('robot');
            const robotHead = document.getElementById('robot-head');
            
            let mouseX = window.innerWidth / 2;
            let mouseY = window.innerHeight / 2;

            // Follow Mouse Cursor
            document.addEventListener('mousemove', (e) => {
                if (body.classList.contains('is-peeking')) return;
                
                mouseX = e.clientX;
                mouseY = e.clientY;

                const headRect = robotHead.getBoundingClientRect();
                const headCenterX = headRect.left + headRect.width / 2;
                const headCenterY = headRect.top + headRect.height / 2;

                const angle = Math.atan2(mouseY - headCenterY, mouseX - headCenterX);
                const maxDistance = 8;
                const distance = Math.min(maxDistance, Math.hypot(mouseX - headCenterX, mouseY - headCenterY) / 25);

                const x = Math.cos(angle) * distance;
                const y = Math.sin(angle) * distance;

                if(body.classList.contains('is-typing')) {
                    eyes.style.transform = `translate(calc(-50% + ${x * 0.4}px), calc(-15% + ${y * 0.4}px))`;
                } else {
                    eyes.style.transform = `translate(calc(-50% + ${x}px), calc(-50% + ${y}px))`;
                }
            });

            // Focus Email -> Typing State
            emailInput.addEventListener('focus', () => {
                body.classList.add('is-typing');
                body.classList.remove('is-peeking');
            });
            emailInput.addEventListener('blur', () => {
                body.classList.remove('is-typing');
                eyes.style.transform = `translate(-50%, -50%)`;
            });

            // Focus Password -> Peeking State
            passwordInput.addEventListener('focus', () => {
                if (passwordInput.type === 'password') {
                    body.classList.add('is-peeking');
                }
                body.classList.remove('is-typing');
            });
            passwordInput.addEventListener('blur', () => {
                body.classList.remove('is-peeking');
                eyes.style.transform = `translate(-50%, -50%)`;
            });

            // Blink Animation
            setInterval(() => {
                if (!body.classList.contains('is-peeking')) {
                    eyeElements.forEach(eye => {
                        const originalHeight = window.getComputedStyle(eye).height;
                        eye.style.height = '2px';
                        setTimeout(() => {
                            eye.style.height = originalHeight;
                            setTimeout(() => eye.style.height = '', 50); // Clean up inline style
                        }, 150);
                    });
                }
            }, 4500);

            // Toggle Password
            togglePassword.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    eyeIcon.classList.replace('ri-eye-off-line', 'ri-eye-line');
                    body.classList.remove('is-peeking');
                    body.classList.add('is-typing');
                } else {
                    eyeIcon.classList.replace('ri-eye-line', 'ri-eye-off-line');
                    if (document.activeElement === passwordInput) {
                        body.classList.add('is-peeking');
                        body.classList.remove('is-typing');
                    }
                }
            });

            // Poke Robot Interaction
            robot.addEventListener('click', () => {
                robot.style.transform = 'scale(1.05) translateY(-5px)';
                robotHead.style.borderColor = '#60a5fa';
                setTimeout(() => {
                    robot.style.transform = '';
                    robotHead.style.borderColor = '';
                }, 200);
            });
        });
    </script>
</body>
</html>
