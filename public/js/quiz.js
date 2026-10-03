/**
 * ============================================================
 * SIBI LEARN - QUIZ INTERAKTIF JAVASCRIPT
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // CSRF Token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Route configuration from DOM or defaults
    const configEl = document.getElementById('quiz-config');
    let routes = {
        guest: '/quiz/guest',
        questions: '/quiz/questions',
        score: '/quiz/score',
        resetSession: '/quiz/reset-session'
    };

    if (configEl && configEl.textContent.trim()) {
        try {
            const parsed = JSON.parse(configEl.textContent);
            if (parsed.routes) {
                routes = Object.assign(routes, parsed.routes);
            }
        } catch (e) {
            console.error('Error parsing quiz config JSON:', e);
        }
    }

    // UI Steps Containers
    const stepNameContainer = document.getElementById('stepNameContainer');
    const stepDifficultyContainer = document.getElementById('stepDifficultyContainer');
    const stepQuizArenaContainer = document.getElementById('stepQuizArenaContainer');
    const stepSummaryContainer = document.getElementById('stepSummaryContainer');

    // Step 1: Name Elements
    const formGuestName = document.getElementById('formGuestName');
    const guestNameInput = document.getElementById('guestNameInput');
    const guestNameError = document.getElementById('guestNameError');
    const btnSubmitName = document.getElementById('btnSubmitName');

    // Camera Elements
    const video = document.getElementById('webcamVideo');
    const skeletonCanvas = document.getElementById('skeletonCanvas');
    const canvasCtx = skeletonCanvas ? skeletonCanvas.getContext('2d') : null;
    const standbyImg = document.getElementById('standbyImg');
    const standbyOverlay = document.getElementById('standbyOverlay');
    const cameraHud = document.getElementById('cameraHud');
    const btnStartCam = document.getElementById('btnStartCam');
    const btnQuickStart = document.getElementById('btnQuickStart');
    const btnStopCam = document.getElementById('btnStopCam');
    const btnToggleMirror = document.getElementById('btnToggleMirror');
    const btnOpenSettings = document.getElementById('btnOpenSettings');
    const hudStatusText = document.getElementById('hudStatusText');
    const hudStatusDot = document.getElementById('hudStatusDot');
    const aiModelBadge = document.getElementById('aiModelBadge');
    const aiModelDot = document.getElementById('aiModelDot');
    const aiModelStatus = document.getElementById('aiModelStatus');
    const liveAiPredictionBadge = document.getElementById('liveAiPredictionBadge');
    const liveAiPredText = document.getElementById('liveAiPredText');
    const handCountText = document.getElementById('handCountText');
    const bottomHintText = document.getElementById('bottomHintText');
    const holdProgressWrapper = document.getElementById('holdProgressWrapper');
    const holdProgressBar = document.getElementById('holdProgressBar');
    const successNotice = document.getElementById('successNotice');
    const successNoticeText = document.getElementById('successNoticeText');
    const timeoutNotice = document.getElementById('timeoutNotice');
    const timeoutNoticeText = document.getElementById('timeoutNoticeText');
    const btnSimulateMatch = document.getElementById('btnSimulateMatch');
    const btnNextQuestion = document.getElementById('btnNextQuestion');

    // 10-Second Question Timer Elements
    const questionTimerContainer = document.getElementById('questionTimerContainer');
    const questionTimerText = document.getElementById('questionTimerText');
    const questionTimerBar = document.getElementById('questionTimerBar');
    const timerIconWrapper = document.getElementById('timerIconWrapper');

    // Settings Modal Elements
    const settingsModal = document.getElementById('settingsModal');
    const btnCloseSettings = document.getElementById('btnCloseSettings');
    const btnCancelSettings = document.getElementById('btnCancelSettings');
    const btnSaveSettings = document.getElementById('btnSaveSettings');
    const landmarkDimSelect = document.getElementById('landmarkDimSelect');
    const modelUrlInput = document.getElementById('modelUrlInput');
    const classesInput = document.getElementById('classesInput');
    const thresholdSlider = document.getElementById('thresholdSlider');
    const thresholdVal = document.getElementById('thresholdVal');

    // Step 2: Difficulty Elements
    const displayNameBadge = document.getElementById('displayNameBadge');
    const difficultyCards = document.querySelectorAll('.difficulty-card');
    const btnBackToName = document.getElementById('btnBackToName');
    const btnSwitchPlayer = document.getElementById('btnSwitchPlayer');

    // Step 3: Arena Elements
    const playerAvatarLetter = document.getElementById('playerAvatarLetter');
    const playerActiveName = document.getElementById('playerActiveName');
    const activeDifficultyBadge = document.getElementById('activeDifficultyBadge');
    const quizScoreText = document.getElementById('quizScoreText');
    const quizMaxScoreText = document.getElementById('quizMaxScoreText');
    const pointsPerQBadge = document.getElementById('pointsPerQBadge');
    const currentQNumEl = document.getElementById('currentQNum');
    const totalQNumEl = document.getElementById('totalQNum');
    const progressBarFill = document.getElementById('progressBarFill');
    const targetWordsContainer = document.getElementById('targetWordsContainer');

    // Step 4: Summary Elements
    const summaryGuestName = document.getElementById('summaryGuestName');
    const summaryDifficultyText = document.getElementById('summaryDifficultyText');
    const finalScoreDisplay = document.getElementById('finalScoreDisplay');
    const finalMaxScoreDisplay = document.getElementById('finalMaxScoreDisplay');
    const summaryCorrectCount = document.getElementById('summaryCorrectCount');
    const summaryWrongCount = document.getElementById('summaryWrongCount');
    const summaryBtnName = document.getElementById('summaryBtnName');
    const scoreSavedNotification = document.getElementById('scoreSavedNotification');
    const btnPlayAgain = document.getElementById('btnPlayAgain');
    const btnSwitchUserSummary = document.getElementById('btnSwitchUserSummary');

    // Inactivity Modal Elements
    const inactivityModal = document.getElementById('inactivityModal');
    const btnInactivityAcknowledge = document.getElementById('btnInactivityAcknowledge');

    // State variables
    let currentGuest = null; // { guest_id, name }
    let selectedDifficulty = 'mudah';
    let questions = [];
    let currentQuestionIndex = 0;
    let subWords = []; // Array of words in current question
    let currentSubWordIndex = 0;
    let totalScore = 0;
    let maxScore = 100;
    let pointsPerQuestion = 10;
    let isQuestionCompleted = false;

    // 10-Second Question Timer & Scoring Stats State
    const QUESTION_TIME_LIMIT = 10;
    let timeLeft = 10;
    let questionTimerInterval = null;
    let correctCount = 0;
    let wrongCount = 0;
    let autoAdvanceTimeout = null;

    // Camera & MediaPipe State
    let isCameraRunning = false;
    let isMirror = true;
    let streamInstance = null;
    let animFrameId = null;
    let handsDetector = null;
    let isProcessingFrame = false;
    let holdFrames = 0;
    const HOLD_FRAMES_TARGET = 10;
    let handLostFrames = 0;
    const HAND_LOST_TOLERANCE = 8;

    // Deep Learning AI State (TensorFlow.js)
    let tfModel = null;
    let modelUrl = '/models/tfjs_model/model.json';
    let featureDimension = '126'; // '126' (2 tangan 3D/2D) | '63' | '42' | 'normalized_wrist'
    let confidenceThreshold = 0.70;
    let classLabels = [
        'ADIK', 'APA', 'AYAH', 'BAIK', 'BERAPA', 'BERTEMU', 'CANTIK', 'DARI', 'DIA', 'DIMANA',
        'GANTENG', 'GEMUK', 'HALLO', 'HOBI', 'IBU', 'JUMAT', 'JURUSAN', 'KABAR', 'KAKEK', 'KALIAN',
        'KAMI', 'KAMIS', 'KAMPUS', 'KAMU', 'KELAS', 'KELUARGA', 'KEMANA', 'KENAPA', 'KITA', 'KULIAH',
        'KURUS', 'LUCU', 'MALAM', 'MAU', 'MEREKA', 'MINGGU', 'NAMA', 'PAGI', 'PELIT', 'PENDIDIKAN',
        'PINTAR', 'PULANG', 'RABU', 'SABAR', 'SABTU', 'SAKIT', 'SAMPAI JUMPA', 'SAYA', 'SEKOLAH', 'SELAMAT',
        'SELASA', 'SEMESTER', 'SENANG', 'SENIN', 'SIANG', 'SIAPA', 'SORE', 'TERIMAKASIH', 'TINGGAL', 'UMUR'
    ];
    let sequenceBuffer = [];
    const SEQUENCE_LENGTH = 30;

    // Inference Throttler
    let lastInferTime = 0;
    const INFER_INTERVAL_MS = 100;
    let isInferring = false;

    // Offscreen Processing Canvas untuk ekstraksi cepat
    let procCanvas = document.createElement('canvas');
    let procCtx = procCanvas.getContext('2d', { willReadFrequently: true });
    procCanvas.width = 360;
    procCanvas.height = 270;

    // ----------------------------------------------------
    // INACTIVITY MONITOR: Deteksi Tidak Ada Pergerakan (3 Menit)
    // ----------------------------------------------------
    const INACTIVITY_LIMIT_MS = 3 * 60 * 1000; // 3 Menit (180.000 ms)
    let inactivityTimer = null;
    let lastActivityTime = Date.now();

    function registerActivity() {
        lastActivityTime = Date.now();
        resetInactivityTimer();
    }

    function resetInactivityTimer() {
        if (inactivityTimer) {
            clearTimeout(inactivityTimer);
        }

        // Inactivity timer aktif hanya jika pengguna sudah memiliki sesi login aktif
        if (!currentGuest) return;

        inactivityTimer = setTimeout(() => {
            triggerInactivityTimeout();
        }, INACTIVITY_LIMIT_MS);
    }

    function triggerInactivityTimeout() {
        console.warn('Inactivity timeout: Tidak ada pergerakan selama 3 menit.');
        stopCamera();
        currentGuest = null;
        sessionStorage.removeItem('quiz_guest');

        // Reset sesi di Laravel backend
        fetch(routes.resetSession, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).catch(() => {});

        // Tampilkan modal sesi berakhir
        if (inactivityModal) {
            inactivityModal.classList.remove('hidden');
            inactivityModal.classList.add('flex');
        } else {
            showStep('name');
        }
    }

    // Listener aktivitas pergerakan: mouse, sentuhan, ketukan keyboard, scroll
    ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(evt => {
        window.addEventListener(evt, () => {
            if (Date.now() - lastActivityTime > 1000) {
                registerActivity();
            }
        }, { passive: true });
    });

    if (btnInactivityAcknowledge) {
        btnInactivityAcknowledge.addEventListener('click', () => {
            if (inactivityModal) {
                inactivityModal.classList.add('hidden');
                inactivityModal.classList.remove('flex');
            }
            if (guestNameInput) guestNameInput.value = '';
            if (guestNameError) guestNameError.classList.add('hidden');
            showStep('name');
        });
    }

    // ----------------------------------------------------
    // Step Navigation
    // ----------------------------------------------------
    function showStep(step) {
        if (stepNameContainer) stepNameContainer.classList.add('hidden');
        if (stepDifficultyContainer) stepDifficultyContainer.classList.add('hidden');
        if (stepQuizArenaContainer) {
            stepQuizArenaContainer.classList.add('hidden');
            stepQuizArenaContainer.classList.remove('flex');
        }
        if (stepSummaryContainer) stepSummaryContainer.classList.add('hidden');

        if (step === 'name') {
            if (stepNameContainer) stepNameContainer.classList.remove('hidden');
        } else if (step === 'difficulty') {
            if (stepDifficultyContainer) stepDifficultyContainer.classList.remove('hidden');
        } else if (step === 'arena') {
            if (stepQuizArenaContainer) {
                stepQuizArenaContainer.classList.remove('hidden');
                stepQuizArenaContainer.classList.add('flex');
            }
        } else if (step === 'summary') {
            if (stepSummaryContainer) stepSummaryContainer.classList.remove('hidden');
            stopCamera();
        }
    }

    // ----------------------------------------------------
    // INITIAL SESSION RESTORATION (GUEST REUSE)
    // ----------------------------------------------------
    let serverGuest = null;
    try {
        const guestScriptEl = document.getElementById('session-guest-data');
        if (guestScriptEl && guestScriptEl.textContent.trim()) {
            serverGuest = JSON.parse(guestScriptEl.textContent);
        }
    } catch (e) {}

    let cachedGuest = null;
    try {
        cachedGuest = JSON.parse(sessionStorage.getItem('quiz_guest') || 'null');
    } catch (e) {}

    const initialGuest = serverGuest || cachedGuest;
    if (initialGuest && initialGuest.guest_id && initialGuest.name) {
        currentGuest = initialGuest;
        sessionStorage.setItem('quiz_guest', JSON.stringify(currentGuest));
        applyGuestToUI(currentGuest);
        showStep('difficulty');
        resetInactivityTimer();
    } else {
        showStep('name');
    }

    function applyGuestToUI(guest) {
        if (displayNameBadge) displayNameBadge.textContent = guest.name;
        if (playerActiveName) playerActiveName.textContent = guest.name;
        if (summaryGuestName) summaryGuestName.textContent = guest.name;
        if (playerAvatarLetter) playerAvatarLetter.textContent = (guest.name.charAt(0) || 'G').toUpperCase();
        if (summaryBtnName) {
            summaryBtnName.textContent = `Sebagai ${guest.name}`;
        }
    }

    // ----------------------------------------------------
    // STEP 1: Guest Name Submission
    // ----------------------------------------------------
    if (formGuestName) {
        formGuestName.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = guestNameInput.value.trim();
            if (!name) {
                guestNameError.textContent = 'Silakan masukkan nama Anda.';
                guestNameError.classList.remove('hidden');
                return;
            }

            guestNameError.classList.add('hidden');
            btnSubmitName.disabled = true;
            btnSubmitName.classList.add('opacity-75');

            try {
                const res = await fetch(routes.guest, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: name })
                });

                const data = await res.json();
                if (data.success && data.guest) {
                    currentGuest = data.guest;
                    sessionStorage.setItem('quiz_guest', JSON.stringify(currentGuest));
                    applyGuestToUI(currentGuest);
                    showStep('difficulty');
                    resetInactivityTimer();
                } else {
                    guestNameError.textContent = data.message || 'Gagal mendaftarkan nama guest.';
                    guestNameError.classList.remove('hidden');
                }
            } catch (err) {
                console.error('Error storeGuest:', err);
                // Fallback offline / local guest
                currentGuest = { guest_id: Date.now(), name: name };
                sessionStorage.setItem('quiz_guest', JSON.stringify(currentGuest));
                applyGuestToUI(currentGuest);
                showStep('difficulty');
                resetInactivityTimer();
            } finally {
                btnSubmitName.disabled = false;
                btnSubmitName.classList.remove('opacity-75');
            }
        });
    }

    // Fungsi ganti pemain baru (membersihkan session & penyimpanan lokal)
    function switchToNewUser() {
        stopCamera();
        currentGuest = null;
        sessionStorage.removeItem('quiz_guest');
        if (inactivityTimer) clearTimeout(inactivityTimer);

        fetch(routes.resetSession, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).catch(() => {});

        if (guestNameInput) guestNameInput.value = '';
        if (guestNameError) guestNameError.classList.add('hidden');
        showStep('name');
    }

    if (btnSwitchPlayer) btnSwitchPlayer.addEventListener('click', switchToNewUser);
    if (btnBackToName) btnBackToName.addEventListener('click', switchToNewUser);
    if (btnSwitchUserSummary) btnSwitchUserSummary.addEventListener('click', switchToNewUser);

    // ----------------------------------------------------
    // STEP 2: Difficulty Selection (Terkoneksi ke tabel quizzes)
    // ----------------------------------------------------
    difficultyCards.forEach(card => {
        const handleSelectDifficulty = async () => {
            const diff = card.getAttribute('data-difficulty');
            selectedDifficulty = diff;
            await startQuizWithDifficulty(diff);
        };

        card.addEventListener('click', handleSelectDifficulty);
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handleSelectDifficulty();
            }
        });
    });

    async function startQuizWithDifficulty(diff) {
        selectedDifficulty = diff;

        // Batas maksimal skor per tingkatan kesulitan
        if (diff === 'mudah') {
            maxScore = 100;
            if (activeDifficultyBadge) {
                activeDifficultyBadge.textContent = 'Mudah';
                activeDifficultyBadge.className = 'text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full bg-primary/10 text-primary';
            }
            if (summaryDifficultyText) summaryDifficultyText.textContent = 'Mudah (Kata Tunggal · Maks. 100 Poin)';
        } else if (diff === 'sedang') {
            maxScore = 250;
            if (activeDifficultyBadge) {
                activeDifficultyBadge.textContent = 'Sedang';
                activeDifficultyBadge.className = 'text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full bg-primary/10 text-primary';
            }
            if (summaryDifficultyText) summaryDifficultyText.textContent = 'Sedang (Kombinasi 2 Kata · Maks. 250 Poin)';
        } else {
            maxScore = 500;
            if (activeDifficultyBadge) {
                activeDifficultyBadge.textContent = 'Susah';
                activeDifficultyBadge.className = 'text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full bg-primary/10 text-primary';
            }
            if (summaryDifficultyText) summaryDifficultyText.textContent = 'Susah (Kalimat SPOK · Maks. 500 Poin)';
        }

        // Fetch questions from backend directly from quizzes table
        try {
            const endpoint = `${routes.questions}/${diff}`;
            const res = await fetch(endpoint);
            const data = await res.json();
            if (data.success && data.questions && data.questions.length > 0) {
                questions = data.questions;
                if (data.max_score) maxScore = data.max_score;
            } else {
                questions = getFallbackQuestions(diff);
            }
        } catch (e) {
            console.warn('Fallback questions from database:', e);
            questions = getFallbackQuestions(diff);
        }

        // Hitung poin per soal secara proporsional sesuai jumlah soal
        const qCount = questions.length || 1;
        pointsPerQuestion = Math.max(1, Math.floor(maxScore / qCount));

        // Reset quiz states
        currentQuestionIndex = 0;
        totalScore = 0;
        correctCount = 0;
        wrongCount = 0;
        if (quizScoreText) quizScoreText.textContent = '0';
        if (quizMaxScoreText) quizMaxScoreText.textContent = `/ ${maxScore} pts`;
        if (pointsPerQBadge) pointsPerQBadge.textContent = `+${pointsPerQuestion} pts / soal`;
        if (totalQNumEl) totalQNumEl.textContent = questions.length;

        showStep('arena');
        loadQuestion(0);
        resetInactivityTimer();

        // Auto trigger camera & load model
        if (!isCameraRunning) {
            startCamera();
        }
        if (!tfModel) {
            loadModel();
        }
    }

    function getFallbackQuestions(diff) {
        if (diff === 'mudah') {
            return [
                { word_target: 'SAYA', difficulty: 'mudah' },
                { word_target: 'KULIAH', difficulty: 'mudah' },
                { word_target: 'KAMPUS', difficulty: 'mudah' },
                { word_target: 'ADIK', difficulty: 'mudah' },
                { word_target: 'APA', difficulty: 'mudah' }
            ];
        } else if (diff === 'sedang') {
            return [
                { word_target: 'TERIMA KASIH', difficulty: 'sedang' },
                { word_target: 'SELAMAT PAGI', difficulty: 'sedang' },
                { word_target: 'SELAMAT MALAM', difficulty: 'sedang' },
                { word_target: 'SAMPAI JUMPA', difficulty: 'sedang' },
                { word_target: 'KABAR BAIK', difficulty: 'sedang' }
            ];
        } else {
            return [
                { word_target: 'SAYA KULIAH KAMPUS', difficulty: 'susah' },
                { word_target: 'AYAH PULANG SORE', difficulty: 'susah' },
                { word_target: 'IBU SENANG BERTEMU', difficulty: 'susah' },
                { word_target: 'KAMI BERTEMU SENIN PAGI', difficulty: 'susah' },
                { word_target: 'ADIK SEKOLAH SIANG', difficulty: 'susah' }
            ];
        }
    }

    // ----------------------------------------------------
    // STEP 3: Question Display & Sequential Word Chips
    // ----------------------------------------------------
    function loadQuestion(index) {
        isQuestionCompleted = false;
        sequenceBuffer = [];
        holdFrames = 0;
        if (btnNextQuestion) btnNextQuestion.classList.add('hidden');
        if (btnSimulateMatch) btnSimulateMatch.classList.remove('hidden');

        if (autoAdvanceTimeout) {
            clearTimeout(autoAdvanceTimeout);
            autoAdvanceTimeout = null;
        }

        const q = questions[index];
        if (currentQNumEl) currentQNumEl.textContent = index + 1;

        const progressPct = ((index + 1) / questions.length) * 100;
        if (progressBarFill) progressBarFill.style.width = `${progressPct}%`;

        // Split sentence into words
        const rawTarget = (q.word_target || '').trim();
        subWords = rawTarget.split(/\s+/).filter(w => w.length > 0);
        currentSubWordIndex = 0;

        renderWordChips();
        resetInactivityTimer();

        // Mulai hitung mundur 10 detik untuk soal ini
        startQuestionCountdown();
    }

    // ----------------------------------------------------
    // COUNTDOWN TIMER 10 DETIK PER SOAL
    // ----------------------------------------------------
    function startQuestionCountdown() {
        stopQuestionTimer();
        timeLeft = QUESTION_TIME_LIMIT;
        updateTimerUI();

        questionTimerInterval = setInterval(() => {
            timeLeft--;
            updateTimerUI();

            if (timeLeft <= 0) {
                stopQuestionTimer();
                handleQuestionTimeout();
            }
        }, 1000);
    }

    function stopQuestionTimer() {
        if (questionTimerInterval) {
            clearInterval(questionTimerInterval);
            questionTimerInterval = null;
        }
    }

    function updateTimerUI() {
        if (!questionTimerText || !questionTimerBar) return;

        questionTimerText.textContent = `${Math.max(0, timeLeft)}s`;
        const pct = Math.max(0, Math.min(100, (timeLeft / QUESTION_TIME_LIMIT) * 100));
        questionTimerBar.style.width = `${pct}%`;

        if (timeLeft <= 3) {
            questionTimerBar.className = 'h-full bg-rose-500 w-full rounded-full transition-all';
            questionTimerText.className = 'text-xs sm:text-sm font-black text-rose-600 tracking-tight timer-pulse-danger min-w-[22px]';
            if (timerIconWrapper) {
                timerIconWrapper.className = 'w-6 h-6 rounded-lg bg-rose-500/15 text-rose-600 flex items-center justify-center transition-colors shrink-0';
            }
            if (questionTimerContainer) {
                questionTimerContainer.classList.add('border-rose-400/40', 'bg-rose-500/5');
            }
        } else if (timeLeft <= 5) {
            questionTimerBar.className = 'h-full bg-amber-500 w-full rounded-full transition-all';
            questionTimerText.className = 'text-xs sm:text-sm font-black text-amber-600 tracking-tight min-w-[22px]';
            if (timerIconWrapper) {
                timerIconWrapper.className = 'w-6 h-6 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center transition-colors shrink-0';
            }
            if (questionTimerContainer) {
                questionTimerContainer.classList.remove('border-rose-400/40', 'bg-rose-500/5');
            }
        } else {
            questionTimerBar.className = 'h-full bg-primary w-full rounded-full transition-all';
            questionTimerText.className = 'text-xs sm:text-sm font-black text-on-surface tracking-tight min-w-[22px]';
            if (timerIconWrapper) {
                timerIconWrapper.className = 'w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center transition-colors shrink-0';
            }
            if (questionTimerContainer) {
                questionTimerContainer.classList.remove('border-rose-400/40', 'bg-rose-500/5');
            }
        }
    }

    // Penanganan ketika waktu 10 detik habis (Jawaban Salah / 0 Poin)
    function handleQuestionTimeout() {
        if (isQuestionCompleted) return;
        isQuestionCompleted = true;
        wrongCount++;

        // Reset progress tahan isyarat
        holdFrames = 0;
        if (holdProgressBar) holdProgressBar.style.width = '0%';
        if (holdProgressWrapper) holdProgressWrapper.classList.add('hidden');

        // Style kata menjadi gagal/waktu habis
        if (targetWordsContainer) {
            const chips = targetWordsContainer.querySelectorAll('.word-step-card, div');
            chips.forEach(chip => {
                chip.classList.remove('from-blue-600', 'via-indigo-600', 'to-blue-700', 'bg-primary', 'word-step-active', 'ring-2', 'ring-4', 'ring-blue-400/60', 'ring-primary/40', 'bg-emerald-500', 'text-white');
                if (chip.children && chip.children.length > 0) {
                    chip.classList.add('bg-rose-500/15', 'text-rose-600', 'border', 'border-rose-500/40');
                }
            });
        }

        // Tampilkan notifikasi visual merah Waktu Habis
        if (timeoutNotice && timeoutNoticeText) {
            timeoutNoticeText.textContent = 'Waktu 10 Detik Habis! (+0 Poin)';
            timeoutNotice.classList.remove('hidden');
            setTimeout(() => {
                timeoutNotice.classList.add('hidden');
            }, 1400);
        }

        if (bottomHintText) {
            bottomHintText.textContent = 'Waktu 10 detik habis! Seluruh kata harus selesai sebelum 10 detik (+0 Poin).';
            bottomHintText.className = 'text-[10px] text-rose-300 bg-rose-950/80 px-3 py-0.5 rounded-full backdrop-blur-md border border-rose-500/30';
        }

        if (btnSimulateMatch) btnSimulateMatch.classList.add('hidden');

        // Otomatis lanjut ke soal selanjutnya setelah jeda 1.4 detik
        autoAdvanceTimeout = setTimeout(() => {
            if (currentQuestionIndex + 1 < questions.length) {
                currentQuestionIndex++;
                loadQuestion(currentQuestionIndex);
            } else {
                finishQuizAndSaveScore();
            }
        }, 1400);
    }

    function renderWordChips() {
        if (!targetWordsContainer) return;
        targetWordsContainer.innerHTML = '';

        const targetPromptBadge = document.getElementById('targetPromptBadge');
        const targetPromptSubtitle = document.getElementById('targetPromptSubtitle');

        if (subWords.length === 1) {
            // Single word (Tingkat Mudah)
            if (targetPromptBadge) targetPromptBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-primary animate-ping"></span><span>Tantangan 1 Kata (10s)</span>';
            if (targetPromptSubtitle) {
                targetPromptSubtitle.innerHTML = 'Peragakan kata di bawah sebelum waktu <strong>10 detik</strong> habis:';
            }

            const isMatched = currentSubWordIndex > 0;
            const chip = document.createElement('div');
            chip.className = `word-step-card px-5 py-1.5 sm:py-2 rounded-xl font-extrabold text-sm sm:text-base tracking-wider transition-all shadow-xs flex items-center gap-2 min-w-[140px] justify-center ${
                isMatched 
                    ? 'bg-emerald-500 text-white shadow-emerald-500/20 ring-2 ring-emerald-400/50' 
                    : 'bg-primary text-on-primary word-step-active ring-2 ring-primary/40'
            }`;

            chip.innerHTML = `
                <div class="flex items-center gap-1 text-[9px] font-black uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">
                    ${isMatched 
                        ? '<span class="material-symbols-outlined text-[10px]">check</span><span>Selesai</span>' 
                        : '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span><span>Peragakan</span>'
                    }
                </div>
                <div class="flex items-center gap-1.5">
                    <span>${subWords[0]}</span>
                    ${isMatched ? '<span class="material-symbols-outlined text-base">check_circle</span>' : ''}
                </div>
            `;
            targetWordsContainer.appendChild(chip);
        } else {
            // Multi-word (Tingkat Sedang 2 Kata / Tingkat Susah 3-4 Kata)
            if (targetPromptBadge) {
                targetPromptBadge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-primary animate-ping"></span><span>Rangkaian ${subWords.length} Kata (10s)</span>`;
            }
            if (targetPromptSubtitle) {
                targetPromptSubtitle.innerHTML = `Peragakan <strong>${subWords.length} kata</strong> berurutan (total <strong>10s</strong>):`;
            }

            subWords.forEach((word, idx) => {
                const isPassed = idx < currentSubWordIndex;
                const isCurrent = idx === currentSubWordIndex;
                const isUpcoming = idx > currentSubWordIndex;

                // Tanda panah penghubung antar kata dalam rangkaian
                if (idx > 0) {
                    const arrowEl = document.createElement('div');
                    arrowEl.className = `flex items-center justify-center px-0.5 transition-all ${
                        isPassed ? 'text-emerald-500 font-bold' : (isCurrent ? 'text-primary font-bold' : 'text-outline-variant/60')
                    }`;
                    arrowEl.innerHTML = `
                        <span class="material-symbols-outlined text-sm sm:text-base ${isCurrent ? 'step-arrow-pulse' : ''}">
                            arrow_forward
                        </span>
                    `;
                    targetWordsContainer.appendChild(arrowEl);
                }

                const card = document.createElement('div');

                if (isPassed) {
                    // SUDAH TERVERIFIKASI
                    card.className = 'word-step-card px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-xl font-extrabold transition-all shadow-xs bg-emerald-500 text-white flex items-center gap-1.5 text-xs sm:text-sm';
                    card.innerHTML = `
                        <span class="text-[9px] font-mono bg-white/20 px-1.5 py-0.2 rounded-md">${idx + 1}</span>
                        <span class="tracking-wide">${word}</span>
                        <span class="material-symbols-outlined text-xs">check_circle</span>
                    `;
                } else if (isCurrent) {
                    // SEDANG AKTIF (PERAGAKAN SEKARANG)
                    card.className = 'word-step-card px-3.5 py-1.5 sm:px-4 sm:py-1.5 rounded-xl font-extrabold transition-all shadow-sm ring-2 ring-primary/40 bg-primary text-on-primary flex items-center gap-1.5 text-xs sm:text-sm word-step-active';
                    card.innerHTML = `
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span class="text-[9px] font-mono bg-white/25 px-1.5 py-0.2 rounded-md">${idx + 1}</span>
                        <span class="tracking-wide">${word}</span>
                    `;
                } else {
                    // MENUNGGU GILIRAN
                    card.className = 'word-step-card px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-xl font-semibold transition-all bg-surface-container-high text-on-surface-variant/80 border border-outline-variant/30 flex items-center gap-1.5 text-xs sm:text-sm opacity-80';
                    card.innerHTML = `
                        <span class="text-[9px] font-mono bg-surface-container-highest text-on-surface-variant/70 px-1.5 py-0.2 rounded-md">${idx + 1}</span>
                        <span class="tracking-wide">${word}</span>
                    `;
                }

                targetWordsContainer.appendChild(card);
            });
        }
    }

    // Verifikasi kata sub-word saat ini (Bisa dipanggil oleh AI Model atau Tombol)
    function verifyCurrentSubWord(matchedWord = null, confPercent = null) {
        if (isQuestionCompleted) return;

        resetInactivityTimer();
        const targetWord = subWords[currentSubWordIndex];
        currentSubWordIndex++;

        // Reset buffer dan hold frames agar kata berikutnya dimulai dengan rekaman gerakan bersih
        sequenceBuffer = [];
        holdFrames = 0;
        lastInferTime = performance.now() + 600;

        // Visual celebration toast
        if (successNotice && successNoticeText) {
            if (confPercent) {
                successNoticeText.textContent = `Tepat! Model AI Memverifikasi: "${matchedWord}" (${confPercent}% Cocok)`;
            } else {
                successNoticeText.textContent = `Tepat! Kata "${targetWord}" Terverifikasi!`;
            }

            successNotice.classList.remove('hidden');
            setTimeout(() => {
                successNotice.classList.add('hidden');
            }, 1200);
        }

        renderWordChips();

        // Cek apakah semua sub-word dalam soal ini sudah selesai
        if (currentSubWordIndex >= subWords.length) {
            // Soal selesai dengan benar sebelum batas waktu!
            isQuestionCompleted = true;
            stopQuestionTimer();
            correctCount++;

            // Hitung poin untuk soal ini:
            let earnedPoints = pointsPerQuestion;
            if (currentQuestionIndex === questions.length - 1 && wrongCount === 0) {
                earnedPoints = maxScore - totalScore;
                if (earnedPoints < 0) earnedPoints = pointsPerQuestion;
            }

            totalScore += earnedPoints;
            if (quizScoreText) quizScoreText.textContent = totalScore.toLocaleString();

            if (btnSimulateMatch) btnSimulateMatch.classList.add('hidden');
            if (btnNextQuestion) {
                btnNextQuestion.classList.remove('hidden');

                if (currentQuestionIndex + 1 >= questions.length) {
                    btnNextQuestion.innerHTML = `
                        <span>Selesaikan Kuis & Simpan Skor (+${earnedPoints} pts)</span>
                        <span class="material-symbols-outlined text-base">trophy</span>
                    `;
                } else {
                    btnNextQuestion.innerHTML = `
                        <span>Soal Selanjutnya (+${earnedPoints} pts)</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    `;
                }
            }

            // Otomatis lanjut ke soal berikutnya setelah jeda 1.2 detik
            autoAdvanceTimeout = setTimeout(() => {
                if (currentQuestionIndex + 1 < questions.length) {
                    currentQuestionIndex++;
                    loadQuestion(currentQuestionIndex);
                } else {
                    finishQuizAndSaveScore();
                }
            }, 1200);
        }
    }

    if (btnSimulateMatch) {
        btnSimulateMatch.addEventListener('click', () => {
            verifyCurrentSubWord();
        });
    }

    if (btnNextQuestion) {
        btnNextQuestion.addEventListener('click', () => {
            if (autoAdvanceTimeout) {
                clearTimeout(autoAdvanceTimeout);
                autoAdvanceTimeout = null;
            }
            if (currentQuestionIndex + 1 < questions.length) {
                currentQuestionIndex++;
                loadQuestion(currentQuestionIndex);
            } else {
                finishQuizAndSaveScore();
            }
        });
    }

    // ----------------------------------------------------
    // STEP 4: Finish Quiz & Store Score to Database
    // ----------------------------------------------------
    async function finishQuizAndSaveScore() {
        stopQuestionTimer();
        if (autoAdvanceTimeout) {
            clearTimeout(autoAdvanceTimeout);
            autoAdvanceTimeout = null;
        }
        stopCamera();

        if (finalScoreDisplay) finalScoreDisplay.textContent = totalScore.toLocaleString();
        if (finalMaxScoreDisplay) finalMaxScoreDisplay.textContent = `/ ${maxScore.toLocaleString()} pts`;
        if (summaryCorrectCount) summaryCorrectCount.textContent = correctCount;
        if (summaryWrongCount) summaryWrongCount.textContent = wrongCount;
        if (summaryBtnName && currentGuest) {
            summaryBtnName.textContent = `Sebagai ${currentGuest.name}`;
        }

        showStep('summary');
        resetInactivityTimer();

        // Kirim ke backend untuk disimpan ke tabel quiz_scores
        if (currentGuest && currentGuest.guest_id) {
            try {
                const res = await fetch(routes.score, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        guest_id: currentGuest.guest_id,
                        score: totalScore
                    })
                });

                const data = await res.json();
                if (data.success && scoreSavedNotification) {
                    scoreSavedNotification.classList.remove('hidden');
                }
            } catch (e) {
                console.warn('Gagal menyimpan skor:', e);
            }
        }
    }

    if (btnPlayAgain) {
        btnPlayAgain.addEventListener('click', () => {
            // Pemain yang sama dapat langsung main lagi tanpa perlu input nama ulang
            stopQuestionTimer();
            if (autoAdvanceTimeout) {
                clearTimeout(autoAdvanceTimeout);
                autoAdvanceTimeout = null;
            }
            resetInactivityTimer();
            showStep('difficulty');
        });
    }

    // ----------------------------------------------------
    // TENSORFLOW.JS: Ekstraksi Fitur & Model Deep Learning
    // ----------------------------------------------------
    function extractLandmarkFeatures(multiHandLandmarks, multiHandedness) {
        if (!multiHandLandmarks || multiHandLandmarks.length === 0) {
            return new Array(featureDimension === '126' ? 126 : (featureDimension === '42' ? 42 : 63)).fill(0.0);
        }

        const hand = multiHandLandmarks[0];
        let rawFeatures = [];

        if (featureDimension === '42') {
            for (let i = 0; i < hand.length; i++) {
                rawFeatures.push(hand[i].x, hand[i].y);
            }
        } else if (featureDimension === '126') {
            let leftHand = null;
            let rightHand = null;

            if (multiHandedness && multiHandedness.length > 0 && multiHandLandmarks.length > 1) {
                for (let i = 0; i < multiHandLandmarks.length; i++) {
                    const label = multiHandedness[i]?.label;
                    if (label === 'Left' && !leftHand) {
                        leftHand = multiHandLandmarks[i];
                    } else if (label === 'Right' && !rightHand) {
                        rightHand = multiHandLandmarks[i];
                    }
                }
            }

            const firstHand = leftHand || multiHandLandmarks[0];
            const secondHand = rightHand || (multiHandLandmarks.length > 1 && multiHandLandmarks[1] !== firstHand ? multiHandLandmarks[1] : null);

            const hands = [firstHand, secondHand];

            for (let h = 0; h < 2; h++) {
                if (hands[h]) {
                    for (let i = 0; i < 21; i++) {
                        if (hands[h][i]) {
                            rawFeatures.push(hands[h][i].x, hands[h][i].y, hands[h][i].z);
                        } else {
                            rawFeatures.push(0.0, 0.0, 0.0);
                        }
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
    // Keras 3 GRU Direct Model Loader
    // ----------------------------------------------------
    async function loadKeras3GruModel(url) {
        const res = await fetch(url);
        if (!res.ok) throw new Error(`HTTP ${res.status} fetching ${url}`);
        const modelJson = await res.json();

        const baseUrl = url.substring(0, url.lastIndexOf('/') + 1);
        const manifest = modelJson.weightsManifest && modelJson.weightsManifest[0];
        if (!manifest || !manifest.paths || !manifest.weights) {
            throw new Error('Format weightsManifest tidak valid');
        }

        const binUrl = baseUrl + manifest.paths[0];
        const binRes = await fetch(binUrl);
        if (!binRes.ok) throw new Error(`HTTP ${binRes.status} fetching ${binUrl}`);
        const binBuffer = await binRes.arrayBuffer();

        const weights = {};
        let offset = 0;
        for (const w of manifest.weights) {
            const numElements = w.shape.reduce((a, b) => a * b, 1);
            const byteLength = numElements * 4;
            const sliceBuffer = binBuffer.slice(offset, offset + byteLength);
            const floatArray = new Float32Array(sliceBuffer);
            weights[w.name] = tf.tensor(floatArray, w.shape, 'float32');
            offset += byteLength;
        }

        const getW = (key1, key2) => weights[key1] || weights[key2] || null;

        const gruBias1 = getW('gru/gru_cell/bias', 'gru/bias');
        const gruKernel1 = getW('gru/gru_cell/kernel', 'gru/kernel');
        const gruRecKernel1 = getW('gru/gru_cell/recurrent_kernel', 'gru/recurrent_kernel');

        const gruBias2 = getW('gru_1/gru_cell/bias', 'gru_1/bias');
        const gruKernel2 = getW('gru_1/gru_cell/kernel', 'gru_1/kernel');
        const gruRecKernel2 = getW('gru_1/gru_cell/recurrent_kernel', 'gru_1/recurrent_kernel');

        const bnMean1 = getW('batch_normalization/moving_mean');
        const bnVar1 = getW('batch_normalization/moving_variance');
        const bnGamma1 = getW('batch_normalization/gamma');
        const bnBeta1 = getW('batch_normalization/beta');

        const bnMean2 = getW('batch_normalization_1/moving_mean');
        const bnVar2 = getW('batch_normalization_1/moving_variance');
        const bnGamma2 = getW('batch_normalization_1/gamma');
        const bnBeta2 = getW('batch_normalization_1/beta');

        const denseKernel = getW('dense/kernel');
        const denseBias = getW('dense/bias');
        const dense1Kernel = getW('dense_1/kernel');
        const dense1Bias = getW('dense_1/bias');

        if (!gruKernel1 || !gruBias1 || !gruKernel2 || !gruBias2) {
            throw new Error('Bobot GRU tidak lengkap dalam manifest');
        }

        return {
            inputs: [{ shape: [null, 30, 126] }],
            isCustomGru: true,
            weights: weights,
            predict: function(inputTensor) {
                return tf.tidy(() => {
                    const seqLen = 30;
                    const units1 = 128;
                    let h1 = tf.zeros([1, units1]);
                    const gru1Outputs = [];

                    let bIn1, bRec1;
                    if (gruBias1.shape.length === 2) {
                        bIn1 = gruBias1.slice([0, 0], [1, 3 * units1]).reshape([3 * units1]);
                        bRec1 = gruBias1.slice([1, 0], [1, 3 * units1]).reshape([3 * units1]);
                    } else {
                        bIn1 = gruBias1;
                        bRec1 = tf.zeros([3 * units1]);
                    }

                    const unstacked = tf.unstack(inputTensor.reshape([seqLen, 126]));

                    for (let t = 0; t < seqLen; t++) {
                        const xt = unstacked[t].reshape([1, 126]);
                        const xGate = tf.add(tf.matMul(xt, gruKernel1), bIn1);
                        const hGate = tf.add(tf.matMul(h1, gruRecKernel1), bRec1);

                        const [xz, xr, xh] = tf.split(xGate, 3, 1);
                        const [hz, hr, hh] = tf.split(hGate, 3, 1);

                        const z = tf.sigmoid(tf.add(xz, hz));
                        const r = tf.sigmoid(tf.add(xr, hr));
                        const cand = tf.tanh(tf.add(xh, tf.mul(r, hh)));

                        h1 = tf.add(tf.mul(z, h1), tf.mul(tf.sub(1, z), cand));
                        gru1Outputs.push(h1);
                    }

                    const gru1Seq = tf.stack(gru1Outputs, 1);

                    const bn1 = tf.add(
                        tf.mul(
                            tf.div(
                                tf.sub(gru1Seq, bnMean1),
                                tf.sqrt(tf.add(bnVar1, 0.001))
                            ),
                            bnGamma1
                        ),
                        bnBeta1
                    );

                    const units2 = 64;
                    let h2 = tf.zeros([1, units2]);
                    let bIn2, bRec2;
                    if (gruBias2.shape.length === 2) {
                        bIn2 = gruBias2.slice([0, 0], [1, 3 * units2]).reshape([3 * units2]);
                        bRec2 = gruBias2.slice([1, 0], [1, 3 * units2]).reshape([3 * units2]);
                    } else {
                        bIn2 = gruBias2;
                        bRec2 = tf.zeros([3 * units2]);
                    }

                    const bn1Unstacked = tf.unstack(bn1.reshape([seqLen, units1]));

                    for (let t = 0; t < seqLen; t++) {
                        const xt = bn1Unstacked[t].reshape([1, units1]);
                        const xGate = tf.add(tf.matMul(xt, gruKernel2), bIn2);
                        const hGate = tf.add(tf.matMul(h2, gruRecKernel2), bRec2);

                        const [xz, xr, xh] = tf.split(xGate, 3, 1);
                        const [hz, hr, hh] = tf.split(hGate, 3, 1);

                        const z = tf.sigmoid(tf.add(xz, hz));
                        const r = tf.sigmoid(tf.add(xr, hr));
                        const cand = tf.tanh(tf.add(xh, tf.mul(r, hh)));

                        h2 = tf.add(tf.mul(z, h2), tf.mul(tf.sub(1, z), cand));
                    }

                    const bn2 = tf.add(
                        tf.mul(
                            tf.div(
                                tf.sub(h2, bnMean2),
                                tf.sqrt(tf.add(bnVar2, 0.001))
                            ),
                            bnGamma2
                        ),
                        bnBeta2
                    );

                    const d1 = tf.relu(tf.add(tf.matMul(bn2, denseKernel), denseBias));
                    const out = tf.softmax(tf.add(tf.matMul(d1, dense1Kernel), dense1Bias));

                    return out;
                });
            }
        };
    }

    function onModelLoaded() {
        if (aiModelStatus) aiModelStatus.textContent = 'Model TFJS Siap';
        if (aiModelDot) aiModelDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';

        // Auto-load metadata.json jika ada
        const metaUrl = modelUrl.replace('model.json', 'metadata.json');
        fetch(metaUrl)
            .then(r => {
                if (!r.ok) return null;
                return r.json();
            })
            .then(meta => {
                if (meta && meta.labels && Array.isArray(meta.labels)) {
                    classLabels = meta.labels;
                    if (classesInput) classesInput.value = classLabels.join(', ');
                }
            })
            .catch(() => {});
    }

    async function loadModel() {
        if (aiModelStatus) aiModelStatus.textContent = 'Memuat Model TFJS...';
        if (aiModelDot) aiModelDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse';

        try {
            if (typeof tf !== 'undefined') {
                await tf.ready();
                if (tf.findBackend('webgl')) {
                    await tf.setBackend('webgl');
                }
            }
            // Prioritaskan Direct Runner untuk Keras 3 GRU sequential model yang kompatibel dengan bobot model.json
            tfModel = await loadKeras3GruModel(modelUrl);
            onModelLoaded();
            console.log('Model Keras 3 GRU berhasil dimuat via Direct Runner:', modelUrl, tfModel);
        } catch (e) {
            console.warn('Direct runner tidak cocok, mencoba tf.loadLayersModel...', e);
            try {
                tfModel = await tf.loadLayersModel(modelUrl);
                onModelLoaded();
                console.log('Model TFJS berhasil dimuat via LayersModel:', modelUrl, tfModel);
            } catch (err2) {
                console.warn('Gagal memuat via layers model, mencoba graph model...', err2);
                try {
                    tfModel = await tf.loadGraphModel(modelUrl);
                    onModelLoaded();
                    console.log('Model TFJS berhasil dimuat via GraphModel:', modelUrl, tfModel);
                } catch (err3) {
                    tfModel = null;
                    if (aiModelStatus) aiModelStatus.textContent = 'Model Belum Dimuat (Klik ⚙)';
                    if (aiModelDot) aiModelDot.className = 'w-2.5 h-2.5 rounded-full bg-rose-400';
                    console.info('Model TFJS lokal belum ada di ' + modelUrl + '. Gunakan tombol ⚙ untuk konfigurasi URL model.');
                }
            }
        }
    }

    // ----------------------------------------------------
    // Settings Modal Event Listeners
    // ----------------------------------------------------
    if (btnOpenSettings) {
        btnOpenSettings.addEventListener('click', () => {
            if (modelUrlInput) modelUrlInput.value = modelUrl;
            if (landmarkDimSelect) landmarkDimSelect.value = featureDimension;
            if (classesInput) classesInput.value = classLabels.join(', ');
            if (thresholdSlider) thresholdSlider.value = Math.round(confidenceThreshold * 100);
            if (thresholdVal) thresholdVal.textContent = `${thresholdSlider.value}%`;
            if (settingsModal) {
                settingsModal.classList.remove('hidden');
                settingsModal.classList.add('flex');
            }
        });
    }

    function closeSettings() {
        if (settingsModal) {
            settingsModal.classList.add('hidden');
            settingsModal.classList.remove('flex');
        }
    }

    if (btnCloseSettings) btnCloseSettings.addEventListener('click', closeSettings);
    if (btnCancelSettings) btnCancelSettings.addEventListener('click', closeSettings);

    if (thresholdSlider) {
        thresholdSlider.addEventListener('input', (e) => {
            if (thresholdVal) thresholdVal.textContent = `${e.target.value}%`;
        });
    }

    if (btnSaveSettings) {
        btnSaveSettings.addEventListener('click', async () => {
            if (modelUrlInput) modelUrl = modelUrlInput.value.trim();
            if (landmarkDimSelect) featureDimension = landmarkDimSelect.value;
            if (classesInput) classLabels = classesInput.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
            if (thresholdSlider) confidenceThreshold = parseInt(thresholdSlider.value, 10) / 100;

            closeSettings();
            await loadModel();
        });
    }

    // ----------------------------------------------------
    // MediaPipe Hands & Camera Pipeline
    // ----------------------------------------------------
    function initMediaPipeHands() {
        if (typeof Hands === 'undefined') {
            console.error('MediaPipe Hands library belum dimuat.');
            return;
        }

        try {
            handsDetector = new Hands({
                locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
            });

            handsDetector.setOptions({
                maxNumHands: 2,
                modelComplexity: 0, // 0 = Lite (Cepat & Ringan)
                minDetectionConfidence: 0.5,
                minTrackingConfidence: 0.5
            });

            handsDetector.onResults(onHandResults);
        } catch (e) {
            console.error('Error inisialisasi MediaPipe:', e);
        }
    }

    function onHandResults(results) {
        if (!isCameraRunning || !canvasCtx) return;

        // Sesuaikan canvas
        if (skeletonCanvas.width !== video.videoWidth || skeletonCanvas.height !== video.videoHeight) {
            skeletonCanvas.width = video.videoWidth || 640;
            skeletonCanvas.height = video.videoHeight || 480;
        }

        canvasCtx.save();
        canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);

        const hasHands = results.multiHandLandmarks && results.multiHandLandmarks.length > 0;
        if (handCountText) {
            handCountText.textContent = hasHands ? `${results.multiHandLandmarks.length} Tangan Terdeteksi` : '0 Tangan';
        }

        if (hasHands) {
            // Pergerakan tangan pengguna di depan kamera mereset timer inaktivitas
            if (Date.now() - lastActivityTime > 2000) {
                registerActivity();
            }

            // Gambar Skeleton (Cyan & Biru Neon)
            for (const landmarks of results.multiHandLandmarks) {
                if (typeof drawConnectors !== 'undefined' && typeof HAND_CONNECTIONS !== 'undefined') {
                    drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {
                        color: '#00D2FF',
                        lineWidth: 4
                    });
                }
                if (typeof drawLandmarks !== 'undefined') {
                    drawLandmarks(canvasCtx, landmarks, {
                        color: '#0058be',
                        fillColor: '#FFFFFF',
                        lineWidth: 2,
                        radius: 4
                    });
                }
            }

            handLostFrames = 0;
            // Ekstraksi fitur landmark koordinat tangan
            const features = extractLandmarkFeatures(results.multiHandLandmarks, results.multiHandedness);

            // Selalu tambahkan ke sequence buffer untuk menjaga kontinuitas waktu gerakan
            sequenceBuffer.push(features);
            if (sequenceBuffer.length > SEQUENCE_LENGTH) {
                sequenceBuffer.shift();
            }

            // Verifikasi menggunakan Model Deep Learning (Asinkronus & Throttled agar 30+ FPS)
            if (tfModel && sequenceBuffer.length >= 8 && !isQuestionCompleted) {
                const nowTime = performance.now();
                if (!isInferring && (nowTime - lastInferTime >= INFER_INTERVAL_MS)) {
                    lastInferTime = nowTime;
                    isInferring = true;
                    setTimeout(() => {
                        try {
                            predictAndVerifyGesture();
                        } finally {
                            isInferring = false;
                        }
                    }, 0);
                }
            } else if (!tfModel) {
                if (liveAiPredictionBadge) liveAiPredictionBadge.classList.add('hidden');
                if (bottomHintText) bottomHintText.textContent = 'Menunggu model TensorFlow.js dimuat (Klik ⚙ jika perlu ubah path model)';
            }
        } else {
            handLostFrames++;
            // Ketika tangan benar-benar hilang melampaui toleransi, baru reset sequenceBuffer
            if (handLostFrames >= HAND_LOST_TOLERANCE) {
                sequenceBuffer = [];
                holdFrames = 0;
                if (holdProgressBar) holdProgressBar.style.width = '0%';
                if (holdProgressWrapper) holdProgressWrapper.classList.add('hidden');
                if (liveAiPredictionBadge) liveAiPredictionBadge.classList.add('hidden');
                if (bottomHintText) {
                    bottomHintText.textContent = 'Arahkan tangan ke kamera dan peragakan kata yang disorot';
                    bottomHintText.className = 'text-[11px] text-white/90 bg-black/60 px-3.5 py-1 rounded-full backdrop-blur-md border border-white/10';
                }
            }
        }

        canvasCtx.restore();
    }

    function predictAndVerifyGesture() {
        if (isQuestionCompleted || !tfModel || sequenceBuffer.length === 0) return;

        try {
            tf.tidy(() => {
                let inputTensor;
                const inputShape = tfModel.inputs && tfModel.inputs[0] ? tfModel.inputs[0].shape : null;

                if (inputShape && inputShape.length === 3) {
                    const targetSeqLen = inputShape[1] || SEQUENCE_LENGTH;
                    const targetFeatLen = inputShape[2] || (featureDimension === '126' ? 126 : 63);
                    
                    let seq = [...sequenceBuffer];
                    while (seq.length < targetSeqLen) {
                        seq.unshift(seq.length > 0 ? seq[0] : new Array(targetFeatLen).fill(0));
                    }
                    if (seq.length > targetSeqLen) {
                        seq = seq.slice(-targetSeqLen);
                    }
                    inputTensor = tf.tensor3d([seq], [1, targetSeqLen, targetFeatLen]);
                } else {
                    const latestFeat = sequenceBuffer[sequenceBuffer.length - 1];
                    inputTensor = tf.tensor2d([latestFeat], [1, latestFeat.length]);
                }

                const outputTensor = tfModel.predict(inputTensor);
                const scores = outputTensor.dataSync();

                let maxIndex = 0;
                let maxProb = scores[0];

                for (let i = 0; i < scores.length; i++) {
                    if (scores[i] > maxProb) {
                        maxProb = scores[i];
                        maxIndex = i;
                    }
                }

                const predictedClass = (classLabels[maxIndex] || `Kelas ${maxIndex + 1}`).trim().toUpperCase();
                const confPercent = Math.round(maxProb * 100);

                // Update Live AI Prediction HUD Badge
                if (liveAiPredictionBadge && liveAiPredText) {
                    liveAiPredictionBadge.classList.remove('hidden');
                    liveAiPredictionBadge.classList.add('flex');
                    liveAiPredText.textContent = `${predictedClass} (${confPercent}%)`;
                }

                // TARGET MATCHING: Bandingkan prediksi AI dengan target kata saat ini
                const targetWord = (subWords[currentSubWordIndex] || '').trim().toUpperCase();

                if (predictedClass === targetWord && maxProb >= confidenceThreshold) {
                    // Isyarat tangan sesuai target & di atas threshold!
                    if (holdProgressWrapper) holdProgressWrapper.classList.remove('hidden');
                    holdFrames++;
                    const progress = Math.min(100, Math.round((holdFrames / HOLD_FRAMES_TARGET) * 100));
                    if (holdProgressBar) holdProgressBar.style.width = `${progress}%`;
                    if (bottomHintText) {
                        bottomHintText.textContent = `Isyarat Cocok! Tahan pose: "${predictedClass}" (${confPercent}%)`;
                        bottomHintText.className = 'text-[11px] text-emerald-300 bg-black/80 px-3.5 py-1 rounded-full backdrop-blur-md border border-emerald-500/30';
                    }

                    if (holdFrames >= HOLD_FRAMES_TARGET) {
                        holdFrames = 0;
                        if (holdProgressBar) holdProgressBar.style.width = '0%';
                        if (holdProgressWrapper) holdProgressWrapper.classList.add('hidden');
                        verifyCurrentSubWord(predictedClass, confPercent);
                    }
                } else {
                    // Belum cocok atau belum di atas threshold
                    if (holdFrames > 0) holdFrames--;
                    if (holdProgressBar) holdProgressBar.style.width = `${Math.round((holdFrames / HOLD_FRAMES_TARGET) * 100)}%`;
                    if (bottomHintText) {
                        bottomHintText.textContent = `Target: "${targetWord}" · Terdeteksi AI: ${predictedClass} (${confPercent}%)`;
                        bottomHintText.className = 'text-[11px] text-white/90 bg-black/60 px-3.5 py-1 rounded-full backdrop-blur-md border border-white/10';
                    }
                }
            });
        } catch (err) {
            console.error('Error inferensi TensorFlow.js:', err);
        }
    }

    async function startCamera() {
        try {
            if (!handsDetector) initMediaPipeHands();

            streamInstance = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    frameRate: { ideal: 30, max: 30 },
                    facingMode: 'user'
                },
                audio: false
            });

            video.srcObject = streamInstance;
            await video.play();

            isCameraRunning = true;
            if (video) video.classList.remove('hidden');
            if (skeletonCanvas) skeletonCanvas.classList.remove('hidden');
            if (standbyImg) standbyImg.classList.add('hidden');
            if (standbyOverlay) standbyOverlay.classList.add('hidden');
            if (cameraHud) cameraHud.classList.remove('hidden');

            if (btnStartCam) btnStartCam.classList.add('hidden');
            if (btnStopCam) {
                btnStopCam.classList.remove('hidden');
                btnStopCam.classList.add('flex');
            }

            startMediaPipeLoop();
        } catch (err) {
            console.error('Gagal mengakses kamera:', err);
            alert('Tidak dapat mengaktifkan kamera. Pastikan izin kamera telah diberikan pada browser Anda.');
        }
    }

    function startMediaPipeLoop() {
        const processFrame = async () => {
            if (!isCameraRunning) return;

            if (handsDetector && video && video.readyState >= 2 && !isProcessingFrame) {
                isProcessingFrame = true;
                try {
                    procCtx.drawImage(video, 0, 0, procCanvas.width, procCanvas.height);
                    await handsDetector.send({ image: procCanvas });
                } catch (e) {
                    // ignore frame drop
                } finally {
                    isProcessingFrame = false;
                }
            }

            animFrameId = requestAnimationFrame(processFrame);
        };

        animFrameId = requestAnimationFrame(processFrame);
    }

    function stopCamera() {
        isCameraRunning = false;
        stopQuestionTimer();
        if (autoAdvanceTimeout) {
            clearTimeout(autoAdvanceTimeout);
            autoAdvanceTimeout = null;
        }
        if (animFrameId) {
            cancelAnimationFrame(animFrameId);
            animFrameId = null;
        }
        if (streamInstance) {
            streamInstance.getTracks().forEach(t => t.stop());
            streamInstance = null;
        }
        if (video) video.srcObject = null;

        if (video) video.classList.add('hidden');
        if (skeletonCanvas) skeletonCanvas.classList.add('hidden');
        if (standbyImg) standbyImg.classList.remove('hidden');
        if (standbyOverlay) standbyOverlay.classList.remove('hidden');
        if (cameraHud) cameraHud.classList.add('hidden');

        if (btnStopCam) {
            btnStopCam.classList.add('hidden');
            btnStopCam.classList.remove('flex');
        }
        if (btnStartCam) btnStartCam.classList.remove('hidden');

        if (canvasCtx && skeletonCanvas) {
            canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);
        }
    }

    if (btnStartCam) btnStartCam.addEventListener('click', startCamera);
    if (btnQuickStart) btnQuickStart.addEventListener('click', startCamera);
    if (btnStopCam) btnStopCam.addEventListener('click', stopCamera);

    if (btnToggleMirror) {
        btnToggleMirror.addEventListener('click', () => {
            isMirror = !isMirror;
            if (isMirror) {
                if (video) video.classList.add('mirror-mode');
                if (skeletonCanvas) skeletonCanvas.classList.add('mirror-mode');
            } else {
                if (video) video.classList.remove('mirror-mode');
                if (skeletonCanvas) skeletonCanvas.classList.remove('mirror-mode');
            }
        });
    }

    // Initialize MediaPipe detector & TFJS Model
    initMediaPipeHands();
    loadModel();
});
