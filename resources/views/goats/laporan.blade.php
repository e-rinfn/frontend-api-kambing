@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Laporan Data Kambing</h1>

        <!-- Filter Input and Dropdowns -->
        <div class="filter-section mb-3">
            <div class="row gy-3 gx-2 align-items-center">
                <!-- Search Input -->
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="filterInput" class="form-control" placeholder="Cari data kambing...">
                    </div>
                </div>

                <!-- Gender Filter -->
                <div class="col-12 col-md-4">
                    <select id="genderFilter" class="form-select">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>

                <!-- Cage Position Filter -->
                <div class="col-12 col-md-4">
                    <select id="cagePositionFilter" class="form-select">
                        <option value="">Semua Posisi Kandang</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position }}">{{ $position }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" id="printButton">
                <i class="bi bi-printer"></i> Cetak Laporan
            </button>
        </div>


        <!-- Data Table -->
        <div class="table-responsive"> <!-- Tabel dibungkus dengan div table-responsive -->
            <table class="table table-hover table-bordered mt-4" id="goatTable">
                <thead class="table-success">
                    <tr>
                        <th scope="col">No Tag</th>
                        <th scope="col">Nama</th>
                        <th scope="col" class="d-none d-md-table-cell">Jenis Kelamin</th>
                        <!-- Kolom ini disembunyikan di mobile -->
                        <th scope="col">Jenis</th>
                        <th scope="col">Bobot</th>
                        <th scope="col" class="d-none d-md-table-cell">Posisi Kandang</th>
                        <!-- Kolom ini disembunyikan di mobile -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filteredGoats as $goat)
                        <tr>
                            <td>{{ $goat['noTag'] }}</td>
                            <td>{{ $goat['nama'] }}</td>
                            <td class="d-none d-md-table-cell">{{ $goat['kelamin'] }}</td>
                            <!-- Kolom ini disembunyikan di mobile -->
                            <td>{{ $goat['jenis'] }}</td>
                            <td>{{ $goat['bobot'] }} kg</td>
                            <td class="d-none d-md-table-cell">{{ $goat['posisiKandang'] }}</td>
                            <!-- Kolom ini disembunyikan di mobile -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterInput = document.getElementById('filterInput');
            const genderFilter = document.getElementById('genderFilter');
            const cagePositionFilter = document.getElementById('cagePositionFilter');
            const goatTable = document.getElementById('goatTable');
            const rows = goatTable.querySelectorAll('tbody tr');
            const printButton = document.getElementById('printButton');

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

            // Print functionality
            printButton.addEventListener('click', function() {
                const searchText = filterInput.value;
                const gender = genderFilter.value;
                const cagePosition = cagePositionFilter.value;

                let filterSummary = 'Mencetak semua data kambing';
                if (searchText || gender || cagePosition) {
                    filterSummary = 'Filter yang digunakan:\n';
                    if (searchText) filterSummary += `- Nama/Tag: ${searchText}\n`;
                    if (gender) filterSummary += `- Jenis Kelamin: ${gender}\n`;
                    if (cagePosition) filterSummary += `- Posisi Kandang: ${cagePosition}\n`;
                }

                let printContent = `
                        <h3>Daftar Kambing</h3>
                        <p>${filterSummary}</p>
                        <table border="1" cellpadding="5" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No Tag</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jenis</th>
                                    <th>Bobot</th>
                                    <th>Posisi Kandang</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        printContent += '<tr>';
                        cells.forEach(cell => {
                            printContent += `<td>${cell.textContent}</td>`;
                        });
                        printContent += '</tr>';
                    }
                });

                printContent += `
                            </tbody>
                        </table>
                    `;

                const printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Cetak Daftar Kambing</title>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContent);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>

    <style>
        /* Responsive Design */
        @media (max-width: 768px) {

            .table th,
            .table td {
                padding: 0.5rem;
                /* Kurangi padding agar tabel lebih rapat */
                font-size: 0.9rem;
                /* Perkecil font */
            }

            .input-group-text {
                font-size: 0.8rem;
            }

            #goatTable th,
            #goatTable td {
                white-space: nowrap;
                /* Hindari teks pecah */
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.5rem;
                /* Perkecil heading di perangkat kecil */
            }

            .btn {
                font-size: 0.9rem;
                /* Perkecil tombol */
            }
        }
    </style>
@endsection
