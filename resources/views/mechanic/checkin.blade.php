@extends('layouts.app')

@section('styles')
<style>
    #reader-wrapper {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #e3e3e0;
        background-color: #000000;
        position: relative;
    }
    #qr-reader {
        width: 100%;
    }
    .checkin-alert {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ auth()->user()->isMainAdmin() ? route('admin.statistics.index') : (auth()->user()->isAdmin() ? route('admin.bookings.index') : route('mechanic.dashboard')) }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('admin.mc_back') }}</a>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h3 class="fw-bold text-center text-dark mb-2"><i class="fa-solid fa-qrcode text-brand me-1"></i> {{ __('admin.mc_scan') }}</h3>
                <p class="small text-muted text-center mb-4">{{ __('admin.mc_scan_d') }}</p>

                <!-- Check-in Status Alerts -->
                <div class="alert checkin-alert border-0 shadow-sm text-white" role="alert" id="status-alert">
                    <span id="alert-message">{{ __('admin.mc_proc') }}</span>
                </div>

                <!-- QR Camera Reader Screen -->
                <div class="mb-4">
                    <div id="reader-wrapper">
                        <div id="qr-reader"></div>
                    </div>
                </div>

                <!-- Manual Backup Form -->
                <div class="border-top pt-4">
                    <h6 class="fw-bold mb-3 text-secondary text-center">{{ __('admin.mc_cam_no') }}</h6>
                    <form id="manualCheckinForm">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-key text-muted"></i></span>
                            <input type="text" name="uuid" id="manual-uuid" class="form-control bg-light border-0" placeholder="e.g. 550e8400-e29b-41d4-a716-446655440000" required>
                            <button type="submit" class="btn btn-brand fw-bold px-4">{{ __('admin.mc_sub') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include HTML5 QR Code Scanner Library via CDN -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusAlert = document.getElementById('status-alert');
        const alertMsg = document.getElementById('alert-message');
        const manualForm = document.getElementById('manualCheckinForm');

        // Initialize scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            },
            /* verbose= */ false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanner to prevent multiple parallel submissions
            html5QrcodeScanner.clear().then(_ => {
                // Parse UUID out of scanned URL if full URL is present
                let uuid = decodedText;
                try {
                    if (decodedText.startsWith('http')) {
                        const urlObj = new URL(decodedText);
                        uuid = urlObj.searchParams.get('uuid') || decodedText;
                    }
                } catch (e) {
                    console.error("URL parsing failed, fallback to raw string.", e);
                }

                submitCheckin(uuid);
            }).catch(err => {
                console.error("Failed to stop scanner.", err);
            });
        }

        function onScanFailure(error) {
            // Failures are typical while camera is hunting for QR code, ignore them.
        }

        // Manual form submission
        manualForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const uuid = document.getElementById('manual-uuid').value.trim();
            if (uuid) {
                submitCheckin(uuid);
            }
        });

        function submitCheckin(uuid) {
            showAlert('info', 'Processing check-in scan... Please wait.', 'fa-solid fa-spinner fa-spin');
            
            fetch('/mechanic/checkin/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ uuid: uuid })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 && res.body.success) {
                    showAlert('success', `Check-in Successful! Booking #${res.body.booking.number} for ${res.body.booking.customer} is now In Progress. Redirecting to workspace...`, 'fa-solid fa-circle-check');
                    setTimeout(() => {
                        window.location.href = "{{ auth()->user()->isMainAdmin() ? route('admin.statistics.index') : (auth()->user()->isAdmin() ? route('admin.bookings.index') : route('mechanic.dashboard')) }}";
                    }, 3000);
                } else {
                    showAlert('danger', res.body.message || 'Check-in failed. Please verify the QR code and try again.', 'fa-solid fa-circle-xmark');
                    // Re-render scanner after 4 seconds to let them retry
                    setTimeout(() => {
                        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                        hideAlert();
                    }, 4000);
                }
            })
            .catch(err => {
                console.error("Check-in request failed.", err);
                showAlert('danger', 'A connection error occurred. Please try again.', 'fa-solid fa-circle-exclamation');
                setTimeout(() => {
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                    hideAlert();
                }, 4000);
            });
        }

        function showAlert(type, message, iconClass) {
            statusAlert.style.display = 'block';
            statusAlert.className = `alert checkin-alert border-0 shadow-sm text-white bg-${type}`;
            alertMsg.innerHTML = `<i class="${iconClass} me-2"></i> ${message}`;
        }

        function hideAlert() {
            statusAlert.style.display = 'none';
        }
    });
</script>
@endsection
