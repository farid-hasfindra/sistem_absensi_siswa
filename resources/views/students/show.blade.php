@extends('layouts.app')

@section('content')
    <div class="container-fluid fade-in-up">
        <!-- Breadcrumb & Back Button -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('students.index') }}" class="btn btn-light rounded-circle p-2 me-3 shadow-sm text-secondary">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Detail Siswa</h2>
                <p class="text-muted mb-0">Informasi lengkap data siswa</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Profile Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-4 position-relative d-inline-block">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}"
                                    class="rounded-circle img-thumbnail shadow-sm object-fit-cover"
                                    style="width: 150px; height: 150px;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                    style="width: 150px; height: 150px;">
                                    <i class="bi bi-person fs-1 text-secondary"></i>
                                </div>
                            @endif
                            <span
                                class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 border border-3 border-white shadow-sm"
                                title="Siswa Aktif">
                                <i class="bi bi-check-lg small"></i>
                            </span>
                        </div>

                        <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
                        <p class="text-muted mb-3">{{ $student->nis }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                {{ $student->schoolClass->name ?? 'Tanpa Kelas' }}
                            </span>
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                {{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Details & QR -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-bold px-4" id="pills-info-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-info" type="button">Data Diri</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-bold px-4" id="pills-qr-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-qr" type="button">Barcode / QR</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <!-- Info Tab -->
                            <div class="tab-pane fade show active" id="pills-info" role="tabpanel">
                                <h5 class="fw-bold mb-4 text-primary">Informasi Akademik & Pribadi</h5>
                                <div class="row g-4">
                                    <div class="col-sm-6">
                                        <label class="small text-muted text-uppercase fw-bold mb-1">Nama Wali Murid</label>
                                        <p class="fw-semibold fs-5 mb-0">{{ $student->parent->name ?? '-' }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="small text-muted text-uppercase fw-bold mb-1">Nomor Telepon
                                            Wali</label>
                                        <p class="fw-semibold fs-5 mb-0">{{ $student->parent->phone ?? '-' }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="small text-muted text-uppercase fw-bold mb-1">Tanggal Lahir</label>
                                        <p class="fw-semibold fs-5 mb-0">
                                            {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d F Y') : '-' }}
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="small text-muted text-uppercase fw-bold mb-1">Alamat</label>
                                        <p class="fw-semibold fs-5 mb-0 text-break">{{ $student->parent->address ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                                    <!-- ID Card Container -->
                                    <div class="id-card-wrapper d-inline-block position-relative shadow-lg rounded-4 overflow-hidden mb-4" id="printableArea" style="width: 320px; background: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                        <!-- Modern Header with curve -->
                                        <div class="card-header-bg p-4 text-white text-center position-relative" style="background: linear-gradient(135deg, #0056b3 0%, #004494 100%); padding-bottom: 2rem !important; -webkit-print-color-adjust: exact;">
                                            <div class="d-flex align-items-center justify-content-center flex-column position-relative z-1">
                                                <div class="bg-white p-2 rounded-circle shadow-sm mb-2 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                                    <img src="{{ asset('logo_sekolah.jpg') }}" class="img-fluid" style="max-height: 50px;">
                                                </div>
                                                <div class="fw-bold" style="font-size: 14px; letter-spacing: 0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.2); line-height: 1.2;">
                                                    SMP NEGERI 1 <br> PANGKALAN KOTO BARU
                                                </div>
                                            </div>
                                            <!-- Decorative curve -->
                                            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 20px; background: #fff; border-top-left-radius: 50% 100%; border-top-right-radius: 50% 100%;"></div>
                                        </div>
                                        
                                        <!-- Body -->
                                        <div class="card-body bg-white px-4 pb-4 pt-1 position-relative text-center">
                                            
                                            <h3 class="fw-bolder text-dark mb-1" style="color: #003366 !important;">{{ $student->name }}</h3>
                                            <div class="text-muted small fw-bold text-uppercase letter-spacing-2 mb-3">Siswa / Student</div>

                                            <div class="qr-wrapper p-3 border rounded-4 d-inline-block bg-white shadow-sm mb-3 position-relative">
                                                <!-- Corner accents -->
                                                <div style="position: absolute; top: 10px; left: 10px; width: 15px; height: 15px; border-top: 3px solid #0056b3; border-left: 3px solid #0056b3; border-top-left-radius: 4px;"></div>
                                                <div style="position: absolute; top: 10px; right: 10px; width: 15px; height: 15px; border-top: 3px solid #0056b3; border-right: 3px solid #0056b3; border-top-right-radius: 4px;"></div>
                                                <div style="position: absolute; bottom: 10px; left: 10px; width: 15px; height: 15px; border-bottom: 3px solid #0056b3; border-left: 3px solid #0056b3; border-bottom-left-radius: 4px;"></div>
                                                <div style="position: absolute; bottom: 10px; right: 10px; width: 15px; height: 15px; border-bottom: 3px solid #0056b3; border-right: 3px solid #0056b3; border-bottom-right-radius: 4px;"></div>
                                                
                                                <div id="barcodeDisplay" class="m-1"></div>
                                            </div>

                                            <div class="fw-bold fs-5 text-secondary inter-font" style="letter-spacing: 2px; color: #555;">{{ $student->nis }}</div>
                                        </div>
                                        
                                        <!-- Footer decoration -->
                                        <div class="card-footer-line" style="height: 6px; background: linear-gradient(90deg, #0056b3 0%, #00aaff 100%);"></div>
                                    </div>

                                    <div class="d-flex justify-content-center gap-3 d-print-none">
                                        <button class="btn btn-primary rounded-pill px-4" onclick="downloadCard()">
                                            <i class="bi bi-download me-2"></i> Download Kartu
                                        </button>
                                        <button class="btn btn-outline-dark rounded-pill px-4" onclick="printCard()">
                                            <i class="bi bi-printer me-2"></i> Cetak Kartu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Init QR
            const container = document.getElementById('barcodeDisplay');
            new QRCode(container, {
                text: "{{ $student->barcode_code }}",
                width: 160, // Enlarge QR
                height: 160,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        function downloadCard() {
            const cardElement = document.getElementById('printableArea');
            
            html2canvas(cardElement, {
                scale: 3, 
                useCORS: true, 
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Kartu_Siswa_{{ str_replace(" ", "_", $student->name) }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }

        function printCard() {
            const container = document.getElementById('printableArea');
            const printWindow = window.open('', '', 'height=800,width=800');
            
            printWindow.document.write('<html><head><title>Cetak Kartu Siswa</title>');
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('<style>');
            printWindow.document.write('@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");');
            printWindow.document.write('@media print { body { -webkit-print-color-adjust: exact; } }');
            printWindow.document.write('body { display: flex; align-items: center; justify-content: center; height: 100vh; background: #fff; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(container.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.focus();
                printWindow.print();
            }, 1000);
        }
    </script>
@endsection