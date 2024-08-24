@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Kambing</h4>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Field No Tag -->
                        <div class="mb-3">
                            <label class="form-label">No Tag Kambing</label>
                            <p class="form-control-plaintext">{{ $goat['noTag'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Field Tanggal Lahir -->
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <p class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($goat['tanggalLahir'])->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Field Nama -->
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <p class="form-control-plaintext">{{ $goat['nama'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Field Bobot -->
                        <div class="mb-3">
                            <label class="form-label">Bobot (KG)</label>
                            <p class="form-control-plaintext">{{ $goat['bobot'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Field Kelamin -->
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <p class="form-control-plaintext">{{ $goat['kelamin'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Field Jenis -->
                        <div class="mb-3">
                            <label class="form-label">Jenis Kambing</label>
                            <p class="form-control-plaintext">{{ $goat['jenis'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Field Induk -->
                        <div class="mb-3">
                            <label class="form-label">Nama Induk</label>
                            <p class="form-control-plaintext">{{ $goat['induk'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Field Pejantan -->
                        <div class="mb-3">
                            <label class="form-label">Nama Pejantan</label>
                            <p class="form-control-plaintext">{{ $goat['pejantan'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Field Posisi Kandang -->
                        <div class="mb-3">
                            <label class="form-label">Posisi Kandang</label>
                            <p class="form-control-plaintext">{{ $goat['posisiKandang'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Field Asal -->
                        <div class="mb-3">
                            <label class="form-label">Asal</label>
                            <p class="form-control-plaintext">{{ $goat['asal'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Field Harga -->
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <p class="form-control-plaintext">{{ number_format($goat['harga'], 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Field Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <p class="form-control-plaintext">{{ ucfirst($goat['status']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- QR Code Button -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary px-5" data-bs-toggle="modal"
                        data-bs-target="#qrCodeModal">
                        Lihat QR Code
                    </button>
                </div>

                <!-- Button to view care events -->
                <div class="text-center mt-4">
                    <a href="{{ route('goats.cares.index', $goat['id']) }}" class="btn btn-secondary px-5">
                        Lihat Perawatan
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
                                <!-- Loading spinner -->
                                <div id="qrCodeLoading" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <!-- QR Code will be loaded here -->
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
                </script>

            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
@endsection
