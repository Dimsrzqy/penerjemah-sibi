@extends('layouts.app')

@section('title', 'Penerjemah SIBI Real-Time (MediaPipe Hands) - SIBI Learn')

@push('styles')
<style>
    /* Mirror video and canvas */
    .mirror-mode {
        transform: scaleX(-1);
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto flex flex-col space-y-6 mt-2 md:mt-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                MediaPipe Hands (21 Landmarks)
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight">Penerjemah Bahasa Isyarat SIBI</h1>
            <p class="text-sm text-on-surface-variant mt-1">
                Pelacakan 21 titik sendi jari secara *real-time* langsung dari kamera dengan MediaPipe Hands.
            </p>
        </div>

        <div class="flex items-center gap-2 self-end sm:self-auto">
            <button id="btnOpenSettings" type="button" class="flex items-center gap-2 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold px-4 py-2.5 rounded-xl border border-outline-variant/30 transition-all shadow-sm">
                <span class="material-symbols-outlined text-base text-primary">tune</span>
                <span>Pengaturan Model & Landmark</span>
            </button>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Column: Camera Feed & Canvas Skeleton (7 Cols) -->
        <div class="lg:col-span-7 flex flex-col space-y-4">
            <!-- Viewport Container -->
            <div class="relative w-full aspect-video rounded-3xl overflow-hidden liquid-glass bg-inverse-surface flex items-center justify-center shadow-2xl border-4 border-white/40">
                <!-- Live Video Element -->
                <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden mirror-mode" autoplay playsinline muted></video>

                <!-- Canvas for MediaPipe Hand Skeleton Overlay -->
                <canvas id="skeletonCanvas" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden mirror-mode z-10"></canvas>

                <!-- Standby Background Image (Shown when camera is OFF) -->
                <img id="standbyImg" class="absolute inset-0 w-full h-full object-cover opacity-40 transition-opacity duration-300"
                    src="{{ asset('images/laptop-practice.jpg') }}"
                    alt="Camera Standby Background">

                <!-- Standby Status UI Card (Shown when camera is OFF) -->
                <div id="standbyOverlay" class="z-20 flex flex-col items-center gap-3 text-center px-4 max-w-md">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner mb-1">
                        <span class="material-symbols-outlined text-4xl text-white opacity-90">videocam_off</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white">Kamera Belum Aktif</h3>
                    <p class="text-xs sm:text-sm text-gray-200 leading-relaxed">
                        Klik tombol <strong>"Mulai Kamera"</strong> di bawah untuk mengaktifkan video dan garis sendi tangan MediaPipe.
                    </p>
                    <button id="btnQuickStart" type="button" class="mt-2 bg-primary hover:bg-surface-tint text-on-primary font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full shadow-lg flex items-center gap-2 transition-transform active:scale-95">
                        <span class="material-symbols-outlined text-lg">play_arrow</span>
                        Aktifkan Kamera Sekarang
                    </button>
                </div>

                <!-- Active Camera HUD Overlay (Shown when camera is ON) -->
                <div id="cameraHud" class="hidden absolute inset-0 pointer-events-none p-4 sm:p-5 flex flex-col justify-between z-20">
                    <!-- Top HUD Bar -->
                    <div class="flex justify-between items-center gap-2">
                        <div class="bg-black/60 backdrop-blur-md px-3.5 py-1.5 rounded-full flex items-center gap-2 border border-white/15">
                            <span id="hudStatusDot" class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span id="hudStatusText" class="text-xs font-semibold text-white tracking-wide">MediaPipe Hands Siap</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/15 text-xs font-bold text-emerald-300 flex items-center gap-1">
                                <span>FPS:</span>
                                <span id="fpsDisplay">0</span>
                            </div>
                            <div class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/15 text-xs font-bold text-sky-300 flex items-center gap-1">
                                <span id="handCountDisplay">0 Tangan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Target Focus Frame -->
                    <div class="relative w-3/5 h-3/5 mx-auto border-2 border-dashed border-primary/70 rounded-3xl flex items-center justify-center bg-primary/5 pointer-events-none">
                        <div class="absolute -top-3 px-3 py-0.5 bg-primary text-on-primary text-[10px] font-bold tracking-wider uppercase rounded-full shadow">
                            Area Peragaan Tangan
                        </div>
                        <!-- Crosshairs -->
                        <div class="w-6 h-0.5 bg-primary/40 absolute"></div>
                        <div class="h-6 w-0.5 bg-primary/40 absolute"></div>
                    </div>

                    <!-- Bottom HUD Hint -->
                    <div class="text-center">
                        <span id="bottomHintText" class="text-[11px] text-white/90 bg-black/60 px-3 py-1 rounded-full backdrop-blur-md border border-white/10">
                            Arahkan tangan ke kamera untuk melihat titik landmark sendi tangan
                        </span>
                    </div>
                </div>
            </div>

            <!-- Controls Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 bg-surface-container-low p-3.5 rounded-2xl border border-outline-variant/20 shadow-sm">
                <div class="flex items-center gap-2">
                    <button id="btnStart" type="button" class="flex items-center gap-2 bg-primary text-on-primary px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-lg">videocam</span>
                        <span>Mulai Kamera</span>
                    </button>
                    <button id="btnStop" type="button" class="hidden items-center gap-2 bg-error text-on-error px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-red-700 active:scale-95 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-lg">videocam_off</span>
                        <span>Hentikan</span>
                    </button>
                    <button id="btnToggleMirror" type="button" class="bg-surface-container-high text-on-surface hover:bg-surface-dim p-2.5 rounded-xl transition-all" title="Mirror Kamera">
                        <span class="material-symbols-outlined text-lg">flip</span>
                    </button>
                    <button id="btnToggleSkeleton" type="button" class="bg-surface-container-high text-primary hover:bg-surface-dim p-2.5 rounded-xl transition-all font-semibold text-xs flex items-center gap-1" title="Tampilkan/Sembunyikan Garis Sendi">
                        <span class="material-symbols-outlined text-base">polyline</span>
                        <span class="hidden sm:inline">Skeleton</span>
                    </button>
                </div>

                <!-- Auto-Ketik Option & Hold Progress Bar -->
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-on-surface cursor-pointer select-none">
                        <input id="toggleAutoType" type="checkbox" checked class="rounded text-primary focus:ring-primary h-4 w-4">
                        <span>Auto-Ketik</span>
                    </label>

                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant font-semibold">
                        <div class="w-16 bg-surface-container-highest rounded-full h-2 overflow-hidden">
                            <div id="holdProgressBar" class="bg-primary h-2 rounded-full transition-all duration-75" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recognition Result & Sentence Builder (5 Cols) -->
        <div class="lg:col-span-5 flex flex-col space-y-4">
            <!-- Active Recognition Card -->
            <div class="bg-surface-container-lowest p-5 rounded-3xl border border-surface-container-highest shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Hasil Deteksi Isyarat</span>
                    <span id="confidenceBadge" class="text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant">
                        -
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 my-2">
                    <div>
                        <p id="liveDetectedText" class="text-3xl sm:text-4xl font-black text-primary tracking-tight">
                            -
                        </p>
                        <p id="detectedCategory" class="text-xs text-on-surface-variant mt-1 font-medium">
                            Kamera belum aktif
                        </p>
                    </div>
                    <button id="btnManualAdd" type="button" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-primary/10 hover:bg-primary/20 text-primary transition-all active:scale-95 text-center" title="Tambahkan hasil ini ke susunan kalimat">
                        <span class="material-symbols-outlined text-2xl">add_box</span>
                        <span class="text-[10px] font-bold mt-0.5">Tambah</span>
                    </button>
                </div>

                <!-- Top Predictions Probability Breakdown -->
                <div class="mt-4 pt-3 border-t border-outline-variant/15 space-y-2">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Probabilitas Teratas</span>
                    <div id="topPredictionsContainer" class="space-y-1.5">
                        <div class="text-xs text-on-surface-variant italic">
                            Belum ada data inferensi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sentence Builder Box -->
            <div class="bg-surface-container-lowest p-5 rounded-3xl border border-surface-container-highest shadow-sm flex flex-col flex-1">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">short_text</span>
                        <span class="text-xs font-bold text-on-surface uppercase tracking-wider">Susunan Kalimat</span>
                    </div>
                    <span id="charCount" class="text-xs text-on-surface-variant font-mono">0 karakter</span>
                </div>

                <!-- Textarea / Output Display -->
                <div class="relative flex-1 min-h-[105px] bg-surface-container-low rounded-2xl p-3.5 border border-outline-variant/20 mb-3">
                    <p id="sentenceOutput" class="text-base sm:text-lg font-medium text-on-surface leading-relaxed break-words">
                        <span class="text-on-surface-variant/60 italic text-sm">Kalimat hasil terjemahan akan tersusun di sini...</span>
                    </p>
                </div>

                <!-- Sentence Actions & Speech -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button id="btnSpeech" type="button" class="flex items-center justify-center gap-1.5 bg-primary text-on-primary py-2.5 px-3 rounded-xl text-xs font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-sm" title="Suarakan dengan Text-To-Speech">
                        <span class="material-symbols-outlined text-base">volume_up</span>
                        <span>Suarakan</span>
                    </button>
                    <button id="btnSpace" type="button" class="flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-dim text-on-surface py-2.5 px-3 rounded-xl text-xs font-bold active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-base">space_bar</span>
                        <span>Spasi</span>
                    </button>
                    <button id="btnBackspace" type="button" class="flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-dim text-on-surface py-2.5 px-3 rounded-xl text-xs font-bold active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-base">backspace</span>
                        <span>Hapus</span>
                    </button>
                    <button id="btnCopy" type="button" class="flex items-center justify-center gap-1.5 bg-surface-container-high hover:bg-surface-dim text-on-surface py-2.5 px-3 rounded-xl text-xs font-bold active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-base">content_copy</span>
                        <span id="copyBtnText">Salin</span>
                    </button>
                </div>

                <div class="mt-2 text-right">
                    <button id="btnClearAll" type="button" class="text-xs text-error hover:underline font-semibold inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">delete</span>
                        Bersihkan Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Panduan / Tips Section (3 Columns) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
        <div class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/15 space-y-2">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">polyline</span>
            </div>
            <h3 class="font-bold text-on-surface text-base">21 Titik Sendi Tangan</h3>
            <p class="text-sm text-on-surface-variant">MediaPipe mendeteksi sendi jari secara presisi dari pergelangan tangan hingga ujung jari.</p>
        </div>
        <div class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/15 space-y-2">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">center_focus_strong</span>
            </div>
            <h3 class="font-bold text-on-surface text-base">Posisi Tangan Stabil</h3>
            <p class="text-sm text-on-surface-variant">Posisikan telapak tangan sejajar dada dan buka jari secara jelas untuk deteksi akurat.</p>
        </div>
        <div class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/15 space-y-2">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">wb_sunny</span>
            </div>
            <h3 class="font-bold text-on-surface text-base">Pencahayaan Jelas</h3>
            <p class="text-sm text-on-surface-variant">Pastikan tangan memiliki pencahayaan cukup dan kontras dengan latar belakang.</p>
        </div>
    </div>
</div>

<!-- Modal Pengaturan Model & Fitur Landmark -->
<div id="settingsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-3xl max-w-lg w-full p-6 border border-outline-variant/30 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-outline-variant/15 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">tune</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface text-base">Pengaturan Model & MediaPipe</h3>
                    <p class="text-xs text-on-surface-variant">Konfigurasi input koordinat dan model TensorFlow.js</p>
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
                <p class="text-[11px] text-on-surface-variant">Sesuaikan dengan jumlah input feature tensor saat Anda melatih model Python.</p>
            </div>

            <!-- Path Model Lokal -->
            <div class="space-y-1.5">
                <label class="font-bold text-on-surface block">Lokasi File Model TFJS (model.json):</label>
                <input id="modelUrlInput" type="text"
                    value="/models/sibi_model/model.json"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">
                <p class="text-[11px] text-on-surface-variant">
                    Letakkan file di <code>public/models/sibi_model/model.json</code>.
                </p>
            </div>

            <!-- Daftar Label Kelas (Pisahkan dengan koma) -->
            <div class="space-y-1.5">
                <label class="font-bold text-on-surface block">Daftar Label / Kelas SIBI (Pisahkan dengan koma):</label>
                <textarea id="classesInput" rows="2" class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, TERIMA KASIH, HALO, SAMA-SAMA</textarea>
            </div>

            <!-- Threshold Slider -->
            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label class="font-bold text-on-surface">Ambang Batas Akurasi (Threshold):</label>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Elements
        const video = document.getElementById('webcamVideo');
        const skeletonCanvas = document.getElementById('skeletonCanvas');
        const canvasCtx = skeletonCanvas.getContext('2d');
        const standbyImg = document.getElementById('standbyImg');
        const standbyOverlay = document.getElementById('standbyOverlay');
        const cameraHud = document.getElementById('cameraHud');
        const fpsDisplay = document.getElementById('fpsDisplay');
        const handCountDisplay = document.getElementById('handCountDisplay');
        const hudStatusDot = document.getElementById('hudStatusDot');
        const hudStatusText = document.getElementById('hudStatusText');
        const toggleAutoType = document.getElementById('toggleAutoType');

        // Controls
        const btnStart = document.getElementById('btnStart');
        const btnQuickStart = document.getElementById('btnQuickStart');
        const btnStop = document.getElementById('btnStop');
        const btnToggleMirror = document.getElementById('btnToggleMirror');
        const btnToggleSkeleton = document.getElementById('btnToggleSkeleton');

        // Recognition & Predictions
        const liveDetectedText = document.getElementById('liveDetectedText');
        const detectedCategory = document.getElementById('detectedCategory');
        const confidenceBadge = document.getElementById('confidenceBadge');
        const topPredictionsContainer = document.getElementById('topPredictionsContainer');
        const holdProgressBar = document.getElementById('holdProgressBar');

        // Sentence Box & Actions
        const sentenceOutput = document.getElementById('sentenceOutput');
        const charCount = document.getElementById('charCount');
        const btnManualAdd = document.getElementById('btnManualAdd');
        const btnSpeech = document.getElementById('btnSpeech');
        const btnSpace = document.getElementById('btnSpace');
        const btnBackspace = document.getElementById('btnBackspace');
        const btnCopy = document.getElementById('btnCopy');
        const btnClearAll = document.getElementById('btnClearAll');
        const copyBtnText = document.getElementById('copyBtnText');

        // Settings Modal
        const settingsModal = document.getElementById('settingsModal');
        const btnOpenSettings = document.getElementById('btnOpenSettings');
        const btnCloseSettings = document.getElementById('btnCloseSettings');
        const btnCancelSettings = document.getElementById('btnCancelSettings');
        const btnSaveSettings = document.getElementById('btnSaveSettings');
        const landmarkDimSelect = document.getElementById('landmarkDimSelect');
        const modelUrlInput = document.getElementById('modelUrlInput');
        const classesInput = document.getElementById('classesInput');
        const thresholdSlider = document.getElementById('thresholdSlider');
        const thresholdVal = document.getElementById('thresholdVal');

        // State Variables
        let isCameraRunning = false;
        let isMirror = true;
        let showSkeleton = true;
        let streamInstance = null;
        let animFrameId = null;
        let handsDetector = null;
        let isProcessingFrame = false;

        // AI State
        let tfModel = null;
        let modelUrl = '/models/sibi_model/model.json';
        let confidenceThreshold = 0.70;
        let featureDimension = '63'; // '63' | '42' | '126' | 'normalized_wrist'
        let classLabels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'TERIMA KASIH', 'HALO', 'SAMA-SAMA'];

        // Auto-Type Dwell Logic
        let lastStableWord = '';
        let stableWordCount = 0;
        const DWELL_FRAMES_REQUIRED = 18;
        let currentSentence = '';

        // FPS Tracker
        let frameCount = 0;
        let lastFpsTime = performance.now();

        // ----------------------------------------------------
        // 1. Inisialisasi MediaPipe Hands
        // ----------------------------------------------------
        function initMediaPipeHands() {
            if (typeof Hands === 'undefined') {
                console.error('MediaPipe Hands library belum selesai dimuat dari CDN.');
                return;
            }

            try {
                handsDetector = new Hands({
                    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
                });

                handsDetector.setOptions({
                    maxNumHands: 2,
                    modelComplexity: 1,
                    minDetectionConfidence: 0.5,
                    minTrackingConfidence: 0.5
                });

                handsDetector.onResults(onHandResults);
                console.log('MediaPipe Hands detector berhasil diinisialisasi.');
            } catch (e) {
                console.error('Error inisialisasi MediaPipe:', e);
            }
        }

        // ----------------------------------------------------
        // 2. Callback Menggambar Landmark Skeleton
        // ----------------------------------------------------
        function onHandResults(results) {
            isProcessingFrame = false;
            if (!isCameraRunning) return;

            // FPS Tracker
            frameCount++;
            const now = performance.now();
            if (now - lastFpsTime >= 1000) {
                fpsDisplay.textContent = frameCount;
                frameCount = 0;
                lastFpsTime = now;
            }

            // Sesuaikan resolusi internal canvas dengan video
            if (video.videoWidth > 0 && (skeletonCanvas.width !== video.videoWidth || skeletonCanvas.height !== video.videoHeight)) {
                skeletonCanvas.width = video.videoWidth;
                skeletonCanvas.height = video.videoHeight;
            }

            // Bersihkan canvas setiap frame
            canvasCtx.save();
            canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);

            const hasHands = results.multiHandLandmarks && results.multiHandLandmarks.length > 0;
            handCountDisplay.textContent = hasHands ? `${results.multiHandLandmarks.length} Tangan Terdeteksi` : '0 Tangan';

            if (hasHands) {
                // Gambar Garis Skeleton & Titik Sendi (Warna Terang Berkilau)
                if (showSkeleton) {
                    for (const landmarks of results.multiHandLandmarks) {
                        if (typeof drawConnectors !== 'undefined' && typeof HAND_CONNECTIONS !== 'undefined') {
                            // Garis penghubung sendi (Biru Neon)
                            drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {
                                color: '#00D2FF',
                                lineWidth: 4
                            });
                        }
                        if (typeof drawLandmarks !== 'undefined') {
                            // Titik-titik sendi (Putih dengan lingkaran luar cyan)
                            drawLandmarks(canvasCtx, landmarks, {
                                color: '#0058be',
                                fillColor: '#FFFFFF',
                                lineWidth: 2,
                                radius: 4
                            });
                        }
                    }
                }

                // Ekstraksi Fitur Landmark
                const features = extractLandmarkFeatures(results.multiHandLandmarks);

                // Prediksi jika model TFJS sudah ada
                if (tfModel && features.length > 0) {
                    predictSignGesture(features);
                } else {
                    liveDetectedText.textContent = '-';
                    detectedCategory.textContent = 'Landmark Tangan Aktif (Menunggu model.json)';
                }
            } else {
                liveDetectedText.textContent = '-';
                detectedCategory.textContent = 'Arahkan tangan ke kamera...';
                confidenceBadge.textContent = '-';
                holdProgressBar.style.width = '0%';
                stableWordCount = 0;
            }

            canvasCtx.restore();
        }

        // ----------------------------------------------------
        // 3. Ekstraksi Fitur Landmark
        // ----------------------------------------------------
        function extractLandmarkFeatures(multiHandLandmarks) {
            const hand = multiHandLandmarks[0];
            let rawFeatures = [];

            if (featureDimension === '42') {
                for (let i = 0; i < hand.length; i++) {
                    rawFeatures.push(hand[i].x, hand[i].y);
                }
            } else if (featureDimension === '126') {
                for (let h = 0; h < 2; h++) {
                    if (multiHandLandmarks[h]) {
                        for (let i = 0; i < 21; i++) {
                            rawFeatures.push(multiHandLandmarks[h][i].x, multiHandLandmarks[h][i].y, multiHandLandmarks[h][i].z);
                        }
                    } else {
                        for (let i = 0; i < 63; i++) rawFeatures.push(0.0);
                    }
                }
            } else if (featureDimension === 'normalized_wrist') {
                const wrist = hand[0];
                for (let i = 0; i < hand.length; i++) {
                    rawFeatures.push(hand[i].x - wrist.x, hand[i].y - wrist.y, hand[i].z - wrist.z);
                }
            } else {
                // Default 63 fitur: 21 titik x, y, z
                for (let i = 0; i < hand.length; i++) {
                    rawFeatures.push(hand[i].x, hand[i].y, hand[i].z);
                }
            }

            return rawFeatures;
        }

        // ----------------------------------------------------
        // 4. Prediksi TensorFlow.js
        // ----------------------------------------------------
        function predictSignGesture(features) {
            try {
                tf.tidy(() => {
                    const inputTensor = tf.tensor2d([features], [1, features.length]);
                    const outputTensor = tfModel.predict(inputTensor);
                    const scores = outputTensor.dataSync();

                    let maxIndex = 0;
                    let maxProb = scores[0];
                    let predictions = [];

                    for (let i = 0; i < scores.length; i++) {
                        const label = classLabels[i] || `Kelas ${i + 1}`;
                        predictions.push({
                            className: label,
                            probability: scores[i]
                        });
                        if (scores[i] > maxProb) {
                            maxProb = scores[i];
                            maxIndex = i;
                        }
                    }

                    predictions.sort((a, b) => b.probability - a.probability);
                    renderTopPredictions(predictions.slice(0, 3));

                    const topClass = classLabels[maxIndex] || `Kelas ${maxIndex + 1}`;
                    const confPercent = Math.round(maxProb * 100);

                    if (maxProb >= confidenceThreshold) {
                        liveDetectedText.textContent = topClass.toUpperCase();
                        detectedCategory.textContent = 'Isyarat SIBI Terdeteksi';
                        confidenceBadge.textContent = `${confPercent}% Cocok`;
                        confidenceBadge.className = 'text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300';

                        if (toggleAutoType.checked) {
                            if (topClass === lastStableWord) {
                                stableWordCount++;
                                const progress = Math.min(100, Math.round((stableWordCount / DWELL_FRAMES_REQUIRED) * 100));
                                holdProgressBar.style.width = `${progress}%`;

                                if (stableWordCount === DWELL_FRAMES_REQUIRED) {
                                    appendWordToSentence(topClass);
                                    liveDetectedText.classList.add('scale-110');
                                    setTimeout(() => liveDetectedText.classList.remove('scale-110'), 200);
                                }
                            } else {
                                lastStableWord = topClass;
                                stableWordCount = 1;
                                holdProgressBar.style.width = '5%';
                            }
                        }
                    } else {
                        liveDetectedText.textContent = '...';
                        detectedCategory.textContent = 'Menganalisis pose tangan...';
                        confidenceBadge.textContent = `${confPercent}% (Di bawah threshold)`;
                        confidenceBadge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
                        holdProgressBar.style.width = '0%';
                        stableWordCount = 0;
                    }
                });
            } catch (err) {
                console.error('Error inferensi model:', err);
            }
        }

        function renderTopPredictions(top3) {
            topPredictionsContainer.innerHTML = '';
            top3.forEach(pred => {
                const percent = Math.round(pred.probability * 100);
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between text-xs text-on-surface';
                row.innerHTML = `
                <span class="font-semibold">${pred.className}</span>
                <div class="flex items-center gap-2">
                    <div class="w-16 bg-surface-container-high rounded-full h-1.5 overflow-hidden">
                        <div class="bg-primary h-1.5 rounded-full" style="width: ${percent}%"></div>
                    </div>
                    <span class="font-mono text-[11px] w-7 text-right">${percent}%</span>
                </div>
            `;
                topPredictionsContainer.appendChild(row);
            });
        }

        // ----------------------------------------------------
        // 5. Robust Camera Pipeline & MediaPipe Frame Sender
        // ----------------------------------------------------
        async function startCamera() {
            try {
                if (!handsDetector) initMediaPipeHands();

                // Akses kamera browser langsung via WebRTC getUserMedia
                streamInstance = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        },
                        facingMode: 'user'
                    },
                    audio: false
                });

                video.srcObject = streamInstance;
                await video.play();

                isCameraRunning = true;

                // Tampilkan elemen Video dan Canvas Skeleton
                video.classList.remove('hidden');
                skeletonCanvas.classList.remove('hidden');
                standbyImg.classList.add('hidden');
                standbyOverlay.classList.add('hidden');
                cameraHud.classList.remove('hidden');

                btnStart.classList.add('hidden');
                btnStop.classList.remove('hidden');
                btnStop.classList.add('flex');

                detectedCategory.textContent = 'Mendeteksi titik sendi tangan...';

                // Jalankan loop pengiriman frame ke MediaPipe Hands
                startMediaPipeLoop();
            } catch (err) {
                console.error('Gagal mengakses kamera:', err);
                alert('Tidak dapat mengaktifkan kamera. Pastikan izin kamera telah diberikan di browser.');
            }
        }

        function startMediaPipeLoop() {
            const processFrame = async () => {
                if (!isCameraRunning) return;

                if (handsDetector && video.readyState >= 2 && !isProcessingFrame) {
                    isProcessingFrame = true;
                    try {
                        await handsDetector.send({
                            image: video
                        });
                    } catch (e) {
                        console.warn('Gagal memproses frame MediaPipe:', e);
                        isProcessingFrame = false;
                    }
                }

                animFrameId = requestAnimationFrame(processFrame);
            };

            animFrameId = requestAnimationFrame(processFrame);
        }

        function stopCamera() {
            isCameraRunning = false;
            if (animFrameId) {
                cancelAnimationFrame(animFrameId);
                animFrameId = null;
            }
            if (streamInstance) {
                streamInstance.getTracks().forEach(t => t.stop());
                streamInstance = null;
            }
            video.srcObject = null;

            // Reset Tampilan
            video.classList.add('hidden');
            skeletonCanvas.classList.add('hidden');
            standbyImg.classList.remove('hidden');
            standbyOverlay.classList.remove('hidden');
            cameraHud.classList.add('hidden');

            btnStop.classList.add('hidden');
            btnStop.classList.remove('flex');
            btnStart.classList.remove('hidden');

            // Bersihkan canvas
            canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);
            resetLiveDisplay();
        }

        function resetLiveDisplay() {
            liveDetectedText.textContent = '-';
            detectedCategory.textContent = 'Kamera belum aktif';
            confidenceBadge.textContent = '-';
            fpsDisplay.textContent = '0';
            handCountDisplay.textContent = '0 Tangan';
            holdProgressBar.style.width = '0%';
            topPredictionsContainer.innerHTML = '<div class="text-xs text-on-surface-variant italic">Belum ada data inferensi</div>';
        }

        // ----------------------------------------------------
        // 6. Sentence Builder Actions
        // ----------------------------------------------------
        function appendWordToSentence(word) {
            if (!word || word === '-' || word === '...') return;
            if (word.length === 1) {
                currentSentence += word;
            } else {
                if (currentSentence.length > 0 && !currentSentence.endsWith(' ')) {
                    currentSentence += ' ';
                }
                currentSentence += word + ' ';
            }
            updateSentenceDisplay();
        }

        function updateSentenceDisplay() {
            if (currentSentence.trim() === '') {
                sentenceOutput.innerHTML = '<span class="text-on-surface-variant/60 italic text-sm">Kalimat hasil terjemahan akan tersusun di sini...</span>';
                charCount.textContent = '0 karakter';
            } else {
                sentenceOutput.textContent = currentSentence;
                charCount.textContent = `${currentSentence.length} karakter`;
            }
        }

        btnManualAdd.addEventListener('click', () => {
            const text = liveDetectedText.textContent.trim();
            if (text && text !== '-' && text !== '...') {
                appendWordToSentence(text);
            }
        });

        btnSpace.addEventListener('click', () => {
            currentSentence += ' ';
            updateSentenceDisplay();
        });

        btnBackspace.addEventListener('click', () => {
            currentSentence = currentSentence.slice(0, -1);
            updateSentenceDisplay();
        });

        btnClearAll.addEventListener('click', () => {
            currentSentence = '';
            updateSentenceDisplay();
        });

        btnCopy.addEventListener('click', async () => {
            if (!currentSentence.trim()) return;
            try {
                await navigator.clipboard.writeText(currentSentence);
                copyBtnText.textContent = 'Tersalin!';
                setTimeout(() => copyBtnText.textContent = 'Salin', 1500);
            } catch (e) {
                console.error('Gagal menyalin teks:', e);
            }
        });

        // Text-To-Speech
        btnSpeech.addEventListener('click', () => {
            const text = currentSentence.trim();
            if (!text) {
                alert('Belum ada kalimat untuk disuarakan.');
                return;
            }

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.95;

                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang.includes('id') || v.lang.includes('ID'));
                if (idVoice) utterance.voice = idVoice;

                window.speechSynthesis.speak(utterance);
            } else {
                alert('Browser Anda tidak mendukung Text-to-Speech.');
            }
        });

        // ----------------------------------------------------
        // 7. Load Model TFJS
        // ----------------------------------------------------
        async function loadModel() {
            try {
                tfModel = await tf.loadLayersModel(modelUrl);
                hudStatusText.textContent = 'Model TFJS Siap';
                hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';
            } catch (e) {
                try {
                    tfModel = await tf.loadGraphModel(modelUrl);
                    hudStatusText.textContent = 'Model TFJS Siap';
                    hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';
                } catch (err) {
                    tfModel = null;
                    hudStatusText.textContent = 'MediaPipe Hands Siap';
                    hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400';
                }
            }
        }

        // ----------------------------------------------------
        // 8. Event Listeners
        // ----------------------------------------------------
        btnStart.addEventListener('click', startCamera);
        btnQuickStart.addEventListener('click', startCamera);
        btnStop.addEventListener('click', stopCamera);

        btnToggleMirror.addEventListener('click', () => {
            isMirror = !isMirror;
            if (isMirror) {
                video.classList.add('mirror-mode');
                skeletonCanvas.classList.add('mirror-mode');
            } else {
                video.classList.remove('mirror-mode');
                skeletonCanvas.classList.remove('mirror-mode');
            }
        });

        btnToggleSkeleton.addEventListener('click', () => {
            showSkeleton = !showSkeleton;
            btnToggleSkeleton.classList.toggle('text-primary');
            btnToggleSkeleton.classList.toggle('text-on-surface-variant');
        });

        thresholdSlider.addEventListener('input', (e) => {
            thresholdVal.textContent = `${e.target.value}%`;
        });

        btnOpenSettings.addEventListener('click', () => {
            modelUrlInput.value = modelUrl;
            landmarkDimSelect.value = featureDimension;
            classesInput.value = classLabels.join(', ');
            thresholdSlider.value = Math.round(confidenceThreshold * 100);
            thresholdVal.textContent = `${thresholdSlider.value}%`;
            settingsModal.classList.remove('hidden');
            settingsModal.classList.add('flex');
        });

        function closeSettings() {
            settingsModal.classList.add('hidden');
            settingsModal.classList.remove('flex');
        }

        btnCloseSettings.addEventListener('click', closeSettings);
        btnCancelSettings.addEventListener('click', closeSettings);

        btnSaveSettings.addEventListener('click', async () => {
            modelUrl = modelUrlInput.value.trim();
            featureDimension = landmarkDimSelect.value;
            classLabels = classesInput.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
            confidenceThreshold = parseInt(thresholdSlider.value, 10) / 100;

            closeSettings();
            await loadModel();
        });

        // Inisialisasi MediaPipe dan Model
        initMediaPipeHands();
        loadModel();
    });
</script>
@endpush