@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-warehouse text-brand me-2"></i>{{ $branch->name }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.br_show_desc') }}</p>
        </div>
        <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('admin.br_btn_back') }}
        </a>
    </div>

    <div class="row g-4">
        {{-- Left column: Info cards --}}
        <div class="col-lg-5">

            {{-- Status & Capacity --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-circle-info text-brand me-2"></i>{{ __('admin.br_overview') }}</h5>
                        @if($branch->is_active)
                            <span class="badge bg-success px-3 py-2">{{ __('admin.br_active') }}</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">{{ __('admin.br_inactive') }}</span>
                        @endif
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.br_service_bays') }}</div>
                                <div class="fs-3 fw-bold text-dark">{{ $branch->service_capacity }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.br_branch_id') }}</div>
                                <div class="fs-3 fw-bold text-dark">#{{ $branch->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operating Hours --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock text-brand me-2"></i>{{ __('admin.br_operating_hours') }}</h5>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-3 p-3 flex-grow-1 text-center">
                            <div class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.br_opens') }}</div>
                            <div class="fs-5 fw-bold text-dark">{{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }}</div>
                        </div>
                        <div class="text-muted fs-4">—</div>
                        <div class="bg-light rounded-3 p-3 flex-grow-1 text-center">
                            <div class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.br_closes') }}</div>
                            <div class="fs-5 fw-bold text-dark">{{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact & Address --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-address-book text-brand me-2"></i>{{ __('admin.br_contact_address') }}</h5>

                    <div class="mb-3">
                        <div class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.br_phone_number') }}</div>
                        <div class="fs-6 text-dark">
                            <i class="fa-solid fa-phone text-brand me-2"></i>
                            <a href="tel:{{ $branch->contact_number }}" class="text-dark text-decoration-none">{{ $branch->contact_number }}</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.br_full_address') }}</div>
                        <div class="fs-6 text-dark">
                            <i class="fa-solid fa-location-dot text-brand me-2"></i>{{ $branch->address }}
                        </div>
                    </div>

                    @if($branch->google_map_link)
                        <a href="{{ $branch->google_map_link }}" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm px-3">
                            <i class="fa-solid fa-diamond-turn-right me-1"></i> {{ __('admin.br_get_directions') }}
                        </a>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right column: Google Map embed --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 pb-0">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-map-location-dot text-brand me-2"></i>{{ __('admin.br_location') }}</h5>
                </div>
                <div class="px-4 pb-4">
                    <div class="rounded-4 overflow-hidden border">
                        <iframe
                            src="https://maps.google.com/maps?q={{ urlencode($branch->address) }}&output=embed"
                            width="100%"
                            height="400"
                            style="border:0; display:block;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                    <div class="mt-3 small text-muted">
                        <i class="fa-solid fa-map-pin me-1"></i> {{ $branch->address }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
