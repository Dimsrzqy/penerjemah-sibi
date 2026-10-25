@extends('layouts.app')

@section('title', 'Kuis Praktik - Isyarat K-SULI')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
@endpush

@section('content')
<div class="max-w-6xl mx-auto w-full flex flex-col space-y-3 sm:space-y-4 px-1 sm:px-0">

    <!-- ======================================================== -->
    <!-- ALUR PENDAFTARAN & STEPPER (UKURAN KONSISTEN)            -->
    <!-- ======================================================== -->
    <div id="quizStepperWrapper" class="max-w-5xl mx-auto w-full flex flex-col gap-2.5 sm:gap-3">
        <!-- Breadcrumb Context Navigation -->
        <nav class="flex items-center gap-1.5 text-xs text-on-surface-variant font-medium pt-0.5">
            <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[15px]">home</span>
                <span>Beranda</span>
            </a>
            <span class="material-symbols-outlined text-[13px] text-outline-variant select-none">chevron_right</span>
            <span class="text-primary font-semibold">Kuis Praktik SIBI</span>
        </nav>

        <!-- Stepper Component Partial (Identical Across Steps) -->
        @include('partials.quiz-stepper')
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 1: INPUT NAMA GUEST (STITCH: IDENTITAS TAMU)        -->
    <!-- ======================================================== -->
    <div id="stepNameContainer" class="max-w-xl mx-auto w-full flex flex-col gap-3 sm:gap-3.5 pb-4 sm:pb-8">
        <!-- Main Central Focus Card (Stitch: Form Input Tamu) -->
        <section class="relative bg-white rounded-3xl shadow-[0_12px_40px_-8px_rgba(30,91,232,0.08),0_2px_10px_-1px_rgba(22,28,39,0.04)] border border-slate-200/80 overflow-hidden">
            <div class="p-6 sm:p-8 flex flex-col gap-5 w-full">
                
                <!-- Heading Info & Avatar in balanced layout -->
                <div class="flex flex-col items-center text-center gap-2.5">
                    <!-- Live Dynamic Avatar Preview -->
                    <div class="relative">
                        <div id="avatar-preview" class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center text-2xl font-extrabold transition-all duration-200 shadow-xs border-2 border-primary/20">
                            <span id="avatar-preview-char">T</span>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center shadow-xs" title="Siap Bermain">
                            <span class="material-symbols-outlined text-[12px] text-white">check</span>
                        </span>
                    </div>
                    
                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl text-on-surface font-extrabold tracking-tight">Mulai Kuis Praktik SIBI</h1>
                        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">Cukup masukkan nama panggilan untuk mencatat skormu.</p>
                    </div>
                </div>

                <!-- Form Input -->
                <form id="formGuestName" class="space-y-4 text-left pt-1">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs sm:text-sm font-bold text-on-surface" for="guestNameInput">Nama Panggilan</label>
                            <div class="flex items-center gap-1 text-xs text-on-surface-variant">
                                <span class="font-bold text-primary" id="char-counter">0</span>
                                <span>/</span>
                                <span>20</span>
                            </div>
                        </div>
                        <div class="relative flex items-center">
                            <div class="absolute left-4 pointer-events-none text-outline flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                            </div>
                            <input autofocus 
                                class="w-full h-12 sm:h-13 pl-12 pr-12 rounded-2xl bg-surface-container-low text-on-surface text-sm sm:text-base placeholder:text-outline/70 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 outline-none shadow-xs border border-slate-200/80 font-medium" 
                                id="guestNameInput" 
                                name="guest_name" 
                                maxlength="20" 
                                placeholder="Contoh: Budi" 
                                type="text" 
                                required>
                            <button aria-label="Hapus input" class="hidden absolute right-3.5 w-7 h-7 rounded-full bg-slate-200/70 text-slate-600 hover:text-slate-900 items-center justify-center transition-colors min-h-[32px] min-w-[32px]" id="clear-btn" type="button">
                                <span class="material-symbols-outlined text-[17px]">close</span>
                            </button>
                        </div>
                        <p id="guestNameError" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                        <p class="text-[11px] sm:text-xs text-on-surface-variant pl-0.5" id="input-helper">Nama akan dicantumkan di papan peringkat dan sertifikat latihan.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <a class="inline-flex items-center justify-center gap-1.5 text-xs sm:text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors py-2 px-3.5 rounded-xl hover:bg-surface-container order-2 sm:order-1 min-h-[44px]" href="{{ route('beranda') }}">
                            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                            <span>Kembali ke Beranda</span>
                        </a>
                        <button class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 sm:h-12 px-6 sm:px-7 rounded-2xl bg-primary text-on-primary font-bold text-xs sm:text-sm shadow-md hover:shadow-lg hover:bg-blue-700 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all duration-200 order-1 sm:order-2 min-h-[44px]" id="btnSubmitName" type="submit">
                            <span>Lanjut Pilih Level</span>
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">arrow_forward</span>
                        </button>
                    </div>
                </form>

            </div>
        </section>
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 2: PILIH TINGKAT KESULITAN (STITCH: PILIH LEVEL)     -->
    <!-- ======================================================== -->
    <div id="stepDifficultyContainer" class="hidden max-w-5xl mx-auto w-full pt-0 pb-6 px-1 sm:px-0 space-y-5 sm:space-y-6">

        <!-- Contextual Greeting & Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-2.5 border-b border-slate-200/80 pb-3 pt-0.5">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-sm text-primary font-bold tracking-wide">Halo, <strong id="displayNameBadge">-</strong></span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs sm:text-sm text-slate-500 font-medium">Kedai Susu Tuli</span>
                    <button type="button" id="btnSwitchPlayer" class="ml-2 text-xs text-slate-500 hover:text-primary transition-colors underline font-medium">
                        Ganti Pemain
                    </button>
                </div>
                <h2 class="text-2xl sm:text-3xl text-on-surface tracking-tight font-extrabold">Pilih Tingkat Kesulitan</h2>
            </div>
            <span class="text-xs text-on-surface-variant font-medium">Tantangan dapat disesuaikan kembali sebelum kamera aktif</span>
        </div>

        <!-- 3 Level Cards (Interactive Grid Stitch Design) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6 items-stretch">

            <!-- Level 1: Mudah -->
            <div class="difficulty-card group relative flex flex-col justify-between rounded-3xl bg-white p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-200 cursor-pointer border-2 border-slate-200/80 hover:border-primary/50" data-difficulty="mudah" id="card-mudah">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors shadow-2xs">
                            <span class="material-symbols-outlined text-[26px]">sentiment_satisfied</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-primary border border-slate-200 font-bold text-xs">
                            <span class="material-symbols-outlined text-[17px] text-amber-500">stars</span>
                            <span>100 Poin</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-on-surface group-hover:text-primary transition-colors">Level Mudah</h3>
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[11px] font-semibold text-slate-600">Kata Tunggal</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Peragakan 1 kata dasar isyarat secara tepat di depan kamera.</p>
                    </div>
                    <div class="flex flex-col gap-2 py-3 border-y border-slate-100 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Format</span>
                            <span class="text-slate-800 font-bold">1 Kata (10 Soal)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Fokus</span>
                            <span class="text-slate-800 font-bold">Kosakata Dasar</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Contoh Kata:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">SAYA</span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">KAMU</span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">IBU</span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">SENANG</span>
                        </div>
                    </div>
                </div>
                <div class="pt-5">
                    <button class="level-select-btn w-full h-11 rounded-xl bg-slate-100 text-slate-800 font-semibold text-xs flex items-center justify-center gap-2 group-hover:bg-primary group-hover:text-white transition-colors" type="button">
                        <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                        <span>Pilih Level Mudah</span>
                    </button>
                </div>
            </div>

            <!-- Level 2: Sedang (Default Terpilih / Populer) -->
            <div class="difficulty-card group relative flex flex-col justify-between rounded-3xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 cursor-pointer border-2 border-primary" data-difficulty="sedang" id="card-sedang">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[26px]">local_cafe</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-bold text-xs shadow-2xs">
                            <span class="material-symbols-outlined text-[17px] text-amber-500" style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                            <span>250 Poin</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-primary">Level Sedang</h3>
                            <span class="px-2 py-0.5 rounded-md bg-primary text-white text-[11px] font-bold">Populer • 2 Kata</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Rangkai 2 kata berurutan untuk melatih kelancaran transisi gestur.</p>
                    </div>
                    <div class="flex flex-col gap-2 py-3 border-y border-slate-100 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Format</span>
                            <span class="text-slate-800 font-bold">Frasa (10 Soal)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Fokus</span>
                            <span class="text-slate-800 font-bold">Transisi Gerakan</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Contoh Frasa:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">TERIMA KASIH</span>
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">APA KABAR</span>
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">SELAMAT PAGI</span>
                        </div>
                    </div>
                </div>
                <div class="pt-5">
                    <button class="level-select-btn w-full h-11 rounded-xl bg-primary text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-all" type="button">
                        <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                        <span>Pilih Level Sedang</span>
                    </button>
                </div>
            </div>

            <!-- Level 3: Susah -->
            <div class="difficulty-card group relative flex flex-col justify-between rounded-3xl bg-white p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-200 cursor-pointer border-2 border-slate-200/80 hover:border-amber-400" data-difficulty="susah" id="card-susah">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors shadow-2xs">
                            <span class="material-symbols-outlined text-[26px]">bolt</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100/70 text-amber-800 border border-amber-200 font-bold text-xs shadow-2xs">
                            <span class="material-symbols-outlined text-[17px] text-amber-600" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                            <span>500 Poin</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-on-surface group-hover:text-amber-700 transition-colors">Level Sulit</h3>
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-[11px] font-semibold text-amber-800">3–4 Kata (SPOK)</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Peragakan kalimat lengkap berpola SPOK secara runtut dan fasih.</p>
                    </div>
                    <div class="flex flex-col gap-2 py-3 border-y border-slate-100 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Format</span>
                            <span class="text-slate-800 font-bold">Kalimat SPOK (10 Soal)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Fokus</span>
                            <span class="text-slate-800 font-bold">Rangkaian SPOK</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Contoh Kalimat:</span>
                        <div class="flex flex-col gap-1.5">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold truncate">SAYA KULIAH DARI PAGI</span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold truncate">AYAH PULANG DARI KAMPUS</span>
                        </div>
                    </div>
                </div>
                <div class="pt-5">
                    <button class="level-select-btn w-full h-11 rounded-xl bg-slate-100 text-slate-800 font-semibold text-xs flex items-center justify-center gap-2 group-hover:bg-amber-600 group-hover:text-white transition-colors" type="button">
                        <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                        <span>Pilih Level Sulit</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Practical Rules Preview Summary Box (Stitch) -->
        <div class="w-full rounded-2xl bg-white border border-slate-200/80 px-4 py-3 sm:px-5 sm:py-3.5 flex flex-col sm:flex-row items-center justify-between gap-3 text-slate-600 shadow-xs">
            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <span class="material-symbols-outlined text-[20px] text-primary shrink-0">info</span>
                <span>Pastikan pencahayaan terang dan kedua tangan leluasa di depan webcam kuis.</span>
            </div>
            <span class="text-xs text-primary font-bold shrink-0">Sistem Penilaian AI: 100 - 500 Poin</span>
        </div>

        <!-- Footer Back Navigation -->
        <div class="text-center pt-1">
            <button type="button" id="btnBackToName" class="text-xs font-semibold text-slate-600 hover:text-primary transition-colors inline-flex items-center justify-center gap-1.5 px-5 py-2.5 min-h-[44px] rounded-full bg-white hover:bg-slate-50 border border-slate-200/80 shadow-xs focus:outline-none">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Kembali ke Pendaftaran Nama</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 3: ARENA KUIS BERBASIS KAMERA MEDIAPIPE            -->
    <!-- ======================================================== -->
    <div id="stepQuizArenaContainer" class="hidden flex-col space-y-2.5 sm:space-y-3">
        <!-- Header Info Bar (Mirip Penerjemah Header) -->
        <div class="flex flex-row items-center justify-between gap-2 bg-surface-container-low py-2 px-3 sm:px-4 rounded-xl sm:rounded-2xl border border-outline-variant/20 shadow-xs">
            <div class="flex items-center gap-2.5">
                <div id="playerAvatarLetter" class="w-8 h-8 rounded-xl bg-primary text-on-primary flex items-center justify-center font-black text-xs shadow-xs shrink-0">
                    G
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Pemain:</span>
                        <strong id="playerActiveName" class="text-xs sm:text-sm font-extrabold text-on-surface">Guest</strong>
                        <span id="activeDifficultyBadge" class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20">
                            Mudah
                        </span>
                    </div>
                    <p class="hidden sm:block text-[11px] text-on-surface-variant leading-none mt-0.5">
                        Tirukan bahasa isyarat SIBI sesuai tantangan soal yang ditampilkan
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <!-- Score Capsule -->
                <div class="flex items-center gap-1.5 bg-surface-container-lowest px-2.5 py-1 rounded-lg border border-outline-variant/20 shadow-xs">
                    <span class="material-symbols-outlined text-amber-500 text-base">stars</span>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span id="quizScoreText" class="text-xs sm:text-sm font-black text-primary">0</span>
                            <span id="quizMaxScoreText" class="text-[10px] font-bold text-on-surface-variant">/ 100 pts</span>
                        </div>
                        <span id="pointsPerQBadge" class="text-[8px] text-emerald-600 font-bold block leading-none">+10 pts / soal</span>
                    </div>
                </div>

                <!-- Progress Soal Capsule -->
                <div class="flex items-center gap-1.5 bg-surface-container-lowest px-2.5 py-1 rounded-lg border border-outline-variant/20 shadow-xs">
                    <span class="material-symbols-outlined text-primary text-base">quiz</span>
                    <div>
                        <span id="questionProgressText" class="text-xs font-black text-on-surface leading-none block">
                            Soal <span id="currentQNum">1</span> / <span id="totalQNum">5</span>
                        </span>
                        <div class="w-14 h-1 bg-surface-container-highest rounded-full overflow-hidden mt-1">
                            <div id="progressBarFill" class="h-full bg-primary w-[20%] rounded-full transition-all duration-300"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace Grid (Mirip Penerjemah: 7 Cols Kiri, 5 Cols Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 items-start">
            <!-- Left Column: Camera Feed & Controls (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col space-y-2.5">
                <!-- Viewport Container with macOS Header (Stitch Screen 746b1218917c45429b05ee1825fa5c20) -->
                <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
                    <!-- macOS Window Header -->
                    <div class="px-3.5 py-2 bg-slate-100 border-b border-slate-200 flex items-center justify-between select-none">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#ff5f56] shadow-xs"></span>
                            <span class="w-3 h-3 rounded-full bg-[#febd2e] shadow-xs"></span>
                            <span class="w-3 h-3 rounded-full bg-[#27c93f] shadow-xs"></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-semibold text-slate-600">Sensor Kamera & AI SIBI</span>
                        </div>
                        <div class="flex items-center gap-1 text-slate-500 text-xs font-semibold">
                            <span class="material-symbols-outlined text-sm">videocam</span>
                            <span>Live</span>
                        </div>
                    </div>

                    <!-- Live Viewport Frame -->
                    <div class="relative w-full aspect-video overflow-hidden bg-slate-950 flex items-center justify-center">
                    <!-- Live Video Element -->
                    <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden mirror-mode" autoplay playsinline muted></video>

                    <!-- Skeleton Canvas Overlay -->
                    <canvas id="skeletonCanvas" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden mirror-mode z-10"></canvas>

                    <!-- Standby Background Image -->
                    <img id="standbyImg" class="absolute inset-0 w-full h-full object-cover opacity-30 transition-opacity duration-300"
                        src="{{ asset('images/laptop-practice.jpg') }}"
                        alt="Camera Standby Background">

                    <!-- Standby Status UI Card -->
                    <div id="standbyOverlay" class="z-20 flex flex-col items-center gap-2 text-center px-4 max-w-sm">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner">
                            <span class="material-symbols-outlined text-2xl text-white opacity-90">videocam_off</span>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-white">Kamera Belum Aktif</h3>
                        <p class="text-xs text-gray-200 leading-tight">
                            Klik tombol <strong>"Nyalakan Kamera"</strong> di bawah untuk mendeteksi isyarat SIBI Anda.
                        </p>
                        <button id="btnQuickStart" type="button" class="mt-0.5 bg-primary hover:bg-surface-tint text-on-primary font-bold text-xs px-4 py-2 rounded-full shadow-md flex items-center gap-1.5 transition-transform active:scale-95 min-h-[38px]">
                            <span class="material-symbols-outlined text-sm">play_arrow</span>
                            <span>Aktifkan Kamera Sekarang</span>
                        </button>
                    </div>

                    <!-- Active Camera HUD Overlay -->
                    <div id="cameraHud" class="hidden absolute inset-0 pointer-events-none p-3 sm:p-4 flex flex-col justify-between z-20">
                        <!-- Top HUD Bar -->
                        <div class="flex flex-wrap justify-between items-center gap-2">
                            <div class="flex items-center gap-1.5">
                                <div class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-full flex items-center gap-1.5 border border-white/15">
                                    <span id="hudStatusDot" class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span id="hudStatusText" class="text-[11px] sm:text-xs font-semibold text-white tracking-wide">MediaPipe Hands</span>
                                </div>

                                <div id="aiModelBadge" class="bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full flex items-center gap-1.5 border border-white/15 text-[11px] sm:text-xs font-semibold text-white">
                                    <span id="aiModelDot" class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                    <span id="aiModelStatus">Memuat TFJS...</span>
                                </div>
                            </div>

                            <div class="bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/15 text-[11px] sm:text-xs font-semibold text-sky-300 flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">front_hand</span>
                                <span id="handCountText">0 Tangan</span>
                            </div>
                        </div>

                        <!-- Target Focus Frame Guide (Mirip Penerjemah) -->
                        <div class="relative w-3/4 h-3/4 sm:w-3/5 sm:h-3/5 mx-auto border-2 border-dashed border-primary/40 rounded-2xl flex items-center justify-center bg-primary/5 pointer-events-none">
                            <div class="absolute -top-2.5 px-2.5 py-0.5 bg-primary/90 text-on-primary text-[9px] sm:text-[10px] font-bold tracking-wider uppercase rounded-full shadow">
                                Area Peragaan
                            </div>
                        </div>

                        <!-- Bottom HUD Hint -->
                        <div class="text-center">
                            <span id="bottomHintText" class="text-[10px] sm:text-[11px] text-white/90 bg-black/60 px-3 py-1 rounded-full backdrop-blur-md border border-white/10 inline-block">
                                Arahkan tangan ke kamera dan peragakan kata yang disorot
                            </span>
                        </div>
                    </div>

                    <!-- Success Toast -->
                    <div id="successNotice" class="hidden absolute top-4 left-1/2 -translate-x-1/2 bg-emerald-600 text-white px-5 py-2 rounded-full font-extrabold text-xs sm:text-sm shadow-2xl flex items-center gap-1.5 animate-bounce z-30">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span id="successNoticeText">Gerakan Tepat! Terverifikasi Model AI</span>
                    </div>

                    <!-- Timeout Toast -->
                    <div id="timeoutNotice" class="hidden absolute top-4 left-1/2 -translate-x-1/2 bg-rose-600 text-white px-5 py-2 rounded-full font-extrabold text-xs sm:text-sm shadow-2xl flex items-center gap-1.5 animate-bounce z-30">
                        <span class="material-symbols-outlined text-base">timer_off</span>
                        <span id="timeoutNoticeText">Waktu Habis! Soal ini bernilai 0 Poin</span>
                    </div>
                </div>
            </div>

                <!-- Controls Bar (Mirip Penerjemah) -->
                <div class="flex flex-wrap items-center justify-between gap-2 bg-surface-container-low py-1.5 px-2.5 sm:px-3 rounded-xl border border-outline-variant/20 shadow-xs">
                    <div class="flex items-center gap-1.5">
                        <button id="btnStartCam" type="button" class="flex items-center justify-center gap-1.5 bg-primary text-on-primary px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-xs min-h-[36px]">
                            <span class="material-symbols-outlined text-base">videocam</span>
                            <span>Mulai Kamera</span>
                        </button>
                        <button id="btnStopCam" type="button" class="hidden items-center justify-center gap-1.5 bg-error text-on-error px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-700 active:scale-95 transition-all shadow-xs min-h-[36px]">
                            <span class="material-symbols-outlined text-base">videocam_off</span>
                            <span>Hentikan</span>
                        </button>
                        <button id="btnToggleMirror" type="button" class="bg-surface-container hover:bg-surface-container-high text-on-surface w-9 h-9 min-h-[36px] rounded-lg flex items-center justify-center transition-all" title="Mirror Kamera">
                            <span class="material-symbols-outlined text-base">flip</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <!-- Fallback Verification Button -->
                        <button id="btnSimulateMatch" type="button" class="bg-surface-container-highest hover:bg-surface-dim text-primary text-xs font-bold px-3 py-1.5 rounded-lg border border-primary/20 transition-all active:scale-95 flex items-center gap-1 min-h-[36px]" title="Verifikasi peragaan kata saat ini jika deteksi otomatis lambat">
                            <span class="material-symbols-outlined text-sm text-emerald-500">task_alt</span>
                            <span>Verifikasi Isyarat</span>
                        </button>

                        <button id="btnNextQuestion" type="button" class="hidden items-center gap-1 bg-primary text-on-primary px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-xs min-h-[36px]">
                            <span>Soal Selanjutnya</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Target Words Challenge & AI Detection (5 Cols - Stitch Screen 746b1218917c45429b05ee1825fa5c20) -->
            <div class="lg:col-span-5 flex flex-col space-y-2.5">
                <!-- Card 1: MAIN PROMPT CARD (Stitch: Challenged Word) -->
                <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm p-3 sm:p-3.5 relative overflow-hidden space-y-2">
                    <div class="flex items-center justify-between gap-1.5 flex-wrap">
                        <span id="targetPromptBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span>
                            <span>Soal <span id="currentQNum">1</span> dari <span id="totalQNum">5</span></span>
                        </span>

                        <div class="flex items-center gap-1.5">
                            <!-- Points Per Question Badge -->
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold">
                                <span class="material-symbols-outlined text-xs text-amber-500">stars</span>
                                <span id="pointsPerQBadge">+10 Pts</span>
                            </span>

                            <!-- 10-Second Timer -->
                            <div id="questionTimerContainer" class="flex items-center gap-1 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-200 shadow-xs transition-all">
                                <div id="timerIconWrapper" class="w-4 h-4 rounded bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[13px]">timer</span>
                                </div>
                                <span id="questionTimerText" class="text-xs font-black text-slate-800 tracking-tight min-w-[18px]">10s</span>
                                <div class="w-10 sm:w-12 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div id="questionTimerBar" class="h-full bg-blue-600 w-full rounded-full transition-all"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prominent Central Prompt Area (Stitch Screen 746b1218917c45429b05ee1825fa5c20) -->
                    <div class="text-center py-2.5 px-3 bg-slate-50 rounded-xl border border-slate-100 flex flex-col items-center justify-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Peragakan Gerakan:</span>
                        <h1 id="targetWordTitle" class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight leading-none uppercase">
                            -
                        </h1>
                        <div class="mt-1 mb-1">
                            <span id="targetWordSyllable" class="inline-block text-[11px] font-semibold text-slate-500 bg-slate-200/60 px-2.5 py-0.5 rounded-full border border-slate-200 font-mono tracking-tight">
                                -
                            </span>
                        </div>
                        <!-- Words List Chips Container (for sequences / SPOK) -->
                        <div id="targetWordsContainer" class="flex flex-wrap items-center justify-center gap-1.5 pt-1">
                            <!-- Injected dynamically via JS -->
                        </div>
                    </div>

                    <!-- Hidden redundant subtitle to keep card clean and uncluttered -->
                    <p id="targetPromptSubtitle" class="hidden text-[11px] text-slate-500 font-medium leading-tight text-center">
                        Peragakan isyarat di depan kamera sebelum waktu 10 detik habis.
                    </p>

                    <!-- Stitch Hint Collapsible Section -->
                    <div class="flex flex-col gap-1.5 pt-0.5 border-t border-slate-100">
                        <button type="button" id="hint-toggle-btn" class="w-full py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer shadow-2xs">
                            <span class="material-symbols-outlined text-[17px] text-amber-500">lightbulb</span>
                            <span id="hint-btn-label">Lihat Petunjuk Isyarat SIBI</span>
                            <span class="material-symbols-outlined text-sm text-slate-400">expand_more</span>
                        </button>
                        <div id="hint-container" class="hidden p-2.5 bg-blue-50/60 border border-blue-200/70 rounded-xl flex items-start gap-2.5 transition-all">
                            <div class="w-7 h-7 rounded-lg bg-blue-600/10 text-blue-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-base">sign_language</span>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-[11px] font-bold text-slate-800">Panduan Gerakan:</span>
                                <p id="hint-text" class="text-[11px] text-slate-600 leading-tight mt-0.5">
                                    Posisikan tangan sejajar dada atau dagu di depan kamera. Lakukan gestur isyarat dengan mantap dan tahan sejenak hingga verifikasi terdeteksi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hold/Dwell Progress Bar (Subtle inside Card 1) -->
                    <div id="holdProgressWrapper" class="hidden w-full pt-0.5">
                        <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 mb-0.5">
                            <span>Menahan Posisi Isyarat:</span>
                            <span id="verificationStatusText" class="text-emerald-600 font-bold">0.0s / 1.0s</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden border border-slate-200/60">
                            <div id="holdProgressBar" class="bg-emerald-500 h-1.5 rounded-full transition-all duration-75" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: UPCOMING QUESTION QUEUE (Stitch: Daftar Soal) -->
                <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm p-3 sm:p-3.5 flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-slate-600 text-[18px]">format_list_numbered</span>
                            <span class="text-xs font-bold text-slate-900 uppercase tracking-wide">Daftar Soal</span>
                        </div>
                        <span id="queueDifficultyLabel" class="text-[10px] font-bold text-blue-700 px-2 py-0.5 rounded-full bg-blue-50 border border-blue-200">
                            Tingkat Mudah
                        </span>
                    </div>
                    <!-- Dynamic Question Queue -->
                    <div id="quizQuestionQueue" class="flex flex-col gap-1.5 max-h-[140px] sm:max-h-[170px] overflow-y-auto pr-1">
                        <!-- Populated dynamically via quiz.js -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 4: RANGKUMAN HASIL KUIS & PENYIMPANAN SKOR         -->
    <!-- ======================================================== -->
    <div id="stepSummaryContainer" class="hidden max-w-xl mx-auto w-full py-6 sm:py-10">
        <div class="bg-surface-container rounded-3xl p-6 sm:p-10 shadow-2xl border border-white/60 space-y-6 text-center">
            <!-- Trophy Badge -->
            <div class="w-20 h-20 rounded-3xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto shadow-inner border border-amber-500/20">
                <span class="material-symbols-outlined text-5xl gold-rank">emoji_events</span>
            </div>

            <div class="space-y-2">
                <span class="inline-block text-xs font-bold px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600">
                    Kuis Selesai!
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-on-surface">
                    Hebat, <span id="summaryGuestName">Pemain</span>!
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    Anda telah menyelesaikan seluruh tantangan kuis SIBI tingkat <strong id="summaryDifficultyText">Mudah</strong>.
                </p>
            </div>

            <!-- Score Card Box -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/20 shadow-sm space-y-1">
                <span class="text-xs text-on-surface-variant font-medium block">Total Perolehan Skor:</span>
                <div class="flex items-baseline justify-center gap-1.5">
                    <span id="finalScoreDisplay" class="text-4xl sm:text-5xl font-extrabold text-primary tracking-tight">
                        0
                    </span>
                    <span id="finalMaxScoreDisplay" class="text-sm sm:text-base font-bold text-on-surface-variant">
                        / 100 pts
                    </span>
                </div>
            </div>

            <!-- Answer Breakdown Stats (Benar vs Salah) -->
            <div class="grid grid-cols-2 gap-3 pt-1">
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-3.5 flex flex-col items-center">
                    <div class="flex items-center gap-1.5 text-emerald-600 font-bold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        <span>Benar</span>
                    </div>
                    <span id="summaryCorrectCount" class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">0</span>
                    <span class="text-[10px] text-emerald-700/80 font-medium">Soal Selesai</span>
                </div>
                <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-3.5 flex flex-col items-center">
                    <div class="flex items-center gap-1.5 text-rose-600 font-bold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-sm">cancel</span>
                        <span>Salah</span>
                    </div>
                    <span id="summaryWrongCount" class="text-2xl sm:text-3xl font-black text-rose-600 mt-1">0</span>
                    <span class="text-[10px] text-rose-700/80 font-medium">Waktu Habis (0 Pts)</span>
                </div>
            </div>

            <div id="scoreSavedNotification" class="text-xs text-emerald-600 font-semibold flex items-center justify-center gap-1.5 bg-emerald-500/10 py-2 rounded-xl">
                <span class="material-symbols-outlined text-base">cloud_done</span>
                <span>Skor telah berhasil disimpan ke tabel quiz_scores!</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="button" id="btnPlayAgain" class="flex-1 bg-surface-container-highest text-primary font-bold py-3 px-5 rounded-2xl hover:bg-surface-dim transition-all active:scale-95 border border-primary/20 text-xs sm:text-sm flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-base">replay</span>
                    <span>Main Lagi (<span id="summaryBtnName">Sebagai Tamu</span>)</span>
                </button>
                <a href="{{ route('beranda') }}" class="flex-1 bg-primary text-on-primary font-bold py-3 px-5 rounded-2xl hover:bg-surface-tint transition-all active:scale-95 shadow-md text-xs sm:text-sm inline-flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-base">leaderboard</span>
                    <span>Lihat Papan Peringkat</span>
                </a>
            </div>

            <div class="pt-2">
                <button type="button" id="btnSwitchUserSummary" class="text-xs text-on-surface-variant hover:text-red-500 font-semibold transition-colors inline-flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    <span>Bukan Anda? Ganti Pemain Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL SESI KEDALUWARSA / INACTIVITY TIMEOUT             -->
    <!-- ======================================================== -->
    <div id="inactivityModal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="bg-surface-container rounded-3xl max-w-md w-full p-6 sm:p-8 border border-outline-variant/30 shadow-2xl space-y-5 text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto shadow-inner border border-amber-500/20">
                <span class="material-symbols-outlined text-4xl">timer_off</span>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold text-on-surface">Sesi Kuis Berakhir</h3>
                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    Sistem tidak mendeteksi adanya pergerakan atau interaksi selama lebih dari 3 menit. Demi ketertiban giliran dan keakuratan skor, silakan masukkan nama Anda kembali.
                </p>
            </div>
            <button id="btnInactivityAcknowledge" type="button" class="w-full bg-primary text-on-primary font-bold py-3.5 px-6 rounded-2xl hover:bg-surface-tint active:scale-[0.98] transition-all text-xs sm:text-sm shadow-lg flex items-center justify-center gap-2">
                <span>Masukkan Nama Pengguna Kembali</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL PENGATURAN MODEL DEEP LEARNING (TFJS)             -->
    <!-- ======================================================== -->
    <div id="settingsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-3xl max-w-lg w-full p-6 border border-outline-variant/30 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-outline-variant/15 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">tune</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-on-surface text-base">Pengaturan Model TensorFlow.js</h3>
                        <p class="text-xs text-on-surface-variant">Konfigurasi input fitur landmark & model Deep Learning</p>
                    </div>
                </div>
                <button id="btnCloseSettings" type="button" class="p-1.5 rounded-full hover:bg-surface-container text-on-surface-variant">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Pilihan Dimensi Fitur Landmark -->
                <div class="space-y-1.5">
                    <label class="font-bold text-on-surface block">Bentuk Fitur Input Saat Training (Landmark Shape):</label>
                    <select id="landmarkDimSelect" class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary">
                        <option value="126" selected>21 Titik x, y, z (126 Fitur 2 Tangan) [Sesuai Model TFJS]</option>
                        <option value="63">21 Titik x, y, z (63 Fitur 1 Tangan)</option>
                        <option value="42">21 Titik x, y (42 Fitur 1 Tangan)</option>
                        <option value="normalized_wrist">Normalisasi Relatif ke Pergelangan Tangan (Wrist)</option>
                    </select>
                </div>

                <!-- Path Model Lokal -->
                <div class="space-y-1.5">
                    <label class="font-bold text-on-surface block">Lokasi File Model TFJS (model.json):</label>
                    <input id="modelUrlInput" type="text"
                        value="/models/tfjs_model/model.json"
                        class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">
                    <p class="text-[11px] text-on-surface-variant">
                        File model aktif di <code>public/models/tfjs_model/model.json</code>.
                    </p>
                </div>

                <!-- Daftar Label Kelas -->
                <div class="space-y-1.5">
                    <label class="font-bold text-on-surface block">Daftar Label / Kelas SIBI (Pisahkan dengan koma):</label>
                    <textarea id="classesInput" rows="3" class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">ADIK, APA, AYAH, BAIK, BERAPA, BERTEMU, CANTIK, DARI, DIA, DIMANA, GANTENG, GEMUK, HALLO, HOBI, IBU, JUMAT, JURUSAN, KABAR, KAKEK, KALIAN, KAMI, KAMIS, KAMPUS, KAMU, KELAS, KELUARGA, KEMANA, KENAPA, KITA, KULIAH, KURUS, LUCU, MALAM, MAU, MEREKA, MINGGU, NAMA, PAGI, PELIT, PENDIDIKAN, PINTAR, PULANG, RABU, SABAR, SABTU, SAKIT, SAMPAI JUMPA, SAYA, SEKOLAH, SELAMAT, SELASA, SEMESTER, SENANG, SENIN, SIANG, SIAPA, SORE, TERIMAKASIH, TINGGAL, UMUR</textarea>
                </div>

                <!-- Threshold Slider -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="font-bold text-on-surface">Ambang Batas Keyakinan (Threshold):</label>
                        <span id="thresholdVal" class="font-bold text-primary">70%</span>
                    </div>
                    <input id="thresholdSlider" type="range" min="30" max="95" value="70" class="w-full accent-primary">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-outline-variant/15">
                <button id="btnCancelSettings" type="button" class="px-4 py-2 rounded-xl text-xs font-semibold hover:bg-surface-container text-on-surface">
                    Batal
                </button>
                <button id="btnSaveSettings" type="button" class="px-5 py-2 rounded-xl text-xs font-bold bg-primary text-on-primary hover:bg-surface-tint shadow-sm">
                    Terapkan & Muat Ulang Model
                </button>
            </div>
        </div>
    </div>

    <!-- Quiz Configuration Data JSON -->
    <script id="quiz-config" type="application/json">
    {
        "routes": {
            "guest": "{{ route('quiz.guest') }}",
            "questions": "{{ url('/quiz/questions') }}",
            "score": "{{ route('quiz.score') }}",
            "resetSession": "{{ route('quiz.reset-session') }}"
        }
    }
    </script>

    <!-- Session Guest Data JSON -->
    <script id="session-guest-data" type="application/json">
    {!! json_encode($sessionGuest ?? null) !!}
    </script>

</div>
@endsection

@push('scripts')
<!-- TensorFlow.js Library -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.18.0/dist/tf.min.js"></script>

<!-- MediaPipe Hands & Drawing Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>

<script src="{{ asset('js/quiz.js') }}"></script>
@endpush