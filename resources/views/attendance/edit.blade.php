@extends('layouts.app')

@section('content')
    <div class="fade-in-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Edit Absensi</h2>
                <p class="text-muted">Perbarui data absensi siswa</p>
            </div>
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            @if($attendance->student->photo)
                                <img src="{{ asset('storage/' . $attendance->student->photo) }}" class="rounded-circle me-3"
                                    width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    {{ substr($attendance->student->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="fw-bold mb-1">{{ $attendance->student->name }}</h5>
                                <p class="text-muted mb-0 small">{{ $attendance->student->nis }} •
                                    {{ $attendance->student->schoolClass->name }}</p>
                            </div>
                        </div>

                        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold">Sesi Absensi</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="pagi" {{ $attendance->type == 'pagi' ? 'selected' : '' }}>Absen Pagi
                                    </option>
                                    <option value="solat" {{ $attendance->type == 'solat' ? 'selected' : '' }}>Absen Sholat
                                    </option>
                                    <option value="pulang" {{ $attendance->type == 'pulang' ? 'selected' : '' }}>Absen Pulang
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Status Kehadiran</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="hadir" {{ $attendance->status == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="telat" {{ $attendance->status == 'telat' ? 'selected' : '' }}>Telat
                                    </option>
                                    <option value="izin" {{ $attendance->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ $attendance->status == 'sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="alpha" {{ $attendance->status == 'alpha' ? 'selected' : '' }}>Alpha
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection