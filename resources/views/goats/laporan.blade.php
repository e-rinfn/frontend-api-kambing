<!-- resources/views/goats/laporan.blade.php -->
@extends('layouts.app')

@section('content')
    <style>
        #filterInput {
            width: 100%;
            max-width: 300px;
        }

        .filter-section {
            margin-bottom: 20px;
        }
    </style>
    <div class="container">
        <h1 class="text-center mb-4">Laporan Kambing</h1>

        <div class="row text-center">
            <!-- Card Display -->
            <div class="col-6 col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Kambing</h5>
                        <p class="card-text">{{ $totalGoats }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Jantan</h5>
                        <p class="card-text">{{ $totalMale }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Betina</h5>
                        <p class="card-text">{{ $totalFemale }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Bobot</h5>
                        <p class="card-text">{{ number_format($averageWeight, 2) }} kg</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Input and Dropdowns -->
        <div class="filter-section">
            <div class="row">
                <!-- Search Input -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="filterInput" class="form-control" placeholder="Cari data kambing...">
                    </div>
                </div>

                <!-- Gender Filter -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <select id="genderFilter" class="form-select">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>

                <!-- Cage Position Filter -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <select id="cagePositionFilter" class="form-select">
                        <option value="">Semua Posisi Kandang</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position }}">{{ $position }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered mt-4" id="goatTable">
                <thead class="table-success">
                    <tr>
                        <th scope="col">No Tag</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jenis Kelamin</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Bobot</th>
                        <th scope="col">Posisi Kandang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goats as $goat)
                        <tr>
                            <td>{{ $goat['noTag'] }}</td>
                            <td>{{ $goat['nama'] }}</td>
                            <td>{{ $goat['kelamin'] }}</td>
                            <td>{{ $goat['jenis'] }}</td>
                            <td>{{ $goat['bobot'] }} kg</td>
                            <td>{{ $goat['posisiKandang'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterInput = document.getElementById('filterInput');
                const genderFilter = document.getElementById('genderFilter');
                const cagePositionFilter = document.getElementById('cagePositionFilter');
                const goatTable = document.getElementById('goatTable');
                const rows = goatTable.querySelectorAll('tbody tr');

                function filterTable() {
                    const searchText = filterInput.value.toLowerCase();
                    const gender = genderFilter.value.toLowerCase();
                    const cagePosition = cagePositionFilter.value.toLowerCase();

                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                        const matchesSearch = rowText.includes(searchText);
                        const matchesGender = !gender || cells[2].textContent.toLowerCase() === gender;
                        const matchesCagePosition = !cagePosition || cells[5].textContent.toLowerCase() ===
                            cagePosition;

                        if (matchesSearch && matchesGender && matchesCagePosition) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                filterInput.addEventListener('keyup', filterTable);
                genderFilter.addEventListener('change', filterTable);
                cagePositionFilter.addEventListener('change', filterTable);
            });
        </script>
    </div>
@endsection
