<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIBI Learn - Platform Belajar Bahasa Isyarat')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
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
                        "sans": ["Inter", "sans-serif"],
                        "heading": ["Outfit", "Inter", "sans-serif"],
                        "body-lg": ["Inter", "sans-serif"],
                        "display-lg-mobile": ["Inter", "sans-serif"],
                        "headline-md": ["Inter", "sans-serif"],
                        "label-sm": ["Inter", "sans-serif"],
                        "display-lg": ["Inter", "sans-serif"],
                        "body-md": ["Inter", "sans-serif"]
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
    </style>
    @stack('styles')
</head>

<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col antialiased">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-xl border-b border-white/40 shadow-[0_4px_24px_0_rgba(0,0,0,0.04)]">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 h-16 flex justify-between items-center">
            <!-- Brand Logo -->
            <a href="{{ route('beranda') }}" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-sm border border-primary/10 group-hover:scale-105 transition-transform">
                    <svg class="h-6 w-6 text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 8H19C20.1046 8 21 8.89543 21 10V11C21 12.1046 20.1046 13 19 13H18" stroke="#0058be" stroke-width="2" stroke-linecap="round" />
                        <path d="M6 8H18V16C18 17.6569 16.6569 19 15 19H9C7.34315 19 6 17.6569 6 16V8Z" stroke="#0058be" stroke-width="2" />
                        <path d="M9 3V5M12 2V5M15 3V5" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <span class="font-bold text-primary text-xl tracking-tight">K-Suli</span>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex gap-8 items-center">
                <a href="{{ route('beranda') }}"
                    class="{{ request()->routeIs('beranda') ? 'text-primary border-b-2 border-primary font-bold' : 'text-on-surface-variant hover:text-primary' }} pb-1 text-sm font-semibold transition-all duration-200">
                    Beranda
                </a>
                <a href="{{ route('penerjemah') }}"
                    class="{{ request()->routeIs('penerjemah') ? 'text-primary border-b-2 border-primary font-bold' : 'text-on-surface-variant hover:text-primary' }} pb-1 text-sm font-semibold transition-all duration-200">
                    Penerjemah
                </a>
                <a href="{{ route('kamus') }}"
                    class="{{ request()->routeIs('kamus') ? 'text-primary border-b-2 border-primary font-bold' : 'text-on-surface-variant hover:text-primary' }} pb-1 text-sm font-semibold transition-all duration-200">
                    Kamus
                </a>
                <a href="{{ route('quiz') }}"
                    class="{{ request()->routeIs('quiz') ? 'text-primary border-b-2 border-primary font-bold' : 'text-on-surface-variant hover:text-primary' }} pb-1 text-sm font-semibold transition-all duration-200">
                    Quiz
                </a>
            </div>

            <!-- Desktop Action Button -->
            <div class="hidden md:block">
                <a href="{{ route('quiz') }}" class="bg-primary text-on-primary text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-surface-tint active:scale-95 duration-150 ease-in-out transition-all shadow-md inline-block">
                    Mulai Belajar
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuToggle" type="button" class="p-2 rounded-xl text-on-surface hover:bg-surface-container-high focus:outline-none transition-colors" aria-label="Toggle navigation">
                    <span id="menuOpenIcon" class="material-symbols-outlined text-2xl">menu</span>
                    <span id="menuCloseIcon" class="material-symbols-outlined text-2xl hidden">close</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Optimized for mobile) -->
        <div id="mobileMenuDrawer" class="hidden md:hidden bg-surface/95 backdrop-blur-2xl border-b border-outline-variant/20 px-6 py-5 shadow-2xl transition-all duration-300">
            <div class="flex flex-col gap-3">
                <a href="{{ route('beranda') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ request()->routeIs('beranda') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
                    <span class="material-symbols-outlined text-xl">home</span>
                    Beranda
                </a>
                <a href="{{ route('penerjemah') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ request()->routeIs('penerjemah') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
                    <span class="material-symbols-outlined text-xl">videocam</span>
                    Penerjemah
                </a>
                <a href="{{ route('kamus') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ request()->routeIs('kamus') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
                    <span class="material-symbols-outlined text-xl">menu_book</span>
                    Kamus
                </a>
                <a href="{{ route('quiz') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ request()->routeIs('quiz') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
                    <span class="material-symbols-outlined text-xl">quiz</span>
                    Quiz
                </a>

                <div class="pt-2 border-t border-outline-variant/15 mt-1">
                    <a href="{{ route('quiz') }}" class="w-full bg-primary text-on-primary text-center py-3 rounded-full font-semibold text-sm shadow-md block">
                        Mulai Belajar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow pt-20 pb-16 px-4 md:px-10 max-w-[1280px] mx-auto w-full">
        @yield('content')
    </main>

    <!-- Shared Footer -->
    <footer class="w-full py-10 px-4 md:px-10 flex flex-col md:flex-row justify-between items-center gap-6 bg-surface-container-low border-t border-outline-variant/15 mt-auto">
        <div class="flex items-center gap-2">
            <svg class="h-6 w-6 text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8H19C20.1046 8 21 8.89543 21 10V11C21 12.1046 20.1046 13 19 13H18" stroke="#0058be" stroke-width="2" stroke-linecap="round" />
                <path d="M6 8H18V16C18 17.6569 16.6569 19 15 19H9C7.34315 19 6 17.6569 6 16V8Z" stroke="#0058be" stroke-width="2" />
                <path d="M9 3V5M12 2V5M15 3V5" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" />
            </svg>
            <span class="font-bold text-primary text-base">K-Suli</span>
        </div>
        <div class="flex flex-wrap justify-center gap-6 text-sm text-on-surface-variant">
            <a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
            <a class="hover:text-primary transition-colors" href="#">Syarat Layanan</a>
            <a class="hover:text-primary transition-colors" href="#">Bantuan</a>
        </div>
        <div class="text-xs text-on-surface-variant text-center md:text-right">
            © 2026 K-Suli. Aksesibilitas Untuk Semua.
        </div>
    </footer>

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('mobileMenuToggle');
            const drawer = document.getElementById('mobileMenuDrawer');
            const openIcon = document.getElementById('menuOpenIcon');
            const closeIcon = document.getElementById('menuCloseIcon');

            if (toggleBtn && drawer) {
                toggleBtn.addEventListener('click', () => {
                    const isHidden = drawer.classList.contains('hidden');
                    if (isHidden) {
                        drawer.classList.remove('hidden');
                        openIcon.classList.add('hidden');
                        closeIcon.classList.remove('hidden');
                    } else {
                        drawer.classList.add('hidden');
                        openIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>