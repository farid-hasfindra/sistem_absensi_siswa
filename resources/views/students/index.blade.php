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
                                            <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle me-2 object-fit-cover shadow-sm" width="40" height="40">
                                            @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 border" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                            @endif
                                            <a href="{{ route('students.show', $student) }}" class="text-decoration-none text-dark hover-primary display-name">
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
                                        <!-- Clickable Container -->
                                        <div class="barcode-container position-relative d-inline-block" 
                                             style="cursor: pointer;"
                                             data-code="{{ $student->barcode_code }}" 
                                             data-name="{{ $student->name }}"
                                             onclick="openBarcodeModal(this)">
                                            <div class="barcode-list" data-value="{{ $student->barcode_code }}"></div>
                                            <!-- Hover Overlay hint -->
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-overlay transition-opacity rounded">
                                                <i class="bi bi-arrows-fullscreen text-white"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-end">
                                        <button class="btn btn-sm btn-light text-primary"
                                            onclick="showBarcode('{{ $student->barcode_code }}', '{{ $student->name }}')">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light text-warning" onclick="editStudent(@json($student))">
                                            <i class="bi bi-pencil"></i>
                                        </button>
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

    <!-- Add Modal -->
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
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border border-2 border-dashed shadow-sm hover-bg-light transition" style="width: 100px; height: 100px;">
                                        <i class="bi bi-camera fs-3 text-muted" id="photoPreviewIcon"></i>
                                        <img id="photoPreview" src="#" class="rounded-circle w-100 h-100 object-fit-cover d-none">
                                    </div>
                                </label>
                                <input type="file" name="photo" id="photoUpload" class="d-none" accept="image/*" onchange="previewImage(this)">
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
                        <!-- Barcode Auto Generated -->
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

    <!-- Barcode Modal -->
    <div class="modal fade" id="barcodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body text-center p-4">
                    <h5 class="fw-bold mb-1" id="barcodeName"></h5>
                    <p class="text-muted small mb-3">Scan kode ini</p>
                    <div class="bg-white p-3 rounded border d-inline-block shadow-sm" id="printableArea">
                        <div id="barcodeDisplay" class="d-flex justify-content-center"></div>
                    </div>
                    <div class="mt-4 d-grid gap-2">
                        <button class="btn btn-primary" onclick="downloadBarcode()">
                            <i class="bi bi-download me-2"></i> Download
                        </button>
                        <button class="btn btn-outline-secondary" onclick="printBarcode()">
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
    <style>
        .hover-overlay:hover {
            opacity: 1 !important;
        }
        .transition-opacity {
            transition: opacity 0.2s;
        }
    </style>
    <script>
        // Init barcodes in table
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize QRs
            document.querySelectorAll('.barcode-list').forEach(function(el) {
                new QRCode(el, {
                    text: el.dataset.value,
                    width: 50,
                    height: 50,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            });
        });

        function openBarcodeModal(element) {
            let code = element.getAttribute('data-code');
            let name = element.getAttribute('data-name');
            showBarcode(code, name); 
        }

        function showBarcode(code, name) {
            document.getElementById('barcodeName').innerText = name;
            
            // Clear previous
            const container = document.getElementById('barcodeDisplay');
            container.innerHTML = '';
            
            // Generate New QR
            new QRCode(container, {
                text: code,
                width: 200,
                height: 200,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            
            new bootstrap.Modal(document.getElementById('barcodeModal')).show();
        }

        function downloadBarcode() {
            const container = document.getElementById('barcodeDisplay');
            let dataUrl = '';
            const img = container.querySelector('img');
            const canvas = container.querySelector('canvas');
            
            if (img && img.src) {
                dataUrl = img.src;
            } else if (canvas) {
                dataUrl = canvas.toDataURL("image/png");
            }
            
            if(dataUrl) {
                const name = document.getElementById('barcodeName').innerText;
                const lnk = document.createElement('a');
                lnk.download = 'QR-' + name.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.png';
                lnk.href = dataUrl;
                document.body.appendChild(lnk);
                lnk.click();
                document.body.removeChild(lnk);
            } else {
                alert('Gambar sedang diproses, silakan coba sesaat lagi.');
            }
        }

        function printBarcode() {
            const name = document.getElementById('barcodeName').innerText;
            const container = document.getElementById('barcodeDisplay');
            
            let dataUrl = '';
            const img = container.querySelector('img');
            const canvas = container.querySelector('canvas');
            
            if (img && img.src) {
                dataUrl = img.src;
            } else if (canvas) {
                dataUrl = canvas.toDataURL("image/png");
            }
            
            if(!dataUrl) {
                alert('Gambar sedang diproses...');
                return;
            }

            var printWindow = window.open('', '', 'height=600,width=600');
            printWindow.document.write('<html><head><title>Print QR Code - ' + name + '</title>');
            printWindow.document.write('</head><body style="text-align:center; padding-top: 50px; font-family: sans-serif;">');
            printWindow.document.write('<h2>' + name + '</h2>');
            printWindow.document.write('<img src="' + dataUrl + '" style="border: 1px solid #ddd; padding: 20px; width: 300px;">');
            printWindow.document.write('<br><br><div style="font-size: 14px; font-weight: bold;">Sistem Absensi Siswa</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            setTimeout(function() {
                printWindow.focus();
                printWindow.print();
            }, 500);
        }

        function editStudent(student) {
             // Simple edit implementation 
            alert("Fitur Edit untuk " + student.name + " akan segera hadir.");
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                    document.getElementById('photoPreview').classList.remove('d-none');
                    document.getElementById('photoPreviewIcon').classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection