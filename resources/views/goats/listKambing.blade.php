@extends('layouts.app')

@section('content')
    <style>
        /* In your CSS file */
        .compact-button {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .search-bar-container {
            background-color: white;
            /* White background */
            padding: 10px;
            /* Padding for the search bar */
            border-radius: 5px;
            /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Box shadow for depth */
            margin-bottom: 10px;
            /* Space below the search bar */
        }

        .input-group {
            width: 100%;
            /* Full width */
        }

        .input-group .form-control {
            padding-left: 40px;
            /* Space for the icon */
        }

        .input-group .input-group-text {
            background-color: transparent;
            /* Transparent background */
            border: none;
            /* No border for the icon */
            padding: 0;
            /* Remove padding */
            margin-right: 0;
            /* Remove right margin */
            display: flex;
            /* Center the icon vertically */
            align-items: center;
            /* Align icon vertically */
        }

        .input-group .bi {
            font-size: 1.2rem;
            /* Adjust icon size */
        }
    </style>

    <div class="container mt-4">
        <div class="card p-3 mb-4">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-success text-center fw-bold">List Kambing</h2>
                        <p class="text-muted">Kelola data kambing dengan mudah dan efisien.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Input Field Container -->
        <div class="sticky-top search-bar-container" style="z-index: 1030; top: 60px;"> <!-- Sticky container -->
            <div class="input-group">
                <span class="input-group-text" id="search-addon">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="goatSearchInput" class="form-control" placeholder="Cari kambing..."
                    aria-label="Cari data kambing" onkeyup="filterGoats()">
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row" id="goatCardContainer">
            @foreach ($goats as $goat)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 goat-card">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title goat-name text-center">{{ $goat['nama'] ?? 'Unknown Name' }}</h5>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="card-text mb-1"><strong>No Tag:</strong> <span
                                            class="goat-tag">{{ $goat['noTag'] ?? 'No Tag Available' }}</span></p>
                                    <p class="card-text mb-1"><strong>Bobot:</strong> {{ $goat['bobot'] ?? 'N/A' }} KG</p>
                                    <p class="card-text mb-1"><strong>Kelamin:</strong> {{ $goat['kelamin'] ?? 'N/A' }}</p>
                                    <p class="card-text mb-1"><strong>Status:</strong> {{ $goat['status'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <img src="https://via.placeholder.com/100" alt="Sample Goat Image" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex p-2">
                            <a href="{{ url('goats/' . $goat['id']) }}" class="btn btn-info btn-sm flex-fill me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ url('goats/' . $goat['id'] . '/edit') }}"
                                class="btn btn-warning btn-sm flex-fill me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ url('goats/' . $goat['id'] . '/cares') }}"
                                class="btn btn-secondary btn-sm flex-fill me-1">
                                <i class="bi bi-heart"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm flex-fill me-1" data-bs-toggle="modal"
                                data-bs-target="#deleteModal-{{ $goat['id'] }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteModal-{{ $goat['id'] }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel-{{ $goat['id'] }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered"> <!-- Modal dialog centered -->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $goat['id'] }}">Konfirmasi
                                            Penghapusan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data kambing ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ url('goats/' . $goat['id']) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>

    <!-- JavaScript for filtering -->
    <script>
        function filterGoats() {
            const searchInput = document.getElementById('goatSearchInput').value.toLowerCase();
            const goatCards = document.getElementsByClassName('goat-card');

            Array.from(goatCards).forEach(function(card) {
                const name = card.querySelector('.goat-name').innerText.toLowerCase();
                const tag = card.querySelector('.goat-tag').innerText.toLowerCase();

                if (name.includes(searchInput) || tag.includes(searchInput)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
@endsection
