<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-6 px-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-2xl flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                Scanner QR Code Peminjaman
            </h2>
            <p class="text-purple-100 text-sm mt-1">Scan QR code untuk verifikasi peminjaman buku</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Scanner Section -->
            <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Scan QR Code
                        </h3>
                        <button id="toggleCamera" onclick="toggleCamera()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-500 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span id="cameraButtonText">Aktifkan Kamera</span>
                        </button>
                    </div>

                    <!-- Scanner Container -->
                    <div id="scanner-container" class="hidden">
                        <div class="relative bg-black rounded-xl overflow-hidden" style="max-width: 100%; margin: 0 auto;">
                            <div id="qr-reader" class="w-full" style="min-height:280px;"></div>
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="absolute inset-0 border-2 border-purple-500 opacity-50 m-8 rounded-lg"></div>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-center">
                                    <svg class="w-16 h-16 mx-auto mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    <p class="text-sm font-semibold">Arahkan kamera ke QR Code</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col sm:flex-row gap-3 items-center justify-between">
                            <div class="text-sm text-gray-600 text-center sm:text-left">
                                <p>💡 <strong>Tips:</strong> Pastikan QR code berada dalam frame dan pencahayaan cukup</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <select id="cameraSelect" class="border-gray-300 rounded-md shadow-sm text-sm"></select>
                                <label class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md cursor-pointer hover:bg-gray-200 text-sm">
                                    Upload/Foto QR
                                    <input id="qr-file-input" type="file" accept="image/*" capture="environment" class="hidden" />
                                </label>
                            </div>
                        </div>
                        <div id="https-warning" class="hidden mt-3 text-sm text-yellow-700 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                            ⚠️ Akses kamera di perangkat mobile biasanya membutuhkan HTTPS. Buka halaman ini lewat https atau gunakan alamat <strong>localhost</strong> di perangkat yang sama.
                        </div>
                    </div>

                    <!-- Placeholder when camera is off -->
                    <div id="camera-placeholder" class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-12 text-center">
                        <svg class="w-24 h-24 mx-auto text-purple-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <h4 class="text-xl font-bold text-gray-700 mb-2">Kamera Belum Aktif</h4>
                        <p class="text-gray-600 mb-4">Klik tombol "Aktifkan Kamera" untuk mulai scan QR code</p>
                        <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Browser akan meminta izin akses kamera
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            <div id="result-section" class="hidden bg-white shadow-xl sm:rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hasil Verifikasi
                    </h3>
                </div>
                <div class="p-6" id="result-content">
                    <!-- Content will be filled by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Runtime config for JS (avoid Blade inside <script>) -->
    <div id="scanner-config" class="hidden"
        data-verify-url="{{ route('scanner.verify', [], false) }}"
        data-borrows-index-url="{{ route('borrows.index', [], false) }}"
        data-csrf="{{ csrf_token() }}"></div>

    @push('scripts')
    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <script>
        let html5QrCode = null;
        let currentCameraId = null;
        let cameraActive = false;
        // Will be populated from #scanner-config on DOMContentLoaded
        let VERIFY_URL = '';
        let BORROWS_INDEX_URL = '';
        let CSRF_TOKEN = '';

        // Transition guards
        let isStarting = false;
        let isStopping = false;
        let stopPromise = null;

        function setTransitionUI(inProgress, label = null) {
            const toggleBtn = document.getElementById('toggleCamera');
            if (toggleBtn) {
                toggleBtn.disabled = inProgress;
                toggleBtn.classList.toggle('opacity-60', inProgress);
                const span = document.getElementById('cameraButtonText');
                if (span && label) span.textContent = label;
            }
            const cameraSelect = document.getElementById('cameraSelect');
            if (cameraSelect) cameraSelect.disabled = inProgress;
        }

        async function toggleCamera() {
            if (cameraActive) {
                await stopCamera();
            } else {
                await startCamera();
            }
        }

        async function startCamera() {
            const scannerContainer = document.getElementById('scanner-container');
            const placeholder = document.getElementById('camera-placeholder');
            const buttonText = document.getElementById('cameraButtonText');
            const cameraSelect = document.getElementById('cameraSelect');
            const httpsWarning = document.getElementById('https-warning');

            try {
                if (isStarting || cameraActive) return; // prevent duplicate starts
                // Wait if a stop is in progress
                if (isStopping && stopPromise) {
                    await stopPromise;
                }
                isStarting = true;
                setTransitionUI(true, 'Mengaktifkan...');
                // HTTPS / Secure context advisory (mobile browsers need https or localhost)
                const isSecure = location.protocol === 'https:' || ['localhost', '127.0.0.1'].includes(location.hostname);
                if (httpsWarning) httpsWarning.classList.toggle('hidden', isSecure);

                // Create scanner instance if not exists
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode('qr-reader');
                }

                const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
                let started = false;

                // Try mobile-friendly facingMode first
                try {
                    await html5QrCode.start({ facingMode: { ideal: 'environment' } }, config, onScanSuccess, onScanError);
                    started = true;
                    currentCameraId = null;
                } catch (e1) {
                    console.warn('FacingMode start failed, falling back to deviceId. Reason:', e1);
                    // Request camera permission and get cameras
                    const cameras = await Html5Qrcode.getCameras();
                    if (!cameras || cameras.length === 0) {
                        alert('Tidak ada kamera yang terdeteksi pada perangkat ini.');
                        return;
                    }
                    // Populate camera picker
                    if (cameraSelect) {
                        cameraSelect.innerHTML = '';
                        cameras.forEach((cam, idx) => {
                            const opt = document.createElement('option');
                            opt.value = cam.id;
                            opt.textContent = cam.label || `Kamera ${idx + 1}`;
                            cameraSelect.appendChild(opt);
                        });
                    }
                    // Prefer back camera
                    let camId = cameras[0].id;
                    for (const cam of cameras) {
                        const label = (cam.label || '').toLowerCase();
                        if (label.includes('back') || label.includes('rear') || label.includes('environment')) { camId = cam.id; break; }
                    }
                    currentCameraId = camId;
                    if (cameraSelect) cameraSelect.value = camId;
                    await html5QrCode.start(camId, config, onScanSuccess, onScanError);
                    started = true;
                }

                // Update UI
                if (started) {
                    cameraActive = true;
                    scannerContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    buttonText.textContent = 'Matikan Kamera';
                    console.log('Camera started successfully');
                }

            } catch (err) {
                console.error('Error starting camera:', err);
                let errorMessage = 'Gagal mengaktifkan kamera.';
                if (err.name === 'NotAllowedError') {
                    errorMessage = 'Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.';
                } else if (err.name === 'NotFoundError') {
                    errorMessage = 'Kamera tidak ditemukan pada perangkat ini.';
                } else if (err.name === 'NotReadableError') {
                    errorMessage = 'Kamera sedang digunakan oleh aplikasi lain.';
                } else if ((err.message || '').toLowerCase().includes('already under transition')) {
                    // Another start/stop is in progress; don't spam alert, just advise retry
                    errorMessage = 'Kamera sedang berganti status. Coba lagi sebentar...';
                } else {
                    errorMessage += ` Error: ${err.message || err}`;
                }
                alert(errorMessage);
            } finally {
                isStarting = false;
                setTransitionUI(false);
            }
        }

        function stopCamera() {
            if (!html5QrCode || !cameraActive) {
                return Promise.resolve();
            }
            if (isStopping && stopPromise) return stopPromise;
            isStopping = true;
            setTransitionUI(true, 'Mematikan...');
            stopPromise = html5QrCode.stop().then(() => {
                cameraActive = false;
                document.getElementById('scanner-container').classList.add('hidden');
                document.getElementById('camera-placeholder').classList.remove('hidden');
                document.getElementById('cameraButtonText').textContent = 'Aktifkan Kamera';
                console.log('Camera stopped successfully');
            }).catch(err => {
                console.error('Error stopping camera:', err);
                // Force reset even if stop fails
                cameraActive = false;
                document.getElementById('scanner-container').classList.add('hidden');
                document.getElementById('camera-placeholder').classList.remove('hidden');
                document.getElementById('cameraButtonText').textContent = 'Aktifkan Kamera';
            }).finally(() => {
                isStopping = false;
                setTransitionUI(false);
            });
            return stopPromise;
        }

        async function restartCamera() {
            try { await stopCamera(); } catch (e) { /* ignore */ }
            await startCamera();
        }

        // Html5Qrcode callbacks
        function onScanSuccess(decodedText, decodedResult) {
            console.log('QR Code detected:', decodedText);
            verifyQRCode(decodedText);
            stopCamera();
        }
        function onScanError(err) {
            // ignore frame errors
        }

        function verifyQRCode(qrData) {
            // Show loading state
            const resultSection = document.getElementById('result-section');
            const resultContent = document.getElementById('result-content');
            
            resultSection.classList.remove('hidden');
            resultContent.innerHTML = `
                <div class="text-center py-8">
                    <svg class="animate-spin w-12 h-12 mx-auto text-purple-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-gray-600">Memverifikasi QR Code...</p>
                </div>
            `;

            // Send to server for verification
            fetch(VERIFY_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                credentials: 'same-origin',
                body: JSON.stringify({ qr_data: qrData })
            })
            .then(async (response) => {
                if (!response.ok) {
                    if (response.status === 419) {
                        throw new Error('Sesi kedaluwarsa atau cookies diblokir. Silakan reload halaman dan pastikan Anda login pada domain/URL ini.');
                    }
                    let text = '';
                    try { text = await response.text(); } catch (_) {}
                    throw new Error('Gagal memverifikasi (HTTP ' + response.status + '). ' + (text || ''));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    displayResult(data.data);
                } else {
                    displayError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayError(error.message || 'Terjadi kesalahan saat memverifikasi QR Code');
            });
        }

        function displayResult(data) {
            const resultContent = document.getElementById('result-content');
            
            const statusColors = {
                'pending': 'yellow',
                'approved': 'green',
                'rejected': 'red',
                'return_requested': 'blue',
                'returned': 'gray'
            };
            
            const color = statusColors[data.status] || 'gray';
            const daysRemaining = data.days_remaining;
            const isOverdue = daysRemaining < 0;
            const dueDateClass = isOverdue ? 'text-red-600 font-bold' : (daysRemaining <= 3 ? 'text-orange-600' : 'text-green-600');
            
            resultContent.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Kode Peminjaman</p>
                            <p class="text-2xl font-bold text-purple-600 font-mono">${data.borrow_code}</p>
                        </div>
                        <div class="bg-${color}-50 rounded-xl p-4 border-2 border-${color}-200">
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <p class="text-xl font-bold text-${color}-600">${data.status_label}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white border-2 border-gray-100 rounded-xl p-4">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Informasi Buku
                        </h4>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-500">Judul</p>
                                <p class="font-semibold text-gray-900">${data.book.title}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pengarang</p>
                                <p class="text-gray-700">${data.book.author}</p>
                            </div>
                            ${data.book.isbn ? `
                            <div>
                                <p class="text-xs text-gray-500">ISBN</p>
                                <p class="text-gray-700 font-mono text-sm">${data.book.isbn}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="bg-white border-2 border-gray-100 rounded-xl p-4">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Peminjam
                        </h4>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-500">Nama</p>
                                <p class="font-semibold text-gray-900">${data.user.name}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-gray-700">${data.user.email}</p>
                            </div>
                        </div>
                    </div>
                    
                    ${data.due_date ? `
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-400 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 mb-1">Tenggat Pengembalian</p>
                                <p class="${dueDateClass} text-lg font-bold">${data.due_date}</p>
                                ${daysRemaining !== null ? `
                                    <p class="text-sm ${isOverdue ? 'text-red-600 font-semibold' : 'text-gray-600'} mt-1">
                                        ${isOverdue ? 
                                            `⚠️ Terlambat ${Math.abs(daysRemaining)} hari` : 
                                            `${daysRemaining} hari lagi`
                                        }
                                    </p>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="flex gap-3">
                        <button onclick="restartCamera()" class="flex-1 px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-500 transition-colors font-semibold">
                            Scan Lagi
                        </button>
                        <button onclick="window.location.href=BORROWS_INDEX_URL" class="flex-1 px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-colors font-semibold">
                            Ke Manajemen Peminjaman
                        </button>
                    </div>
                </div>
            `;
        }

        function displayError(message) {
            const resultContent = document.getElementById('result-content');
            resultContent.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Gagal</h4>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <button onclick="startCamera()" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-500 transition-colors font-semibold">
                        Scan Ulang
                    </button>
                </div>
            `;
        }

        // Clean up when leaving page
        window.addEventListener('beforeunload', () => {
            if (cameraActive) {
                stopCamera();
            }
        });

        // Wire camera selection and image upload fallback
        document.addEventListener('DOMContentLoaded', () => {
            // Load config values from DOM
            const cfg = document.getElementById('scanner-config');
            if (cfg) {
                VERIFY_URL = cfg.dataset.verifyUrl || '';
                BORROWS_INDEX_URL = cfg.dataset.borrowsIndexUrl || '';
                CSRF_TOKEN = cfg.dataset.csrf || '';
            }

            const cameraSelect = document.getElementById('cameraSelect');
            const fileInput = document.getElementById('qr-file-input');
            if (cameraSelect) {
                cameraSelect.addEventListener('change', async (e) => {
                    const newId = e.target.value;
                    if (!html5QrCode) return;
                    try {
                        // Ensure clean switch: stop current if active
                        await stopCamera();
                        isStarting = true;
                        setTransitionUI(true, 'Mengaktifkan...');
                        await html5QrCode.start(newId, { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 }, onScanSuccess, onScanError);
                        cameraActive = true;
                        currentCameraId = newId;
                        document.getElementById('scanner-container').classList.remove('hidden');
                        document.getElementById('camera-placeholder').classList.add('hidden');
                        document.getElementById('cameraButtonText').textContent = 'Matikan Kamera';
                    } catch (err) {
                        console.error('Failed to switch camera:', err);
                        alert('Gagal berpindah kamera');
                    } finally {
                        isStarting = false;
                        setTransitionUI(false);
                    }
                });
            }
            if (fileInput) {
                fileInput.addEventListener('change', async (e) => {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    try {
                        if (!html5QrCode) html5QrCode = new Html5Qrcode('qr-reader');
                        if (cameraActive) { await html5QrCode.stop(); cameraActive = false; }
                        const text = await html5QrCode.scanFile(file, true);
                        console.log('Scanned from file:', text);
                        verifyQRCode(text);
                    } catch (err) {
                        console.error('Image scan failed:', err);
                        alert('Gagal membaca QR dari gambar. Pastikan gambar jelas.');
                    } finally {
                        e.target.value = '';
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
