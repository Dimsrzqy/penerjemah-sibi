@extends('layouts.app')

@section('title', 'Kuis Interaktif Bahasa Isyarat SIBI - SIBI Learn')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
@endpush

@section('content')
<div class="max-w-5xl mx-auto flex flex-col space-y-6 mt-2 md:mt-4">

    <!-- ======================================================== -->
    <!-- TAHAP 1: INPUT NAMA GUEST                                -->
    <!-- ======================================================== -->
    <div id="stepNameContainer" class="max-w-xl mx-auto w-full py-6 sm:py-10">
        <div class="bg-surface-container rounded-3xl p-6 sm:p-10 shadow-2xl border border-white/60 space-y-6 text-center">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto shadow-inner">
                <span class="material-symbols-outlined text-3xl">sports_esports</span>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface">Mulai Kuis SIBI</h1>
                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    Masukkan nama Anda terlebih dahulu. Setiap sesi bermain akan dicatat secara tersendiri di tabel papan peringkat.
                </p>
            </div>

            <form id="formGuestName" class="space-y-4 text-left">
                <div>
                    <label for="guestNameInput" class="block text-xs sm:text-sm font-bold text-on-surface mb-2">
                        Nama Pengguna:
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl pointer-events-none">
                            person
                        </span>
                        <input type="text" id="guestNameInput" name="guest_name" required maxlength="100"
                            placeholder="Contoh: Dimas, Budi, atau Rizky"
                            class="w-full pl-12 pr-4 py-3.5 bg-surface-container-lowest text-on-surface rounded-2xl border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm font-medium transition-all shadow-inner">
                    </div>
                    <p id="guestNameError" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmitName" class="w-full bg-primary text-on-primary font-bold py-3.5 px-6 rounded-2xl hover:bg-surface-tint active:scale-[0.98] transition-all duration-150 shadow-lg flex items-center justify-center gap-2">
                        <span>Lanjutkan ke Pilihan Tingkat</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </div>
            </form>

            <div class="pt-2 text-center">
                <a href="{{ route('beranda') }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors font-medium inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 2: PILIH TINGKAT KESULITAN                         -->
    <!-- ======================================================== -->
    <div id="stepDifficultyContainer" class="hidden max-w-4xl mx-auto w-full py-4 sm:py-6 space-y-8">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-surface-container border border-outline-variant/30 text-xs font-semibold">
                <span class="flex items-center gap-1.5 text-primary font-bold">
                    <span class="material-symbols-outlined text-base">account_circle</span>
                    <span>Pemain: <strong id="displayNameBadge">-</strong></span>
                </span>
                <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                <button type="button" id="btnSwitchPlayer" class="text-on-surface-variant hover:text-red-500 transition-colors flex items-center gap-1 text-[11px] underline">
                    Ganti Pemain
                </button>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-on-surface">Pilih Tingkat Kesulitan</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto">
                Tiap tingkatan memiliki batas maksimal skor kuis. Poin per soal dibagi secara proporsional sesuai jumlah soal yang tersedia.
            </p>
        </div>

        <!-- 3 Kartu Tingkat Kesulitan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
            <!-- 1. Tingkat Mudah -->
            <div class="difficulty-card group bg-surface-container rounded-3xl p-6 shadow-md hover:shadow-xl border-2 border-transparent hover:border-emerald-500 cursor-pointer transition-all duration-200 flex flex-col justify-between"
                data-difficulty="mudah">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xl shadow-sm">
                        <span class="material-symbols-outlined text-2xl">eco</span>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="inline-block text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600">
                                1 Kata / Soal
                            </span>
                            <span class="text-[11px] font-extrabold text-emerald-600 bg-emerald-500/10 px-2 py-0.5 rounded-md">
                                Maks. 100 Poin
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 pt-0.5">
                            <span class="text-[10px] font-bold text-on-surface-variant bg-surface-container-highest px-2 py-0.5 rounded-full">⏱️ 10 Detik / Soal</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-on-surface pt-1">Tingkat Mudah</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Hanya menampilkan <strong>1 kata kosakata dasar</strong> pada setiap soal (contoh: <em>MAKAN</em>, <em>RUMAH</em>). Cocok untuk pemula.
                    </p>
                </div>
                <div class="pt-6 border-t border-outline-variant/15 mt-6 flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-600">Maks. 100 Poin Total</span>
                    <button type="button" class="bg-emerald-600 group-hover:bg-emerald-500 text-white p-2 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                    </button>
                </div>
            </div>

            <!-- 2. Tingkat Sedang -->
            <div class="difficulty-card group bg-surface-container rounded-3xl p-6 shadow-md hover:shadow-xl border-2 border-transparent hover:border-amber-500 cursor-pointer transition-all duration-200 flex flex-col justify-between"
                data-difficulty="sedang">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shadow-sm">
                        <span class="material-symbols-outlined text-2xl">bolt</span>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="inline-block text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600">
                                2 Kata Berkaitan
                            </span>
                            <span class="text-[11px] font-extrabold text-amber-600 bg-amber-500/10 px-2 py-0.5 rounded-md">
                                Maks. 250 Poin
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 pt-0.5">
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-500/10 px-2 py-0.5 rounded-full">⏱️ 10 Detik Total Semua Kata</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-on-surface pt-1">Tingkat Sedang</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Menampilkan <strong>2 kata yang saling berkaitan</strong> berurutan (contoh: <em>TERIMA KASIH</em>, <em>KABAR BAIK</em>). Selesaikan dalam total 10 detik.
                    </p>
                </div>
                <div class="pt-6 border-t border-outline-variant/15 mt-6 flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-600">Maks. 250 Poin Total</span>
                    <button type="button" class="bg-amber-600 group-hover:bg-amber-500 text-white p-2 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                    </button>
                </div>
            </div>

            <!-- 3. Tingkat Susah -->
            <div class="difficulty-card group bg-surface-container rounded-3xl p-6 shadow-md hover:shadow-xl border-2 border-transparent hover:border-rose-500 cursor-pointer transition-all duration-200 flex flex-col justify-between"
                data-difficulty="susah">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-xl shadow-sm">
                        <span class="material-symbols-outlined text-2xl">local_fire_department</span>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="inline-block text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-600">
                                3 - 4 Kata (SPOK)
                            </span>
                            <span class="text-[11px] font-extrabold text-rose-600 bg-rose-500/10 px-2 py-0.5 rounded-md">
                                Maks. 500 Poin
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 pt-0.5">
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-500/10 px-2 py-0.5 rounded-full">⏱️ 10 Detik Total Rangkaian Kata</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-on-surface pt-1">Tingkat Susah</h3>
                    </div>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Menampilkan <strong>3 atau 4 kata berpola SPOK</strong> (contoh: <em>SAYA MAKAN NASI</em>). Peragakan berurutan dalam total 10 detik.
                    </p>
                </div>
                <div class="pt-6 border-t border-outline-variant/15 mt-6 flex items-center justify-between">
                    <span class="text-xs font-bold text-rose-600">Maks. 500 Poin Total</span>
                    <button type="button" class="bg-rose-600 group-hover:bg-rose-500 text-white p-2 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="text-center pt-2">
            <button type="button" id="btnBackToName" class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Pendaftaran Nama</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 3: ARENA KUIS BERBASIS KAMERA MEDIAPIPE            -->
    <!-- ======================================================== -->
    <div id="stepQuizArenaContainer" class="hidden flex-col space-y-5">
        <!-- Top Status HUD Bar (Redesigned Unified Dashboard) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-surface-container-low p-3.5 sm:p-4 rounded-3xl shadow-sm border border-outline-variant/20 items-stretch">
            <!-- Card 1: Player Info -->
            <div class="flex items-center gap-3 bg-surface-container-lowest/90 backdrop-blur-md px-3.5 py-2.5 rounded-2xl border border-outline-variant/20 shadow-xs">
                <div id="playerAvatarLetter" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary to-indigo-600 text-on-primary flex items-center justify-center font-extrabold text-base shadow-sm shrink-0">
                    G
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider block">Pemain</span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span id="playerActiveName" class="text-sm font-extrabold text-on-surface truncate max-w-[90px]">Guest</span>
                        <span id="activeDifficultyBadge" class="text-[9px] font-black uppercase px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-600 shrink-0">
                            Mudah
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Score Badge -->
            <div class="flex items-center gap-3 bg-surface-container-lowest/90 backdrop-blur-md px-3.5 py-2.5 rounded-2xl border border-outline-variant/20 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">stars</span>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider block">Total Skor</span>
                    <div class="flex items-baseline gap-1">
                        <span id="quizScoreText" class="text-base sm:text-lg font-black text-primary">0</span>
                        <span id="quizMaxScoreText" class="text-[11px] font-bold text-on-surface-variant">/ 100 pts</span>
                    </div>
                    <span id="pointsPerQBadge" class="text-[10px] text-emerald-600 font-bold block leading-none">+10 pts / soal</span>
                </div>
            </div>

            <!-- Card 3: Question 10s Countdown Timer Badge -->
            <div id="questionTimerContainer" class="flex items-center gap-3 bg-surface-container-lowest/90 backdrop-blur-md px-3.5 py-2.5 rounded-2xl border border-outline-variant/20 shadow-xs transition-all">
                <div id="timerIconWrapper" class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center transition-colors shrink-0">
                    <span class="material-symbols-outlined text-2xl">timer</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline justify-between">
                        <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Sisa Waktu</span>
                        <span id="questionTimerText" class="text-base sm:text-lg font-black text-on-surface tracking-tight">10s</span>
                    </div>
                    <div class="w-full h-1.5 bg-surface-container-highest rounded-full overflow-hidden mt-1">
                        <div id="questionTimerBar" class="h-full bg-primary w-full rounded-full transition-all"></div>
                    </div>
                    <span class="text-[9px] font-semibold text-rose-500/90 dark:text-rose-400 block mt-0.5 leading-none truncate">⏱️ 10s total seluruh kata</span>
                </div>
            </div>

            <!-- Card 4: Question Counter & Progress Bar -->
            <div class="flex items-center gap-3 bg-surface-container-lowest/90 backdrop-blur-md px-3.5 py-2.5 rounded-2xl border border-outline-variant/20 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">quiz</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline justify-between">
                        <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">Progress</span>
                        <span id="questionProgressText" class="text-xs font-black text-on-surface">
                            <span id="currentQNum">1</span> / <span id="totalQNum">5</span>
                        </span>
                    </div>
                    <div class="w-full h-1.5 bg-surface-container-highest rounded-full overflow-hidden mt-1">
                        <div id="progressBarFill" class="h-full bg-indigo-600 w-[20%] rounded-full transition-all duration-300"></div>
                    </div>
                    <span class="text-[9px] text-on-surface-variant/80 block mt-0.5 leading-none">Selesaikan soal</span>
                </div>
            </div>
        </div>

        <!-- Target Word Prompt (Redesigned Challenge Card) -->
        <div class="text-center py-2 sm:py-3 space-y-2.5 bg-surface-container-lowest/60 backdrop-blur-sm rounded-3xl p-4 border border-outline-variant/15">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-extrabold uppercase tracking-wider border border-primary/20 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                <span id="targetPromptBadge">Tantangan Peragaan Isyarat SIBI</span>
                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/20">⏱️ 10s Total</span>
            </div>
            <p id="targetPromptSubtitle" class="text-xs sm:text-sm font-semibold text-on-surface-variant">
                Peragakan semua kata di bawah secara berurutan dalam total waktu 10 detik:
            </p>

            <!-- Words List Chips Container (Supports sequential animated step chips) -->
            <div id="targetWordsContainer" class="flex flex-wrap items-center justify-center gap-2.5 sm:gap-3.5 max-w-4xl mx-auto pt-1 pb-1">
                <!-- Dynamically injected words chips -->
            </div>
        </div>

        <!-- Camera Viewport Section (Mirip Penerjemah) -->
        <div class="relative w-full aspect-video rounded-3xl overflow-hidden liquid-glass bg-inverse-surface flex items-center justify-center shadow-2xl border-4 border-white/40">
            <!-- Live Webcam Video -->
            <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden mirror-mode" autoplay playsinline muted></video>

            <!-- Skeleton Canvas Overlay -->
            <canvas id="skeletonCanvas" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden mirror-mode z-10"></canvas>

            <!-- Standby Background & Overlay -->
            <img id="standbyImg" class="absolute inset-0 w-full h-full object-cover opacity-30 transition-opacity duration-300"
                src="{{ asset('images/laptop-practice.jpg') }}"
                alt="Standby Camera Background">

            <div id="standbyOverlay" class="z-20 flex flex-col items-center gap-3 text-center px-4 max-w-md">
                <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner mb-1">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90">videocam</span>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white">Kamera Belum Aktif</h3>
                <p class="text-xs sm:text-sm text-gray-200 leading-relaxed">
                    Klik <strong>"Aktifkan Kamera"</strong> untuk mendeteksi peragaan sendi tangan MediaPipe Anda.
                </p>
                <button id="btnQuickStart" type="button" class="mt-2 bg-primary hover:bg-surface-tint text-on-primary font-bold text-xs sm:text-sm px-6 py-2.5 rounded-full shadow-lg flex items-center gap-2 transition-transform active:scale-95">
                    <span class="material-symbols-outlined text-lg">videocam</span>
                    Aktifkan Kamera Sekarang
                </button>
            </div>

            <!-- Active HUD Overlay -->
            <div id="cameraHud" class="hidden absolute inset-0 pointer-events-none p-4 sm:p-5 flex flex-col justify-between z-20">
                <!-- Top HUD -->
                <div class="flex flex-wrap justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <!-- MediaPipe Status -->
                        <div class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-1.5 border border-white/15">
                            <span id="hudStatusDot" class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span id="hudStatusText" class="text-xs font-semibold text-white tracking-wide">MediaPipe Hands</span>
                        </div>

                        <!-- TensorFlow.js Model Status -->
                        <div id="aiModelBadge" class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-1.5 border border-white/15 text-xs font-semibold text-white">
                            <span id="aiModelDot" class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span id="aiModelStatus">Memuat Model TFJS...</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Live Model Prediction Pill -->
                        <div id="liveAiPredictionBadge" class="hidden bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/15 text-xs font-bold text-emerald-300 items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-emerald-400">psychology</span>
                            <span id="liveAiPredText">-</span>
                        </div>

                        <div class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/15 text-xs font-bold text-sky-300 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">front_hand</span>
                            <span id="handCountText">0 Tangan</span>
                        </div>
                    </div>
                </div>

                <!-- Target Frame Guide -->
                <div class="relative w-3/5 h-3/5 mx-auto border-2 border-dashed border-primary/70 rounded-3xl flex items-center justify-center bg-primary/5 pointer-events-none">
                    <div class="absolute -top-3 px-3 py-0.5 bg-primary text-on-primary text-[10px] font-bold tracking-wider uppercase rounded-full shadow">
                        Area Peragaan Isyarat SIBI
                    </div>
                    <div class="w-6 h-0.5 bg-primary/40 absolute"></div>
                    <div class="h-6 w-0.5 bg-primary/40 absolute"></div>
                </div>

                <!-- Bottom Hint & Hold Progress -->
                <div class="flex flex-col items-center gap-2">
                    <!-- Dwell/Hold Progress -->
                    <div id="holdProgressWrapper" class="hidden w-48 bg-black/60 backdrop-blur-md rounded-full h-2 overflow-hidden border border-white/20">
                        <div id="holdProgressBar" class="bg-emerald-400 h-full w-0 transition-all duration-75"></div>
                    </div>

                    <span id="bottomHintText" class="text-[11px] text-white/90 bg-black/60 px-3.5 py-1 rounded-full backdrop-blur-md border border-white/10">
                        Arahkan tangan ke kamera dan peragakan kata yang disorot
                    </span>
                </div>
            </div>

            <!-- Success Overlay Toast -->
            <div id="successNotice" class="hidden absolute top-6 left-1/2 -translate-x-1/2 bg-emerald-600 text-white px-6 py-2.5 rounded-full font-extrabold text-sm shadow-2xl flex items-center gap-2 animate-bounce z-30">
                <span class="material-symbols-outlined text-xl">check_circle</span>
                <span id="successNoticeText">Gerakan Tepat! Terverifikasi Model AI</span>
            </div>

            <!-- Timeout / Failed Overlay Toast -->
            <div id="timeoutNotice" class="hidden absolute top-6 left-1/2 -translate-x-1/2 bg-rose-600 text-white px-6 py-2.5 rounded-full font-extrabold text-sm shadow-2xl flex items-center gap-2 animate-bounce z-30">
                <span class="material-symbols-outlined text-xl">timer_off</span>
                <span id="timeoutNoticeText">Waktu Habis! Soal ini bernilai 0 Poin</span>
            </div>
        </div>

        <!-- Controls Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-surface-container-low p-3.5 rounded-2xl border border-outline-variant/20 shadow-sm">
            <div class="flex items-center gap-2">
                <button id="btnStartCam" type="button" class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-base">videocam</span>
                    <span>Nyalakan Kamera</span>
                </button>
                <button id="btnStopCam" type="button" class="hidden items-center gap-2 bg-error text-on-error px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-red-700 active:scale-95 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-base">videocam_off</span>
                    <span>Matikan Kamera</span>
                </button>
                <button id="btnToggleMirror" type="button" class="bg-surface-container-high text-on-surface hover:bg-surface-dim p-2.5 rounded-xl transition-all" title="Mirror Kamera">
                    <span class="material-symbols-outlined text-base">flip</span>
                </button>
                <button id="btnOpenSettings" type="button" class="bg-surface-container-high text-on-surface hover:bg-surface-dim p-2.5 rounded-xl transition-all" title="Pengaturan Model Deep Learning & Landmark">
                    <span class="material-symbols-outlined text-base text-primary">tune</span>
                </button>
            </div>

            <div class="flex items-center gap-2">
                <!-- Fallback Verification Button (Sangat berguna untuk demonstrasi SEMPRO / simulasi kata) -->
                <button id="btnSimulateMatch" type="button" class="bg-surface-container-highest hover:bg-surface-dim text-primary text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl border border-primary/20 transition-all active:scale-95 flex items-center gap-1.5" title="Verifikasi peragaan kata saat ini jika deteksi otomatis lambat">
                    <span class="material-symbols-outlined text-base text-emerald-500">task_alt</span>
                    <span>Verifikasi Isyarat</span>
                </button>

                <button id="btnNextQuestion" type="button" class="hidden items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-md">
                    <span>Soal Selanjutnya</span>
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </button>
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
                        <option value="63">21 Titik x, y, z (63 Fitur 1 Tangan) [Default]</option>
                        <option value="42">21 Titik x, y (42 Fitur 1 Tangan)</option>
                        <option value="126">21 Titik x, y, z (126 Fitur 2 Tangan)</option>
                        <option value="normalized_wrist">Normalisasi Relatif ke Pergelangan Tangan (Wrist)</option>
                    </select>
                </div>

                <!-- Path Model Lokal -->
                <div class="space-y-1.5">
                    <label class="font-bold text-on-surface block">Lokasi File Model TFJS (model.json):</label>
                    <input id="modelUrlInput" type="text"
                        value="/models/sibi_model/model.json"
                        class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">
                    <p class="text-[11px] text-on-surface-variant">
                        Letakkan file hasil training di <code>public/models/sibi_model/model.json</code>.
                    </p>
                </div>

                <!-- Daftar Label Kelas -->
                <div class="space-y-1.5">
                    <label class="font-bold text-on-surface block">Daftar Label / Kelas SIBI (Pisahkan dengan koma):</label>
                    <textarea id="classesInput" rows="3" class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, MAKAN, RUMAH, TEMAN, BELAJAR, HALO, MAAF, TERIMA KASIH, KABAR BAIK, SAMA-SAMA, SAYA, NASI, IBU, DAPUR, KEDAI</textarea>
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