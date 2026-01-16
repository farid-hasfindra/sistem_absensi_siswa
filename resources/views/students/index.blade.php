@extends('layouts.app')

@section('content')
    <div class="container-fluid fade-in-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Data Siswa</h2>
                <p class="text-muted">Kelola data siswa dan barcode</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-lg"></i> Tambah Siswa Baru
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0 rounded-top-start">NIS</th>
                                <th class="p-4 border-0">Nama</th>
                                <th class="p-4 border-0">Kelas</th>
                                <th class="p-4 border-0">Wali Murid</th>
                                <th class="p-4 border-0">Kode Barcode</th>
                                <th class="p-4 border-0 rounded-top-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td class="p-4">{{ $student->nis }}</td>
                                    <td class="p-4 fw-bold">
                                        <div class="d-flex align-items-center">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}"
                                                    class="rounded-circle me-2 object-fit-cover shadow-sm" width="40" height="40">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 border"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                            @endif
                                            <a href="{{ route('students.show', $student) }}"
                                                class="text-decoration-none text-dark hover-primary display-name">
                                                {{ $student->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @if($student->schoolClass)
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">{{ $student->schoolClass->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        {{ $student->parent->name ?? '-' }}
                                    </td>
                                    <td class="p-4">
                                        <!-- Clickable Barcode Container -->
                                        <div class="barcode-container position-relative d-inline-block" style="cursor: pointer;"
                                            data-code="{{ $student->barcode_code }}" data-name="{{ $student->name }}"
                                            data-nis="{{ $student->nis }}" onclick="openCardModal(this)">

                                            <div class="barcode-list" data-value="{{ $student->barcode_code }}"></div>

                                            <!-- Hover Overlay hint -->
                                            <div
                                                class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-overlay transition-opacity rounded">
                                                <i class="bi bi-arrows-fullscreen text-white fs-4"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-end">
                                        <!-- Zoom Button (Triggers Modal) -->
                                        <button class="btn btn-sm btn-light text-primary"
                                            data-code="{{ $student->barcode_code }}" data-name="{{ $student->name }}"
                                            data-nis="{{ $student->nis }}" onclick="openCardModal(this)">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-light text-warning"
                                            onclick="editStudent(@json($student))">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Tidak ada data siswa. Klik "Tambah Siswa Baru" untuk memulai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <label class="form-label d-block fw-bold small text-secondary">Foto Siswa</label>
                            <div class="image-upload-wrapper d-inline-block position-relative">
                                <label for="photoUpload" class="cursor-pointer">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border border-2 border-dashed shadow-sm hover-bg-light transition"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-camera fs-3 text-muted" id="photoPreviewIcon"></i>
                                        <img id="photoPreview" src="#"
                                            class="rounded-circle w-100 h-100 object-fit-cover d-none">
                                    </div>
                                </label>
                                <input type="file" name="photo" id="photoUpload" class="d-none" accept="image/*"
                                    onchange="previewImage(this)">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select name="class_id" class="form-select rounded-3">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Murid</label>
                            <select name="parent_id" class="form-select rounded-3">
                                <option value="">-- Pilih Wali Murid --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i> Barcode akan digenerate otomatis oleh sistem.
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Student Card Modal -->
    <div class="modal fade" id="barcodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 bg-transparent">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button"
                        class="btn-close position-absolute top-0 end-0 m-3 z-3 bg-white p-2 rounded-circle shadow-sm"
                        data-bs-dismiss="modal"></button>

                    <!-- ID Card Container -->
                    <div class="id-card-wrapper d-inline-block position-relative shadow-lg rounded-4 overflow-hidden zoom-in-animation"
                        id="printableArea"
                        style="width: 320px; background: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <!-- Modern Header with curve -->
                        <div class="card-header-bg p-4 text-white text-center position-relative"
                            style="background: linear-gradient(135deg, #0056b3 0%, #004494 100%); padding-bottom: 2rem !important; -webkit-print-color-adjust: exact;">
                            <div class="d-flex align-items-center justify-content-center flex-column position-relative z-1">
                                <div class="bg-white p-2 rounded-circle shadow-sm mb-2 d-flex align-items-center justify-content-center"
                                    style="width: 65px; height: 65px;">
                                    <img src="{{ asset('logo_sekolah.jpg') }}" class="img-fluid" style="max-height: 50px;">
                                </div>
                                <div class="fw-bold"
                                    style="font-size: 14px; letter-spacing: 0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.2); line-height: 1.2;">
                                    SMP NEGERI 1 <br> PANGKALAN KOTO BARU
                                </div>
                            </div>
                            <!-- Decorative curve -->
                            <div
                                style="position: absolute; bottom: 0; left: 0; width: 100%; height: 20px; background: #fff; border-top-left-radius: 50% 100%; border-top-right-radius: 50% 100%;">
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="card-body bg-white px-4 pb-4 pt-1 position-relative text-center">

                            <h3 class="fw-bolder text-dark mb-1" style="color: #003366 !important;" id="modalStudentName">
                                Nama Siswa</h3>
                            <div class="text-muted small fw-bold text-uppercase letter-spacing-2 mb-3">Siswa / Student</div>

                            <div
                                class="qr-wrapper p-3 border rounded-4 d-inline-block bg-white shadow-sm mb-3 position-relative">
                                <!-- Corner accents -->
                                <div
                                    style="position: absolute; top: 10px; left: 10px; width: 15px; height: 15px; border-top: 3px solid #0056b3; border-left: 3px solid #0056b3; border-top-left-radius: 4px;">
                                </div>
                                <div
                                    style="position: absolute; top: 10px; right: 10px; width: 15px; height: 15px; border-top: 3px solid #0056b3; border-right: 3px solid #0056b3; border-top-right-radius: 4px;">
                                </div>
                                <div
                                    style="position: absolute; bottom: 10px; left: 10px; width: 15px; height: 15px; border-bottom: 3px solid #0056b3; border-left: 3px solid #0056b3; border-bottom-left-radius: 4px;">
                                </div>
                                <div
                                    style="position: absolute; bottom: 10px; right: 10px; width: 15px; height: 15px; border-bottom: 3px solid #0056b3; border-right: 3px solid #0056b3; border-bottom-right-radius: 4px;">
                                </div>

                                <div id="modalBarcodeDisplay" class="m-1"></div>
                            </div>

                            <div class="fw-bold fs-5 text-secondary inter-font" style="letter-spacing: 2px; color: #555;"
                                id="modalStudentNIS">12345</div>
                        </div>

                        <!-- Footer decoration -->
                        <div class="card-footer-line"
                            style="height: 6px; background: linear-gradient(90deg, #0056b3 0%, #00aaff 100%);"></div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center gap-2">
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm fade-in-up delay-100"
                            onclick="downloadCard()">
                            <i class="bi bi-download me-2"></i> Download
                        </button>
                        <button class="btn btn-light rounded-pill px-4 shadow-sm fade-in-up delay-200"
                            onclick="printCard()">
                            <i class="bi bi-printer me-2"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        .hover-overlay:hover {
            opacity: 1 !important;
        }

        .transition-opacity {
            transition: opacity 0.2s;
        }

        .zoom-in-animation {
            animation: zoomIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .delay-100 {
            animation-delay: 0.1s;
            animation-fill-mode: backwards;
        }

        .delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: backwards;
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize QRs in table
            document.querySelectorAll('.barcode-list').forEach(function (el) {
                new QRCode(el, {
                    text: el.dataset.value,
                    width: 50,
                    height: 50,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            });
        });

        function openCardModal(element) {
            let code = element.getAttribute('data-code');
            let name = element.getAttribute('data-name');
            let nis = element.getAttribute('data-nis');

            // Populate Modal
            document.getElementById('modalStudentName').innerText = name;
            document.getElementById('modalStudentNIS').innerText = nis;

            // Clear and Generate QR
            const container = document.getElementById('modalBarcodeDisplay');
            container.innerHTML = '';

            new QRCode(container, {
                text: code,
                width: 160,
                height: 160,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            new bootstrap.Modal(document.getElementById('barcodeModal')).show();
        }

        function downloadCard() {
            const cardElement = document.getElementById('printableArea');
            const name = document.getElementById('modalStudentName').innerText;

            html2canvas(cardElement, {
                scale: 3,
                useCORS: true,
                backgroundColor: null,
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Kartu_Siswa_' + name.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.png';
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

        function editStudent(student) {
            alert("Fitur Edit untuk " + student.name + " akan segera hadir.");
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('photoPreview').src = e.target.result;
                    document.getElementById('photoPreview').classList.remove('d-none');
                    document.getElementById('photoPreviewIcon').classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection