@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Detail Kambing</h4>
            </div>
            <div class="card-body">
                <!-- Goat Information -->
                <div class="row">
                    @php
                        $fields = [
                            'No Tag Kambing' => $goat['noTag'],
                            'Tanggal Lahir' => \Carbon\Carbon::parse($goat['tanggalLahir'])->format('d F Y'),
                            'Jenis Kelamin' => $goat['kelamin'],
                            'Jenis Kambing' => $goat['jenis'],
                        ];

                        $additionalFields = [
                            'Bobot (KG)' => $goat['bobot'],
                            'Nama' => $goat['nama'],
                            'Nama Induk' => $goat['induk'],
                            'Nama Pejantan' => $goat['pejantan'],
                            'Posisi Kandang' => $goat['posisiKandang'],
                            'Asal' => $goat['asal'],
                            'Harga' => 'Rp. ' . number_format($goat['harga'], 2, ',', '.'),
                            'Status' => ucfirst($goat['status']),
                        ];
                    @endphp

                    @foreach ($fields as $label => $value)
                        <div class="col-12 col-md-6 mb-2">
                            <div class="p-2 rounded bg-light shadow-sm">
                                <strong class="d-block mb-1">{{ $label }}</strong>
                                <p class="mb-0">{{ $value }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Collapsible Additional Information -->
                <div class="text-center mb-3">
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#additionalInfo" aria-expanded="false" aria-controls="additionalInfo"
                        id="toggleButton" onclick="toggleButtonText()">
                        Lihat Informasi Tambahan
                    </button>
                </div>

                <!-- Collapsible Additional Information -->
                <div class="collapse mt-3" id="additionalInfo">
                    <div class="row">
                        @foreach ($additionalFields as $label => $value)
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded bg-light shadow-sm">
                                    <strong class="d-block mb-2">{{ $label }}</strong>
                                    <p class="mb-0">{{ $value }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="d-flex justify-content-center mt-3">
                    <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal"
                        data-bs-target="#qrCodeModal">
                        <i class="bi bi-qr-code fs-4"></i><br> Lihat QR Code
                    </button>
                    <a href="{{ route('goats.cares.index', $goat['id']) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-heart fs-4"></i><br> Lihat Perawatan
                    </a>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="qrCodeModalLabel">QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div id="qrCodeLoading" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div id="qrCodeContainer" class="d-none">
                                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid">
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                <a id="downloadQrCode" href="#" download="qrcode.png" class="btn btn-primary">Download
                                    QR Image</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Script to load QR code dynamically -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var qrCodeModal = document.getElementById('qrCodeModal');
                    qrCodeModal.addEventListener('show.bs.modal', function() {
                        var qrCodeContainer = document.getElementById('qrCodeContainer');
                        var qrCodeImage = document.getElementById('qrCodeImage');
                        var qrCodeLoading = document.getElementById('qrCodeLoading');
                        var downloadQrCode = document.getElementById('downloadQrCode');

                        // Show loading spinner and hide QR code container
                        qrCodeLoading.classList.remove('d-none');
                        qrCodeContainer.classList.add('d-none');

                        // Replace {{ url('generate-qr-code') }} with ngrok URL
                        var goatId = {{ $goat['id'] }};
                        var fetchUrl = '{{ url('generate-qr-code') }}/' + goatId;
                        // Ensure URL uses HTTPS
                        fetchUrl = fetchUrl.replace('http://', 'https://');
                        console.log('Fetching QR code from:', fetchUrl); // Debugging line

                        fetch(fetchUrl)
                            .then(response => {
                                console.log('Fetch response status:', response.status); // Debugging line
                                if (!response.ok) {
                                    throw new Error('Network response was not ok: ' + response.statusText);
                                }
                                return response.blob();
                            })
                            .then(blob => {
                                var imageUrl = URL.createObjectURL(blob);
                                qrCodeImage.src = imageUrl;

                                // Set the download link using the blob URL
                                downloadQrCode.href = imageUrl;

                                // Hide loading spinner and show QR code container
                                qrCodeLoading.classList.add('d-none');
                                qrCodeContainer.classList.remove('d-none');
                            })
                            .catch(error => {
                                console.error('Error fetching QR code:', error); // Debugging line
                                qrCodeLoading.classList.add('d-none');
                                qrCodeContainer.innerHTML =
                                    '<p class="text-danger">Failed to load QR code.</p>';
                                qrCodeContainer.classList.remove('d-none');
                            });
                    });
                });

                function toggleButtonText() {
                    const button = document.getElementById('toggleButton');
                    const isExpanded = button.getAttribute('aria-expanded') === 'true'; // Check if currently expanded

                    // Toggle button text based on the current state
                    if (isExpanded) {
                        button.textContent = 'Lihat Informasi Tambahan';
                    } else {
                        button.textContent = 'Sembunyikan Informasi Tambahan';
                    }
                }

                // Optional: Add event listener to toggle button text based on collapse event
                const collapseElement = document.getElementById('additionalInfo');
                collapseElement.addEventListener('shown.bs.collapse', function() {
                    document.getElementById('toggleButton').textContent = 'Sembunyikan Informasi Tambahan';
                });
                collapseElement.addEventListener('hidden.bs.collapse', function() {
                    document.getElementById('toggleButton').textContent = 'Lihat Informasi Tambahan';
                });
            </script>

        </div>
    </div>
    <br>
    <br>
@endsection
