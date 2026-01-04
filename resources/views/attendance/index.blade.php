@extends('layouts.app')

@section('content')
    <div class="row justify-content-center fade-in-up">
        <div class="col-md-6 text-center">
            <h2 class="fw-bold mb-4">Scan Absensi Barcode</h2>

            @if(Auth::user()->role === 'guru')
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-0 position-relative bg-black">
                         <!-- Camera Viewport -->
                        <div id="reader" class="d-none" style="width: 100%; min-height: 300px;"></div>
                        <div id="cameraPlaceholder" class="d-flex align-items-center justify-content-center text-white" style="min-height: 300px;">
                            <div class="text-center">
                                <i class="bi bi-camera-video-off fs-1"></i>
                                <p class="mt-2">Kamera Nonaktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-3">
                         <div id="scanStatus" class="fw-bold text-muted mb-3">Kamera dimatikan. Klik tombol untuk memulai.</div>
                         
                         <button id="toggleCameraBtn" class="btn btn-outline-primary rounded-pill px-4">
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

            <div id="resultCard" class="card border-0 shadow-sm rounded-4 d-none fade-in-up">
                <div class="card-body p-4">
                    <div id="resultIcon" class="mb-2"></div>
                    <h3 class="fw-bold mb-1" id="studentName"></h3>
                    <h5 class="text-muted" id="scanTime"></h5>
                    <div class="badge bg-primary fs-6 mt-2" id="scanType"></div>
                    <div class="alert mt-3 mb-0" id="scanMessage"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(Auth::user()->role === 'guru')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let isScanning = false;
        let html5QrCode = null;
        let isCameraRunning = false;
        
        // Audio objects
        const audioSuccess = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); // Simple ping
        const audioError = new Audio('https://assets.mixkit.co/active_storage/sfx/2572/2572-preview.mp3'); // Error buzz

        function onScanSuccess(decodedText, decodedResult) {
            if(isScanning) return; 
            
            console.log("Scan success:", decodedText);
            isScanning = true;
            processScan(decodedText);
            
            // Cooldown to prevent double scans
            setTimeout(() => { isScanning = false; }, 3000);
        }

        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
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
             // Config: Scan full frame (no qrbox), higher fps
             const config = { fps: 15, qrbox: { width: 250, height: 250 } };
             document.getElementById('scanStatus').innerText = "Memulai kamera...";
             
             document.getElementById('reader').classList.remove('d-none');
             document.getElementById('cameraPlaceholder').classList.add('d-none');
             
             // Use exact config or undefined to let browser choose default camera first
             html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
             .then(() => {
                 isCameraRunning = true;
                 document.getElementById('scanStatus').innerText = "Arahkan kamera ke Barcode Siswa. Pastikan cahaya cukup.";
                 
                 const btn = document.getElementById('toggleCameraBtn');
                 btn.innerHTML = '<i class="bi bi-camera-video-off me-2"></i> Matikan Kamera';
                 btn.classList.replace('btn-outline-primary', 'btn-primary');
             })
             .catch(err => {
                 console.log("Error starting scanner", err);
                 // Fallback: try without facingMode constraint (laptop webcams)
                 html5QrCode.start({}, config, onScanSuccess, onScanFailure)
                 .then(() => {
                     isCameraRunning = true;
                     document.getElementById('scanStatus').innerText = "Kamera Aktif (Mode Fallback)";
                     const btn = document.getElementById('toggleCameraBtn');
                     btn.innerHTML = '<i class="bi bi-camera-video-off me-2"></i> Matikan Kamera';
                     btn.classList.replace('btn-outline-primary', 'btn-primary');
                 })
                 .catch(err2 => {
                     document.getElementById('scanStatus').innerText = "Gagal membuka kamera: " + err2;
                     document.getElementById('reader').classList.add('d-none');
                     document.getElementById('cameraPlaceholder').classList.remove('d-none');
                 });
             });
        }

        function stopCamera() {
            html5QrCode.stop().then(() => {
                isCameraRunning = false;
                document.getElementById('scanStatus').innerText = "Kamera dimatikan";
                document.getElementById('reader').classList.add('d-none');
                document.getElementById('cameraPlaceholder').classList.remove('d-none');
                
                const btn = document.getElementById('toggleCameraBtn');
                btn.innerHTML = '<i class="bi bi-camera-video me-2"></i> Hidupkan Kamera';
                btn.classList.replace('btn-primary', 'btn-outline-primary');
            }).catch(err => {
                console.log("Failed to stop.", err);
            });
        }

        function processScan(code) {
             document.getElementById('scanStatus').innerText = "Memproses: " + code;
             
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
                    showResult(data);
                    document.getElementById('scanStatus').innerHTML = "Siap scan berikutnya... <br><span class='text-primary'>Last Scan: " + code + "</span>";
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('scanStatus').innerText = "Error Sistem atau Koneksi";
                    playSound('error');
                    isScanning = false; 
                });
        }

        function showResult(data) {
            const resultCard = document.getElementById('resultCard');
            const studentName = document.getElementById('studentName');
            const scanTime = document.getElementById('scanTime');
            const scanType = document.getElementById('scanType');
            const scanMessage = document.getElementById('scanMessage');
            const resultIcon = document.getElementById('resultIcon');

            resultCard.classList.remove('d-none');

            if (data.status === 'success') {
                studentName.innerText = data.student.name;
                scanTime.innerText = data.time;
                scanType.innerText = data.type.toUpperCase();
                scanMessage.className = 'alert alert-success mt-3 mb-0';
                scanMessage.innerText = data.message;
                resultIcon.innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>';
                playSound('success');
            } else if (data.status === 'warning') {
                studentName.innerText = data.student.name;
                scanTime.innerText = new Date().toLocaleTimeString();
                scanType.innerText = 'DUPLICATE';
                scanMessage.className = 'alert alert-warning mt-3 mb-0';
                scanMessage.innerText = data.message;
                resultIcon.innerHTML = '<i class="bi bi-exclamation-circle-fill text-warning" style="font-size: 4rem;"></i>';
                playSound('error');
            } else {
                studentName.innerText = 'Unknown';
                scanTime.innerText = '-';
                scanType.innerText = 'ERROR';
                scanMessage.className = 'alert alert-danger mt-3 mb-0';
                scanMessage.innerText = data.message;
                resultIcon.innerHTML = '<i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>';
                playSound('error');
            }

            setTimeout(() => {
                resultCard.classList.add('d-none');
            }, 3000);
        }

        function playSound(type) {
             if(type === 'success') {
                 audioSuccess.currentTime = 0;
                 audioSuccess.play().catch(e => console.log('Audio play failed', e));
             } else {
                 audioError.currentTime = 0;
                 audioError.play().catch(e => console.log('Audio play failed', e));
             }
        }
    </script>
    @endif
@endsection