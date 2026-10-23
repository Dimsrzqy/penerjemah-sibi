@extends('layouts.app')

@section('title', 'Penerjemah Kamera SIBI Real-time - K-SULI')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/penerjemah.css') }}">
@endpush

@section('content')
<div class="w-full bg-surface text-on-surface font-sans flex flex-col justify-start">
    
    <!-- Top Banner & Breadcrumb Context (Clean Spacing & Well-Proportioned) -->
    <section class="w-full pt-1 pb-1 mb-4 sm:mb-6">
        <div class="flex flex-col">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-1.5 text-xs text-on-surface-variant font-medium mb-2 sm:mb-2.5">
                <a href="{{ route('beranda') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">home</span>
                    <span>Beranda</span>
                </a>
                <span class="material-symbols-outlined text-[13px] text-outline-variant select-none">chevron_right</span>
                <span class="text-primary font-semibold">Penerjemah Kamera SIBI</span>
            </nav>

            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight leading-tight">
                    Penerjemah Kamera SIBI Real-time
                </h1>
                <p class="mt-1.5 text-xs sm:text-sm text-on-surface-variant leading-relaxed max-w-3xl">
                    Arahkan gestur tanganmu ke webcam. AI mendeteksi 21 titik sendi jari secara presisi tanpa jeda untuk menerjemahkan ke teks dan suara, mempermudah interaksi inklusif di Kedai K-Suli.
                </p>
            </div>
        </div>
    </section>

    <!-- Interactive Translator Workspace (Two Symmetrical Columns) -->
    <section class="w-full pb-8 sm:pb-12 mb-2 sm:mb-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-stretch">
            
            <!-- Left Column: Camera Viewport (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col h-full">
                <div class="relative bg-white rounded-2xl overflow-hidden shadow-xl flex flex-col h-full border border-slate-200">
                    
                    <!-- macOS Window Top Header Bar (Light Clean Frame per Mockup) -->
                    <div class="w-full px-4 py-2.5 sm:px-5 sm:py-3 bg-slate-50/95 backdrop-blur-md rounded-t-2xl flex items-center justify-between text-slate-800 z-20 border-b border-slate-200 shrink-0">
                        <!-- Left: macOS 3 traffic lights + Title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1.5" aria-label="Lampu status sensor">
                                <span class="w-3 h-3 rounded-full inline-block shadow-2xs" style="background-color: #f87171;"></span>
                                <span class="w-3 h-3 rounded-full inline-block shadow-2xs" style="background-color: #fbbf24;"></span>
                                <span class="w-3 h-3 rounded-full inline-block shadow-2xs" style="background-color: #34d399;"></span>
                            </div>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700">Preview Sensor Kamera SIBI</span>
                        </div>
                        
                        <!-- Right: Status Badge Pill (Default NONAKTIF) -->
                        <div id="cameraStatusBadge" class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 transition-colors cursor-pointer select-none" title="Klik untuk beralih status kamera">
                            <span id="cameraStatusDot" class="relative flex h-2 w-2">
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-400"></span>
                            </span>
                            <span id="cameraStatusText" class="text-xs tracking-wider uppercase font-bold text-slate-500">NONAKTIF</span>
                        </div>
                    </div>

                    <!-- Flexible Viewport Area (16:9 1920x1080 Aspect Ratio) -->
                    <div id="cameraViewportArea" class="relative w-full flex-1 bg-[#0c1017] flex items-center justify-center overflow-hidden group select-none min-h-[260px] aspect-video">
                        
                        <!-- Cafe Background (Kedai Susu K-Suli) -->
                        <img id="standbyImg" 
                             src="{{ asset('images/kedai-susu-tuli.jpg') }}" 
                             alt="" 
                             aria-hidden="true" 
                             class="absolute inset-0 w-full h-full object-cover opacity-40 transition-transform duration-700 group-hover:scale-105 pointer-events-none">

                        <!-- Live Webcam Video -->
                        <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden mirror-mode z-10" autoplay playsinline muted></video>

                        <!-- MediaPipe Skeleton Canvas Overlay -->
                        <canvas id="skeletonCanvas" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden mirror-mode z-20"></canvas>

                        <!-- Dynamic Recognition Floating Tag on Hand (AR HUD Style) -->
                        <div id="activeHandOverlay" class="absolute top-4 left-1/2 -translate-x-1/2 z-25 pointer-events-none transition-all duration-150 hidden">
                            <div class="px-3.5 py-1.5 rounded-full bg-primary/95 text-white text-xs font-bold shadow-lg flex items-center gap-1.5 border border-white/20 backdrop-blur-xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span id="activeHandOverlayText">Mendeteksi...</span>
                            </div>
                        </div>

                        <!-- Zona Pindai Gestur Box -->
                        <div id="targetFocusGuide" class="absolute inset-0 flex items-center justify-center pointer-events-none z-15 opacity-25">
                            <div class="w-56 h-56 sm:w-64 sm:h-64 rounded-[2.5rem] border-2 border-dashed border-white flex items-center justify-center">
                                <span class="text-xs font-bold text-white text-center px-4">Zona Pindai Gestur Dada &amp; Tangan</span>
                            </div>
                        </div>

                        <!-- Camera Off / Standby Overlay (Default Kamera Mati) -->
                        <div id="cameraOffOverlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center p-6 bg-inverse-surface/85 backdrop-blur-xs transition-all duration-300 text-center">
                            <div class="w-14 h-14 rounded-full bg-surface-container-highest/20 border border-inverse-on-surface/20 flex items-center justify-center mb-3 text-inverse-on-surface shadow-lg">
                                <span class="material-symbols-outlined text-[28px] text-inverse-on-surface/80">videocam</span>
                            </div>
                            <h3 class="text-base font-bold text-inverse-on-surface mb-1">Akses Kamera Belum Aktif</h3>
                            <p class="text-xs text-inverse-on-surface/80 max-w-sm mb-4 leading-relaxed">
                                Kamera saat ini dinonaktifkan. Klik tombol di bawah ini untuk menyalakan webcam (resolusi 1920x1080) dan mulai mendeteksi gerakan isyarat SIBI.
                            </p>
                            <button id="turnOnCamOverlayBtn" type="button" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary hover:bg-blue-600 text-on-primary font-bold text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all transform hover:scale-105 active:scale-95 cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">videocam</span>
                                <span>Nyalakan Kamera Sekarang</span>
                            </button>
                        </div>

                        <!-- Fullscreen Translation & Subtitle HUD Overlay (Tampil di bagian bawah kamera saat Layar Penuh) -->
                        <div id="fullscreenTranslationOverlay" 
                             class="absolute bottom-0 inset-x-0 z-40 p-4 sm:p-6 pb-6 sm:pb-8 flex flex-col items-center justify-end pointer-events-none transition-all duration-300">
                            
                            <div class="w-full max-w-4xl flex flex-col gap-2.5 sm:gap-3 pointer-events-auto">
                                
                                <!-- Top Bar: Kata Terdeteksi Hero Badge & Akurasi -->
                                <div class="flex items-center justify-between gap-3 px-4 py-2.5 sm:px-5 sm:py-3 rounded-2xl bg-slate-900/90 backdrop-blur-md border border-white/20 shadow-2xl text-white">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-600/30 border border-blue-400/40 flex items-center justify-center text-blue-400 shrink-0">
                                            <span class="material-symbols-outlined text-[20px] sm:text-[22px]">translate</span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-[10px] uppercase font-bold tracking-wider text-slate-300">Kata Terdeteksi (Live)</div>
                                            <div id="fsLiveDetectedWord" class="text-xl sm:text-3xl font-extrabold text-white/50 tracking-wide truncate">
                                                —
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right side: Confidence & Quick TTS -->
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span id="fsConfidenceBadge" class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-slate-300 border border-white/15 flex items-center gap-1.5">
                                            <span id="fsConfidenceDot" class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            <span id="fsConfidenceText">0%</span>
                                        </span>
                                        <button id="fsSpeakCurrentBtn" type="button" 
                                            class="p-2 sm:p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all active:scale-95 cursor-pointer min-h-[44px] min-w-[44px] flex items-center justify-center" 
                                            title="Dengarkan Kata Terdeteksi (TTS)">
                                            <span class="material-symbols-outlined text-[20px]">volume_up</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bottom Bar: Rangkaian Kalimat / Subtitle Stream -->
                                <div class="flex items-center justify-between gap-3 px-4 py-2.5 sm:px-5 sm:py-3 rounded-2xl bg-black/85 backdrop-blur-md border border-white/15 shadow-2xl text-white">
                                    <div class="flex-1 flex items-center gap-2 overflow-x-auto py-1" id="fsSentenceContainer">
                                        <span class="text-xs sm:text-sm text-slate-300 italic flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">subtitles</span>
                                            <span>Belum ada rangkaian kalimat • Peragakan gestur di depan kamera</span>
                                        </span>
                                    </div>

                                    <!-- Action Controls in Fullscreen -->
                                    <div class="flex items-center gap-1.5 shrink-0 pl-2 border-l border-white/15">
                                        <button id="fsClearBtn" type="button" 
                                            class="px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-xl hover:bg-red-500/20 text-slate-300 hover:text-red-400 text-xs font-semibold flex items-center gap-1 transition-all cursor-pointer min-h-[44px]" 
                                            title="Hapus Rangkaian Kalimat">
                                            <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                                            <span class="hidden sm:inline">Hapus</span>
                                        </button>
                                        <button id="fsSpeakSentenceBtn" type="button" 
                                            class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-1 transition-all shadow-md active:scale-95 cursor-pointer min-h-[44px]" 
                                            title="Dengarkan Seluruh Kalimat">
                                            <span class="material-symbols-outlined text-[18px]">volume_up</span>
                                            <span class="hidden sm:inline">Dengarkan</span>
                                        </button>
                                        <button id="fsExitBtn" type="button" 
                                            class="p-2 sm:p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all active:scale-95 cursor-pointer min-h-[44px] min-w-[44px] flex items-center justify-center" 
                                            title="Keluar Layar Penuh (Esc)">
                                            <span class="material-symbols-outlined text-[20px]">fullscreen_exit</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Bottom Controls Toolbar Bar -->
                    <div class="w-full px-4 py-2.5 bg-white rounded-b-2xl flex items-center justify-between gap-2 border-t border-slate-100 shrink-0">
                        <div class="flex items-center gap-2">
                            <button id="toggleCameraPowerBtn" type="button" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-xs transition-colors active:scale-95 cursor-pointer" 
                                title="Nyalakan / Matikan Kamera">
                                <span class="material-symbols-outlined text-[18px]">videocam</span>
                                <span id="toggleCameraPowerText">Nyalakan Kamera</span>
                            </button>
                            <button id="flipCameraBtn" type="button" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50/60 hover:bg-blue-100 text-slate-700 font-semibold text-xs transition-colors active:scale-95 cursor-pointer" 
                                title="Balik tampilan kamera secara horizontal (Cermin)">
                                <span class="material-symbols-outlined text-[18px] text-slate-600">flip</span>
                                <span class="hidden sm:inline">Cermin Kamera</span>
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="fullscreenBtn" type="button" 
                                class="p-2 rounded-xl bg-blue-50/60 hover:bg-blue-100 text-blue-700 transition-colors active:scale-95 cursor-pointer" 
                                title="Layar Penuh">
                                <span class="material-symbols-outlined text-[18px]">fullscreen</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Results & Sentence Buffer (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col h-full">
                <div class="bg-surface-container-lowest rounded-2xl shadow-xl p-4 sm:p-5 flex flex-col h-full border border-slate-100 relative overflow-hidden">
                    
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Inner Flexible Content Stack (Zero Awkward Gaps) -->
                    <div class="flex flex-col h-full gap-3 relative z-10 min-h-0">
                        
                        <!-- Header with Status Pill -->
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 shrink-0">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-[18px]">translate</span>
                                </div>
                                <h2 class="text-sm sm:text-base font-bold text-on-surface">Hasil Terjemahan Langsung</h2>
                            </div>
                            <span id="stabilityBadge" class="px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface-variant text-[11px] font-bold flex items-center gap-1.5">
                                <span id="stabilityBadgeDot" class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span id="stabilityBadgeText">Menunggu Input</span>
                            </span>
                        </div>

                        <!-- Active Detected Word Card -->
                        <div class="bg-surface-container-low/70 rounded-2xl p-3.5 sm:p-4 flex flex-col gap-2 border border-slate-200/60 shadow-2xs shrink-0">
                            <div class="flex items-start justify-between">
                                <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Kata Terdeteksi</span>
                                <div class="flex items-center gap-1">
                                    <button id="speakCurrentBtn" type="button" 
                                        class="p-1 rounded-lg hover:bg-surface-container text-primary transition-all active:scale-90" 
                                        title="Dengarkan Suara (TTS)">
                                        <span class="material-symbols-outlined text-[18px]">volume_up</span>
                                    </button>
                                    <button id="copyWordBtn" type="button" 
                                        class="p-1 rounded-lg hover:bg-surface-container text-on-surface-variant transition-all active:scale-90" 
                                        title="Salin Kata">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-baseline py-0.5">
                                <div id="liveResultText" class="text-3xl sm:text-4xl font-extrabold text-on-surface-variant/40 tracking-tight select-all leading-none">
                                    —
                                </div>
                            </div>

                            <p id="detectedCategory" class="text-xs text-on-surface-variant leading-relaxed line-clamp-2">
                                Arahkan gestur tangan ke kamera untuk mulai menerjemahkan isyarat SIBI secara langsung.
                            </p>

                            <!-- AI Confidence Bar -->
                            <div class="flex flex-col gap-1 pt-1">
                                <div class="flex items-center justify-between text-[11px] font-medium">
                                    <span class="text-on-surface-variant">Tingkat Kepercayaan AI</span>
                                    <span id="confidenceBadge" class="text-on-surface-variant font-bold">0%</span>
                                </div>
                                <div class="w-full h-1.5 rounded-full bg-surface-container-highest overflow-hidden">
                                    <div id="confidenceBar" class="h-full bg-gradient-to-r from-primary via-blue-500 to-teal-500 rounded-full w-0 transition-all duration-300"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Rangkaian Kalimat (Buffer) - Naturally Fills the Remaining Space -->
                        <div class="flex-1 flex flex-col gap-1.5 min-h-0">
                            <div class="flex items-center justify-between shrink-0">
                                <span class="text-xs text-on-surface font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px] text-on-surface-variant">format_quote</span>
                                    <span>Rangkaian Kalimat (Buffer)</span>
                                </span>
                                <span id="charCount" class="text-[11px] text-on-surface-variant font-medium">0 Frasa Terkumpul</span>
                            </div>

                            <!-- Buffer Canvas / Board -->
                            <div id="sentenceContainer" class="flex-1 min-h-[90px] overflow-y-auto bg-surface-container-low/50 rounded-2xl p-3 border border-slate-200/60 flex flex-wrap gap-2 content-start items-start">
                                <div class="w-full h-full min-h-[80px] flex flex-col items-center justify-center text-center p-3 text-on-surface-variant/60 select-none">
                                    <span class="material-symbols-outlined text-2xl text-outline-variant/60 mb-1">chat_bubble_outline</span>
                                    <span class="text-xs font-medium">Belum ada frasa terkumpul</span>
                                    <span class="text-[11px] text-outline-variant">Lakukan isyarat di depan kamera untuk merangkai kalimat</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Actions Bar -->
                        <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 shrink-0">
                            <button id="clearBufferBtn" type="button" 
                                class="flex items-center gap-1 text-on-surface-variant hover:text-error text-xs font-semibold transition-colors px-2.5 py-1.5 rounded-xl hover:bg-red-50">
                                <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                                <span>Hapus Kalimat</span>
                            </button>
                            <div class="flex items-center gap-2">
                                <button id="btnSpeech" type="button" 
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-surface-container-high hover:bg-surface-container text-on-surface text-xs font-semibold transition-colors active:scale-95" 
                                    title="Dengarkan seluruh kalimat">
                                    <span class="material-symbols-outlined text-[16px]">volume_up</span>
                                    <span class="hidden sm:inline">Dengarkan</span>
                                </button>
                                <button id="copyAllBtn" type="button" 
                                    class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-primary hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition-all active:scale-95" 
                                    title="Salin seluruh kalimat">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                    <span>Salin Semua</span>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>

<!-- Modal Pengaturan Model TFJS -->
<div id="settingsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-3xl max-w-lg w-full p-6 border border-outline-variant/30 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-outline-variant/15 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">tune</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface text-base">Pengaturan Model AI</h3>
                    <p class="text-[11px] text-on-surface-variant">Konfigurasi endpoint dan ambang batas inferensi</p>
                </div>
            </div>
            <button id="btnCloseSettings" type="button" class="p-1.5 rounded-full hover:bg-surface-container text-on-surface-variant min-h-[44px] min-w-[44px] flex items-center justify-center" aria-label="Tutup Pengaturan">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <!-- Path Model Lokal -->
            <div class="space-y-1.5">
                <label for="modelUrlInput" class="font-bold text-on-surface block">Lokasi File Model TFJS (model.json):</label>
                <input id="modelUrlInput" type="text"
                    value="/models/tfjs_model/model.json"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary font-mono text-xs">
                <p class="text-[11px] text-on-surface-variant">
                    File model aktif tersimpan di <code>public/models/tfjs_model/model.json</code>.
                </p>
            </div>

            <!-- Daftar Label Kelas -->
            <div class="space-y-1.5">
                <label for="classesInput" class="font-bold text-on-surface block">Daftar Label / Kelas SIBI (Pisahkan dengan koma):</label>
                <textarea id="classesInput" rows="3" class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary font-mono text-xs">ADIK, APA, AYAH, BAIK, BERAPA, BERTEMU, CANTIK, DARI, DIA, DIMANA, GANTENG, GEMUK, HALLO, HOBI, IBU, JUMAT, JURUSAN, KABAR, KAKEK, KALIAN, KAMI, KAMIS, KAMPUS, KAMU, KELAS, KELUARGA, KEMANA, KENAPA, KITA, KULIAH, KURUS, LUCU, MALAM, MAU, MEREKA, MINGGU, NAMA, PAGI, PELIT, PENDIDIKAN, PINTAR, PULANG, RABU, SABAR, SABTU, SAKIT, SAMPAI JUMPA, SAYA, SEKOLAH, SELAMAT, SELASA, SEMESTER, SENANG, SENIN, SIANG, SIAPA, SORE, TERIMAKASIH, TINGGAL, UMUR</textarea>
            </div>

            <!-- Threshold Slider -->
            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label for="thresholdSlider" class="font-bold text-on-surface">Ambang Batas Akurasi (Threshold):</label>
                    <span id="thresholdVal" class="font-bold text-primary">70%</span>
                </div>
                <input id="thresholdSlider" type="range" min="30" max="95" value="70" class="w-full accent-primary h-2 bg-surface-container-highest rounded-lg cursor-pointer">
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-outline-variant/15">
            <button id="btnCancelSettings" type="button" class="px-4 py-2.5 rounded-xl text-xs font-semibold hover:bg-surface-container text-on-surface min-h-[44px]">
                Batal
            </button>
            <button id="btnSaveSettings" type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-primary text-on-primary hover:bg-blue-700 shadow-sm min-h-[44px] focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                Terapkan & Muat Model
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- TensorFlow.js Library -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.18.0/dist/tf.min.js"></script>

<!-- MediaPipe Hands & Drawing Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>

<script src="{{ asset('js/penerjemah.js') }}"></script>
@endpush