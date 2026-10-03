@extends('layouts.app')

@section('title', 'Penerjemah SIBI Real-Time (MediaPipe Hands) - SIBI Learn')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/penerjemah.css') }}">
@endpush

@section('content')
<div class="max-w-6xl mx-auto flex flex-col space-y-5 sm:space-y-6 mt-1 md:mt-4 px-1 sm:px-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold mb-1.5">
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                <span>MediaPipe Hands</span>
            </div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight">Penerjemah Bahasa Isyarat SIBI</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5 leading-relaxed">
                Pelacakan 21 titik sendi jari secara langsung dari kamera untuk menerjemahkan isyarat SIBI.
            </p>
        </div>

        <div class="flex items-center self-start sm:self-auto">
            <button id="btnOpenSettings" type="button" class="flex items-center gap-1.5 bg-surface-container-low hover:bg-surface-container text-primary text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-outline-variant/30 transition-all shadow-xs min-h-[44px]">
                <span class="material-symbols-outlined text-base">tune</span>
                <span>Pengaturan Model</span>
            </button>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">
        <!-- Left Column: Camera Feed & Canvas Skeleton (7 Cols) -->
        <div class="lg:col-span-7 flex flex-col space-y-3.5 sm:space-y-4">
            <!-- Viewport Container -->
            <div class="relative w-full aspect-video rounded-2xl sm:rounded-3xl overflow-hidden bg-inverse-surface flex items-center justify-center shadow-lg border border-outline-variant/20">
                <!-- Live Video Element -->
                <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden mirror-mode" autoplay playsinline muted></video>

                <!-- Canvas for MediaPipe Hand Skeleton Overlay -->
                <canvas id="skeletonCanvas" class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden mirror-mode z-10"></canvas>

                <!-- Standby Background Image (Shown when camera is OFF) -->
                <img id="standbyImg" class="absolute inset-0 w-full h-full object-cover opacity-30 transition-opacity duration-300"
                    src="{{ asset('images/laptop-practice.jpg') }}"
                    alt="Camera Standby Background">

                <!-- Standby Status UI Card (Shown when camera is OFF) -->
                <div id="standbyOverlay" class="z-20 flex flex-col items-center gap-2.5 text-center px-4 max-w-sm">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner">
                        <span class="material-symbols-outlined text-3xl text-white opacity-90">videocam_off</span>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-white">Kamera Belum Aktif</h3>
                    <p class="text-xs text-gray-200 leading-relaxed">
                        Klik tombol <strong>"Mulai Kamera"</strong> di bawah untuk mendeteksi isyarat SIBI Anda.
                    </p>
                    <button id="btnQuickStart" type="button" class="mt-1 bg-primary hover:bg-surface-tint text-on-primary font-bold text-xs px-5 py-2.5 rounded-full shadow-md flex items-center gap-1.5 transition-transform active:scale-95 min-h-[44px]">
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                        <span>Aktifkan Kamera Sekarang</span>
                    </button>
                </div>

                <!-- Active Camera HUD Overlay (Shown when camera is ON) -->
                <div id="cameraHud" class="hidden absolute inset-0 pointer-events-none p-3 sm:p-4 flex flex-col justify-between z-20">
                    <!-- Top HUD Bar -->
                    <div class="flex justify-between items-center gap-2">
                        <div class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-full flex items-center gap-1.5 border border-white/15">
                            <span id="hudStatusDot" class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span id="hudStatusText" class="text-[11px] sm:text-xs font-semibold text-white tracking-wide">MediaPipe Siap</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <div class="bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/15 text-[11px] sm:text-xs font-semibold text-emerald-300 flex items-center gap-1">
                                <span>FPS:</span>
                                <span id="fpsDisplay">0</span>
                            </div>
                            <div class="bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/15 text-[11px] sm:text-xs font-semibold text-sky-300 flex items-center gap-1">
                                <span id="handCountDisplay">0 Tangan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Target Focus Frame Guide (Subtle Corner Brackets) -->
                    <div class="relative w-3/4 h-3/4 sm:w-3/5 sm:h-3/5 mx-auto border-2 border-dashed border-primary/40 rounded-2xl flex items-center justify-center bg-primary/5 pointer-events-none">
                        <div class="absolute -top-2.5 px-2.5 py-0.5 bg-primary/90 text-on-primary text-[9px] sm:text-[10px] font-bold tracking-wider uppercase rounded-full shadow">
                            Area Peragaan
                        </div>
                    </div>

                    <!-- Bottom HUD Hint -->
                    <div class="text-center">
                        <span id="bottomHintText" class="text-[10px] sm:text-[11px] text-white/90 bg-black/60 px-3 py-1 rounded-full backdrop-blur-md border border-white/10 inline-block">
                            Arahkan tangan ke kamera untuk melihat sendi tangan
                        </span>
                    </div>
                </div>
            </div>

            <!-- Controls Bar -->
            <div class="flex flex-wrap items-center justify-between gap-2.5 bg-surface-container-low p-3 sm:p-3.5 rounded-2xl border border-outline-variant/20 shadow-xs">
                <div class="flex items-center gap-2">
                    <button id="btnStart" type="button" class="flex items-center justify-center gap-1.5 bg-primary text-on-primary px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-xs min-h-[44px]">
                        <span class="material-symbols-outlined text-lg">videocam</span>
                        <span>Mulai Kamera</span>
                    </button>
                    <button id="btnStop" type="button" class="hidden items-center justify-center gap-1.5 bg-error text-on-error px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-red-700 active:scale-95 transition-all shadow-xs min-h-[44px]">
                        <span class="material-symbols-outlined text-lg">videocam_off</span>
                        <span>Hentikan</span>
                    </button>
                    <button id="btnToggleMirror" type="button" class="bg-surface-container hover:bg-surface-container-high text-on-surface w-11 h-11 min-h-[44px] rounded-xl flex items-center justify-center transition-all" title="Mirror Kamera">
                        <span class="material-symbols-outlined text-lg">flip</span>
                    </button>
                    <button id="btnToggleSkeleton" type="button" class="bg-surface-container hover:bg-surface-container-high text-primary px-3 min-h-[44px] rounded-xl transition-all font-semibold text-xs flex items-center gap-1" title="Tampilkan/Sembunyikan Garis Sendi">
                        <span class="material-symbols-outlined text-base">polyline</span>
                        <span class="hidden sm:inline">Skeleton</span>
                    </button>
                </div>

                <!-- Auto-Ketik Option & Hold Progress Bar -->
                <div class="flex items-center gap-2.5 ml-auto sm:ml-0">
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-on-surface cursor-pointer select-none min-h-[44px]">
                        <input id="toggleAutoType" type="checkbox" checked class="rounded text-primary focus:ring-primary h-4 w-4">
                        <span>Auto-Ketik</span>
                    </label>

                    <div class="w-14 sm:w-16 bg-surface-container-highest rounded-full h-2 overflow-hidden">
                        <div id="holdProgressBar" class="bg-primary h-2 rounded-full transition-all duration-75" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recognition Result & Sentence Builder (5 Cols) -->
        <div class="lg:col-span-5 flex flex-col space-y-4">
            <!-- Active Recognition Card -->
            <div class="bg-surface-container-lowest p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-outline-variant/30 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Hasil Deteksi Isyarat</span>
                    <span id="confidenceBadge" class="text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-surface-container text-primary">
                        -
                    </span>
                </div>

                <div class="flex items-center justify-between gap-3 my-2">
                    <div>
                        <p id="liveDetectedText" class="text-3xl sm:text-4xl font-black text-primary tracking-tight">
                            -
                        </p>
                        <p id="detectedCategory" class="text-xs text-on-surface-variant mt-0.5 font-medium">
                            Kamera belum aktif
                        </p>
                    </div>
                    <button id="btnManualAdd" type="button" class="flex flex-col items-center justify-center p-2.5 sm:p-3 rounded-2xl bg-primary/10 hover:bg-primary/20 text-primary transition-all active:scale-95 text-center min-h-[44px] min-w-[56px]" title="Tambahkan hasil ini ke susunan kalimat">
                        <span class="material-symbols-outlined text-2xl">add_box</span>
                        <span class="text-[10px] font-bold mt-0.5">Tambah</span>
                    </button>
                </div>

                <!-- Top Predictions Probability Breakdown -->
                <div class="mt-3 pt-3 border-t border-outline-variant/15 space-y-2">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">Probabilitas Teratas</span>
                    <div id="topPredictionsContainer" class="space-y-1.5">
                        <div class="text-xs text-on-surface-variant italic">
                            Belum ada data inferensi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sentence Builder Box -->
            <div class="bg-surface-container-lowest p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-outline-variant/30 shadow-xs flex flex-col flex-1">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-xl">short_text</span>
                        <span class="text-xs font-bold text-on-surface uppercase tracking-wider">Susunan Kalimat</span>
                    </div>
                    <span id="charCount" class="text-xs text-on-surface-variant font-mono">0 karakter</span>
                </div>

                <!-- Textarea / Output Display -->
                <div class="relative flex-1 min-h-[90px] sm:min-h-[110px] bg-surface-container-low rounded-2xl p-3.5 border border-outline-variant/20 mb-3">
                    <p id="sentenceOutput" class="text-base sm:text-lg font-medium text-on-surface leading-relaxed break-words">
                        <span class="text-on-surface-variant/60 italic text-xs sm:text-sm">Kalimat hasil terjemahan akan tersusun di sini...</span>
                    </p>
                </div>

                <!-- Sentence Actions & Speech -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button id="btnSpeech" type="button" class="flex items-center justify-center gap-1.5 bg-primary text-on-primary py-2.5 px-3 rounded-xl text-xs font-bold hover:bg-surface-tint active:scale-95 transition-all shadow-xs min-h-[44px]" title="Suarakan dengan Text-To-Speech">
                        <span class="material-symbols-outlined text-base">volume_up</span>
                        <span>Suarakan</span>
                    </button>
                    <button id="btnSpace" type="button" class="flex items-center justify-center gap-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface py-2.5 px-3 rounded-xl text-xs font-bold active:scale-95 transition-all min-h-[44px]">
                        <span class="material-symbols-outlined text-base">space_bar</span>
                        <span>Spasi</span>
                    </button>
                    <button id="btnBackspace" type="button" class="flex items-center justify-center gap-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface py-2.5 px-3 rounded-xl text-xs font-bold active:scale-95 transition-all min-h-[44px]">
                        <span class="material-symbols-outlined text-base">backspace</span>
                        <span>Hapus</span>
                    </button>
                    <button id="btnCopy" type="button" class="flex items-center justify-center gap-1.5 bg-surface-container hover:bg-surface-container-high text-on-surface py-2.5 px-3 rounded-xl text-xs font-bold active:scale-95 transition-all min-h-[44px]">
                        <span class="material-symbols-outlined text-base">content_copy</span>
                        <span id="copyBtnText">Salin</span>
                    </button>
                </div>

                <div class="mt-2.5 text-right">
                    <button id="btnClearAll" type="button" class="text-xs text-error hover:underline font-semibold inline-flex items-center gap-1 min-h-[32px] sm:min-h-0">
                        <span class="material-symbols-outlined text-xs">delete</span>
                        <span>Bersihkan Semua</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Panduan / Tips Section (3 Columns) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 sm:gap-4 pt-2">
        <div class="bg-surface-container-low p-4 sm:p-5 rounded-2xl border border-outline-variant/15 space-y-1.5 sm:space-y-2">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">polyline</span>
            </div>
            <h3 class="font-bold text-on-surface text-sm sm:text-base">21 Titik Sendi Tangan</h3>
            <p class="text-xs sm:text-sm text-on-surface-variant">MediaPipe mendeteksi sendi jari secara presisi dari pergelangan tangan hingga ujung jari.</p>
        </div>
        <div class="bg-surface-container-low p-4 sm:p-5 rounded-2xl border border-outline-variant/15 space-y-1.5 sm:space-y-2">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">center_focus_strong</span>
            </div>
            <h3 class="font-bold text-on-surface text-sm sm:text-base">Posisi Tangan Stabil</h3>
            <p class="text-xs sm:text-sm text-on-surface-variant">Posisikan telapak tangan sejajar dada dan buka jari secara jelas untuk deteksi akurat.</p>
        </div>
        <div class="bg-surface-container-low p-4 sm:p-5 rounded-2xl border border-outline-variant/15 space-y-1.5 sm:space-y-2">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">wb_sunny</span>
            </div>
            <h3 class="font-bold text-on-surface text-sm sm:text-base">Pencahayaan Jelas</h3>
            <p class="text-xs sm:text-sm text-on-surface-variant">Pastikan tangan memiliki pencahayaan cukup dan kontras dengan latar belakang.</p>
        </div>
    </div>
</div>

<div id="settingsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-3xl max-w-lg w-full p-6 border border-outline-variant/30 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-outline-variant/15 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">tune</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface text-base">Pengaturan Model</h3>
                </div>
            </div>
            <button id="btnCloseSettings" type="button" class="p-1.5 rounded-full hover:bg-surface-container text-on-surface-variant">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <div class="space-y-4 text-xs">
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

            <!-- Daftar Label Kelas (Pisahkan dengan koma) -->
            <div class="space-y-1.5">
                <label class="font-bold text-on-surface block">Daftar Label / Kelas SIBI (Pisahkan dengan koma):</label>
                <textarea id="classesInput" rows="3" class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface focus:outline-none focus:border-primary font-mono text-xs">ADIK, APA, AYAH, BAIK, BERAPA, BERTEMU, CANTIK, DARI, DIA, DIMANA, GANTENG, GEMUK, HALLO, HOBI, IBU, JUMAT, JURUSAN, KABAR, KAKEK, KALIAN, KAMI, KAMIS, KAMPUS, KAMU, KELAS, KELUARGA, KEMANA, KENAPA, KITA, KULIAH, KURUS, LUCU, MALAM, MAU, MEREKA, MINGGU, NAMA, PAGI, PELIT, PENDIDIKAN, PINTAR, PULANG, RABU, SABAR, SABTU, SAKIT, SAMPAI JUMPA, SAYA, SEKOLAH, SELAMAT, SELASA, SEMESTER, SENANG, SENIN, SIANG, SIAPA, SORE, TERIMAKASIH, TINGGAL, UMUR</textarea>
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

<script src="{{ asset('js/penerjemah.js') }}"></script>
@endpush