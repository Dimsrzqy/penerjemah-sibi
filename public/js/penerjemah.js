
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const video = document.getElementById('webcamVideo');
    const skeletonCanvas = document.getElementById('skeletonCanvas');
    const canvasCtx = skeletonCanvas ? skeletonCanvas.getContext('2d') : null;
    const standbyImg = document.getElementById('standbyImg');
    const standbyOverlay = document.getElementById('standbyOverlay');
    const cameraOffOverlay = document.getElementById('cameraOffOverlay');
    const turnOnCamOverlayBtn = document.getElementById('turnOnCamOverlayBtn');
    const cameraStatusBadge = document.getElementById('cameraStatusBadge');
    const cameraStatusDot = document.getElementById('cameraStatusDot');
    const cameraStatusText = document.getElementById('cameraStatusText');
    const cameraHudTelemetry = document.getElementById('cameraHudTelemetry');
    const cameraViewportArea = document.getElementById('cameraViewportArea');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const cameraHud = document.getElementById('cameraHud');
    const fpsDisplay = document.getElementById('fpsDisplay');
    const handCountDisplay = document.getElementById('handCountDisplay');
    const hudStatusDot = document.getElementById('hudStatusDot');
    const hudStatusText = document.getElementById('hudStatusText');
    const toggleAutoType = document.getElementById('toggleAutoType');
    const activeHandOverlay = document.getElementById('activeHandOverlay');
    const activeHandOverlayText = document.getElementById('activeHandOverlayText');

    // Controls
    const btnStart = document.getElementById('btnStart');
    const btnQuickStart = document.getElementById('btnQuickStart');
    const btnStop = document.getElementById('btnStop');
    const btnTogglePower = document.getElementById('toggleCameraPowerBtn') || document.getElementById('btnStart');
    const toggleCameraPowerText = document.getElementById('toggleCameraPowerText') || document.getElementById('label-toggle-detect');
    const btnToggleMirror = document.getElementById('flipCameraBtn') || document.getElementById('btnToggleMirror');
    const btnToggleSkeleton = document.getElementById('btnToggleSkeleton');

    // Recognition & Predictions
    const liveDetectedText = document.getElementById('liveResultText') || document.getElementById('liveDetectedText');
    const detectedCategory = document.getElementById('detectedCategory');
    const confidenceBadge = document.getElementById('confidenceBadge');
    const confidenceBar = document.getElementById('confidenceBar');
    const stabilityBadge = document.getElementById('stabilityBadge');
    const stabilityBadgeText = document.getElementById('stabilityBadgeText');
    const topPredictionsContainer = document.getElementById('topPredictionsContainer');
    const holdProgressBar = document.getElementById('holdProgressBar');
    const speakCurrentBtn = document.getElementById('speakCurrentBtn');
    const copyWordBtn = document.getElementById('copyWordBtn');

    // Sentence Box & Actions
    const sentenceContainer = document.getElementById('sentenceContainer');
    const sentenceOutput = document.getElementById('sentenceOutput');
    const charCount = document.getElementById('charCount');
    const btnManualAdd = document.getElementById('btnManualAdd');
    const btnSpeech = document.getElementById('btnSpeech');
    const btnSpace = document.getElementById('btnSpace');
    const btnBackspace = document.getElementById('btnBackspace');
    const btnCopy = document.getElementById('copyAllBtn') || document.getElementById('btnCopy');
    const clearBufferBtn = document.getElementById('clearBufferBtn') || document.getElementById('btnClearAll');
    const copyBtnText = document.getElementById('copyBtnText');

    // Settings Modal
    const settingsModal = document.getElementById('settingsModal');
    const btnOpenSettings = document.getElementById('btnOpenSettings');
    const btnCloseSettings = document.getElementById('btnCloseSettings');
    const btnCancelSettings = document.getElementById('btnCancelSettings');
    const btnSaveSettings = document.getElementById('btnSaveSettings');
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
    let modelUrl = '/models/tfjs_model/model.json';
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
    
    // Toleransi deteksi tangan hilang sesaat (flicker) agar buffer 30 frame tidak langsung terhapus
    let handLostFrames = 0;
    const HAND_LOST_TOLERANCE = 8; // ~300ms toleransi sebelum buffer di-reset total

    // Inference Throttler
    let lastInferTime = 0;
    const INFER_INTERVAL_MS = 100;
    let isInferring = false;

    // Offscreen Processing Canvas
    let procCanvas = document.createElement('canvas');
    let procCtx = procCanvas.getContext('2d', { willReadFrequently: true });
    procCanvas.width = 360;
    procCanvas.height = 270;

    // Auto-Type Dwell Logic
    let lastStableWord = '';
    let stableWordCount = 0;
    let lastCommittedWord = '';
    const DWELL_FRAMES_REQUIRED = 8; // Lebih responsif (8 frame berturut-turut)
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
    // 2. Callback Menggambar Landmark Skeleton & Buffer
    // ----------------------------------------------------
    function onHandResults(results) {
        if (!isCameraRunning) return;

        // FPS Tracker
        frameCount++;
        const now = performance.now();
        if (now - lastFpsTime >= 1000) {
            if (fpsDisplay) fpsDisplay.textContent = frameCount;
            frameCount = 0;
            lastFpsTime = now;
        }

        if (video && skeletonCanvas && video.videoWidth > 0 && (skeletonCanvas.width !== video.videoWidth || skeletonCanvas.height !== video.videoHeight)) {
            skeletonCanvas.width = video.videoWidth;
            skeletonCanvas.height = video.videoHeight;
        }

        if (!canvasCtx) return;

        canvasCtx.save();
        canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);

        const hasHands = results.multiHandLandmarks && results.multiHandLandmarks.length > 0;
        if (handCountDisplay) {
            handCountDisplay.textContent = hasHands ? `${results.multiHandLandmarks.length} Tangan Terdeteksi` : '0 Tangan';
        }

        const leftCountEl = document.getElementById('leftLandmarksCount');
        const rightCountEl = document.getElementById('rightLandmarksCount');
        if (leftCountEl && rightCountEl) {
            if (hasHands) {
                if (results.multiHandedness && results.multiHandedness.length > 0) {
                    let lh = 0, rh = 0;
                    results.multiHandedness.forEach(h => {
                        const label = h.label || (h.classification && h.classification[0] ? h.classification[0].label : '');
                        if (label === 'Left') lh = 21;
                        else if (label === 'Right') rh = 21;
                    });
                    leftCountEl.textContent = lh;
                    rightCountEl.textContent = rh;
                } else {
                    leftCountEl.textContent = results.multiHandLandmarks.length > 0 ? 21 : 0;
                    rightCountEl.textContent = results.multiHandLandmarks.length > 1 ? 21 : 0;
                }
            } else {
                leftCountEl.textContent = '0';
                rightCountEl.textContent = '0';
            }
        }

        if (hasHands) {
            if (showSkeleton) {
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
            }
        }

        handLostFrames = hasHands ? 0 : handLostFrames + 1;
        const features = hasHands
            ? extractLandmarkFeatures(results.multiHandLandmarks, results.multiHandedness)
            : new Array(126).fill(0.0);
        sequenceBuffer.push(features);
        if (sequenceBuffer.length > SEQUENCE_LENGTH) {
            sequenceBuffer.shift();
        }

        if (hasHands && tfModel && sequenceBuffer.length >= SEQUENCE_LENGTH) {
            const nowTime = performance.now();
            if (!isInferring && (nowTime - lastInferTime >= INFER_INTERVAL_MS)) {
                lastInferTime = nowTime;
                isInferring = true;
                setTimeout(() => {
                    try {
                        predictSignGesture();
                    } finally {
                        isInferring = false;
                    }
                }, 0);
            }
        } else if (hasHands) {
            if (liveDetectedText && liveDetectedText.textContent === '-') {
                liveDetectedText.textContent = '...';
            }
            if (detectedCategory) {
                detectedCategory.textContent = `Merekam gerakan (${sequenceBuffer.length}/${SEQUENCE_LENGTH})...`;
            }
        }

        if (!hasHands && handLostFrames >= HAND_LOST_TOLERANCE) {
            sequenceBuffer = [];
            if (liveDetectedText) liveDetectedText.textContent = '-';
            if (detectedCategory) detectedCategory.textContent = 'Arahkan tangan ke kamera...';
            if (confidenceBadge) {
                confidenceBadge.textContent = '-';
                confidenceBadge.className = 'text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-surface-container text-primary';
            }
            if (holdProgressBar) holdProgressBar.style.width = '0%';
            stableWordCount = 0;
            lastStableWord = '';
            lastCommittedWord = '';
        }

        canvasCtx.restore();
    }

    // ----------------------------------------------------
    // 3. Ekstraksi Fitur Landmark (100% Identik dengan Python)
    // ----------------------------------------------------
    function extractLandmarkFeatures(multiHandLandmarks, multiHandedness) {
        if (!multiHandLandmarks || multiHandLandmarks.length === 0) {
            return new Array(126).fill(0.0);
        }

        let lh = new Array(63).fill(0.0);
        let rh = new Array(63).fill(0.0);

        for (let i = 0; i < multiHandLandmarks.length; i++) {
            const landmarks = multiHandLandmarks[i];
            let handednessLabel = multiHandedness && multiHandedness[i] ? multiHandedness[i].label : 'Right';

            let coords = [];
            for (let lm of landmarks) {
                coords.push(lm.x, lm.y, lm.z);
            }

            if (handednessLabel === 'Left') {
                lh = coords;
            } else {
                rh = coords;
            }
        }

        return [...lh, ...rh];
    }

    // ----------------------------------------------------
    // 4. Prediksi TensorFlow.js
    // ----------------------------------------------------
    function predictSignGesture() {
        if (!tfModel || sequenceBuffer.length < SEQUENCE_LENGTH) return;

        try {
            tf.tidy(() => {
                const targetFeatLen = 126;
                const seq = sequenceBuffer.slice(-SEQUENCE_LENGTH);
                const inputTensor = tf.tensor3d([seq], [1, SEQUENCE_LENGTH, targetFeatLen]);
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
                    if (liveDetectedText) liveDetectedText.textContent = topClass.toUpperCase();
                    updateSignGuide(topClass);
                    if (confidenceBadge) {
                        confidenceBadge.textContent = `${confPercent}% Cocok`;
                        confidenceBadge.className = 'text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300';
                    }
                    if (confidenceBar) {
                        confidenceBar.style.width = `${confPercent}%`;
                    }
                    if (stabilityBadgeText) {
                        stabilityBadgeText.textContent = `${confPercent}% Stabil`;
                    }

                    if (toggleAutoType && toggleAutoType.checked) {
                        if (topClass === lastStableWord) {
                            stableWordCount = Math.min(stableWordCount + 1, DWELL_FRAMES_REQUIRED + 1);
                            const progress = Math.min(100, Math.round((stableWordCount / DWELL_FRAMES_REQUIRED) * 100));
                            if (holdProgressBar) holdProgressBar.style.width = `${progress}%`;

                            if (stableWordCount === DWELL_FRAMES_REQUIRED) {
                                if (lastCommittedWord !== topClass.toUpperCase()) {
                                    appendWordToSentence(topClass);
                                    lastCommittedWord = topClass.toUpperCase();
                                    if (liveDetectedText) {
                                        liveDetectedText.classList.add('scale-110');
                                        setTimeout(() => liveDetectedText.classList.remove('scale-110'), 200);
                                    }
                                }
                                stableWordCount = DWELL_FRAMES_REQUIRED + 1;
                            }
                        } else {
                            lastStableWord = topClass;
                            stableWordCount = 1;
                            if (holdProgressBar) holdProgressBar.style.width = '5%';
                        }
                    }
                } else {
                    if (liveDetectedText) liveDetectedText.textContent = '...';
                    if (detectedCategory) detectedCategory.textContent = 'Menganalisis pose tangan...';
                    if (confidenceBadge) {
                        confidenceBadge.textContent = `${confPercent}% (Di bawah threshold)`;
                        confidenceBadge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
                    }
                    if (confidenceBar) {
                        confidenceBar.style.width = `${confPercent}%`;
                    }
                    if (stabilityBadgeText) {
                        stabilityBadgeText.textContent = 'Menganalisis...';
                    }
                    if (holdProgressBar) holdProgressBar.style.width = '0%';
                    stableWordCount = 0;
                }
            });
        } catch (err) {
            console.error('Error inferensi model:', err);
        }
    }

    function renderTopPredictions(top3) {
        if (!topPredictionsContainer) return;
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
            if (cameraOffOverlay) {
                cameraOffOverlay.classList.add('hidden');
                cameraOffOverlay.classList.remove('flex');
            }
            if (cameraHud) cameraHud.classList.remove('hidden');
            if (cameraHudTelemetry) cameraHudTelemetry.classList.remove('hidden');

            if (btnStart) btnStart.classList.add('hidden');
            if (btnStop) {
                btnStop.classList.remove('hidden');
                btnStop.classList.add('flex');
            }

            // Update camera header badge & dot
            if (cameraStatusBadge) {
                cameraStatusBadge.className = 'flex items-center gap-2 px-2.5 py-1 rounded-full bg-surface-container-highest/10 border border-inverse-on-surface/10 transition-colors cursor-pointer select-none';
            }
            if (cameraStatusDot) {
                cameraStatusDot.innerHTML = '<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>';
            }
            if (cameraStatusText) {
                cameraStatusText.textContent = 'AKTIF';
            }
            if (toggleCameraPowerText) {
                toggleCameraPowerText.textContent = 'Matikan Kamera';
            }
            if (stabilityBadgeText) {
                stabilityBadgeText.textContent = '98% Stabil';
            }

            if (detectedCategory) detectedCategory.textContent = 'Mendeteksi titik sendi tangan...';

            startMediaPipeLoop();
        } catch (err) {
            console.error('Gagal mengakses kamera:', err);
            alert('Tidak dapat mengaktifkan kamera. Pastikan izin kamera telah diberikan di browser.');
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
                    console.warn('Gagal memproses frame MediaPipe:', e);
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
        if (cameraOffOverlay) {
            cameraOffOverlay.classList.remove('hidden');
            cameraOffOverlay.classList.add('flex');
        }
        if (cameraHud) cameraHud.classList.add('hidden');
        if (cameraHudTelemetry) cameraHudTelemetry.classList.add('hidden');

        if (btnStop) {
            btnStop.classList.add('hidden');
            btnStop.classList.remove('flex');
        }
        if (btnStart) btnStart.classList.remove('hidden');

        // Reset camera badge & dot
        if (cameraStatusBadge) {
            cameraStatusBadge.className = 'flex items-center gap-2 px-2.5 py-1 rounded-full bg-surface-container-highest/10 border border-inverse-on-surface/10 transition-colors cursor-pointer select-none';
        }
        if (cameraStatusDot) {
            cameraStatusDot.innerHTML = '<span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-outline"></span>';
        }
        if (cameraStatusText) {
            cameraStatusText.textContent = 'NONAKTIF';
        }
        if (toggleCameraPowerText) {
            toggleCameraPowerText.textContent = 'Nyalakan Kamera';
        }
        if (stabilityBadgeText) {
            stabilityBadgeText.textContent = 'Siap Mendeteksi';
        }

        if (canvasCtx && skeletonCanvas) {
            canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);
        }
        resetLiveDisplay();
    }

    function resetLiveDisplay() {
        if (liveDetectedText) liveDetectedText.textContent = 'TERIMA KASIH';
        if (detectedCategory) detectedCategory.textContent = 'Konteks: Sapaan sopan saat menerima pesanan susu atau struk pembayaran di kasir K-Suli.';
        if (confidenceBadge) confidenceBadge.textContent = '98.4%';
        if (confidenceBar) confidenceBar.style.width = '98%';
        if (fpsDisplay) fpsDisplay.textContent = '0';
        if (handCountDisplay) handCountDisplay.textContent = '0 Tangan';
        if (holdProgressBar) holdProgressBar.style.width = '0%';
        if (activeHandOverlay) activeHandOverlay.classList.remove('hidden');
        const leftCountEl = document.getElementById('leftLandmarksCount');
        const rightCountEl = document.getElementById('rightLandmarksCount');
        if (leftCountEl) leftCountEl.textContent = '0';
        if (rightCountEl) rightCountEl.textContent = '0';
        if (topPredictionsContainer) {
            topPredictionsContainer.innerHTML = '<div class="text-xs text-on-surface-variant italic">Belum ada data inferensi</div>';
        }
    }

    // ----------------------------------------------------
    // 6. Sentence Builder Actions with Interactive Tags
    // ----------------------------------------------------
    let sentenceWords = ['HALO', 'SELAMAT SIANG', 'SUSU COKELAT', 'TERIMA KASIH'];

    function appendWordToSentence(word) {
        if (!word || word === '-' || word === '...') return;
        const clean = word.trim().toUpperCase();
        if (!clean) return;
        sentenceWords.push(clean);
        updateSentenceDisplay();
    }

    function removeWordAt(index) {
        if (index >= 0 && index < sentenceWords.length) {
            sentenceWords.splice(index, 1);
            updateSentenceDisplay();
        }
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function updateSentenceDisplay() {
        if (!sentenceContainer || !charCount) return;

        if (sentenceWords.length === 0) {
            sentenceContainer.innerHTML = `
                <span class="text-on-surface-variant text-xs italic">Buffer kosong. Mulai lakukan isyarat di hadapan kamera...</span>
            `;
            charCount.textContent = '0 Frasa Terkumpul';
        } else {
            sentenceContainer.innerHTML = sentenceWords.map((w, idx) => {
                const isLast = (idx === sentenceWords.length - 1);
                const bgClass = isLast 
                    ? 'bg-primary text-white font-semibold' 
                    : 'bg-surface-container-lowest text-on-surface font-semibold border border-slate-200/40';
                const closeBtnColor = isLast 
                    ? 'hover:text-red-200 text-white' 
                    : 'hover:text-error text-outline';
                return `
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg ${bgClass} text-xs sm:text-sm shadow-xs transition-all">
                        <span>${escapeHtml(w)}</span>
                        <button type="button" class="btn-remove-tag ${closeBtnColor} text-[14px] leading-none" data-word-idx="${idx}" title="Hapus frasa ini">×</button>
                    </span>
                `;
            }).join('');

            // Bind click event for each tag's remove button
            sentenceContainer.querySelectorAll('.btn-remove-tag').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const idx = parseInt(btn.dataset.wordIdx, 10);
                    removeWordAt(idx);
                });
            });

            charCount.textContent = `${sentenceWords.length} Frasa Terkumpul`;
        }
    }

    function speakText(text) {
        if (!text) return;
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
    }

    if (btnManualAdd) {
        btnManualAdd.addEventListener('click', () => {
            const text = liveDetectedText ? liveDetectedText.textContent.trim() : '';
            if (text && text !== '-' && text !== '...') {
                appendWordToSentence(text);
                lastCommittedWord = text.toUpperCase();
            }
        });
    }

    if (btnSpace) {
        btnSpace.addEventListener('click', () => {
            if (sentenceWords.length > 0) {
                // Keep spacing in sync
            }
        });
    }

    if (btnBackspace) {
        btnBackspace.addEventListener('click', () => {
            if (sentenceWords.length > 0) {
                sentenceWords.pop();
                updateSentenceDisplay();
            }
        });
    }

    if (clearBufferBtn) {
        clearBufferBtn.addEventListener('click', () => {
            sentenceWords = [];
            updateSentenceDisplay();
        });
    }

    if (btnCopy) {
        btnCopy.addEventListener('click', async () => {
            const text = sentenceWords.join(' ').trim();
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                const icon = btnCopy.querySelector('.material-symbols-outlined');
                if (icon) {
                    icon.textContent = 'done';
                    setTimeout(() => { icon.textContent = 'content_copy'; }, 1500);
                }
                const label = btnCopy.querySelector('span:last-child');
                if (label && label !== icon) {
                    const orig = label.textContent;
                    label.textContent = 'Tersalin!';
                    setTimeout(() => { label.textContent = orig; }, 1500);
                }
            } catch (e) {
                console.error('Gagal menyalin teks:', e);
            }
        });
    }

    if (copyWordBtn) {
        copyWordBtn.addEventListener('click', async () => {
            const text = liveDetectedText ? liveDetectedText.textContent.trim() : '';
            if (!text || text === '-' || text === '...') return;
            try {
                await navigator.clipboard.writeText(text);
                const icon = copyWordBtn.querySelector('.material-symbols-outlined');
                if (icon) {
                    icon.textContent = 'done';
                    setTimeout(() => { icon.textContent = 'content_copy'; }, 1500);
                }
            } catch (e) {
                console.error('Gagal menyalin kata:', e);
            }
        });
    }

    if (speakCurrentBtn) {
        speakCurrentBtn.addEventListener('click', () => {
            const text = liveDetectedText ? liveDetectedText.textContent.trim() : '';
            if (!text || text === '-' || text === '...') {
                alert('Belum ada kata terdeteksi untuk disuarakan.');
                return;
            }
            speakText(text);
        });
    }

    if (btnSpeech) {
        btnSpeech.addEventListener('click', () => {
            const text = sentenceWords.join(' ').trim();
            if (!text) {
                alert('Belum ada kalimat untuk disuarakan.');
                return;
            }
            speakText(text);
        });
    }

    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', () => {
            const viewport = document.getElementById('cameraViewportArea') || document.querySelector('.lg\\:col-span-7');
            if (!document.fullscreenElement) {
                if (viewport && viewport.requestFullscreen) {
                    viewport.requestFullscreen();
                } else if (viewport && viewport.webkitRequestFullscreen) {
                    viewport.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });
    }

    // ----------------------------------------------------
    // 7. Load Model Keras 3 GRU via Custom Runner / LayersModel
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
            predict: function (inputTensor) {
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
        if (hudStatusText) hudStatusText.textContent = 'Model TFJS Siap';
        if (hudStatusDot) hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';
        if (detectedCategory) detectedCategory.textContent = 'Model Siap (Arahkan tangan)';
    }

    async function loadLabels() {
        const candidateUrls = [
            modelUrl.replace('model.json', 'label_map.json'),
            modelUrl.replace('model.json', 'metadata.json'),
            '/models/tfjs_model/label_map.json',
            '/models/tfjs_model/metadata.json'
        ];

        for (const url of candidateUrls) {
            try {
                const res = await fetch(url);
                if (res.ok) {
                    const data = await res.json();
                    let labels = null;
                    if (Array.isArray(data)) {
                        labels = data;
                    } else if (data && data.labels && Array.isArray(data.labels)) {
                        labels = data.labels;
                    } else if (data && typeof data === 'object') {
                        const keys = Object.keys(data);
                        if (keys.length > 0) {
                            if (typeof data[keys[0]] === 'number') {
                                labels = keys.sort((a, b) => data[a] - data[b]);
                            } else {
                                labels = keys.sort((a, b) => Number(a) - Number(b)).map(k => data[k]);
                            }
                        }
                    }
                    if (labels && labels.length > 0) {
                        classLabels = labels;
                        if (classesInput) classesInput.value = classLabels.join(', ');
                        console.log(`Label berhasil dimuat dari ${url} (${classLabels.length} kelas)`);
                        return;
                    }
                }
            } catch (e) {}
        }
    }

    async function loadModel() {
        if (hudStatusText) hudStatusText.textContent = 'Memuat Model TFJS...';
        if (hudStatusDot) hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse';

        try {
            if (typeof tf !== 'undefined') {
                await tf.ready();
                if (tf.findBackend('webgl')) {
                    await tf.setBackend('webgl');
                }
            }
            await loadLabels();
            tfModel = await loadKeras3GruModel(modelUrl);
            onModelLoaded();
            console.log('Model Keras 3 GRU berhasil dimuat.');
        } catch (e) {
            console.warn('Mencoba loadLayersModel...', e);
            try {
                tfModel = await tf.loadLayersModel(modelUrl);
                onModelLoaded();
            } catch (err2) {
                tfModel = null;
                if (hudStatusText) hudStatusText.textContent = 'Gagal Memuat Model';
                if (hudStatusDot) hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
                console.error('Model TFJS gagal dimuat:', err2);
            }
        }
    }

    // ----------------------------------------------------
    // 8. Event Listeners
    // ----------------------------------------------------
    if (btnStart) btnStart.addEventListener('click', startCamera);
    if (btnQuickStart) btnQuickStart.addEventListener('click', startCamera);
    if (turnOnCamOverlayBtn) turnOnCamOverlayBtn.addEventListener('click', startCamera);
    if (btnStop) btnStop.addEventListener('click', stopCamera);
    if (btnTogglePower) {
        btnTogglePower.addEventListener('click', () => {
            if (isCameraRunning) {
                stopCamera();
            } else {
                startCamera();
            }
        });
    }

    if (cameraStatusBadge) {
        cameraStatusBadge.addEventListener('click', () => {
            if (isCameraRunning) {
                stopCamera();
            } else {
                startCamera();
            }
        });
    }

    if (fullscreenBtn && cameraViewportArea) {
        fullscreenBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                if (cameraViewportArea.requestFullscreen) {
                    cameraViewportArea.requestFullscreen();
                } else if (cameraViewportArea.webkitRequestFullscreen) {
                    cameraViewportArea.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });
    }

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

    if (btnToggleSkeleton) {
        btnToggleSkeleton.addEventListener('click', () => {
            showSkeleton = !showSkeleton;
            btnToggleSkeleton.classList.toggle('text-primary');
            btnToggleSkeleton.classList.toggle('text-on-surface-variant');
        });
    }

    if (thresholdSlider) {
        thresholdSlider.addEventListener('input', (e) => {
            if (thresholdVal) thresholdVal.textContent = `${e.target.value}%`;
        });
    }

    if (btnOpenSettings) {
        btnOpenSettings.addEventListener('click', () => {
            if (modelUrlInput) modelUrlInput.value = modelUrl;
            if (classesInput) classesInput.value = classLabels.join(', ');
            if (thresholdSlider) thresholdSlider.value = Math.round(confidenceThreshold * 100);
            if (thresholdVal && thresholdSlider) thresholdVal.textContent = `${thresholdSlider.value}%`;
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

    if (btnSaveSettings) {
        btnSaveSettings.addEventListener('click', async () => {
            if (modelUrlInput) modelUrl = modelUrlInput.value.trim();
            if (classesInput) {
                classLabels = classesInput.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
            }
            if (thresholdSlider) {
                confidenceThreshold = parseInt(thresholdSlider.value, 10) / 100;
            }

            closeSettings();
            await loadModel();
        });
    }

    // ----------------------------------------------------
    // 9. SIBI Vocabulary Guide & Hand Formation Metadata
    // ----------------------------------------------------
    const SIBI_GUIDE_MAP = {
        'KOPI': { cat: 'Menu Kafe Kasuli', pos: '(Nomina / Kata Benda)', guide: "Tangan dominan membentuk pola huruf 'C' lalu digerakkan melingkar di atas kepalan tangan statis." },
        'KOPI HANGAT': { cat: 'Menu Kafe Kasuli', pos: '(Frasa Nomina)', guide: "Bentuk huruf 'C' digerakkan melingkar di atas tangan statis, dilanjutkan gestur tangan meremas hangat di depan dada." },
        'SUSU': { cat: 'Menu Kafe Kasuli', pos: '(Nomina / Kata Benda)', guide: 'Kedua tangan membentuk kepalan meremas bergantian ke bawah seolah memerah susu murni.' },
        'GULA': { cat: 'Bahan Menu', pos: '(Nomina / Kata Benda)', guide: 'Ujung ibu jari dan telunjuk saling menggosok di depan bibir melambangkan rasa manis.' },
        'GULA SEDIKIT': { cat: 'Kustomisasi Menu', pos: '(Frasa Modifikasi)', guide: "Ibu jari dan telunjuk saling menggosok (manis), diikuti jari telunjuk dan ibu jari membentuk celah sempit (sedikit)." },
        'SELAMAT': { cat: 'Ungkapan Salam', pos: '(Partikel / Salam)', guide: 'Telapak tangan terbuka saling menyapu diagonal ke arah atas secara ramah.' },
        'SELAMAT PAGI': { cat: 'Salam Ramah', pos: '(Ungkapan Sapaan)', guide: "Telapak terbuka menyapu ke atas (selamat), diikuti tangan naik melengkung seperti matahari fajar terbit (pagi)." },
        'PAGI': { cat: 'Waktu / Salam', pos: '(Nomina / Waktu)', guide: 'Tangan bawah horizontal membentuk horizon, tangan atas naik melengkung seperti fajar merekah.' },
        'SIANG': { cat: 'Waktu / Salam', pos: '(Nomina / Waktu)', guide: 'Lengan tegak lurus membentuk sudut 90 derajat melambangkan matahari tepat di atas kepala.' },
        'SORE': { cat: 'Waktu / Salam', pos: '(Nomina / Waktu)', guide: 'Lengan miring turun 45 derajat melambangkan matahari condong ke ufuk barat.' },
        'MALAM': { cat: 'Waktu / Salam', pos: '(Nomina / Waktu)', guide: 'Kedua tangan menyilang di depan dada dengan telapak menghadap ke bawah menutup.' },
        'TERIMAKASIH': { cat: 'Ungkapan Syukur', pos: '(Partikel Santun)', guide: 'Ujung jari tangan terbuka diletakkan di dagu/bibir lalu diayunkan maju ke depan ke arah lawan bicara.' },
        'TERIMA KASIH': { cat: 'Ungkapan Syukur', pos: '(Partikel Santun)', guide: 'Ujung jari tangan terbuka diletakkan di dagu/bibir lalu diayunkan maju ke depan ke arah lawan bicara.' },
        'SAYA': { cat: 'Kata Ganti', pos: '(Pronomina Persona)', guide: 'Jari telunjuk menunjuk lurus ke bagian tengah dada sendiri dengan mantap.' },
        'KAMU': { cat: 'Kata Ganti', pos: '(Pronomina Persona)', guide: 'Jari telunjuk menunjuk sopan ke arah lawan bicara di depan Anda.' },
        'MAU': { cat: 'Kata Kerja', pos: '(Verba Bantu)', guide: 'Kedua tangan terbuka menghadap atas lalu ditarik ke arah badan sambil merapat.' },
        'PESAN': { cat: 'Aktivitas Kedai', pos: '(Verba Tindakan)', guide: 'Jari telunjuk dan tengah mengetuk permukaan telapak tangan kiri seolah mencatat pesanan.' },
        'BERAPA': { cat: 'Pertanyaan Kasir', pos: '(Pronomina Tanya)', guide: 'Jari-jemari diketuk-ketuk bergantian dari kelingking ke ibu jari menghadap atas.' },
        'BERAPA HARGA?': { cat: 'Pertanyaan Kasir', pos: '(Kalimat Tanya)', guide: 'Jari-jemari digetarkan terbuka (berapa), diikuti gesekan ibu jari dan telunjuk (uang/harga).' },
        'APA': { cat: 'Kata Tanya', pos: '(Pronomina Tanya)', guide: 'Kedua telapak tangan terbuka menengadah digoyangkan ke kiri dan kanan.' }
    };

    function updateSignGuide(word) {
        if (!word) return;
        const clean = word.trim().toUpperCase();
        const entry = SIBI_GUIDE_MAP[clean] || SIBI_GUIDE_MAP[clean.replace(/\s+/g, '')];

        const catBadge = document.getElementById('categoryBadge');
        const detectedCat = document.getElementById('detectedCategory');
        const handShapeGuide = document.getElementById('handShapeGuide');

        if (entry) {
            if (catBadge) {
                catBadge.innerHTML = `<span class="material-symbols-outlined text-[14px]">local_cafe</span><span>${entry.cat}</span>`;
            }
            if (detectedCat) {
                detectedCat.textContent = entry.pos;
            }
            if (handShapeGuide) {
                handShapeGuide.innerHTML = `<strong class="text-on-surface">Bentuk Tangan SIBI:</strong> ${entry.guide}`;
            }
        } else {
            if (catBadge) {
                catBadge.innerHTML = `<span class="material-symbols-outlined text-[14px]">auto_awesome</span><span>Kosakata SIBI</span>`;
            }
            if (detectedCat) {
                detectedCat.textContent = '(Kosakata Umum SIBI)';
            }
            if (handShapeGuide) {
                handShapeGuide.innerHTML = `<strong class="text-on-surface">Bentuk Tangan SIBI:</strong> Tangan membentuk gestur "${escapeHtml(word)}" sesuai panduan abjad/kata resmi SIBI Kemendikbud.`;
            }
        }
    }

    function addTranslationHistory(text) {
        if (!text || text.trim() === '') return;
        const historyList = document.getElementById('translationHistoryList');
        if (!historyList) return;

        const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
        const item = document.createElement('div');
        item.className = 'flex items-center justify-between p-2 rounded-lg bg-surface-container-low text-on-surface text-xs transition-all hover:bg-surface-container';
        item.innerHTML = `
            <span class="font-medium">${escapeHtml(text)}</span>
            <span class="text-[11px] text-on-surface-variant">${timeStr}</span>
        `;
        historyList.prepend(item);
    }

    // ----------------------------------------------------
    // 10. Barista Modal & Quick Phrases Integrations
    // ----------------------------------------------------
    const btnSendToBarista = document.getElementById('btnSendToBarista');
    const baristaModal = document.getElementById('baristaModal');
    const btnCloseBaristaModal = document.getElementById('btnCloseBaristaModal');
    const btnConfirmBarista = document.getElementById('btnConfirmBarista');
    const btnBaristaSpeech = document.getElementById('btnBaristaSpeech');

    if (btnSendToBarista && baristaModal) {
        btnSendToBarista.addEventListener('click', () => {
            const fullText = sentenceWords.join(' ').trim();
            const textToDisplay = fullText || (liveDetectedText && liveDetectedText.textContent !== '-' && liveDetectedText.textContent !== '...' ? liveDetectedText.textContent : 'Halo, saya mau pesan kopi susu');

            const baristaSentenceText = document.getElementById('baristaSentenceText');
            if (baristaSentenceText) {
                baristaSentenceText.textContent = `"${textToDisplay}"`;
            }
            baristaModal.classList.remove('hidden');
            baristaModal.classList.add('flex');
            addTranslationHistory(textToDisplay);
        });
    }

    function closeBaristaModal() {
        if (baristaModal) {
            baristaModal.classList.add('hidden');
            baristaModal.classList.remove('flex');
        }
    }
    if (btnCloseBaristaModal) btnCloseBaristaModal.addEventListener('click', closeBaristaModal);
    if (btnConfirmBarista) btnConfirmBarista.addEventListener('click', closeBaristaModal);
    if (btnBaristaSpeech) {
        btnBaristaSpeech.addEventListener('click', () => {
            const baristaSentenceText = document.getElementById('baristaSentenceText');
            const text = baristaSentenceText ? baristaSentenceText.textContent.replace(/["']/g, '') : '';
            if (text) speakText(text);
        });
    }

    // Quick phrases buttons
    document.querySelectorAll('.quick-phrase-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const phrase = btn.dataset.phrase;
            if (!phrase) return;
            appendWordToSentence(phrase);
            if (liveDetectedText) liveDetectedText.textContent = phrase.toUpperCase();
            updateSignGuide(phrase);
            speakText(phrase);
        });
    });

    // Troubleshooting Modal
    const btnTroubleshoot = document.getElementById('btnTroubleshoot');
    const troubleshootModal = document.getElementById('troubleshootModal');
    const btnCloseTroubleshoot = document.getElementById('btnCloseTroubleshoot');
    const btnDismissTroubleshoot = document.getElementById('btnDismissTroubleshoot');

    if (btnTroubleshoot && troubleshootModal) {
        btnTroubleshoot.addEventListener('click', () => {
            troubleshootModal.classList.remove('hidden');
            troubleshootModal.classList.add('flex');
        });
    }
    function closeTroubleshootModal() {
        if (troubleshootModal) {
            troubleshootModal.classList.add('hidden');
            troubleshootModal.classList.remove('flex');
        }
    }
    if (btnCloseTroubleshoot) btnCloseTroubleshoot.addEventListener('click', closeTroubleshootModal);
    if (btnDismissTroubleshoot) btnDismissTroubleshoot.addEventListener('click', closeTroubleshootModal);

    const btnRequestPermission = document.getElementById('btnRequestPermission');
    if (btnRequestPermission) {
        btnRequestPermission.addEventListener('click', () => {
            if (!isCameraRunning) {
                startCamera();
            } else {
                alert('Izin kamera sudah aktif dan webcam sedang berjalan.');
            }
        });
    }

    initMediaPipeHands();
    loadModel();
});