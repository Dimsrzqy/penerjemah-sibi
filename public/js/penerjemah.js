
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const video = document.getElementById('webcamVideo');
    const skeletonCanvas = document.getElementById('skeletonCanvas');
    const canvasCtx = skeletonCanvas ? skeletonCanvas.getContext('2d') : null;
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
                    if (detectedCategory) detectedCategory.textContent = 'Isyarat SIBI Terdeteksi';
                    if (confidenceBadge) {
                        confidenceBadge.textContent = `${confPercent}% Cocok`;
                        confidenceBadge.className = 'text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300';
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
            if (cameraHud) cameraHud.classList.remove('hidden');

            if (btnStart) btnStart.classList.add('hidden');
            if (btnStop) {
                btnStop.classList.remove('hidden');
                btnStop.classList.add('flex');
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
        if (cameraHud) cameraHud.classList.add('hidden');

        if (btnStop) {
            btnStop.classList.add('hidden');
            btnStop.classList.remove('flex');
        }
        if (btnStart) btnStart.classList.remove('hidden');

        if (canvasCtx && skeletonCanvas) {
            canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);
        }
        resetLiveDisplay();
    }

    function resetLiveDisplay() {
        if (liveDetectedText) liveDetectedText.textContent = '-';
        if (detectedCategory) detectedCategory.textContent = 'Kamera belum aktif';
        if (confidenceBadge) confidenceBadge.textContent = '-';
        if (fpsDisplay) fpsDisplay.textContent = '0';
        if (handCountDisplay) handCountDisplay.textContent = '0 Tangan';
        if (holdProgressBar) holdProgressBar.style.width = '0%';
        if (topPredictionsContainer) {
            topPredictionsContainer.innerHTML = '<div class="text-xs text-on-surface-variant italic">Belum ada data inferensi</div>';
        }
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
        if (!sentenceOutput || !charCount) return;
        if (currentSentence.trim() === '') {
            sentenceOutput.innerHTML = '<span class="text-on-surface-variant/60 italic text-sm">Kalimat hasil terjemahan akan tersusun di sini...</span>';
            charCount.textContent = '0 karakter';
        } else {
            sentenceOutput.textContent = currentSentence;
            charCount.textContent = `${currentSentence.length} karakter`;
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
            currentSentence += ' ';
            updateSentenceDisplay();
        });
    }

    if (btnBackspace) {
        btnBackspace.addEventListener('click', () => {
            currentSentence = currentSentence.slice(0, -1);
            updateSentenceDisplay();
        });
    }

    if (btnClearAll) {
        btnClearAll.addEventListener('click', () => {
            currentSentence = '';
            updateSentenceDisplay();
        });
    }

    if (btnCopy) {
        btnCopy.addEventListener('click', async () => {
            if (!currentSentence.trim()) return;
            try {
                await navigator.clipboard.writeText(currentSentence);
                if (copyBtnText) copyBtnText.textContent = 'Tersalin!';
                setTimeout(() => {
                    if (copyBtnText) copyBtnText.textContent = 'Salin';
                }, 1500);
            } catch (e) {
                console.error('Gagal menyalin teks:', e);
            }
        });
    }

    if (btnSpeech) {
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
    if (btnStop) btnStop.addEventListener('click', stopCamera);

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

    initMediaPipeHands();
    loadModel();
});