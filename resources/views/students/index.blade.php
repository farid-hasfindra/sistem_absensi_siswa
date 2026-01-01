@extends('layouts.app')

@section('content')
    <div class="container-fluid fade-in-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Data Siswa</h2>
                <p class="text-muted">Manage all student data and barcodes</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-plus-lg"></i> Add New Student
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0 rounded-top-start">NIS</th>
                                <th class="p-4 border-0">Name</th>
                                <th class="p-4 border-0">Class</th>
                                <th class="p-4 border-0">Barcode Code</th>
                                <th class="p-4 border-0 rounded-top-end text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td class="p-4">{{ $student->nis }}</td>
                                    <td class="p-4 fw-bold">{{ $student->name }}</td>
                                    <td class="p-4">
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">{{ $student->class }}</span>
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
                                            onsubmit="return confirm('Delete this student?')">
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
                                    <td colspan="5" class="p-5 text-center text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        No students found. Click "Add New Student" to start.
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
                    <h5 class="modal-title fw-bold">Add New Student</h5>
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
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Class</label>
                            <input type="text" name="class" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Barcode Code</label>
                            <div class="input-group">
                                <input type="text" name="barcode_code" id="barcodeInput"
                                    class="form-control rounded-start-3" required>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="generateRandomCode()">Generate</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Student</button>
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
                    <p class="text-muted small mb-3">Scan this code</p>
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