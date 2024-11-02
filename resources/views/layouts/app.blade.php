<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goat Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <style>
        /* Menyembunyikan scrollbar sidebar pada iOS */
        .offcanvas-end {
            -webkit-overflow-scrolling: touch;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .element {
            -webkit-overflow-scrolling: touch;
            overflow-y: scroll;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .element::-webkit-scrollbar {
            display: none;
        }

        /* Navbar atas tetap di atas */
        .navbar-top {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }

        /* Navbar bawah tetap di bawah */
        .navbar-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }


        /* Jarak antara konten dengan navbar atas dan bawah */
        .main-content {
            padding-top: 56px;
            /* Jarak dari navbar atas */
            padding-bottom: 56px;
            /* Jarak dari navbar bawah */
        }

        /* Sidebar styling */
        .offcanvas {
            background-color: #f8f9fa;
            color: #495057;
        }

        .offcanvas-header {
            border-bottom: 1px solid #dee2e6;
        }

        .offcanvas-title {
            font-size: 1.25rem;
        }

        .list-group-item {
            padding: 1rem 1.5rem;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        .btn-logout {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .nav-link {
            text-align: center;
            color: #6c9e4e;
        }

        .nav-link:hover {
            color: #6c757d;
        }

        .nav-link i {
            font-size: 1.5rem;
            display: block;

        }

        .nav-link div {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .nav-link-scan {
            background-color: #6c9e4e;
            color: white;
            border-radius: 50px;
            padding: 10px 15px;
        }

        .nav-link-scan:hover {
            background-color: #567d3d;
            color: white
        }

        .scan-icon {
            font-size: 1.5rem;
        }

        /* CSS untuk styling sidebar */
        .sidebar-logo {
            width: 40px;
            /* Ukuran logo */
            height: auto;
            /* Menjaga aspek rasio */
        }

        .offcanvas {
            background-color: #f8f9fa;
            /* Latar belakang sidebar */
            width: 250px;
            /* Lebar sidebar */
        }

        .list-group-item {
            border: none;
            /* Menghilangkan border dari item */
        }

        .btn-logout {
            background-color: #dc3545;
            /* Warna tombol logout */
            color: white;
            /* Warna teks tombol logout */
        }

        .btn-logout:hover {
            background-color: #c82333;
            /* Warna tombol saat hover */
        }

        /* Responsif */
        @media (max-width: 576px) {
            .offcanvas {
                width: 100%;
                /* Mengatur lebar sidebar penuh pada perangkat mobile */
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('/logo.png') }}" alt="Logo" class="sidebar-logo me-2">
                <h5 class="offcanvas-title" id="sidebarLabel" style="margin-left: 20px">Menu Utama</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ url('listKambing') }}" class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-list me-2"></i> List Kambing
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{ url('goats/create') }}"
                        class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Kambing
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="{{ url('laporan') }}" class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2"></i> Laporan
                    </a>
                </li>
                <li class="list-group-item mt-3">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-logout w-100">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>



    <!-- Navbar with farm-themed colors -->
    <nav class="navbar navbar-light navbar-top" style="background-color: #6c9e4e; color: white;">
        <div class="container-fluid">
            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                aria-controls="sidebar">
                <i class="bi bi-list" style="font-size: 1.5rem; color: white;"></i>
                <!-- Hamburger icon with larger size and white color -->
            </button>
            <a class="navbar-brand text-white" href="#" style="font-weight: bold;">
                Goat Management
            </a>
        </div>
    </nav>


    <!-- Konten utama -->
    <div class="container main-content">
        @yield('content')
    </div>

    <!-- Navigasi bawah -->
    <nav class="navbar navbar-bottom navbar-light bg-light">
        <div class="container">
            <ul class="nav justify-content-around w-100 mb-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('goats') }}">
                        <i class="bi bi-house-door"></i>
                        <div>Home</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-scan" href="{{ url('scan') }}">
                        <i class="bi bi-qr-code-scan scan-icon"></i>
                        <div>Scan</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('profiles') }}">
                        <i class="bi bi-person-gear"></i>
                        <div>Profile</div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        // Service worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }

        // Logout confirmation modal
        document.querySelector('.btn-logout').addEventListener('click', function(event) {
            event.preventDefault();
            var myModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            myModal.show();
        });
    </script>
</body>

</html>
