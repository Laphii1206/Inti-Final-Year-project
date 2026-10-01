@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
    <style>
    /* Automotive Card UI Enhancements */
    .car-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        border: 1px solid rgba(255,255,255,0.08) !important;
        background: #111111 !important;
    }
    .car-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.5);
        border-color: rgba(229,50,45,0.3) !important;
    }
    .car-brand-logo-wrap {
        width: 48px;
        height: 48px;
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }
    .car-brand-logo-wrap img {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }
    .car-plate-badge {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.15);
        color: #e4e4e7;
        font-family: monospace, sans-serif;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        font-size: 0.8rem;
        display: inline-block;
    }
    .car-spec-box {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 0.75rem 0.9rem;
        display: flex;
        align-items: center;
        transition: background 0.2s;
    }
    .car-spec-box:hover {
        background: rgba(255,255,255,0.05);
    }
    .car-spec-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(236,31,36,0.12);
        color: #EC1F24;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .car-spec-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888888;
        margin-bottom: 2px;
    }
    .car-spec-val {
        font-size: 0.9rem;
        font-weight: 700;
        color: #ffffff;
    }
    .btn-book-car {
        background: #EC1F24;
        color: #ffffff;
        font-weight: 600;
        border: none;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(236,31,36,0.25);
    }
    .btn-book-car:hover {
        background: #c81519;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(236,31,36,0.35);
    }
    .btn-car-action {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #aaaaaa;
        transition: all 0.2s;
    }
    .btn-car-action:hover {
        background: rgba(255,255,255,0.1);
        color: #ffffff;
        border-color: rgba(255,255,255,0.2);
    }
    .btn-car-action.btn-delete:hover {
        background: rgba(229,50,45,0.15);
        color: #ff4d4d;
        border-color: rgba(229,50,45,0.3);
    }
    </style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_cars') => null]" />
@endsection

@section('content')

@php
    $popularCarBrands = ['Perodua', 'Proton', 'Honda', 'Toyota', 'Nissan', 'Mazda', 'BMW', 'Mercedes-Benz'];
@endphp
<div class="container my-3 my-md-5 acct-dark">
    @include('partials.mobile-account-tabs')
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 mb-4 order-2 order-md-1">
            @include('partials.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-12 col-md-9 order-1 order-md-2">
            {{-- Header Card --}}
            <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 mb-4 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fa-solid fa-car text-brand me-2"></i>{{ __('dashboard.cars_my_cars') }}</h2>
                    <p class="text-white-50 mb-0">{{ __('account.vehicles_subtitle') }}</p>
                </div>
                <button class="btn btn-brand rounded-pill px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCarModal">
                    <i class="fa-solid fa-plus me-2"></i>{{ __('dashboard.cars_add_new') }}
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
            @endif

            <div class="row g-3 g-md-4">
                @forelse($cars as $car)
                    <div class="col-12 col-md-6">
                        <div class="card car-card rounded-4 h-100 position-relative overflow-hidden">
                            <div class="card-body p-4 d-flex flex-column">
                                {{-- Top Header: Logo + Title + Plate --}}
                                <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-10">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="car-brand-logo-wrap">
                                            <img src="{{ asset('images/car-brands/' . Str::slug($car->brand) . '.png') }}" 
                                                 alt="{{ $car->brand }}" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <i class="fa-solid fa-car fs-5 text-brand" style="display:none;"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold text-white mb-1">{{ $car->brand }} {{ $car->model }}</h5>
                                            <div class="car-plate-badge">{{ $car->car_plate }}</div>
                                        </div>
                                    </div>
                                    
                                    @if($car->is_default)
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold flex-shrink-0" style="font-size:0.7rem; letter-spacing:0.5px;">
                                            <i class="fa-solid fa-circle-check me-1"></i> DEFAULT
                                        </span>
                                    @endif
                                </div>

                                {{-- Specs Box: Year & Mileage --}}
                                <div class="row g-2 mb-4">
                                    <div class="col-6">
                                        <div class="car-spec-box">
                                            <div class="car-spec-icon-wrap">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </div>
                                            <div>
                                                <div class="car-spec-label">Year</div>
                                                <div class="car-spec-val">{{ $car->year }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="car-spec-box">
                                            <div class="car-spec-icon-wrap">
                                                <i class="fa-solid fa-gauge-high"></i>
                                            </div>
                                            <div>
                                                <div class="car-spec-label">Mileage</div>
                                                <div class="car-spec-val">{{ number_format($car->mileage) }} <span style="font-size:0.75rem;font-weight:normal;color:#888;">km</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Primary Action: Book Service --}}
                                <div class="mt-auto">
                                    <a href="{{ route('bookings.create') }}?car={{ $car->id }}" class="btn btn-book-car w-100 py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2 mb-3 text-decoration-none">
                                        <i class="fa-solid fa-calendar-check"></i> <span>Book Service for This Car</span>
                                    </a>

                                    {{-- Secondary Actions: Set Default, Edit, Delete --}}
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top border-secondary border-opacity-10">
                                        <div>
                                            @if(!$car->is_default)
                                                <form action="{{ route('cars.setDefault', $car->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-car-action rounded-pill px-3 py-1.5 d-inline-flex align-items-center" style="font-size:0.78rem;" title="{{ __('dashboard.cars_set_default') }}">
                                                        <i class="fa-regular fa-star text-warning me-2"></i><span class="text-white-50 fw-medium">Set Default</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small" style="font-size:0.75rem;"><i class="fa-solid fa-shield-halved text-success me-1"></i> Primary Vehicle</span>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-car-action rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px;" data-bs-toggle="modal" data-bs-target="#editCarModal{{ $car->id }}" title="{{ __('dashboard.cars_edit') }}">
                                                <i class="fa-solid fa-pencil" style="font-size:0.8rem;"></i>
                                            </button>
                                            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-car-action btn-delete rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px;" title="Delete Vehicle">
                                                    <i class="fa-solid fa-trash" style="font-size:0.8rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-dark">
                            <i class="fa-solid fa-car-side fs-1 text-white-50 mb-3"></i>
                            <h5 class="fw-bold text-white mb-2">{{ __('dashboard.cars_none_registered') }}</h5>
                            <p class="text-white-50 small mb-4">Add your vehicle details once to speed up future appointments.</p>
                            <div>
                                <button class="btn btn-brand px-4 py-2 rounded-pill fw-medium" data-bs-toggle="modal" data-bs-target="#addCarModal"><i class="fa-solid fa-plus me-1"></i> Add Your First Vehicle</button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

        </div> {{-- End col-md-9 --}}
    </div>
</div>

<!-- Modal: Add Car -->
<div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="addCarModalLabel"><i class="fa-solid fa-car me-2 text-brand"></i>{{ __('dashboard.cars_add_new') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cars.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="brand" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_brand_name') }}</label>
                            <input type="hidden" name="brand" id="add_brand_input" required>
                            <div class="dropdown d-grid">
                                <button class="btn btn-light border-0 text-start d-flex justify-content-between align-items-center py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="add_brand_button">
                                    <span class="text-muted">{{ __('dashboard.cars_select_brand') }}</span>
                                    <i class="fa-solid fa-chevron-down text-muted"></i>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm border-0" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($popularCarBrands as $brand)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#" onclick="document.getElementById('add_brand_input').value='{{ $brand }}'; document.getElementById('add_brand_button').querySelector('span').innerText='{{ $brand }}'; document.getElementById('add_brand_button').querySelector('span').classList.remove('text-muted'); return false;">
                                            <img src="{{ asset('images/car-brands/' . Str::slug($brand) . '.png') }}" alt="{{ $brand }}" style="width:24px; height:24px; object-fit:contain;" onerror="this.style.display='none'">
                                            {{ $brand }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="model" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_model_name') }}</label>
                            <input type="text" name="model" id="model" class="form-control bg-light border-0 py-2" placeholder="Civic, Vios..." required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="year" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_manufacture_year') }}</label>
                            <input type="number" name="year" id="year" class="form-control bg-light border-0 py-2" placeholder="2020" min="1900" max="{{ date('Y') + 1 }}" required>
                        </div>
                        <div class="col-6">
                            <label for="car_plate" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_plate_number') }}</label>
                            <input type="text" name="car_plate" id="car_plate" class="form-control bg-light border-0 py-2 text-uppercase" placeholder="WXX 1234" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mileage" class="form-label small fw-bold text-secondary">Current Mileage (km)</label>
                        <input type="number" name="mileage" id="mileage" class="form-control bg-light border-0 py-2" placeholder="45000" min="0" required>
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="addIsDefault">
                        <label class="form-check-label small fw-semibold text-dark" for="addIsDefault">
                            Set as default booking vehicle
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('dashboard.cars_register') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals: Edit Car -->
@foreach($cars as $car)
<div class="modal fade" id="editCarModal{{ $car->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square me-2 text-brand"></i>{{ __('dashboard.cars_edit') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cars.update', $car->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white text-start">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_brand_name') }}</label>
                            <input type="hidden" name="brand" id="edit_brand_input_{{ $car->id }}" value="{{ $car->brand }}" required>
                            <div class="dropdown d-grid">
                                <button class="btn btn-light border-0 text-start d-flex justify-content-between align-items-center py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="edit_brand_button_{{ $car->id }}">
                                    <span>{{ $car->brand }}</span>
                                    <i class="fa-solid fa-chevron-down text-muted"></i>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm border-0" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($popularCarBrands as $brand)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#" onclick="document.getElementById('edit_brand_input_{{ $car->id }}').value='{{ $brand }}'; document.getElementById('edit_brand_button_{{ $car->id }}').querySelector('span').innerText='{{ $brand }}'; return false;">
                                            <img src="{{ asset('images/car-brands/' . Str::slug($brand) . '.png') }}" alt="{{ $brand }}" style="width:24px; height:24px; object-fit:contain;" onerror="this.style.display='none'">
                                            {{ $brand }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_model_name') }}</label>
                            <input type="text" name="model" class="form-control bg-light border-0 py-2" value="{{ $car->model }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_manufacture_year') }}</label>
                            <input type="number" name="year" class="form-control bg-light border-0 py-2" value="{{ $car->year }}" min="1900" max="{{ date('Y') + 1 }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_plate_number') }}</label>
                            <input type="text" name="car_plate" class="form-control bg-light border-0 py-2 text-uppercase" value="{{ $car->car_plate }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Current Mileage (km)</label>
                        <input type="number" name="mileage" class="form-control bg-light border-0 py-2" value="{{ $car->mileage }}" min="0" required>
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="editIsDefault{{ $car->id }}" {{ $car->is_default ? 'checked disabled' : '' }}>
                        <label class="form-check-label small fw-semibold text-dark" for="editIsDefault{{ $car->id }}">
                            Set as default booking vehicle
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('dashboard.cars_save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
