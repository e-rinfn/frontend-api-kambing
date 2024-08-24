@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center">Scan QR Code</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <video id="preview" class="rounded" width="100%" height="auto"
                                style="border: 2px solid #ddd;"></video>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            <button id="start-scan" class="btn btn-success btn-lg">Start Scan</button>
                            <button id="stop-scan" class="btn btn-danger btn-lg ms-3" disabled>Stop Scan</button>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            <button id="toggle-torch" class="btn btn-warning btn-lg" disabled>Toggle Torch</button>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            <select id="device-select" class="form-select w-50">
                                <!-- Video input device options will be dynamically added here -->
                            </select>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p id="qrResultText"></p>
                    </div>
                    <div class="modal-footer">
                        <a id="qrResultLink" href="#" class="btn btn-primary" target="_blank">View Link</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="resumeScan">Back</button>
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
            const startButton = document.getElementById('start-scan');
            const stopButton = document.getElementById('stop-scan');
            const torchButton = document.getElementById('toggle-torch');
            const deviceSelect = document.getElementById('device-select');
            let activeDeviceId = null;
            let scanning = false;
            let isTorchOn = false;
            let currentStream = null;

            function startScanner(deviceId) {
                if (scanning) return; // Prevent multiple scanners running

                scanning = true;
                codeReader.decodeFromVideoDevice(deviceId, previewElement, (result, error) => {
                    if (result) {
                        const scannedUrl = result.text.trim();
                        if (scannedUrl) {
                            // Show modal with result
                            const modal = new bootstrap.Modal(document.getElementById('resultModal'));
                            document.getElementById('qrResultText').innerText =
                                `QR Code scanned: ${scannedUrl}`;
                            document.getElementById('qrResultLink').href = scannedUrl;
                            modal.show();
                            codeReader.reset(); // Stop scanning
                            scanning = false;
                        }
                    }
                    if (error && !(error instanceof ZXing.NotFoundException)) {
                        console.error(error);
                    }
                });

                // Obtain the media stream for torch control
                navigator.mediaDevices.getUserMedia({
                        video: {
                            deviceId: {
                                exact: deviceId
                            }
                        }
                    })
                    .then(stream => {
                        currentStream = stream;
                        const track = stream.getVideoTracks()[0];
                        if (track.getCapabilities().torch) {
                            torchButton.disabled = false; // Enable torch button
                        }
                    })
                    .catch(err => {
                        console.error('Error accessing media devices:', err);
                    });
            }

            function toggleTorch() {
                if (currentStream) {
                    const track = currentStream.getVideoTracks()[0];
                    if (track.getCapabilities().torch) {
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
                    } else {
                        alert('Torch not supported on this device.');
                    }
                }
            }

            function handleDeviceSelection() {
                codeReader.listVideoInputDevices()
                    .then((videoInputDevices) => {
                        if (videoInputDevices.length === 0) {
                            alert('No video input devices found.');
                            return;
                        }

                        deviceSelect.innerHTML = ''; // Clear previous options
                        videoInputDevices.forEach(device => {
                            const option = document.createElement('option');
                            option.value = device.deviceId;
                            option.text = device.label || `Camera ${device.deviceId}`;
                            deviceSelect.appendChild(option);
                        });

                        // Automatically select the rear camera (if available)
                        const rearCamera = videoInputDevices.find(device => device.label.toLowerCase().includes(
                            'back') || device.label.toLowerCase().includes('rear'));
                        const selectedDeviceId = rearCamera ? rearCamera.deviceId : videoInputDevices[0]
                            .deviceId;
                        deviceSelect.value = selectedDeviceId;
                        startScanner(selectedDeviceId);

                        deviceSelect.addEventListener('change', () => {
                            codeReader.reset();
                            startScanner(deviceSelect.value);
                        });
                    })
                    .catch((err) => {
                        console.error('Error accessing video input devices:', err);
                        alert('Error accessing video input devices.');
                    });
            }

            startButton.addEventListener('click', () => {
                startButton.disabled = true;
                stopButton.disabled = false;
                startScanner(deviceSelect.value);
            });

            stopButton.addEventListener('click', () => {
                codeReader.reset();
                scanning = false;
                startButton.disabled = false;
                stopButton.disabled = true;
                torchButton.disabled = true; // Disable torch button
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                    currentStream = null;
                }
            });

            torchButton.addEventListener('click', () => {
                toggleTorch();
            });

            document.getElementById('resumeScan').addEventListener('click', () => {
                scanning = false;
                startScanner(deviceSelect.value);
            });

            // Check if mediaDevices API is supported
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Camera access is not supported in this browser.');
            } else {
                handleDeviceSelection();
            }
        });
    </script>
@endsection
