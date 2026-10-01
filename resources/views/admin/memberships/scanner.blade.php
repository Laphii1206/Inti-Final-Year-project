@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-qrcode text-dark me-2"></i>{{ __('admin.mem_scan_qr_title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin.mem_scan_qr_desc') }}</p>
        </div>
        <a href="{{ route('admin.memberships.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>{{ __('admin.mem_back') }}
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0 text-center bg-dark" style="position: relative; min-height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div id="reader" style="width: 100%; border: none;"></div>
                    <div id="loadingMessage" class="position-absolute top-50 start-50 translate-middle text-white" style="pointer-events: none; z-index: 10;">
                        <div class="spinner-border text-brand mb-2" role="status"></div>
                        <div>{{ __('admin.mem_cam_req') }}</div>
                    </div>
                </div>
                <div class="card-footer bg-white p-4 text-center">
                    <h5 class="fw-bold text-dark mb-1">{{ __('admin.mem_cam_align') }}</h5>
                    <p class="text-muted small mb-0">{{ __('admin.mem_cam_redirect') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("reader");
        const loadingMsg = document.getElementById('loadingMessage');
        
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        const onScanSuccess = (decodedText, decodedResult) => {
            html5QrCode.stop().then((ignore) => {
                loadingMsg.innerHTML = `<div class="text-success"><i class="fa-solid fa-circle-check fs-1 mb-2"></i><br>Scan Successful! Redirecting...</div>`;
                loadingMsg.style.display = 'block';
                window.location.href = decodedText;
            }).catch((err) => {
                console.log(err);
            });
        };

        // Try environment (rear) camera first, fallback to user (front) camera
        html5QrCode.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess
        ).then(() => {
            loadingMsg.style.display = 'none';
        }).catch((err) => {
            console.warn("Rear camera failed, trying front camera...", err);
            // Fallback: try front camera
            html5QrCode.start(
                { facingMode: "user" }, 
                config, 
                onScanSuccess
            ).then(() => {
                loadingMsg.style.display = 'none';
            }).catch((err2) => {
                console.warn("Front camera failed, trying any camera...", err2);
                // Last fallback: try any available camera
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        html5QrCode.start(
                            devices[0].id,
                            config,
                            onScanSuccess
                        ).then(() => {
                            loadingMsg.style.display = 'none';
                        }).catch((err3) => {
                            showError(err3);
                        });
                    } else {
                        showError("No cameras found");
                    }
                }).catch((err3) => {
                    showError(err3);
                });
            });
        });

        function showError(err) {
            loadingMsg.innerHTML = `<div class="text-danger"><i class="fa-solid fa-triangle-exclamation fs-1 mb-2"></i><br>Camera access denied.</div><div class="small text-white-50 mt-3 px-4">Please allow camera permission in your browser.<br>If the problem persists, try accessing via <b>localhost</b> or <b>HTTPS</b>.</div>`;
            console.error("QR Code scanner failed to start", err);
        }
    });
</script>
@endsection
