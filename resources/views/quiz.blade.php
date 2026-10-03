@extends('layouts.app')

@section('title', 'Kuis Interaktif Bahasa Isyarat SIBI - SIBI Learn')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
@endpush

@section('content')
<div class="max-w-6xl mx-auto flex flex-col space-y-5 sm:space-y-6 mt-1 md:mt-4 px-1 sm:px-0">

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
    <div id="stepDifficultyContainer" class="hidden max-w-5xl mx-auto w-full py-2 sm:py-6 px-1 sm:px-0 space-y-6 sm:space-y-8">
        <!-- Header & Profil Pemain -->
        <div class="text-center space-y-2.5 sm:space-y-3">
            <div class="inline-flex flex-wrap items-center justify-center gap-2 sm:gap-3 px-3.5 py-1.5 rounded-full bg-surface-container border border-outline-variant/30 text-xs font-semibold max-w-full">
                <span class="flex items-center gap-1.5 text-primary font-bold">
                    <span class="material-symbols-outlined text-base">account_circle</span>
                    <span>Pemain: <strong id="displayNameBadge" class="text-on-surface truncate max-w-[120px] sm:max-w-none inline-block align-bottom">-</strong></span>
                </span>
                <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                <button type="button" id="btnSwitchPlayer" class="text-on-surface-variant hover:text-primary transition-colors flex items-center gap-1 text-[11px] underline min-h-[32px] sm:min-h-0">
                    Ganti Pemain
                </button>
            </div>
            
            <div class="space-y-1">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Pilih Tingkat Kesulitan</h2>
                <p class="text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed px-2 sm:px-0">
                    Tentukan tingkat kesulitan kuis peragaan isyarat SIBI sesuai kemampuan Anda. Setiap soal memiliki batas waktu 10 detik.
                </p>
            </div>
        </div>

        <!-- 3 Kartu Tingkat Kesulitan (Mobile Optimized Soft Blue Theme) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 items-stretch">
            <!-- 1. Tingkat Mudah -->
            <div class="difficulty-card difficulty-card-modern group bg-surface-container-lowest hover:bg-surface-container-low active:bg-surface-container-low rounded-3xl p-5 sm:p-7 shadow-sm hover:shadow-md border border-outline-variant/30 hover:border-primary/40 active:scale-[0.99] cursor-pointer flex flex-col justify-between"
                data-difficulty="mudah" role="button" tabindex="0" aria-label="Pilih Tingkat Mudah">
                <div class="space-y-3.5 sm:space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">sentiment_satisfied</span>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-surface-container text-primary">
                            Tingkat 1
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-on-surface group-hover:text-primary transition-colors">
                            Tingkat Mudah
                        </h3>
                        <p class="text-xs text-on-surface-variant mt-0.5 sm:mt-1">
                            1 Kata Dasar · 10 Detik
                        </p>
                    </div>

                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Menampilkan 1 kata kosakata dasar pada setiap soal (contoh: SAYA, KULIAH). Cocok untuk pemula yang baru memulai.
                    </p>
                </div>

                <div class="pt-4 sm:pt-5 border-t border-outline-variant/20 mt-5 sm:mt-6 flex items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] sm:text-[11px] text-on-surface-variant block">Maksimal Skor</span>
                        <span class="text-sm font-bold text-primary">100 Poin</span>
                    </div>
                    <button type="button" class="bg-primary text-on-primary hover:bg-surface-tint active:bg-surface-tint text-xs font-semibold px-4 py-2.5 min-h-[44px] rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Mulai</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- 2. Tingkat Sedang -->
            <div class="difficulty-card difficulty-card-modern group bg-surface-container-lowest hover:bg-surface-container-low active:bg-surface-container-low rounded-3xl p-5 sm:p-7 shadow-sm hover:shadow-md border border-outline-variant/30 hover:border-primary/40 active:scale-[0.99] cursor-pointer flex flex-col justify-between"
                data-difficulty="sedang" role="button" tabindex="0" aria-label="Pilih Tingkat Sedang">
                <div class="space-y-3.5 sm:space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">speed</span>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-surface-container text-primary">
                            Tingkat 2
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-on-surface group-hover:text-primary transition-colors">
                            Tingkat Sedang
                        </h3>
                        <p class="text-xs text-on-surface-variant mt-0.5 sm:mt-1">
                            2 Kata Berurutan · 10 Detik
                        </p>
                    </div>

                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Menampilkan 2 kata yang saling berkaitan berurutan (contoh: TERIMA KASIH, SELAMAT PAGI). Selesaikan dalam total 10 detik.
                    </p>
                </div>

                <div class="pt-4 sm:pt-5 border-t border-outline-variant/20 mt-5 sm:mt-6 flex items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] sm:text-[11px] text-on-surface-variant block">Maksimal Skor</span>
                        <span class="text-sm font-bold text-primary">250 Poin</span>
                    </div>
                    <button type="button" class="bg-primary text-on-primary hover:bg-surface-tint active:bg-surface-tint text-xs font-semibold px-4 py-2.5 min-h-[44px] rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Mulai</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- 3. Tingkat Susah -->
            <div class="difficulty-card difficulty-card-modern group bg-surface-container-lowest hover:bg-surface-container-low active:bg-surface-container-low rounded-3xl p-5 sm:p-7 shadow-sm hover:shadow-md border border-outline-variant/30 hover:border-primary/40 active:scale-[0.99] cursor-pointer flex flex-col justify-between"
                data-difficulty="susah" role="button" tabindex="0" aria-label="Pilih Tingkat Susah">
                <div class="space-y-3.5 sm:space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">psychology</span>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-surface-container text-primary">
                            Tingkat 3
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-on-surface group-hover:text-primary transition-colors">
                            Tingkat Susah
                        </h3>
                        <p class="text-xs text-on-surface-variant mt-0.5 sm:mt-1">
                            3 - 4 Kata (SPOK) · 10 Detik
                        </p>
                    </div>

                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Menampilkan 3 atau 4 kata berpola SPOK (contoh: SAYA KULIAH DARI PAGI). Peragakan berurutan dalam total 10 detik.
                    </p>
                </div>

                <div class="pt-4 sm:pt-5 border-t border-outline-variant/20 mt-5 sm:mt-6 flex items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] sm:text-[11px] text-on-surface-variant block">Maksimal Skor</span>
                        <span class="text-sm font-bold text-primary">500 Poin</span>
                    </div>
                    <button type="button" class="bg-primary text-on-primary hover:bg-surface-tint active:bg-surface-tint text-xs font-semibold px-4 py-2.5 min-h-[44px] rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Mulai</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer Back Navigation -->
        <div class="text-center pt-1 sm:pt-2">
            <button type="button" id="btnBackToName" class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors inline-flex items-center justify-center gap-1.5 px-5 py-2.5 min-h-[44px] rounded-full hover:bg-surface-container active:bg-surface-container-high focus:outline-none">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                <span>Kembali ke Pendaftaran Nama</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAHAP 3: ARENA KUIS BERBASIS KAMERA MEDIAPIPE            -->
    <!-- ======================================================== -->
    <div id="stepQuizArenaContainer" class="hidden flex-col space-y-4 sm:space-y-5">
        <!-- Header Info Bar (Mirip Penerjemah Header) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-surface-container-low p-3.5 sm:p-4 rounded-2xl sm:rounded-3xl border border-outline-variant/20 shadow-xs">
            <div class="flex items-center gap-3">
                <div id="playerAvatarLetter" class="w-10 h-10 rounded-2xl bg-primary text-on-primary flex items-center justify-center font-black text-sm shadow-xs shrink-0">
                    G
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pemain:</span>
                        <strong id="playerActiveName" class="text-sm sm:text-base font-extrabold text-on-surface">Guest</strong>
                        <span id="activeDifficultyBadge" class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20">
                            Mudah
                        </span>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        Tirukan bahasa isyarat SIBI sesuai tantangan soal yang ditampilkan
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 self-start sm:self-auto flex-wrap">
                <!-- Score Capsule -->
                <div class="flex items-center gap-2 bg-surface-container-lowest px-3.5 py-1.5 rounded-xl border border-outline-variant/20 shadow-xs">
                    <span class="material-symbols-outlined text-amber-500 text-lg">stars</span>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span id="quizScoreText" class="text-sm sm:text-base font-black text-primary">0</span>
                            <span id="quizMaxScoreText" class="text-xs font-bold text-on-surface-variant">/ 100 pts</span>
                        </div>
                        <span id="pointsPerQBadge" class="text-[9px] text-emerald-600 font-bold block leading-none">+10 pts / soal</span>
                    </div>
                </div>

                <!-- Progress Soal Capsule -->
                <div class="flex items-center gap-2 bg-surface-container-lowest px-3.5 py-1.5 rounded-xl border border-outline-variant/20 shadow-xs">
                    <span class="material-symbols-outlined text-primary text-lg">quiz</span>
                    <div>
                        <div class="flex items-baseline justify-between gap-2">
                            <span id="questionProgressText" class="text-xs sm:text-sm font-black text-on-surface">
                                Soal <span id="currentQNum">1</span> / <span id="totalQNum">5</span>
                            </span>
                        </div>
                        <div class="w-16 sm:w-20 h-1.5 bg-surface-container-highest rounded-full overflow-hidden mt-1">
                            <div id="progressBarFill" class="h-full bg-primary w-[20%] rounded-full transition-all duration-300"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace Grid (Mirip Penerjemah: 7 Cols Kiri, 5 Cols Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-start">
            <!-- Left Column: Camera Feed & Controls (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col space-y-3.5 sm:space-y-4">
                <!-- Viewport Container -->
                <div class="relative w-full aspect-video rounded-2xl sm:rounded-3xl overflow-hidden bg-inverse-surface flex items-center justify-center shadow-lg border border-outline-variant/20">
                    <!-- Live Video Element -->
                    <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden mirror-mode" autoplay playsinline muted></video>

                    <!-- Skeleton Canvas Overlay -->
                    <canvas id="skeletonCanvas" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden mirror-mode z-10"></canvas>

                    <!-- Standby Background Image -->
                    <img id="standbyImg" class="absolute inset-0 w-full h-full object-cover opacity-30 transition-opacity duration-300"
                        src="{{ asset('images/laptop-practice.jpg') }}"
                        alt="Camera Standby Background">

                    <!-- Standby Status UI Card -->
                    <div id="standbyOverlay" class="z-20 flex flex-col items-center gap-2.5 text-center px-4 max-w-sm">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner">
                            <span class="material-symbols-outlined text-3xl text-white opacity-90">videocam_off</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-white">Kamera Belum Aktif</h3>
                        <p class="text-xs text-gray-200 leading-relaxed">
                            Klik tombol <strong>"Nyalakan Kamera"</strong> di bawah untuk mendeteksi isyarat SIBI Anda.
                        </p>
                        <button id="btnQuickStart" type="button" class="mt-1 bg-primary hover:bg-surface-tint text-on-primary font-bold text-xs px-5 py-2.5 rounded-full shadow-md flex items-center gap-1.5 transition-transform active:scale-95 min-h-[44px]">
                            <span class="material-symbols-outlined text-base">play_arrow</span>
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

                <!-- Controls Bar (Mirip Penerjemah) -->
                <div class="flex flex-wrap items-center justify-between gap-2.5 bg-surface-container-low p-3 sm:p-3.5 rounded-2xl border border-outline-variant/20 shadow-xs">
                    <div class="flex items-center gap-2">
                        <button id="btnStartCam" type="button" class="flex items-center justify-center gap-1.5 bg-primary text-on-primary px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-xs min-h-[44px]">
                            <span class="material-symbols-outlined text-lg">videocam</span>
                            <span>Mulai Kamera</span>
                        </button>
                        <button id="btnStopCam" type="button" class="hidden items-center justify-center gap-1.5 bg-error text-on-error px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-red-700 active:scale-95 transition-all shadow-xs min-h-[44px]">
                            <span class="material-symbols-outlined text-lg">videocam_off</span>
                            <span>Hentikan</span>
                        </button>
                        <button id="btnToggleMirror" type="button" class="bg-surface-container hover:bg-surface-container-high text-on-surface w-11 h-11 min-h-[44px] rounded-xl flex items-center justify-center transition-all" title="Mirror Kamera">
                            <span class="material-symbols-outlined text-lg">flip</span>
                        </button>
                        <button id="btnOpenSettings" type="button" class="bg-surface-container hover:bg-surface-container-high text-primary px-3 min-h-[44px] rounded-xl transition-all font-semibold text-xs flex items-center gap-1" title="Pengaturan Model Deep Learning & Landmark">
                            <span class="material-symbols-outlined text-base text-primary">tune</span>
                            <span class="hidden sm:inline">Model</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Fallback Verification Button -->
                        <button id="btnSimulateMatch" type="button" class="bg-surface-container-highest hover:bg-surface-dim text-primary text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl border border-primary/20 transition-all active:scale-95 flex items-center gap-1.5 min-h-[44px]" title="Verifikasi peragaan kata saat ini jika deteksi otomatis lambat">
                            <span class="material-symbols-outlined text-base text-emerald-500">task_alt</span>
                            <span>Verifikasi Isyarat</span>
                        </button>

                        <button id="btnNextQuestion" type="button" class="hidden items-center gap-1.5 bg-primary text-on-primary px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-xs min-h-[44px]">
                            <span>Soal Selanjutnya</span>
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Target Words Challenge & AI Detection (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col space-y-4">
                <!-- Card 1: Target Kata Soal & Countdown Timer -->
                <div class="bg-surface-container-lowest p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-outline-variant/30 shadow-xs relative overflow-hidden space-y-3.5">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span id="targetPromptBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                            <span>Tantangan SIBI</span>
                        </span>

                        <!-- Countdown Timer 10 Detik -->
                        <div id="questionTimerContainer" class="flex items-center gap-2 bg-surface-container-low px-3 py-1.5 rounded-xl border border-outline-variant/20 shadow-xs transition-all">
                            <div id="timerIconWrapper" class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center transition-colors shrink-0">
                                <span class="material-symbols-outlined text-base">timer</span>
                            </div>
                            <span id="questionTimerText" class="text-xs sm:text-sm font-black text-on-surface tracking-tight min-w-[22px]">10s</span>
                            <div class="w-14 sm:w-16 h-1.5 bg-surface-container-highest rounded-full overflow-hidden">
                                <div id="questionTimerBar" class="h-full bg-primary w-full rounded-full transition-all"></div>
                            </div>
                        </div>
                    </div>

                    <p id="targetPromptSubtitle" class="text-xs sm:text-sm text-on-surface-variant font-medium leading-relaxed">
                        Peragakan kata di bawah secara berurutan dalam waktu 10 detik:
                    </p>

                    <!-- Words List Chips Container -->
                    <div id="targetWordsContainer" class="flex flex-wrap items-center gap-2 pt-1 pb-1">
                        <!-- Dynamically injected words chips -->
                    </div>

                    <!-- Hold/Dwell Progress -->
                    <div id="holdProgressWrapper" class="hidden pt-1">
                        <div class="flex justify-between text-[10px] font-bold text-on-surface-variant mb-1">
                            <span>Menahan Posisi Isyarat:</span>
                        </div>
                        <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                            <div id="holdProgressBar" class="bg-emerald-500 h-2 rounded-full transition-all duration-75" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Live AI Detection Box (Mirip Active Recognition Card di Penerjemah) -->
                <div class="bg-surface-container-lowest p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-outline-variant/30 shadow-xs flex flex-col space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-lg">psychology</span>
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Hasil Deteksi AI</span>
                        </div>
                        <div id="liveAiPredictionBadge" class="hidden px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-xs font-extrabold flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">check</span>
                            <span id="liveAiPredText">-</span>
                        </div>
                    </div>

                    <div class="bg-surface-container-low p-3.5 rounded-2xl border border-outline-variant/15 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">front_hand</span>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider block">Petunjuk Peragaan</span>
                            <p class="text-xs font-semibold text-on-surface truncate">
                                Hadapkan telapak tangan & peragakan kata yang disorot
                            </p>
                        </div>
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