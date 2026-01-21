@extends('layouts.app')

@section('content')
    <div class="container fade-in-up">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Scan Absensi Barcode</h2>
                <p class="text-muted">Pilih sesi absensi disebelah kanan kamera sebelum memulai.</p>
            </div>

            @if(Auth::user()->role === 'guru')
                <!-- Camera Section -->
                <div class="col-lg-7 mb-4">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-body p-0 position-relative bg-black" style="min-height: 400px;">
                            <!-- Camera Viewport -->
                            <div class="position-relative h-100">
                                <div id="reader" class="d-none w-100 h-100"></div>

                                <!-- Scanning Animation Overlay -->
                                <div id="scanOverlay"
                                    class="position-absolute top-0 start-0 w-100 h-100 d-none d-flex flex-column align-items-center justify-content-center"
                                    style="background: rgba(0,0,0,0.3); z-index: 10;">
                                    <div class="scan-line"></div>
                                    <div class="text-white fw-bold mt-3 text-shadow">Sedang Membaca...</div>
                                </div>
                            </div>

                            <div id="cameraPlaceholder"
                                class="d-flex flex-column align-items-center justify-content-center text-white h-100 position-absolute top-0 start-0 w-100">
                                <i class="bi bi-camera-video-off display-1 mb-3 opacity-50"></i>
                                <h5 class="fw-light">Kamera Nonaktif</h5>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-3 text-center">
                            <div id="scanStatus" class="fw-bold text-muted small mb-2">Pilih sesi & hidupkan kamera</div>
                            <button id="toggleCameraBtn" class="btn btn-primary rounded-pill px-5 shadow-sm hover-lift">
                                <i class="bi bi-camera-video me-2"></i> Hidupkan Kamera
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Session Selection -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-clock-history me-2"></i>Sesi Absensi</h5>

                            <div class="d-flex flex-column gap-3" id="sessionSelector">
                                <!-- Pagi -->
                                <div class="position-relative">
                                    <input type="radio" class="btn-check session-radio" name="session_type" id="pagi"
                                        value="pagi" autocomplete="off">
                                    <label
                                        class="btn btn-outline-light text-start w-100 p-3 rounded-4 d-flex align-items-center session-btn shadow-sm text-dark border"
                                        for="pagi" style="transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);">
                                        <div class="bg-primary text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px;">
                                            <i class="bi bi-sun-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-6">Absen Pagi</div>
                                        </div>
                                        <i
                                            class="bi bi-check-circle-fill fs-4 ms-auto text-primary check-icon opacity-0 transition-opacity"></i>
                                    </label>
                                </div>

                                <!-- Sholat -->
                                <div class="position-relative">
                                    <input type="radio" class="btn-check session-radio" name="session_type" id="sholat"
                                        value="solat" autocomplete="off">
                                    <label
                                        class="btn btn-outline-light text-start w-100 p-3 rounded-4 d-flex align-items-center session-btn shadow-sm text-dark border"
                                        for="sholat" style="transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);">
                                        <div class="bg-info text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px;">
                                            <i class="bi bi-cloud-sun-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-6">Absen Sholat</div>
                                        </div>
                                        <i
                                            class="bi bi-check-circle-fill fs-4 ms-auto text-info check-icon opacity-0 transition-opacity"></i>
                                    </label>
                                </div>

                                <!-- Pulang -->
                                <div class="position-relative">
                                    <input type="radio" class="btn-check session-radio" name="session_type" id="pulang"
                                        value="pulang" autocomplete="off">
                                    <label
                                        class="btn btn-outline-light text-start w-100 p-3 rounded-4 d-flex align-items-center session-btn shadow-sm text-dark border"
                                        for="pulang" style="transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);">
                                        <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px;">
                                            <i class="bi bi-house-door-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-6">Absen Pulang</div>
                                        </div>
                                        <i
                                            class="bi bi-check-circle-fill fs-4 ms-auto text-success check-icon opacity-0 transition-opacity"></i>
                                    </label>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Fitur Scan Absensi hanya tersedia untuk Guru.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @if(Auth::user()->role === 'guru')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .scan-line { width: 80%; height: 4px; background: #0d6efd; box-shadow: 0 0 10px #0d6efd; animation: scan 1.5s infinite linear; border-radius: 50%; }
        @keyframes scan {
            0% { transform: translateY(-50px); opacity: 0; }
            10% { opacity: 1; }
            100% { transform: translateY(250px); opacity: 0; }
        }
        .text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.8); }
        #reader video { transform: scaleX(-1) !important; object-fit: cover; width: 100%; height: 100%; }
        
        /* Session Button Styles */
        .session-btn:hover {
            transform: translateX(5px);
            background-color: #f8f9fa;
        }
        .btn-check:checked + .session-btn {
            border-color: currentColor !important;
            background-color: #f0f7ff;
            border-width: 2px !important;
        }
        .btn-check:checked + .session-btn .check-icon {
            opacity: 1 !important;
        }
    </style>
    <script>
        let isScanning = false;
        let html5QrCode = null;
        let isCameraRunning = false;
        let currentSession = null;
        const storageBaseUrl = "{{ asset('storage') }}/";
        
        const audioSuccess = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); 
        const audioError = new Audio('https://assets.mixkit.co/active_storage/sfx/2572/2572-preview.mp3'); 

        // Toggle Logic for Radio Buttons
        document.querySelectorAll('.session-radio').forEach(radio => {
            radio.addEventListener('click', function(e) {
                // If Camera is RUNNING
                if (isCameraRunning) {
                    if (currentSession === this.value) {
                        // User clicked the SAME button while camera is ON
                        // Prevent unchecking, keep it active (as requested)
                        e.preventDefault(); 
                        this.checked = true; // Ensure it stays checked
                    } else {
                        // User clicked a DIFFERENT button while camera is ON
                        // Switch session immediately and update status text
                        currentSession = this.value;
                        const labelText = this.nextElementSibling.querySelector('div.fw-bold').innerText;
                        document.getElementById('scanStatus').innerText = "Kamera Aktif: " + labelText;
                    }
                } 
                // If Camera is OFF
                else {
                    if (currentSession === this.value) {
                        // Allow toggle off only if camera is NOT running
                        this.checked = false;
                        currentSession = null;
                        document.getElementById('toggleCameraBtn').classList.replace('btn-primary', 'btn-outline-primary');
                    } else {
                         currentSession = this.value;
                    }
                }
            });
        });

        function stopCameraSafe() {
            if(isCameraRunning) stopCamera();
        }

        function onScanSuccess(decodedText, decodedResult) {
        if (isScanning) return;
        isScanning = true;

        // 1. FREEZE Camera (Capture effect)
        if (html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
        html5QrCode.pause(true);
        }

        // 2. Show Scanning Animation
        document.getElementById('scanOverlay').classList.remove('d-none');
        document.getElementById('scanStatus').innerText = "Mendeteksi Siswa...";

        // 3. Process after a brief visual delay (feeling of "reading")
        setTimeout(() => {
        processScan(decodedText);
        }, 800);
        }

        function onScanFailure(error) {
        // ignore
        }

        document.addEventListener('DOMContentLoaded', function () {
        html5QrCode = new Html5Qrcode("reader");

        document.getElementById('toggleCameraBtn').addEventListener('click', function () {
        if (isCameraRunning) {
        stopCamera();
        } else {
        startCamera();
        }
        });
        });

        function startCamera() {
        // Validate Session Selection
        const session = document.querySelector('input[name="session_type"]:checked');
        if (!session) {
        Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: 'Silakan pilih sesi absensi (Pagi / Sholat / Pulang) terlebih dahulu!',
        confirmButtonText: 'OK'
        });
        return;
        }

        // Higher FPS for faster detection
        const config = { fps: 30, qrbox: { width: 250, height: 250 } };
        document.getElementById('scanStatus').innerText = "Memulai kamera...";

        document.getElementById('reader').classList.remove('d-none');
        document.getElementById('cameraPlaceholder').classList.add('d-none');

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
        .then(() => {
        isCameraRunning = true;
        document.getElementById('scanStatus').innerText = "Kamera Aktif: " + session.nextElementSibling.innerText;

        const btn = document.getElementById('toggleCameraBtn');
        btn.innerHTML = '<i class="bi bi-camera-video-off me-2"></i> Matikan Kamera';
        btn.classList.replace('btn-outline-primary', 'btn-primary');
        })
        .catch(err => {
        console.log("Error starting scanner", err);
        // Fallback config
        html5QrCode.start({}, config, onScanSuccess, onScanFailure)
        .then(() => {
        isCameraRunning = true;
        document.getElementById('scanStatus').innerText = "Kamera Aktif (Fallback)";
        const btn = document.getElementById('toggleCameraBtn');
        btn.innerHTML = '<i class="bi bi-camera-video-off me-2"></i> Matikan Kamera';
        btn.classList.replace('btn-outline-primary', 'btn-primary');
        })
        .catch(err2 => {
        document.getElementById('scanStatus').innerText = "Error: " + err2;
        });
        });
        }

        function stopCamera() {
        html5QrCode.stop().then(() => {
        isCameraRunning = false;
        document.getElementById('scanStatus').innerText = "Kamera dimatikan";
        document.getElementById('reader').classList.add('d-none');
        document.getElementById('cameraPlaceholder').classList.remove('d-none');
        document.getElementById('scanOverlay').classList.add('d-none');

        const btn = document.getElementById('toggleCameraBtn');
        btn.innerHTML = '<i class="bi bi-camera-video me-2"></i> Hidupkan Kamera';
        btn.classList.replace('btn-primary', 'btn-outline-primary');
        }).catch(err => {
        console.log("Failed to stop.", err);
        });
        }

        function processScan(code) {
        const session = document.querySelector('input[name="session_type"]:checked').value;

        fetch('{{ route("attendance.scan") }}', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
        barcode_code: code,
        session_type: session
        })
        })
        .then(response => response.json())
        .then(data => {
        document.getElementById('scanOverlay').classList.add('d-none'); // Hide anim
        showResult(data);
        })
        .catch(error => {
        document.getElementById('scanOverlay').classList.add('d-none');
        console.error('Error:', error);
        playSound('error');

        Swal.fire({
        icon: 'error',
        title: 'Error Sistem',
        text: 'Gagal memproses data.',
        timer: 2000,
        showConfirmButton: false
        }).then(() => {
        resumeScanning();
        });
        });
        }

        function showResult(data) {
        if (data.status === 'success') {
        playSound('success');

        let imageUrl = null;
        if (data.student && data.student.photo) {
        imageUrl = storageBaseUrl + data.student.photo;
        }

        Swal.fire({
        title: data.student.name,
        html: `<div class="fs-4 mb-2">Absen <b>${data.type.toUpperCase()}</b></div>
        <div class="text-muted"><i class="bi bi-clock me-1"></i> ${data.time}</div>`,
        icon: 'success',
        imageUrl: imageUrl,
        imageWidth: 120,
        imageHeight: 120,
        imageAlt: 'Foto Siswa',
        imageClass: 'rounded-circle shadow border border-3 border-success',
        timer: 3000, // Show for 3 sec
        showConfirmButton: false,
        backdrop: `rgba(0,0,0,0.4)`
        }).then(() => {
        resumeScanning();
        });

        } else if (data.status === 'warning') {
        playSound('error');
        Swal.fire({
        title: 'Peringatan',
        text: data.message,
        icon: 'warning',
        timer: 2000,
        showConfirmButton: false
        }).then(() => {
        resumeScanning();
        });
        } else {
        playSound('error');
        Swal.fire({
        title: 'Gagal',
        text: data.message,
        icon: 'error',
        timer: 2000,
        showConfirmButton: false
        }).then(() => {
        resumeScanning();
        });
        }
        }

        function resumeScanning() {
        isScanning = false;
        document.getElementById('scanStatus').innerText = "Siap scan berikutnya...";
        if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.PAUSED) {
        html5QrCode.resume();
        }
        }

        function playSound(type) {
        if (type === 'success') {
        audioSuccess.currentTime = 0;
        audioSuccess.play().catch(e => console.log('Audio error', e));
        } else {
        audioError.currentTime = 0;
        audioError.play().catch(e => console.log('Audio error', e));
        }
        }
        </script>
    @endif
@endsection