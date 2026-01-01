@extends('layouts.app')

@section('content')
<div class="row justify-content-center fade-in-up">
    <div class="col-md-8 text-center">
        <h2 class="fw-bold mb-4">Scan Absensi Barcode</h2>
        
        <div class="card glass-card border-0 p-5 mb-4">
            <div class="mb-4">
                <i class="bi bi-upc-scan" style="font-size: 5rem; color: #fff;"></i>
            </div>
            <h4 class="text-white mb-4">Silahkan Scan Barcode Kartu Siswa</h4>
            
            <div class="input-group mb-3 justify-content-center">
                <input type="text" id="barcode" class="form-control form-control-lg text-center fw-bold rounded-pill" style="max-width: 400px;" placeholder="Scanning..." autofocus autocomplete="off">
            </div>
            <p class="text-white-50 small">Cursor will automatically focus on input</p>
        </div>

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
<script>
    const barcodeInput = document.getElementById('barcode');
    
    // Auto focus
    barcodeInput.focus();
    document.addEventListener('click', () => barcodeInput.focus());

    barcodeInput.addEventListener('change', function() {
        if(this.value.length > 3) {
            processScan(this.value);
            this.value = '';
        }
    });

    // Handle Enter key manually if scanner doesn't trigger change
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            if(this.value.length > 3) {
                processScan(this.value);
                this.value = '';
            }
        }
    });

    function processScan(code) {
        // Show loading or visual feedback
        
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
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
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
            playSound('warning');
        } else {
             // Error
            studentName.innerText = 'Unknown';
            scanTime.innerText = '-';
            scanType.innerText = 'ERROR';
            scanMessage.className = 'alert alert-danger mt-3 mb-0';
            scanMessage.innerText = data.message;
            resultIcon.innerHTML = '<i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>';
            playSound('error');
        }

        // Hide after 3 seconds
        setTimeout(() => {
            resultCard.classList.add('d-none');
        }, 4000);
    }

    function playSound(type) {
        // Optional: Implement simple beep
        // let audio = new Audio('/sounds/' + type + '.mp3');
        // audio.play().catch(e => console.log(e));
    }
</script>
@endsection