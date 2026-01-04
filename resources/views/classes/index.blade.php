@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Manajemen Kelas</h2>
                <p class="text-muted">Kelola Kelas dan Wali Kelas</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kelas Baru
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-soft">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0 rounded-top-start">Nama Kelas</th>
                                <th class="p-4 border-0">Tingkat</th>
                                <th class="p-4 border-0">Wali Kelas</th>
                                <th class="p-4 border-0 rounded-top-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $class)
                                <tr>
                                    <td class="p-4 fw-bold">{{ $class->name }}</td>
                                    <td class="p-4">{{ $class->level ?? '-' }}</td>
                                    <td class="p-4">
                                        @if($class->teacher)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                    {{ substr($class->teacher->name, 0, 1) }}
                                                </div>
                                                <div>{{ $class->teacher->name }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted italic">Belum ada Wali Kelas</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-end">
                                        <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light text-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: X IPA 1"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tingkat</label>
                                <select name="level" class="form-select rounded-3">
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select name="teacher_id" class="form-select rounded-3">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->nip }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Buat Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection