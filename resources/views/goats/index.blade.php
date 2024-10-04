@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Total Goats Card -->
            <div class="col-md-4 mb-4">
                <div class="card bg-light border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Total Kambing</h5>
                        <p class="card-text display-4">{{ $totalGoats }}</p>
                    </div>
                </div>
            </div>
            <!-- Combined Total Goats Card -->
            <div class="col-md-12 mb-4">
                <div class="card bg-light border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Jenis Kelamin</h5>
                        <div class="d-flex justify-content-between">
                            <!-- Total Male Goats Card -->
                            <div class="flex-fill mx-1">
                                <div class="card bg-light border-success">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-success">Total Jantan</h5>
                                        <p class="card-text display-4">{{ $totalMale }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Female Goats Card -->
                            <div class="flex-fill mx-1">
                                <div class="card bg-light border-danger">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-danger">Total Betina</h5>
                                        <p class="card-text display-4">{{ $totalFemale }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Weight Card -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light border-info">
                    <div class="card-body text-center">
                        <h5 class="card-title text-info">Rata-Rata Berat</h5>
                        <p class="card-text display-4">{{ number_format($averageWeight, 2) }} kg</p>
                    </div>
                </div>
            </div>

            <!-- Most Common Breed Card -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light border-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title text-warning">Ras Paling Banyak</h5>
                        <p class="card-text display-4">{{ $mostCommonBreed }}</p>
                    </div>
                </div>
            </div>

            <!-- Cage Distribution Card -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light border-secondary">
                    <div class="card-body">
                        <h5 class="card-title text-secondary">Lokasi Kandang</h5>
                        <ul class="list-group">
                            @foreach ($cageDistribution as $cage => $count)
                                <li class="list-group-item">{{ $cage }}: {{ $count }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Age Distribution Card -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light border-dark">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Rentang Usia</h5>
                        <ul class="list-group">
                            @foreach ($ageDistribution as $ageRange => $count)
                                <li class="list-group-item">{{ $ageRange }}: {{ $count }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h3 class="text-center">Diagram Perbandingan Umur</h3>
                <canvas id="ageDistributionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('ageDistributionChart').getContext('2d');
        var ageDistributionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($ageDistribution)) !!},
                datasets: [{
                    label: 'Jumlah Kambing',
                    data: {!! json_encode(array_values($ageDistribution)) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <br>
@endsection
