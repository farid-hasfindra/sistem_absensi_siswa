@extends('layouts.app')

@section('content')
    <div class="row justify-content-center fade-in-up">
        <div class="col-md-6 text-center">
            <h2 class="fw-bold mb-4">Scan Absensi Barcode</h2>

            @if(Auth::user()->role === 'guru')
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-0 position-relative bg-black">
                        <!-- Camera Viewport -->
                        <div class="position-relative">
                            <div id="reader" class="d-none" style="width: 100%; min-height: 300px; background: black;"></div>
                            
                            <!-- Scanning Animation Overlay -->
                            <div id="scanOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-none d-flex flex-column align-items-center justify-content-center" 
                                 style="background: rgba(0,0,0,0.3); z-index: 10;">
                                <div class="scan-line"></div>
                                <div class="text-white fw-bold mt-3 text-shadow">Sedang Membaca...</div>
                            </div>
                        </div>

                        <div id="cameraPlaceholder" class="d-flex align-items-center justify-content-center text-white" style="min-height: 300px;">
                            <div class="text-center">
                                <i class="bi bi-camera-video-off fs-1"></i>
                                <p class="mt-2">Kamera Nonaktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-3">
                         <!-- Removed previous status text to keep it clean -->
                         <div id="scanStatus" class="fw-bold text-muted small">Siap Scan</div>
                         
                         <button id="toggleCameraBtn" class="btn btn-outline-primary rounded-pill px-4 mt-2">
                            <i class="bi bi-camera-video me-2"></i> Hidupkan Kamera
                         </button>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Fitur Scan Absensi hanya tersedia untuk Guru.
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @if(Auth::user()->role === 'guru')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .scan-line {
            width: 80%;
            height: 4px;
            background: #0d6efd;
            box-shadow: 0 0 10px #0d6efd;
            animation: scan 1.5s infinite linear;
            border-radius: 50%;
        }
        @keyframes scan {
            0% { transform: translateY(-50px); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(50px); opacity: 0; }
        }
        .text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.8); }
        /* Mirror the video to make aiming natural */
        #reader video {
            transform: scaleX(-1) !important;
        }
    </style>
    <script>
        let isScanning = false;
        let html5QrCode = null;
        let isCameraRunning = false;
        const storageBaseUrl = "{{ asset('storage') }}/";
        
        // Audio objects
        const audioSuccess = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); 
        const audioError = new Audio('https://assets.mixkit.co/active_storage/sfx/2572/2572-preview.mp3'); 

        function onScanSuccess(decodedText, decodedResult) {
            if(isScanning) return; 
            isScanning = true;

            // 1. FREEZE Camera (Capture effect)
            if(html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
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
        
        document.addEventListener('DOMContentLoaded', function() {
             html5QrCode = new Html5Qrcode("reader");
             
             document.getElementById('toggleCameraBtn').addEventListener('click', function() {
                 if (isCameraRunning) {
                     stopCamera();
                 } else {
                     startCamera();
                 }
             });
        });

        function startCamera() {
             // Higher FPS for faster detection
             const config = { fps: 30, qrbox: { width: 250, height: 250 } };
             document.getElementById('scanStatus').innerText = "Memulai kamera...";
             
             document.getElementById('reader').classList.remove('d-none');
             document.getElementById('cameraPlaceholder').classList.add('d-none');
             
             html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
             .then(() => {
                 isCameraRunning = true;
                 document.getElementById('scanStatus').innerText = "Kamera Aktif";
                 
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
            fetch('{{ route("attendance.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ barcode_code: code })
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
                if(data.student && data.student.photo) {
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
             if(html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.PAUSED) {
                 html5QrCode.resume();
             }
        }

        function playSound(type) {
             if(type === 'success') {
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