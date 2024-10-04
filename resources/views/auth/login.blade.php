<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>Login Informan Kambing</title>
    <style>
        /* Background color */
        body {
            background-color: #e8f5e9;
        }

        .login-container {
            min-height: 100vh;
        }

        .card {
            border-radius: 15px;
            padding: 20px;
            background-color: #ffffff;
            border: none;
        }

        .btn-primary {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .form-control {
            border-radius: 10px;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        /* Mobile-first styles */
        @media (max-width: 576px) {
            .card {
                padding: 15px;
            }

            .btn {
                font-size: 14px;
            }

            .form-control {
                font-size: 14px;
            }
        }

        /* Desktop-specific adjustments */
        @media (min-width: 992px) {
            .login-container {
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #4caf50;
            }

            .col-md-4 {
                margin: auto;
            }

            .logo img {
                width: 120px;
            }

            .card {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center login-container">
        <div class="col-md-4 col-sm-10">
            <!-- Logo -->
            <div class="text-center mb-4 logo">
                <img src="https://via.placeholder.com/150" alt="Logo" class="img-fluid">
            </div>
            <div class="card shadow-sm">
                <h4 class="text-center mb-4">Login</h4>

                <form action="{{ url('login') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Masukkan username" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                </form>
            </div>

            <div class="text-center mt-3">
                <a href="{{ url('/register') }}" class="text-success">Belum memiliki akun? Registrasi disini.</a>
            </div>
        </div>
    </div>

    <!-- Modal for Errors -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Login Failed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="errorList">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endif
                    </ul>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            @endif
        });
    </script>
</body>

</html>
