<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Absensi Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
        }

        .nav-link {
            color: #666;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .user-profile {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none p-3">
            <span class="fs-4 fw-bold text-primary">Absensi<span class="text-dark">Siswa</span></span>
        </a>
        <hr>

        <div class="user-profile mb-4 fade-in-up">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                    style="width: 40px; height: 40px;">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <div class="fw-bold">{{ Auth::user()->name ?? 'Guest' }}</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">{{ ucfirst(Auth::user()->role ?? '') }}</div>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills flex-column mb-auto fade-in-up" style="animation-delay: 0.1s;">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('students.index') }}"
                    class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Data Siswa
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('attendance.index') }}"
                    class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i>
                    Scan Absensi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index') }}"
                    class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    Laporan
                </a>
            </li>
        </ul>
        <hr>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 border-0 bg-transparent text-danger">
                <i class="bi bi-box-arrow-right"></i>
                Sign out
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
            });
        </script>
    @endif

    @yield('scripts')
</body>

</html>