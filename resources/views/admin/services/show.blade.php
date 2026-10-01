@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('admin.svc_back') ?? 'Back to Services' }}
            </a>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-wrench text-brand me-2"></i>{{ $service->name }}</h2>
            <p class="text-muted mb-0">Category: <span class="badge bg-dark">{{ ucfirst($service->category) }}</span></p>
        </div>
        <div>
            @if($service->is_active)
                <span class="badge bg-success px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
            @else
                <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
            @endif
            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-brand px-4 ms-2 fw-bold">
                <i class="fa-solid fa-pen me-1"></i> Edit Service
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: Service Image & Quick Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center mb-4">
                @if($service->image_path)
                    <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->name }}" class="img-fluid rounded-3 mb-3 shadow-sm" style="max-height: 250px; object-fit: cover; width: 100%;">
                @else
                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fa-solid fa-image fs-1 text-secondary opacity-50"></i>
                    </div>
                @endif
                <h3 class="fw-bold text-brand mb-1">RM {{ number_format($service->price, 2) }}</h3>
                <span class="text-muted small d-block"><i class="fa-solid fa-clock me-1"></i> Estimated Duration: {{ $service->estimated_duration }} Mins</span>
                
                <hr class="my-3">
                
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Branch:</span>
                        <span class="fw-bold">{{ $service->branch ? $service->branch->name : 'All Branches' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Created:</span>
                        <span class="fw-bold">{{ $service->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Last Updated:</span>
                        <span class="fw-bold">{{ $service->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Description & Options -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-align-left text-brand me-2"></i>Service Description</h5>
                <p class="text-dark mb-0" style="white-space: pre-line;">{{ $service->description ?? 'No detailed description provided for this service.' }}</p>
            </div>

            @if(!empty($service->meta_data))
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-sliders text-brand me-2"></i>Service Configuration & Meta Data</h5>
                <pre class="bg-light p-3 rounded-3 mb-0 small text-dark" style="max-height: 350px; overflow-y: auto;"><code>{{ json_encode($service->meta_data, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
