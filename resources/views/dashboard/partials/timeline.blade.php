@forelse($recent as $atten)
    <div
        class="timeline-item position-relative pb-4 ps-4 border-start border-2 {{ $loop->last ? 'border-transparent' : 'border-light' }}">
        <div class="timeline-dot position-absolute top-0 start-0 translate-middle rounded-circle 
                {{ $atten->status == 'hadir' ? 'bg-success' : ($atten->status == 'sakit' ? 'bg-warning' : ($atten->status == 'izin' ? 'bg-info' : 'bg-danger')) }}"
            style="width: 12px; height: 12px; border: 2px solid white; box-shadow: 0 0 0 1px #eee;"></div>

        <div class="d-flex justify-content-between mb-1">
            <span
                class="fw-bold text-dark">{{ $atten->status == 'hadir' ? 'Hadir di Sekolah' : 'Tidak Hadir (' . ucfirst($atten->status) . ')' }}</span>
            <span
                class="small text-muted">{{ \Carbon\Carbon::parse($atten->scanned_at)->locale('id')->diffForHumans() }}</span>
        </div>
        <div class="d-flex align-items-center text-muted small mb-1">
            <i class="bi bi-calendar me-2"></i>
            {{ \Carbon\Carbon::parse($atten->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </div>
        <div class="d-flex align-items-center text-muted small">
            <i class="bi bi-clock me-2"></i> Pukul {{ \Carbon\Carbon::parse($atten->scanned_at)->format('H:i') }}
            <span class="mx-2">•</span>
            <span class="badge bg-light text-dark border">{{ ucfirst($atten->type) }}</span>
        </div>
    </div>
@empty
    <div class="text-center py-4 text-muted">
        <small>Belum ada aktivitas terbaru.</small>
    </div>
@endforelse