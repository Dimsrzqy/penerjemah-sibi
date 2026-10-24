<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Isyarat K-SULI - Belajar Bahasa Isyarat SIBI')</title>

    <!-- Favicon (K-Suli Official Logo) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link rel="alternate icon" type="image/svg+xml" href="{{ asset('images/k-suli-logo.svg') }}?v=3">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-low": "#eff4ff",
                        "secondary-container": "#57dffe",
                        "on-tertiary": "#ffffff",
                        "on-surface-variant": "#424754",
                        "surface-container": "#e5eeff",
                        "outline-variant": "#c2c6d6",
                        "inverse-primary": "#adc6ff",
                        "tertiary-container": "#a36700",
                        "on-error": "#ffffff",
                        "secondary-fixed-dim": "#4cd7f6",
                        "error-container": "#ffdad6",
                        "on-secondary-container": "#006172",
                        "secondary-fixed": "#acedff",
                        "tertiary-fixed-dim": "#ffb95f",
                        "on-tertiary-fixed": "#2a1700",
                        "primary-fixed": "#d8e2ff",
                        "inverse-on-surface": "#eaf1ff",
                        "primary-fixed-dim": "#adc6ff",
                        "tertiary-fixed": "#ffddb8",
                        "on-tertiary-container": "#fffbff",
                        "on-primary": "#ffffff",
                        "on-background": "#0b1c30",
                        "on-primary-container": "#fefcff",
                        "secondary": "#00687a",
                        "on-secondary-fixed-variant": "#004e5c",
                        "on-secondary-fixed": "#001f26",
                        "error": "#ba1a1a",
                        "primary-container": "#2170e4",
                        "surface-container-high": "#dce9ff",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#005ac2",
                        "outline": "#727785",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#001a42",
                        "surface": "#f8f9ff",
                        "inverse-surface": "#213145",
                        "surface-bright": "#f8f9ff",
                        "on-primary-fixed-variant": "#004395",
                        "background": "#f8f9ff",
                        "tertiary": "#825100",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#0b1c30",
                        "on-tertiary-fixed-variant": "#653e00",
                        "surface-dim": "#cbdbf5",
                        "primary": "#0058be",
                        "surface-container-highest": "#d3e4fe",
                        "surface-variant": "#d3e4fe"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "sans": ["'Plus Jakarta Sans'", "Inter", "sans-serif"],
                        "heading": ["'Plus Jakarta Sans'", "sans-serif"],
                        "body-lg": ["'Plus Jakarta Sans'", "sans-serif"],
                        "display-lg-mobile": ["'Plus Jakarta Sans'", "sans-serif"],
                        "headline-md": ["'Plus Jakarta Sans'", "sans-serif"],
                        "label-sm": ["'Plus Jakarta Sans'", "sans-serif"],
                        "display-lg": ["'Plus Jakarta Sans'", "sans-serif"],
                        "body-md": ["'Plus Jakarta Sans'", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .liquid-glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-top: 1px solid rgba(255, 255, 255, 0.7);
            border-left: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
        }

        .gold-rank {
            background: linear-gradient(135deg, #F59E0B, #B45309);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .silver-rank {
            background: linear-gradient(135deg, #9CA3AF, #4B5563);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bronze-rank {
            background: linear-gradient(135deg, #D97706, #92400E);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ================= Dynamic Island Morph Glass (iPhone Style) ================= */
        #mainHeader {
            pointer-events: auto;
        }

        /* Scrolled state: Header container becomes transparent floating area */
        #mainHeader.is-scrolled {
            pointer-events: none;
        }

        #mainHeader.is-scrolled #headerBackdrop {
            opacity: 0;
            transform: translateY(-10px);
        }

        #mainHeader.is-scrolled #headerBrandArea {
            opacity: 0;
            transform: translateX(-30px) scale(0.9);
            pointer-events: none;
        }

        #mainHeader.is-scrolled #headerActionArea {
            opacity: 0;
            transform: translateX(30px) scale(0.9);
            pointer-events: none;
        }

        /* The Morphed Dynamic Island Capsule */
        #mainHeader.is-scrolled #floatingNavPill {
            pointer-events: auto;
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(28px) saturate(190%);
            -webkit-backdrop-filter: blur(28px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 
                0 20px 40px -12px rgba(0, 67, 191, 0.16),
                0 10px 24px -6px rgba(15, 23, 42, 0.08),
                0 0 0 1px rgba(0, 0, 0, 0.04),
                inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95);
            transform: translateY(8px) scale(1.02);
            padding: 0.55rem 1.6rem;
        }

        #floatingNavPill:hover {
            box-shadow: 
                0 24px 48px -10px rgba(0, 67, 191, 0.2),
                0 12px 28px -6px rgba(15, 23, 42, 0.1),
                0 0 0 1px rgba(0, 0, 0, 0.05),
                inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 1);
        }

        /* Mobile Island Scrolled State */
        #mainHeader.is-scrolled #mobileIslandContainer {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, 8px) scale(1);
        }

        #mobileIslandPill {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 
                0 16px 36px -8px rgba(0, 67, 191, 0.16),
                0 8px 18px -4px rgba(15, 23, 42, 0.08),
                0 0 0 1px rgba(0, 0, 0, 0.04),
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.95);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col antialiased">
    <!-- ================= NAVBAR (Dynamic Island Morph Layout) ================= -->
    <header id="mainHeader" class="sticky top-0 z-50 w-full transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <!-- Full-Width Background Backdrop (fades out when scrolled) -->
        <div id="headerBackdrop" class="absolute inset-0 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all duration-500 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between transition-all duration-500">
            <!-- Brand / Logo Area -->
            <div id="headerBrandArea" class="flex items-center transition-all duration-400 ease-[cubic-bezier(0.16,1,0.3,1)]">
                <a class="flex items-center gap-3.5 group focus:outline-none" href="{{ route('beranda') }}">
                    <div class="flex items-center justify-center">
                        <img alt="Logo K-Suli" class="h-10 w-10 object-contain group-hover:scale-105 transition-transform" src="{{ asset('images/k-suli-logo.svg') }}">
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-none">K-SULI</span>
                            <span class="bg-blue-50 text-blue-600 text-[11px] font-bold px-2 py-0.5 rounded-md leading-none border border-blue-100">SIBI</span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium leading-none mt-1">Kedai Susu Tuli</span>
                    </div>
                </a>
            </div>

            <!-- Pill Navigation Bar (Mathematically Centered, Morphs into Dynamic Island Glass) -->
            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center pointer-events-auto">
                <nav id="floatingNavPill" class="flex items-center gap-1.5 sm:gap-2 bg-slate-50/90 border border-slate-200/80 px-4 py-1.5 rounded-full shadow-xs text-sm transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
                    <a class="px-3.5 py-1.5 rounded-full transition-all text-xs lg:text-sm {{ request()->routeIs('beranda') ? 'font-bold text-blue-600 bg-blue-50/90 shadow-2xs' : 'font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-100/70' }}" href="{{ route('beranda') }}">Beranda</a>
                    <a class="px-3.5 py-1.5 rounded-full transition-all text-xs lg:text-sm {{ request()->routeIs('penerjemah') ? 'font-bold text-blue-600 bg-blue-50/90 shadow-2xs' : 'font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-100/70' }}" href="{{ route('penerjemah') }}">Penerjemah Kamera</a>
                    <a class="px-3.5 py-1.5 rounded-full transition-all text-xs lg:text-sm {{ request()->routeIs('kamus') ? 'font-bold text-blue-600 bg-blue-50/90 shadow-2xs' : 'font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-100/70' }}" href="{{ route('kamus') }}">Kamus SIBI</a>
                    <a class="px-3.5 py-1.5 rounded-full transition-all text-xs lg:text-sm {{ request()->routeIs('quiz') ? 'font-bold text-blue-600 bg-blue-50/90 shadow-2xs' : 'font-medium text-slate-600 hover:text-blue-600 hover:bg-slate-100/70' }}" href="{{ route('quiz') }}">Kuis Praktik</a>
                </nav>
            </div>

            <!-- Right Action Buttons -->
            <div id="headerActionArea" class="flex items-center gap-3 transition-all duration-400 ease-[cubic-bezier(0.16,1,0.3,1)]">
                <a class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-xs transition-all active:scale-95 min-h-[40px]" href="{{ route('quiz') }}">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Mulai Belajar</span>
                </a>

                <!-- Mobile Hamburger Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobileMenuToggle" type="button" class="p-2 rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none transition-colors" aria-label="Toggle navigation">
                        <span id="menuOpenIcon" class="material-symbols-outlined text-2xl">menu</span>
                        <span id="menuCloseIcon" class="material-symbols-outlined text-2xl hidden">close</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dynamic Island Pill (When Scrolled on Mobile) -->
        <div id="mobileIslandContainer" class="md:hidden absolute top-2 left-1/2 -translate-x-1/2 opacity-0 pointer-events-none scale-90 transition-all duration-400 ease-[cubic-bezier(0.16,1,0.3,1)]">
            <div id="mobileIslandPill" class="flex items-center gap-2.5 px-4 py-2 rounded-full text-xs font-semibold cursor-pointer select-none active:scale-95 transition-transform">
                <img alt="K-Suli" class="h-5 w-5 object-contain" src="{{ asset('images/k-suli-logo.svg') }}">
                <span class="font-bold text-slate-800">
                    @if(request()->routeIs('beranda')) Beranda
                    @elseif(request()->routeIs('penerjemah')) Penerjemah SIBI
                    @elseif(request()->routeIs('kamus')) Kamus SIBI
                    @elseif(request()->routeIs('quiz')) Kuis Praktik
                    @else K-Suli SIBI @endif
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span class="material-symbols-outlined text-sm text-slate-500">expand_more</span>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Stitch Optimized) -->
        <div id="mobileMenuDrawer" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-b border-slate-200 px-6 py-5 shadow-xl transition-all duration-300">
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('beranda') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('beranda') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-xl">home</span>
                    Beranda
                </a>
                <a href="{{ route('penerjemah') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('penerjemah') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-xl">videocam</span>
                    Penerjemah Kamera
                </a>
                <a href="{{ route('kamus') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('kamus') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-xl">menu_book</span>
                    Kamus SIBI
                </a>
                <a href="{{ route('quiz') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors {{ request()->routeIs('quiz') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-xl">quiz</span>
                    Kuis Praktik
                </a>

                <div class="pt-3 border-t border-slate-100 mt-1">
                    <a href="{{ route('quiz') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-full font-semibold text-sm shadow-sm block">
                        Mulai Belajar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow {{ request()->routeIs('beranda') ? 'pt-0 pb-16 px-3.5 sm:px-6 md:px-10 w-full' : (request()->routeIs('penerjemah') ? 'pt-2 pb-12 sm:pb-20 px-3.5 sm:px-6 lg:px-8 w-full max-w-[1360px] mx-auto' : (request()->routeIs('quiz') ? 'pt-3 sm:pt-5 pb-12 sm:pb-16 px-3.5 sm:px-6 md:px-8 w-full max-w-[1280px] mx-auto' : 'pt-6 pb-16 px-3.5 sm:px-6 md:px-10 w-full max-w-[1280px] mx-auto')) }}">
        @yield('content')
    </main>

    <!-- Shared Footer -->
    <footer class="w-full mt-auto border-t border-outline-variant/30 bg-surface-container-low text-on-surface">
        <div class="mx-auto max-w-[1280px] px-4 py-9 sm:px-6 sm:py-10 md:px-10 md:py-12">
            <div class="grid grid-cols-1 gap-x-8 gap-y-7 sm:grid-cols-2 lg:grid-cols-4 lg:gap-10">
                <div class="space-y-3">
                    <a href="{{ route('beranda') }}" class="inline-flex min-h-11 items-center gap-2.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                        <img src="{{ asset('images/k-suli-logo.svg') }}" alt="Logo K-Suli" class="h-8 w-8 object-contain">
                        <span class="text-base font-extrabold text-slate-900 tracking-tight">K-SULI</span>
                        <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-1.5 py-0.5 rounded leading-none border border-blue-100">SIBI</span>
                    </a>
                    <p class="max-w-xs text-sm leading-6 text-on-surface-variant">
                        Belajar bahasa isyarat SIBI melalui penerjemah visual, kamus kosakata, dan kuis.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="text-sm font-bold text-on-surface">Menu Cepat</h2>
                    <ul class="space-y-0 text-sm text-on-surface-variant">
                        <li><a href="{{ route('beranda') }}" class="inline-flex min-h-9 items-center hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Beranda</a></li>
                        <li><a href="{{ route('penerjemah') }}" class="inline-flex min-h-9 items-center hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Penerjemah Bahasa Isyarat</a></li>
                        <li><a href="{{ route('kamus') }}" class="inline-flex min-h-9 items-center hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Kamus Kosakata SIBI</a></li>
                        <li><a href="{{ route('quiz') }}" class="inline-flex min-h-9 items-center hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Kuis dan Latihan</a></li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <h2 class="text-sm font-bold text-on-surface">Kafe Kasuli</h2>
                    <p class="text-sm leading-6 text-on-surface-variant">
                        Kenali K-Suli dan semangat belajar bahasa isyarat melalui komunitas.
                    </p>
                    <a href="{{ route('beranda') }}#tentang-kasuli" class="inline-flex min-h-11 items-center gap-1 text-sm font-semibold text-primary hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                        Tentang K-Suli
                        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>

                <div class="space-y-3">
                    <h2 class="text-sm font-bold text-on-surface">Komitmen Visual</h2>
                    <ul class="space-y-2 text-sm leading-6 text-on-surface-variant">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined mt-0.5 shrink-0 text-base text-primary" aria-hidden="true">visibility</span>
                            <span>Materi belajar disajikan dengan dukungan visual.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined mt-0.5 shrink-0 text-base text-primary" aria-hidden="true">touch_app</span>
                            <span>Fitur dapat dibuka melalui tautan dan kontrol yang jelas.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-7 flex flex-col gap-2 border-t border-outline-variant/30 pt-4 text-xs text-on-surface-variant sm:mt-8 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                <span>&copy; {{ date('Y') }} K-Suli. Hak cipta dilindungi.</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-primary" aria-hidden="true">sign_language</span>
                    Belajar SIBI bersama K-Suli
                </span>
            </div>
        </div>
    </footer>

    <!-- Dynamic Island Navbar & Mobile Menu Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainHeader = document.getElementById('mainHeader');
            const toggleBtn = document.getElementById('mobileMenuToggle');
            const mobileIslandPill = document.getElementById('mobileIslandPill');
            const drawer = document.getElementById('mobileMenuDrawer');
            const openIcon = document.getElementById('menuOpenIcon');
            const closeIcon = document.getElementById('menuCloseIcon');

            // Scroll Handler for Dynamic Island Morph
            if (mainHeader) {
                let ticking = false;
                const updateHeader = () => {
                    if (window.scrollY > 40) {
                        mainHeader.classList.add('is-scrolled');
                    } else {
                        mainHeader.classList.remove('is-scrolled');
                    }
                    ticking = false;
                };

                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(updateHeader);
                        ticking = true;
                    }
                }, { passive: true });

                // Run on initial load
                updateHeader();
            }

            // Mobile Drawer Toggle
            const toggleDrawer = () => {
                if (!drawer) return;
                const isHidden = drawer.classList.contains('hidden');
                if (isHidden) {
                    drawer.classList.remove('hidden');
                    if (openIcon) openIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                } else {
                    drawer.classList.add('hidden');
                    if (openIcon) openIcon.classList.remove('hidden');
                    if (closeIcon) closeIcon.classList.add('hidden');
                }
            };

            if (toggleBtn) toggleBtn.addEventListener('click', toggleDrawer);
            if (mobileIslandPill) mobileIslandPill.addEventListener('click', toggleDrawer);
        });
    </script>
    @stack('scripts')
</body>

</html>