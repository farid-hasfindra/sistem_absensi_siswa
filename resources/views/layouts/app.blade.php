<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Absensi Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        .sidebar {
            width: 260px;
            background: #ffffff;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            border-right: 1px solid #eef2f5;
            overflow-y: auto;
            max-height: 100vh;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }

        /* Logo Area */
        .sidebar-logo {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            color: var(--primary-color);
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

        .user-card {
            background: #F8FAFC;
            border-radius: 12px;
            padding: 15px;
            margin: 0 15px 20px 15px;
            border: 1px solid #eef2f5;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <a href="{{ route('dashboard') }}"
            class="sidebar-logo text-decoration-none d-flex flex-column align-items-center text-center pb-2">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm"
                style="width: 80px; height: 80px;">
                <img src="{{ asset('logo_sekolah_baru.jpg') }}" alt="Logo Sekolah" class="img-fluid rounded-circle"
                    style="max-height: 70px;">
            </div>
            <div>
                <div class="fw-bold fs-6 text-dark text-uppercase" style="line-height: 1.2;">SMP NEGERI 1</div>
                <div class="small fw-bold text-primary text-uppercase"
                    style="font-size: 0.65rem; letter-spacing: 0.5px;">Pangkalan Koto Baru</div>
            </div>
        </a>

        <div class="user-card d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                style="width: 35px; height: 35px;">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div style="overflow: hidden;">
                <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                    {{ Auth::user()->name ?? 'Tamu' }}
                </div>
                <div class="text-muted" style="font-size: 0.75rem;">
                    @if(Auth::user()->role == 'admin') Administrator
                    @elseif(Auth::user()->role == 'guru') Wali Kelas
                    @elseif(Auth::user()->role == 'guru_mapel') Guru Mapel
                    @elseif(Auth::user()->role == 'siswa') Siswa
                    @else Wali Murid @endif
                </div>
            </div>
        </div>

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill me-2"></i>
                    Dashboard
                </a>
            </li>

            @if(Auth::user()->role === 'admin')
                <li class="nav-item mt-3">
                    <div class="text-uppercase text-muted ps-3 mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        Administrasi</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('classes.index') }}"
                        class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                        <i class="bi bi-building-fill me-2"></i>
                        Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('subjects.index') }}"
                        class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                        <i class="bi bi-book-half me-2"></i>
                        Mata Pelajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('schedules.index') }}"
                        class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-range-fill me-2"></i>
                        Jadwal Pelajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teachers.index') }}"
                        class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-video3 me-2"></i>
                        Daftar Guru
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <div class="text-uppercase text-muted ps-3 mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                        Akademik</div>
                </li>
            @endif
            @if(Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('students.index') }}"
                        class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Data Siswa
                    </a>
                </li>
            @endif

            @if(Auth::user()->role === 'guru' || Auth::user()->role === 'guru_mapel')
                <li class="nav-item">
                    <a href="{{ route('attendance.index') }}"
                        class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan"></i>
                        Scan Absensi
                    </a>
                </li>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'guru', 'guru_mapel', 'wali_murid']))
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        @if(Auth::user()->role == 'wali_murid')
                            Riwayat Absensi
                        @else
                            Laporan
                        @endif
                    </a>
                </li>
            @endif
        </ul>
        <hr>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 border-0 bg-transparent text-danger"
                onclick="localStorage.removeItem('sidebarScrollPos')">
                <i class="bi bi-box-arrow-right"></i>
                Keluar
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content fade-in-up">
        @yield('content')
    </div>

    @if(session('success'))
        <script>         Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        </script>
    @endif

    @if(session('error'))
        <script>         Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", });
        </script>
    @endif

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prevent auto-scroll on refresh
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            window.scrollTo(0, 0);

            const sidebar = document.querySelector('.sidebar');
            const key = 'sidebarScrollPos';

            // Restore scroll position
            const savedPos = localStorage.getItem(key);
            if (savedPos) {
                sidebar.scrollTop = savedPos;
            }

            // Save scroll position
            sidebar.addEventListener('scroll', function () {
                localStorage.setItem(key, sidebar.scrollTop);
            });
        });
    </script>
</body>

</html>