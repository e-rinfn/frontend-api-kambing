@extends('layouts.app')

@section('content')
    <style>
        /* Scanning beam animation */
        #scanner-beam {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: rgba(0, 255, 0, 0.6);
            /* Red color beam */
            animation: scan 3s linear infinite;
            z-index: 10;
        }

        /* Keyframes for scanning animation */
        @keyframes scan {
            0% {
                top: 0;
            }

            50% {
                top: 100%;
            }

            100% {
                top: 0;
            }
        }

        /* Ensure the video scan box is responsive */
        #preview-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            /* This ensures the scanning beam stays within the video bounds */
        }

        #preview {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Adjust the card and buttons */
        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        /* Button styling */
        .btn-lg {
            padding: 10px 20px;
        }
    </style>

    <div class="container align-items-center justify-content-center mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header text-dark">
                        <h3 class="text-center">Scanning QR Code</h3>
                    </div>
                    <div class="card-body">
                        <div id="preview-container" class="d-flex justify-content-center position-relative">
                            <video id="preview" class="rounded"></video>
                            <!-- Scanning animation beam -->
                            <div id="scanner-beam"></div>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            <button id="toggle-torch" class="btn btn-warning btn-lg" disabled>Toggle Torch</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">QR Code Result</h5>
                </div>
                <div class="modal-body text-center">
                    <p id="qrResultText"></p>
                </div>
                <div class="modal-footer">
                    <a id="qrResultLink" href="#" class="btn btn-primary" target="_blank">View Link</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="resumeScan">Back</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeReader = new ZXing.BrowserQRCodeReader();
            const previewElement = document.getElementById('preview');
            const torchButton = document.getElementById('toggle-torch');
            let isTorchOn = false;
            let currentStream = null;

            function startScanner() {
                codeReader.decodeFromVideoDevice(null, previewElement, (result, error) => {
                    if (result) {
                        const scannedUrl = result.text.trim();
                        if (scannedUrl) {
                            const modal = new bootstrap.Modal(document.getElementById('resultModal'), {
                                backdrop: 'static', // Prevent closing by clicking outside
                                keyboard: false // Prevent closing with the Escape key
                            });
                            document.getElementById('qrResultText').innerText =
                                `QR Code scanned: ${scannedUrl}`;
                            document.getElementById('qrResultLink').href = scannedUrl;
                            modal.show();
                            codeReader.reset();
                            stopScanning();
                        }
                    }

                    if (error && !(error instanceof ZXing.NotFoundException)) {
                        console.error(error);
                    }
                });

                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    })
                    .then(stream => {
                        currentStream = stream;
                        previewElement.srcObject = stream;
                        const track = stream.getVideoTracks()[0];

                        // Check if torch is supported and enable button if it is
                        if (track.getCapabilities().torch) {
                            torchButton.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error('Error accessing media devices:', err);
                    });
            }

            function stopScanning() {
                codeReader.reset();
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                    currentStream = null;
                }
            }

            function toggleTorch() {
                if (currentStream) {
                    const track = currentStream.getVideoTracks()[0];
                    track.applyConstraints({
                        advanced: [{
                            torch: !isTorchOn
                        }]
                    }).then(() => {
                        isTorchOn = !isTorchOn;
                        torchButton.innerText = isTorchOn ? 'Turn Torch Off' : 'Turn Torch On';
                    }).catch(err => {
                        console.error('Error toggling torch:', err);
                    });
                }
            }

            torchButton.addEventListener('click', () => {
                toggleTorch();
            });

            document.getElementById('resumeScan').addEventListener('click', () => {
                startScanner();
            });

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Camera access is not supported in this browser.');
            } else {
                startScanner();
            }
        });
    </script>
@endsection
