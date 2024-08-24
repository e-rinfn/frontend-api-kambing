<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goat Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            background-color: #007bff;
            color: white;
            border-radius: 50px;
            padding: 10px 15px;
        }

        .nav-link-scan:hover {
            background-color: #0056b3;
        }

        .scan-icon {
            font-size: 1.5rem;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
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
                    <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Kambing
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i> Data Kambing
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2"></i> Laporan
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                        <i class="bi bi-gear me-2"></i> Pengaturan
                    </a>
                </li>
                <li class="list-group-item mt-3">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-logout w-100">Logout</button>
                    </form>
                </li>
            </ul>

        </div>
    </div>



    <!-- Navbar atas tetap di atas -->
    <nav class="navbar navbar-light bg-light navbar-top">
        <div class="container-fluid">
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                aria-controls="sidebar">
                <i class="bi bi-list"></i> Menu
            </button>
            <a class="navbar-brand" href="#">Goat Management</a>
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
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-gear"></i>
                        <div>Akun</div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>
