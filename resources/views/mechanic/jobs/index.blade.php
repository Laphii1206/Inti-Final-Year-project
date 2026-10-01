@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Portal Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-1"><i class="fa-solid fa-wrench text-brand me-2"></i>{{ __('admin.mc_work') }}</h2>
                        <p class="text-white-50 mb-0">{{ __('admin.mc_work_d') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('mechanic.checkin.scan') }}" class="btn btn-brand btn-lg px-4 fw-bold">
                            <i class="fa-solid fa-qrcode me-2"></i> {{ __('admin.mc_scan_c') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WebAuthn: Passkey Registration -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            @include('partials.webauthn-register')
        </div>
    </div>

    <!-- Jobs List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-list-check text-brand me-2"></i>{{ __('admin.mc_ass') }}</h4>
                
                @forelse($jobs as $job)
                    <div class="card border-0 shadow-sm p-3 mb-3 bg-light border-start border-4 {{ $job->status === 'in_progress' ? 'border-info' : 'border-success' }}">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <span class="badge bg-secondary mb-2">#{{ $job->number }}</span>
                                <h5 class="fw-bold text-dark mb-1">{{ $job->service->name ?? 'Deleted Service' }}</h5>
                                <div class="row g-2 mt-2 small text-secondary">
                                    <div class="col-sm-6 col-md-4">
                                        <i class="fa-solid fa-user me-1 text-brand"></i> {{ __('admin.mc_c') }} <strong>{{ $job->user->name ?? 'Deleted User' }}</strong>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <i class="fa-solid fa-car me-1 text-brand"></i> {{ __('admin.mc_v') }} <strong>{{ $job->car->brand ?? 'Unknown' }} {{ $job->car->model ?? 'Vehicle' }} ({{ $job->car->car_plate ?? 'N/A' }})</strong>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <i class="fa-solid fa-clock me-1 text-brand"></i> {{ __('admin.mc_s') }} <strong>{{ \Carbon\Carbon::parse($job->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($job->end_time)->format('h:i A') }}</strong>
                                    </div>
                                </div>
                                @if($job->customer_remark)
                                    <div class="mt-2 text-dark small italic bg-white p-2 rounded border-start border-3 border-danger-subtle">
                                        <i class="fa-solid fa-comment-dots text-brand me-1"></i> "{{ $job->customer_remark }}"
                                    </div>
                                @endif
                            </div>
                            <div class="text-md-end">
                                <h5 class="fw-bold text-brand mb-2">RM {{ number_format($job->service_price_at_booking, 2) }}</h5>
                                
                                @if($job->status === 'confirmed')
                                    <span class="badge bg-success text-white px-3 py-1.5 rounded-pill mb-2 d-inline-block"><i class="fa-solid fa-check-double me-1"></i> {{ __('admin.mc_conf') }}</span>
                                    <div class="text-muted small italic">{{ __('admin.mc_conf_d') }}</div>
                                @elseif($job->status === 'in_progress')
                                    <span class="badge bg-info text-dark px-3 py-1.5 rounded-pill mb-2 d-inline-block"><i class="fa-solid fa-screwdriver-wrench me-1"></i> {{ __('admin.mc_prog') }}</span>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-brand px-4 btn-complete-job" data-id="{{ $job->id }}" data-number="{{ $job->number }}" data-plate="{{ $job->car->car_plate ?? 'N/A' }}" data-mileage="{{ $job->car->mileage ?? 0 }}">
                                            <i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.mech_comp_job') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-secondary">
                        <i class="fa-solid fa-clipboard-list fs-1 text-muted mb-3"></i>
                        <p class="small mb-0">{{ __('admin.mc_no') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal: {{ __('admin.mech_comp_job') }} (Input Mileage) -->
<div class="modal fade" id="completeJobModal" tabindex="-1" aria-labelledby="completeJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="completeJobModalLabel"><i class="fa-solid fa-circle-check me-2 text-brand"></i>{{ __('admin.mc_comp_s') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="completeJobForm">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <p class="small text-secondary mb-4">Please record the vehicle's final odometer reading to mark booking <strong id="complete-job-number">#</strong> as completed.</p>
                    
                    <div class="mb-3">
                        <label for="recorded_mileage" class="form-label small fw-bold text-secondary">{{ __('admin.mc_final') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-gauge-high text-muted"></i></span>
                            <input type="number" name="recorded_mileage" id="recorded_mileage" class="form-control bg-light border-0 py-2.5" placeholder="45000" min="0" required>
                        </div>
                        <span class="small text-muted mt-1 d-block">{{ __('admin.mc_plate') }} <strong id="complete-job-plate">N/A</strong>{{ __('admin.mc_prev') }} <strong id="complete-job-prev-mileage">0</strong> km.</span>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.mc_can') }}</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('admin.mc_comp_b') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const completeButtons = document.querySelectorAll('.btn-complete-job');
        const modal = new bootstrap.Modal(document.getElementById('completeJobModal'));
        const form = document.getElementById('completeJobForm');

        completeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const jobId = this.dataset.id;
                const jobNum = this.dataset.number;
                const carPlate = this.dataset.plate;
                const prevMileage = this.dataset.mileage;

                // Update modal elements
                document.getElementById('complete-job-number').innerText = '#' + jobNum;
                document.getElementById('complete-job-plate').innerText = carPlate;
                document.getElementById('complete-job-prev-mileage').innerText = Number(prevMileage).toLocaleString();
                
                const mileageInput = document.getElementById('recorded_mileage');
                mileageInput.value = prevMileage;
                mileageInput.min = prevMileage; // Cannot be less than current mileage

                // Set form action
                form.action = `/mechanic/jobs/${jobId}/complete`;

                modal.show();
            });
        });
    });
</script>
@endsection
