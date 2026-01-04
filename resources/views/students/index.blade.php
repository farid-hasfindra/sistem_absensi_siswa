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
                                    <td class="p-4 fw-bold">{{ $student->name }}</td>
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
                                    <td class="p-4 text-muted">{{ $student->barcode_code }}</td>
                                    <td class="p-4 text-end">
                                        <button class="btn btn-sm btn-light text-primary"
                                            onclick="showBarcode('{{ $student->barcode_code }}', '{{ $student->name }}')">
                                            <i class="bi bi-qr-code"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light text-warning" onclick="editStudent({{ $student }})">
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
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
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
                        <div class="mb-3">
                            <label class="form-label">Kode Barcode</label>
                            <div class="input-group">
                                <input type="text" name="barcode_code" id="barcodeInput"
                                    class="form-control rounded-start-3" required>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="generateRandomCode()">Acak</button>
                            </div>
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
                    <div class="bg-white p-3 rounded border d-inline-block">
                        <!-- We'll use a library or just display the code text for now, but user asked for Barcode app. 
                                     Ideally use a JS library to render barcode. -->
                        <svg id="barcodeDisplay"></svg>
                    </div>
                    <h3 class="fw-mono mt-2" id="barcodeText"></h3>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        function generateRandomCode() {
            document.getElementById('barcodeInput').value = Math.random().toString(36).substr(2, 9).toUpperCase();
        }

        function showBarcode(code, name) {
            document.getElementById('barcodeName').innerText = name;
            document.getElementById('barcodeText').innerText = code;
            JsBarcode("#barcodeDisplay", code, {
                format: "CODE128",
                lineColor: "#000",
                width: 2,
                height: 50,
                displayValue: false
            });
            new bootstrap.Modal(document.getElementById('barcodeModal')).show();
        }

        function editStudent(student) {
            // Simple edit implementation could use another modal or redirect
            // For brevity/modern feel, let's keep it simple or impl strict edit mode later
            alert("Edit feature for " + student.name + " to be implemented via filling a modal.");
        }
    </script>
@endsection