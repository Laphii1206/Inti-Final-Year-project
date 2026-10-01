@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ─── Big Tech / Apple Style Date Selector ─── */
    .date-strip::-webkit-scrollbar { height: 8px; }
    .date-strip::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 99px; margin: 0 10px; }
    .acct-dark .date-strip::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
    .date-strip::-webkit-scrollbar-thumb { background: rgba(150, 150, 150, 0.4); border-radius: 99px; cursor: pointer; }
    .date-strip::-webkit-scrollbar-thumb:hover { background: rgba(236, 31, 36, 0.7); }
    .date-card {
        min-width: 82px;
        padding: 14px 10px;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .acct-dark .date-card {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.1);
        color: #e4e4e7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .date-card:hover {
        transform: translateY(-3px);
        border-color: rgba(236, 31, 36, 0.4);
        box-shadow: 0 10px 20px rgba(236, 31, 36, 0.12);
    }
    .date-card.active {
        background: linear-gradient(135deg, #EC1F24 0%, #b81418 100%) !important;
        border-color: #EC1F24 !important;
        color: #ffffff !important;
        box-shadow: 0 8px 24px rgba(236, 31, 36, 0.4) !important;
        transform: translateY(-2px);
    }
    .date-card .date-month {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.7;
        margin-bottom: 2px;
    }
    .date-card.active .date-month { opacity: 0.95; color: #ffffff; }
    .date-card .date-day {
        font-size: 1.45rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 5px;
    }
    .date-card .date-weekday {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(0, 0, 0, 0.06);
    }
    .acct-dark .date-card .date-weekday { background: rgba(255, 255, 255, 0.1); }
    .date-card.active .date-weekday { background: rgba(255, 255, 255, 0.25); color: #ffffff; }
</style>
@include('partials.flatpickr-dark-styles')
<style>

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 50px;
        position: relative;
        padding: 0 15px;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 21px;
        left: 30px;
        right: 30px;
        height: 3px;
        background-color: #f3f4f6;
        z-index: 1;
    }

    .step-dot {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 3px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #9ca3af;
        z-index: 2;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.04);
    }

    .step-dot.active {
        border-color: #EC1F24;
        background-color: #EC1F24;
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(236, 31, 36, 0.15), 0 4px 12px rgba(236, 31, 36, 0.25);
        transform: scale(1.08);
    }

    .step-dot.completed {
        border-color: #EC1F24;
        background-color: #EC1F24;
        color: #ffffff;
    }

    .step-label {
        font-size: 0.8rem;
        font-weight: 600;
        position: absolute;
        bottom: -28px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        color: #6b7280;
        transition: color 0.3s ease;
    }

    .step-dot.active .step-label {
        color: #111827;
        font-weight: 700;
    }

    /* Tech Giant Style Time Slot Pills */
    .slot-pill {
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #1f2937;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .slot-pill:hover:not(.pe-none) {
        border-color: #EC1F24;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(236, 31, 36, 0.1), 0 2px 4px -1px rgba(236, 31, 36, 0.06);
    }
    .slot-pill.active,
    .slot-pill.btn-dark {
        background: #111827 !important;
        border-color: #111827 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
    }

    .booking-step {
        display: none;
    }

    .booking-step.active {
        display: block;
    }

    /* Option Cards */
    .option-card {
        border: 2px solid #e3e3e0;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #ffffff;
        height: 100%;
    }

    .option-card:hover {
        border-color: #EC1F24;
        background-color: #fff8f8;
    }

    .option-card.selected {
        border-color: #EC1F24;
        background-color: #fff3f4;
    }

    /* Disabled time-slot options (Full / Past) */
    select#start_time option:disabled {
        color: #a1a09e;
        background-color: #f5f5f4;
        font-style: italic;
    }

    .slot-badge {
        display: inline-block;
        font-size: 0.7rem;
        padding: 1px 6px;
        border-radius: 4px;
        margin-left: 6px;
        vertical-align: middle;
    }
    .slot-badge.full  { background: #fee2e2; color: #b91c1c; }
    .slot-badge.past  { background: #f3f4f6; color: #6b7280; }
    .slot-badge.avail { background: #dcfce7; color: #166534; }
    .acct-dark .step-indicator::before { background-color: rgba(255,255,255,.12) !important; }
    .acct-dark .step-dot { background-color:#1b1b1b !important; border-color: rgba(255,255,255,.15) !important; color:#9ca3af !important; }
    .acct-dark .step-label { color:#a1a1aa !important; }
    .acct-dark .step-dot.active .step-label { color:#fff !important; }
    .acct-dark .slot-pill { background:#1b1b1b !important; border-color: rgba(255,255,255,.12) !important; color:#e4e4e7 !important; }
    .acct-dark .slot-pill.active,
    .acct-dark .slot-pill.btn-dark { background:#EC1F24 !important; border-color:#EC1F24 !important; color:#fff !important; }
    .acct-dark .option-card { background-color:#111 !important; border-color: rgba(255,255,255,.12) !important; }
    .acct-dark .option-card:hover { background-color:#1a1212 !important; border-color:#EC1F24 !important; }
    .acct-dark .option-card.selected { background-color: rgba(236,31,36,.12) !important; border-color:#EC1F24 !important; }
    .acct-dark .slot-badge.full { background: rgba(239,68,68,.18) !important; color:#fca5a5 !important; }
    .acct-dark .slot-badge.past { background: rgba(255,255,255,.08) !important; color:#9ca3af !important; }
    .acct-dark .slot-badge.avail { background: rgba(34,197,94,.18) !important; color:#86efac !important; }
    .acct-dark input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1) opacity(.7); }
    .acct-dark select option:disabled { color:#6b7280 !important; background-color:#1b1b1b !important; }

    /* ─── Big Tech / Apple Style Voucher Cards ─── */
    .voucher-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 240px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .voucher-grid::-webkit-scrollbar { width: 6px; }
    .voucher-grid::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); border-radius: 99px; }
    .acct-dark .voucher-grid::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .voucher-grid::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.4); border-radius: 99px; }
    .voucher-card {
        display: flex;
        align-items: center;
        padding: 14px 16px;
        border-radius: 16px;
        background: #ffffff;
        border: 2px solid rgba(0,0,0,0.08);
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .acct-dark .voucher-card {
        background: #181920 !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
    .voucher-card:hover {
        transform: translateY(-2px);
        border-color: rgba(236, 31, 36, 0.4) !important;
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .voucher-card.active {
        border-color: #EC1F24 !important;
        background: rgba(236, 31, 36, 0.04);
        box-shadow: 0 8px 20px rgba(236, 31, 36, 0.12);
    }
    .acct-dark .voucher-card.active {
        background: rgba(236, 31, 36, 0.12) !important;
        border-color: #EC1F24 !important;
    }
    .voucher-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #6b7280;
        margin-right: 14px;
        flex-shrink: 0;
    }
    .acct-dark .voucher-icon { background: rgba(255,255,255,0.06); color: #a1a1aa; }
    .voucher-icon.discount {
        background: rgba(236, 31, 36, 0.1);
        color: #EC1F24;
    }
    .voucher-info {
        flex-grow: 1;
    }
    .voucher-title {
        font-weight: 700;
        font-size: 1rem;
        color: #1f2937;
        margin-bottom: 2px;
    }
    .acct-dark .voucher-title { color: #ffffff !important; }
    .voucher-sub {
        font-size: 0.85rem;
        color: #6b7280;
    }
    .acct-dark .voucher-sub { color: #9ca3af; }
    .voucher-check {
        font-size: 1.4rem;
        color: #d1d5db;
        margin-left: 12px;
        transition: all 0.2s;
    }
    .acct-dark .voucher-check { color: rgba(255,255,255,0.15); }
    .voucher-card.active .voucher-check {
        color: #EC1F24 !important;
        transform: scale(1.1);
    }

    /* ---------- Mobile Step Indicator Optimization ---------- */
    @media (max-width: 767.98px) {
        .step-indicator {
            padding: 0 5px;
            margin-bottom: 38px;
        }
        .step-indicator::before {
            top: 17px;
            left: 18px;
            right: 18px;
            height: 2px;
        }
        .step-dot {
            width: 36px;
            height: 36px;
            font-size: 0.85rem;
            border-width: 2px;
        }
        .step-dot.active {
            box-shadow: 0 0 0 4px rgba(236, 31, 36, 0.15), 0 4px 10px rgba(236, 31, 36, 0.25);
            transform: scale(1.08);
        }
        /* Hide inactive step labels on mobile to prevent overlapping/crowding */
        .step-label {
            display: none;
        }
        /* Only display the active step's label prominently */
        .step-dot.active .step-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #EC1F24 !important;
            bottom: -26px;
        }
        /* Prevent screen edge clipping for first and last step labels */
        .step-dot:first-child .step-label {
            left: 0;
            transform: none;
        }
        .step-dot:last-child .step-label {
            left: auto;
            right: 0;
            transform: none;
        }
    }
</style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_bookings') => route('bookings.index'), __('account.breadcrumb_new_booking') => null]" />
@endsection

@section('content')
<div class="container my-5 acct-dark">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h2 class="fw-bold text-center mb-5">{{ __('booking.cb_title') }} <span class="text-brand">{{ __('booking.cb_title_red') }}</span> {{ __('booking.cb_title_suffix') }}</h2>

                <!-- Step Indicators (6 steps) -->
                <div class="step-indicator px-4">
                    <div class="step-dot active" id="dot-1">1<span class="step-label">{{ __('booking.cb_step_branch') }}</span></div>
                    <div class="step-dot" id="dot-2">2<span class="step-label">{{ __('booking.cb_step_1') }}</span></div>
                    <div class="step-dot" id="dot-3">3<span class="step-label">{{ __('booking.cb_step_2') }}</span></div>
                    <div class="step-dot" id="dot-4">4<span class="step-label">{{ __('booking.cb_step_3') }}</span></div>
                    <div class="step-dot" id="dot-5">5<span class="step-label">{{ __('booking.cb_step_4') }}</span></div>
                    <div class="step-dot" id="dot-6">6<span class="step-label">{{ __('booking.cb_step_payment') }}</span></div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 bg-danger text-white small mb-4 mt-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm" class="mt-5">
                    @csrf
                    <!-- Hidden inputs -->
                    <input type="hidden" name="branch_id" id="input-branch-id" value="{{ $branch->id }}">
                    <input type="hidden" name="service_id" id="input-service-id" value="{{ old('service_id', $preselectedServiceId ?? '') }}">
                    <input type="hidden" name="car_id" id="input-car-id" value="{{ old('car_id', $preselectedCarId ?? ($cars->firstWhere('is_default', true)->id ?? ($cars->first()->id ?? ''))) }}">

                    <!-- Step 1: Branch Selection -->
                    <div class="booking-step active" id="step-1">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-store text-brand me-2"></i>{{ __('booking.bc_select_branch') }}</h4>
                        <div class="row g-4 text-start" id="branch-selector">
                            @foreach($branches as $b)
                                @php
                                    $bImg = $b->image_path ? asset('storage/' . $b->image_path) : asset('images/workshop.jpg');
                                    $openStr = $b->opening_time ? \Carbon\Carbon::parse($b->opening_time)->format('h:i A') : '09:00 AM';
                                    $closeStr = $b->closing_time ? \Carbon\Carbon::parse($b->closing_time)->format('h:i A') : '07:00 PM';
                                    $isOpen = $b->isOpenNow();
                                @endphp
                                <div class="col-md-6">
                                    <div class="option-card branch-option {{ $loop->first ? 'selected' : '' }} p-0 overflow-hidden d-flex flex-column radius-lg" data-id="{{ $b->id }}" data-name="{{ $b->name }}" data-capacity="{{ $b->service_capacity ?? 3 }}" style="transition: all 0.3s ease;">
                                        <!-- Widescreen Workshop Photograph -->
                                        <div class="position-relative w-100" style="height: 200px; background: #eee;">
                                            <img src="{{ $bImg }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $b->name }}">
                                            
                                            <!-- Top Status Pills -->
                                            <div class="position-absolute top-0 end-0 p-3">
                                                @if($isOpen)
                                                    <span class="badge bg-success shadow px-3 py-2 rounded-pill fw-bold fs-xs"><i class="fa-solid fa-store me-1"></i> {{ __('booking.cb_open_now') }}</span>
                                                @else
                                                    <span class="badge bg-dark shadow px-3 py-2 rounded-pill fw-bold fs-xs"><i class="fa-solid fa-moon me-1"></i> {{ __('booking.cb_closed') }}</span>
                                                @endif
                                            </div>

                                            <!-- Bottom Gradient Hours Banner -->
                                            <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex align-items-center justify-content-between" style="background: linear-gradient(0deg, rgba(17,24,39,0.92) 0%, rgba(17,24,39,0.4) 60%, rgba(17,24,39,0) 100%);">
                                                <div class="text-white small fw-semibold d-flex align-items-center gap-2">
                                                    <div class="bg-brand rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                        <i class="fa-solid fa-clock text-white fs-2xs"></i>
                                                    </div>
                                                    <span>{{ __('booking.cb_op_hours') }}: <span class="text-warning fw-bold ms-1">{{ $openStr }} - {{ $closeStr }}</span> <span class="text-white-50 ms-1">{{ __('booking.bc_daily') }}</span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Info Area -->
                                        <div class="p-4 d-flex flex-column flex-grow-1 bg-white">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h5 class="fw-bolder mb-0 text-dark fs-5">{{ $b->name }}</h5>
                                                <i class="fa-solid fa-circle-check text-brand check-icon fs-4 shadow-sm rounded-circle" style="{{ $loop->first ? '' : 'display:none;' }}"></i>
                                            </div>

                                            <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.55;">
                                                <i class="fa-solid fa-location-dot text-brand me-1.5"></i> {{ $b->address }}
                                            </p>

                                            <div class="pt-3 border-top d-flex align-items-center justify-content-between small text-muted">
                                                <span class="fw-medium"><i class="fa-solid fa-phone-volume text-brand me-1"></i> {{ $b->contact_number ?: '+60 3-8060 1234' }}</span>
                                                @if($b->google_map_link)
                                                    <a href="{{ $b->google_map_link }}" target="_blank" onclick="event.stopPropagation();" class="text-brand fw-bold text-decoration-none d-flex align-items-center gap-1">
                                                        <span>{{ __('booking.cb_google_maps') }}</span>
                                                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.7rem;"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-end mt-5">
                            <button type="button" class="btn btn-brand px-4 btn-next" data-step="1" id="step1NextBtn">{{ __('booking.bc_next_package') }} <i class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- Step 2: Service Selection -->
                    <div class="booking-step" id="step-2">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-screwdriver-wrench text-brand me-2"></i>{{ __('booking.cb_step_1_title') }}</h4>
                        
                        <!-- Level 1: Category Selection -->
                        <div class="mb-4 text-start">
                            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-2"><i class="fa-solid fa-layer-group text-brand me-1"></i> {{ __('booking.bc_step1_category') }}</label>
                            @php
                                $catMeta = [
                                    'Tyres' => ['img' => 'images/categories/tyres.webp'],
                                    'Maintenance' => ['img' => 'images/categories/maintenance.jpg'],
                                    'Tinting Films' => ['img' => 'images/categories/tinting-films.webp'],
                                    'Wipers' => ['img' => 'images/categories/wipers.webp'],
                                    'Dashcams' => ['img' => 'images/categories/dashcams.webp'],
                                    'Car Mats' => ['img' => 'images/categories/car-mats.jpg'],
                                ];
                            @endphp
                            <div class="d-flex flex-wrap gap-2" id="category-selector">
                                @foreach(['Tyres', 'Maintenance', 'Tinting Films', 'Wipers', 'Dashcams', 'Car Mats'] as $catName)
                                    <button type="button" class="btn btn-outline-dark bg-white text-dark rounded-pill ps-2 pe-4 py-1.5 cat-btn fw-bold small d-inline-flex align-items-center gap-2 shadow-sm border" data-category="{{ $catName }}" style="transition: all 0.2s ease;">
                                        <img src="{{ asset($catMeta[$catName]['img']) }}" class="rounded-circle shadow-sm flex-shrink-0" style="width: 32px; height: 32px; object-fit: cover;" alt="{{ $catName }}">
                                        <span>{{ $catName }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Level 2: Brand Selection (Hidden initially) -->
                        <div class="mb-4 p-3 bg-light rounded-4 border text-start" id="brand-section" style="display: none;">
                            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-2"><i class="fa-solid fa-tag text-brand me-1"></i> {{ __('booking.bc_step2_brand') }}</label>
                            <div class="d-flex flex-wrap gap-2" id="brand-selector">
                                <!-- Populated dynamically by JS -->
                            </div>
                        </div>

                        <!-- Level 3: Service Item Selection (Hidden initially) -->
                        <div id="item-section" class="text-start" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-0"><i class="fa-solid fa-box-open text-brand me-1"></i> {{ __('booking.bc_step3_spec') }}</label>
                            </div>

                            <!-- Dynamic Filter Bar for Specification & Price -->
                            <div class="card border shadow-sm rounded-4 p-3 mb-4 bg-light" id="dynamic-filter-bar">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-8 text-start">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fa-solid fa-filter text-brand small"></i>
                                            <span class="small fw-bolder text-dark text-uppercase tracking-wider fs-xs" id="filter-sub-label">{{ __('booking.bc_spec_filter') }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-1.5" id="filter-sub-pills">
                                            <!-- Populated dynamically -->
                                        </div>
                                    </div>
                                    <div class="col-md-4 border-start ps-md-3 text-start">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="small fw-bolder text-secondary text-uppercase tracking-wider fs-xs"><i class="fa-solid fa-tags text-brand me-1"></i>{{ __('booking.bc_max_price') }}</span>
                                            <span class="badge bg-danger text-white fw-bolder px-2 py-1 fs-2xs" id="price-range-val">RM 2,000</span>
                                        </div>
                                        <input type="range" class="form-range text-brand" id="price-range-input" min="50" max="2000" step="50" value="2000">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3" id="services-container">
                                @forelse($services as $service)
                                    @php
                                        $autoSelected = old('service_id')
                                            ? old('service_id') == $service->id
                                            : (isset($preselectedServiceId) && $preselectedServiceId == $service->id);
                                        $sImg = $service->image_path ? asset('storage/' . $service->image_path) : asset('images/product-car.png');
                                    @endphp
                                    <div class="col-md-6 service-card-wrapper"
                                         data-category="{{ $service->mapped_category }}"
                                         data-brand="{{ $service->mapped_brand }}"
                                         data-branch="{{ $service->branch_id }}"
                                         data-sub="{{ $service->mapped_sub ?? '' }}"
                                         data-price="{{ $service->price }}"
                                         data-booking-count="{{ $service->completed_count ?? 0 }}"
                                         style="display: none;">
                                        <div class="option-card service-option {{ $autoSelected ? 'selected' : '' }} p-3 d-flex align-items-center gap-3 h-100 shadow-sm radius-md" 
                                             data-id="{{ $service->id }}" 
                                             data-name="{{ $service->name }}"
                                             data-price="{{ $service->price }}"
                                             data-duration="{{ $service->estimated_duration }} min"
                                             data-booking-options='{{ json_encode($service->meta_data["booking_options"] ?? []) }}'
                                             style="transition: all 0.25s ease;">
                                            
                                            <!-- Compact 96x96 Product Thumbnail -->
                                            <div class="flex-shrink-0 position-relative overflow-hidden rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 96px; height: 96px; border: 1px solid rgba(0,0,0,0.06);">
                                                <img src="{{ $sImg }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $service->name }}">
                                                {{-- Most Popular badge (top-left, shown/hidden by JS) --}}
                                                <span class="badge-popular-wizard position-absolute top-0 start-0 d-none" style="font-size:0.6rem;font-weight:700;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:8px;padding:2px 5px;pointer-events:none;">🔥 Popular</span>
                                            </div>

                                            <!-- Item Details -->
                                            <div class="d-flex flex-column flex-grow-1 text-start h-100 justify-content-between" style="min-width: 0;">
                                                <div style="min-width: 0;">
                                                    <div class="d-flex align-items-start justify-content-between gap-1 mb-1">
                                                        <h6 class="fw-bolder text-dark mb-0 text-truncate fs-base">{{ $service->name }}</h6>
                                                        <span class="badge bg-danger-subtle text-danger fw-bold flex-shrink-0 fs-xs">RM {{ number_format($service->price, 2) }}</span>
                                                    </div>
                                                    <p class="text-secondary small mb-2 text-truncate fs-xs" style="opacity: 0.85; max-width: 100%;">
                                                        {{ $service->description ?: __('booking.cb_duration') . ' ' . $service->estimated_duration . ' ' . __('booking.cb_minutes') }}
                                                    </p>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light">
                                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold px-2 py-0.5 text-truncate fs-2xs" style="max-width: 60%;">{{ $service->mapped_brand }}</span>
                                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                                        @if(($service->completed_count ?? 0) >= 10)
                                                            <span class="small fw-semibold d-flex align-items-center gap-1 fs-2xs" style="color:#16a34a;">
                                                                <i class="fa-solid fa-circle-check" style="font-size:0.65rem;"></i>
                                                                {{ number_format($service->completed_count) }}×
                                                            </span>
                                                        @endif
                                                        <span class="small text-muted fw-medium d-flex align-items-center gap-1 fs-xs">
                                                            <i class="fa-solid fa-clock text-brand"></i>
                                                            <span>{{ $service->estimated_duration }} min</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <p class="text-muted">{{ __('booking.cb_no_services') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- ── Service-Specific Options Panel ── --}}
                        {{-- Rendered dynamically by JS when a service with booking_options is selected --}}
                        <div id="service-options-panel" class="mt-4 p-4 bg-light rounded-4 border" style="display:none;">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fa-solid fa-sliders text-brand me-2"></i>Service Options
                            </h6>
                            @error('booking_options')
                                <div class="alert alert-danger py-2 small mb-3">{{ $message }}</div>
                            @enderror
                            <div id="service-options-inputs"></div>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev" data-step="2"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('booking.cb_btn_back') }}</button>
                            <button type="button" class="btn btn-brand px-4 btn-next" data-step="2" id="step2NextBtn" disabled>{{ __('booking.cb_btn_next_vehicle') }} <i class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Vehicle Selection -->
                    <div class="booking-step" id="step-3">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0"><i class="fa-solid fa-car text-brand me-2"></i>{{ __('booking.cb_step_2_title') }}</h4>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#addCarModal">
                                <i class="fa-solid fa-plus me-1"></i> {{ __('booking.bc_add_vehicle') }}
                            </button>
                        </div>
                        
                        <div class="row g-4" id="carsListContainer">
                            @php
                                $defaultSelectedCarId = old('car_id', $preselectedCarId ?? ($cars->firstWhere('is_default', true)->id ?? ($cars->first()->id ?? '')));
                            @endphp
                            @forelse($cars as $car)
                                <div class="col-md-6">
                                    <div class="option-card car-option {{ $defaultSelectedCarId == $car->id ? 'selected' : '' }}" 
                                         data-id="{{ $car->id }}" 
                                         data-name="{{ $car->brand }} {{ $car->model }} [{{ $car->car_plate }}]">
                                        <h5 class="fw-bold text-dark mb-2">{{ $car->brand }} {{ $car->model }}</h5>
                                        <div class="badge bg-dark mb-2">{{ $car->car_plate }}</div>
                                        <div class="small text-muted"><i class="fa-solid fa-gauge-high me-1"></i> {{ __('booking.db_mileage') }} {{ number_format($car->mileage) }} km</div>
                                        <div class="small text-muted"><i class="fa-solid fa-calendar me-1"></i> {{ __('booking.db_year') }} {{ $car->year }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 bg-light rounded-3" id="noCarsPlaceholder">
                                    <i class="fa-solid fa-car-side text-muted fs-1 mb-3"></i>
                                    <p class="text-muted mb-3">{{ __('booking.cb_no_vehicles') }}</p>
                                    <button type="button" class="btn btn-brand px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCarModal">
                                        <i class="fa-solid fa-plus me-1"></i> {{ __('booking.cb_btn_add_vehicle') }}
                                    </button>
                                </div>
                            @endforelse
                        </div>
                        <div class="d-flex justify-content-between mt-5">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev" data-step="3"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('booking.cb_btn_back') }}</button>
                            @if(count($cars) > 0)
                                <button type="button" class="btn btn-brand px-4 btn-next" data-step="3" id="step3NextBtn">{{ __('booking.cb_btn_next_time') }} <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            @else
                                <button type="button" class="btn btn-brand px-4 btn-next" data-step="3" id="step3NextBtn" disabled>{{ __('booking.cb_btn_next_time') }} <i class="fa-solid fa-arrow-right ms-1"></i></button>
                            @endif
                        </div>
                    </div>

                    <!-- Step 4: Schedule Booking -->
                    <div class="booking-step" id="step-4">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-calendar-days text-brand me-2"></i>{{ __('booking.cb_step_3_title') }}</h4>
                        
                        <!-- Hidden real form input for backend submission and existing JS logic -->
                        <input type="hidden" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" required>

                        <!-- Big Tech / Apple Style Interactive Date Strip & Calendar Picker -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <label class="form-label small fw-bold text-secondary mb-0 d-flex align-items-center gap-2">
                                    <i class="fa-regular fa-calendar-check text-brand fs-6"></i>
                                    <span>{{ __('booking.cb_label_date') }}</span>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2.5 py-1 fw-normal ms-1" style="font-size:0.7rem;">{{ __('booking.bc_quick_select') }}</span>
                                </label>
                                <div class="position-relative">
                                    <input type="text" id="flatpickr_input" class="form-control bg-transparent border-0 position-absolute" style="width: 1px; height: 1px; opacity: 0; pointer-events: none;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-2 shadow-sm" style="border-color: rgba(255,255,255,0.2);" onclick="document.querySelector('#flatpickr_input')._flatpickr.open()">
                                        <i class="fa-solid fa-calendar-plus text-brand"></i>
                                        <span id="display_selected_date">{{ date('d M Y') }}</span>
                                        <i class="fa-solid fa-chevron-down small text-muted ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Horizontal Date Strip Container -->
                            <div class="date-strip d-flex gap-2 overflow-auto pb-3 pt-1 px-1" id="date_strip">
                                <!-- JS renders upcoming 14 date cards -->
                            </div>
                        </div>

                        <label class="form-label small fw-bold text-secondary mb-3">{{ __('booking.cb_select_timeslot_header') }}</label>
                        <select name="start_time" id="start_time" class="d-none" required>
                            <option value="">{{ __('booking.cb_select_time') }}</option>
                        </select>
                        <div id="time-pills-container" class="d-flex flex-wrap gap-2 mb-4"></div>

                        <div id="slot-legend" class="mt-2 small text-muted border-top pt-3 d-flex align-items-center gap-4" style="display:none;">
                            <div><span class="slot-badge avail me-1">●</span> {{ __('booking.cb_slot_avail') }}</div>
                            <div><span class="slot-badge full me-1">●</span> {{ __('booking.cb_slot_full') }}</div>
                            <div><span class="slot-badge past me-1">●</span> {{ __('booking.cb_slot_past') }}</div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 border-top pt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 py-2 fw-semibold btn-prev rounded-pill" data-step="4"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('booking.cb_btn_back') }}</button>
                            <button type="button" class="btn btn-brand px-5 py-2 fw-bold btn-next rounded-pill shadow-sm" data-step="4">{{ __('booking.cb_btn_next_review') }} <i class="fa-solid fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- Step 5: Remarks and Confirm -->
                    <div class="booking-step" id="step-5">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-circle-check text-brand me-2"></i>{{ __('booking.cb_step_4_title') }}</h4>
                        
                        <div class="card border-0 shadow-sm p-4 bg-light rounded-4 mb-4">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">{{ __('booking.cb_summary_title') }}</h5>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="small text-muted d-block">{{ __('booking.cb_summary_branch') }}</span>
                                    <strong class="text-dark" id="summary-branch">{{ $branch->name }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="small text-muted d-block">{{ __('booking.cb_summary_service') }}</span>
                                    <strong class="text-dark" id="summary-service">N/A</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="small text-muted d-block">{{ __('booking.cb_summary_vehicle') }}</span>
                                    <strong class="text-dark" id="summary-car">N/A</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="small text-muted d-block">{{ __('booking.cb_summary_slot') }}</span>
                                    <strong class="text-dark" id="summary-schedule">N/A</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="small text-muted d-block">{{ __('booking.cb_summary_price') }}</span>
                                    <strong class="text-brand fs-6" id="summary-price">RM 0.00</strong>
                                </div>
                            </div>
                        </div>

                        @if(!auth()->user()->phone || !auth()->user()->date_of_birth)
                        <div class="card border-0 bg-light p-4 rounded-4 mb-4 border-start border-4 border-brand">
                            <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-user-check text-brand me-2"></i>{{ __('account.prog_profile_title') ?? 'Complete Your Profile Details' }}</h6>
                            <p class="small text-secondary mb-3">{{ __('account.prog_profile_desc') ?? 'Please add your phone number to receive workshop service alerts, and your birthday date to unlock 2X reward points on your birthday month!' }}</p>
                            <div class="row g-3">
                                @if(!auth()->user()->phone)
                                <div class="col-md-6">
                                    <label for="booking_phone" class="form-label small fw-bold text-secondary">{{ __('account.settings_label_phone') ?? 'Phone Number' }} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="booking_phone" class="form-control bg-white border" placeholder="e.g. 0123456789" value="{{ old('phone') }}" required>
                                </div>
                                @endif
                                @if(!auth()->user()->date_of_birth)
                                <div class="col-md-6">
                                    <label for="booking_dob" class="form-label small fw-bold text-secondary d-flex align-items-center justify-content-between">
                                        <span>{{ __('account.settings_label_dob') ?? 'Birthday Date' }}</span>
                                        <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.65rem;"><i class="fa-solid fa-cake-candles me-1"></i>{{ __('account.settings_dob_badge') ?? '2X Birthday Points' }}</span>
                                    </label>
                                    <div class="input-group flatpickr-dob-group">
                                        <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                        <input type="text" name="date_of_birth" id="booking_dob" class="form-control flatpickr-dob" placeholder="{{ __('booking.cb_select_date') ?? 'DD/MM/YYYY' }}" max="{{ date('Y-m-d') }}" value="{{ old('date_of_birth') }}" readonly>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="customer_remark" class="form-label small fw-bold text-secondary">{{ __('booking.cb_label_remark') }}</label>
                            <textarea name="customer_remark" id="customer_remark" rows="3" class="form-control bg-light border-0" placeholder="{{ __('booking.cb_remark_ph') }}">{{ old('customer_remark') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev" data-step="5"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('booking.cb_btn_back') }}</button>
                            <button type="button" class="btn btn-brand btn-lg px-5 fw-bold btn-next" data-step="5">{{ __('booking.cb_btn_next_payment') }} <i class="fa-solid fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 6: Payment Method -->
                    <div class="booking-step" id="step-6">
                        <h4 class="fw-bold mb-4"><i class="fa-solid fa-credit-card text-brand me-2"></i>{{ __('booking.cb_step_payment_title') }}</h4>
                        
                        <input type="hidden" name="payment_method" id="input-payment-method" value="counter">

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="option-card selected payment-option p-4" data-method="counter" id="pay-opt-counter">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light p-3 rounded-circle me-3 text-dark">
                                            <i class="fa-solid fa-store fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-0">{{ __('booking.pp_pay_counter') }}</h5>
                                            <span class="small text-muted">{{ __('booking.cb_pay_counter') }}</span>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">{{ __('booking.cb_pay_counter_desc') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="option-card payment-option p-4" data-method="card" id="pay-opt-card">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light p-3 rounded-circle me-3 text-success">
                                            <i class="fa-solid fa-credit-card fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-0">{{ __('booking.pp_pay_online') }}</h5>
                                            <span class="small text-muted">{{ __('booking.cb_pay_online') }}</span>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">{{ __('booking.cb_pay_online_desc') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Apply Voucher Section -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-ticket text-brand me-2"></i>{{ __('booking.cb_apply_voucher') }}</h6>
                                    <span class="small text-muted">{{ __('booking.cb_select_voucher') }}</span>
                                </div>
                            </div>

                            @if(isset($availableVouchers) && $availableVouchers->count() > 0)
                                <select name="voucher_id" id="wizardVoucherSelect" class="d-none">
                                    <option value="" data-discount="0">{{ __('booking.cb_no_voucher_opt') }}</option>
                                    @foreach($availableVouchers as $v)
                                        <option value="{{ $v->id }}" data-type="{{ $v->type }}" data-value="{{ $v->value }}">{{ $v->code }} — {{ $v->getDiscountLabel() }}</option>
                                    @endforeach
                                </select>

                                <div class="voucher-grid mb-3" id="voucherCardGrid">
                                    <div class="voucher-card active" data-id="">
                                        <div class="voucher-icon"><i class="fa-solid fa-ban"></i></div>
                                        <div class="voucher-info">
                                            <div class="voucher-title">{{ __('booking.cb_no_voucher_title') }}</div>
                                            <div class="voucher-sub">{{ __('booking.cb_no_voucher_sub') }}</div>
                                        </div>
                                        <div class="voucher-check"><i class="fa-solid fa-circle-check"></i></div>
                                    </div>
                                    @foreach($availableVouchers as $v)
                                        <div class="voucher-card" data-id="{{ $v->id }}">
                                            <div class="voucher-icon discount"><i class="fa-solid fa-ticket-simple"></i></div>
                                            <div class="voucher-info flex-grow-1 pe-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="voucher-title mb-0">{{ $v->code }}</div>
                                                    <button type="button" class="btn btn-sm btn-link p-1 text-secondary voucher-info-btn"
                                                            data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                                            data-code="{{ $v->code }}"
                                                            data-discount="{{ $v->getDiscountLabel() }}"
                                                            data-source="{{ $v->getSourceLabel() }}"
                                                            data-status="{{ __('rewards.modal_active') }}"
                                                            data-status-class="bg-success"
                                                            data-expiry="{{ $v->expires_at ? $v->expires_at->format('d M Y') : __('dashboard.rw_no_expiry') }}"
                                                            data-tnc="{{ $v->getTermsAndConditions() }}"
                                                            title="{{ __('rewards.modal_voucher_details_tnc') }}">
                                                        <i class="fa-solid fa-circle-info text-brand fs-6"></i>
                                                    </button>
                                                </div>
                                                <div class="voucher-sub text-brand fw-bold">{{ $v->getValueLabel() }} {{ __('booking.cb_voucher_discount') }}</div>
                                            </div>
                                            <div class="voucher-check"><i class="fa-solid fa-circle-check"></i></div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-secondary py-2 small mb-3 border-0">
                                    <i class="fa-solid fa-circle-info me-1"></i>{{ __('booking.cb_no_vouchers_avail') }}
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center pt-2">
                                <span class="text-secondary fw-semibold fs-6">{{ __('booking.cb_total_due') }}:</span>
                                <div class="text-end">
                                    <span id="wizardOrigPrice" class="text-muted text-decoration-line-through small me-2 d-none"></span>
                                    <h3 class="fw-bold text-brand mb-0 d-inline-block">RM <span id="wizardFinalAmount">0.00</span></h3>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev" data-step="6"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('booking.cb_btn_back') }}</button>
                            <button type="submit" class="btn btn-brand btn-lg px-5 fw-bold"><i class="fa-solid fa-check-double me-2"></i> {{ __('booking.cb_btn_confirm_submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@include('partials.flatpickr-dob-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Slot-availability data from server ──
        let slotCounts  = @json($slotCounts);          // { "2026-06-21|09:00": 2, … }
        let serviceCapacity = {{ $serviceCapacity }}; // e.g. 3
        const oldStartTime = '{{ old("start_time", "") }}';
        const slotAvailabilityUrl = '{{ route("bookings.slotAvailability") }}';

        const timeSlots = [
            { value: '09:00', label: '09:00 AM' },
            { value: '10:00', label: '10:00 AM' },
            { value: '11:00', label: '11:00 AM' },
            { value: '12:00', label: '12:00 PM' },
            { value: '13:00', label: '01:00 PM' },
            { value: '14:00', label: '02:00 PM' },
            { value: '15:00', label: '03:00 PM' },
            { value: '16:00', label: '04:00 PM' },
            { value: '17:00', label: '05:00 PM' },
        ];

        const serviceOptions = document.querySelectorAll('.service-option');
        const carOptions = document.querySelectorAll('.car-option');
        const serviceInput = document.getElementById('input-service-id');
        const carInput = document.getElementById('input-car-id');
        const nextButtons = document.querySelectorAll('.btn-next');
        const prevButtons = document.querySelectorAll('.btn-prev');
        const dateInput   = document.getElementById('booking_date');
        const timeSelect  = document.getElementById('start_time');
        const slotLegend  = document.getElementById('slot-legend');

        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentInput = document.getElementById('input-payment-method');

        paymentOptions.forEach(card => {
            card.addEventListener('click', function() {
                paymentOptions.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                paymentInput.value = this.getAttribute('data-method');
            });
        });

        // ── Render time-slot <option>s ──
        function renderTimeSlots(countsForDate) {
            const selectedDate = dateInput.value || '';
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;

            const currentVal = timeSelect.value || oldStartTime;

            timeSelect.innerHTML = '<option value="">{{ __('booking.cb_select_time') }}</option>';
            const pillsContainer = document.getElementById('time-pills-container');
            if (pillsContainer) pillsContainer.innerHTML = '';

            timeSlots.forEach(slot => {
                const opt = document.createElement('option');
                opt.value = slot.value;
                let label = slot.label;
                let disabled = false;
                let statusClass = 'avail';

                const count = countsForDate[slot.value] || 0;

                // Check if selectedDate is before today
                if (selectedDate && selectedDate < todayStr) {
                    disabled = true;
                    statusClass = 'past';
                    label += ' — ' + '{{ __("booking.cb_slot_past") }}';
                }
                // Check if slot is in the past (today only)
                else if (selectedDate === todayStr) {
                    const [h, m] = slot.value.split(':').map(Number);
                    const slotTime = new Date(yyyy, now.getMonth(), now.getDate(), h, m);
                    if (slotTime <= now) {
                        disabled = true;
                        statusClass = 'past';
                        label += ' — ' + '{{ __("booking.cb_slot_past") }}';
                    }
                }

                // Check if slot is full
                if (!disabled && count >= serviceCapacity) {
                    disabled = true;
                    statusClass = 'full';
                    label += ' — ' + '{{ __("booking.cb_slot_full") }}';
                } else if (!disabled) {
                    const remaining = serviceCapacity - count;
                    label += ` (${remaining} ${remaining !== 1 ? '{{ __("booking.cb_slot_left") }}' : '{{ __("booking.cb_slot_left_single") }}'})`;
                }

                opt.textContent = label;
                opt.disabled = disabled;
                if (slot.value === currentVal && !disabled) {
                    opt.selected = true;
                }
                timeSelect.appendChild(opt);

                // Render modern pill button
                if (pillsContainer) {
                    const pill = document.createElement('button');
                    pill.type = 'button';
                    pill.className = `btn rounded-pill px-3 py-2 small fw-semibold text-start d-flex align-items-center justify-content-between transition-all slot-pill ${disabled ? 'btn-light text-muted opacity-50 pe-none' : (slot.value === currentVal ? 'btn-dark text-white shadow-sm ring-2' : 'btn-outline-secondary bg-white')}`;
                    pill.style.minWidth = '145px';
                    
                    let pillText = slot.label;
                    if (statusClass === 'past') pillText += ' (' + @json(__('booking.cb_slot_past_badge')) + ')';
                    else if (statusClass === 'full') pillText += ' (' + @json(__('booking.cb_slot_full_badge')) + ')';
                    else {
                        const rem = serviceCapacity - count;
                        const leftStr = rem !== 1 ? @json(__('booking.cb_slot_left')) : @json(__('booking.cb_slot_left_single'));
                        pillText += ` <span class="badge ${slot.value === currentVal ? 'bg-white text-dark' : 'bg-light text-secondary'} rounded-pill ms-2 fw-normal" style="font-size:10px;">${rem} ${leftStr}</span>`;
                    }

                    if (slot.value === currentVal && !disabled) {
                        pill.innerHTML = `<i class="fa-solid fa-circle-check text-brand me-1.5"></i><span>${pillText}</span>`;
                    } else {
                        pill.innerHTML = `<span>${pillText}</span>`;
                    }

                    if (!disabled) {
                        pill.addEventListener('click', function() {
                            timeSelect.value = slot.value;
                            renderTimeSlots(countsForDate);
                        });
                    }

                    pillsContainer.appendChild(pill);
                }
            });

            if (slotLegend) slotLegend.style.display = 'flex';
        }

        // Build counts-for-date from the full slotCounts map
        function extractCountsForDate(dateStr) {
            const result = {};
            for (const key in slotCounts) {
                if (key.startsWith(dateStr + '|')) {
                    const time = key.split('|')[1];
                    result[time] = slotCounts[key];
                }
            }
            return result;
        }

        // Initial render
        renderTimeSlots(extractCountsForDate(dateInput.value));

        // ── On date change or step change: fetch fresh counts via AJAX ──
        function fetchSlotAvailability() {
            const selectedDate = dateInput.value;
            if (!selectedDate) return;

            const branchEl = document.getElementById('input-branch-id');
            const serviceEl = document.getElementById('input-service-id');
            const branchVal = branchEl ? branchEl.value : '';
            const serviceVal = serviceEl ? serviceEl.value : '';
            fetch(slotAvailabilityUrl + '?date=' + encodeURIComponent(selectedDate) + '&branch_id=' + encodeURIComponent(branchVal) + '&service_id=' + encodeURIComponent(serviceVal), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                timeSlots.forEach(slot => {
                    delete slotCounts[selectedDate + '|' + slot.value];
                });
                for (const time in data) {
                    slotCounts[selectedDate + '|' + time] = data[time];
                }
                renderTimeSlots(data);
            })
            .catch(() => {
                renderTimeSlots(extractCountsForDate(selectedDate));
            });
        }

        dateInput.addEventListener('change', fetchSlotAvailability);

        // ── Big Tech / Apple Style Interactive Date Strip & Calendar Picker ──
        const dateStrip = document.getElementById('date_strip');
        const displaySelectedDate = document.getElementById('display_selected_date');
        const flatpickrInput = document.getElementById('flatpickr_input');

        const monthsShort = [
            @json(__('booking.m_jan')), @json(__('booking.m_feb')), @json(__('booking.m_mar')), @json(__('booking.m_apr')),
            @json(__('booking.m_may')), @json(__('booking.m_jun')), @json(__('booking.m_jul')), @json(__('booking.m_aug')),
            @json(__('booking.m_sep')), @json(__('booking.m_oct')), @json(__('booking.m_nov')), @json(__('booking.m_dec'))
        ];
        const daysShort = [
            @json(__('booking.d_sun')), @json(__('booking.d_mon')), @json(__('booking.d_tue')), @json(__('booking.d_wed')),
            @json(__('booking.d_thu')), @json(__('booking.d_fri')), @json(__('booking.d_sat'))
        ];

        function initDateSelector() {
            const initialDateStr = dateInput.value || new Date().toISOString().split('T')[0];
            
            // Initialize Flatpickr
            if (window.flatpickr && flatpickrInput) {
                flatpickrInput._flatpickr = flatpickr(flatpickrInput, {
                    defaultDate: initialDateStr,
                    minDate: "today",
                    maxDate: new Date().fp_incr(30),
                    dateFormat: "Y-m-d",
                    disableMobile: true,
                    monthSelectorType: "static",
                    locale: {
                        weekdays: {
                            shorthand: daysShort,
                            longhand: daysShort
                        },
                        months: {
                            shorthand: monthsShort,
                            longhand: monthsShort
                        }
                    },
                    onChange: function(selectedDates, dateStr) {
                        selectCustomDate(dateStr);
                    }
                });
            }

            renderDateStrip(initialDateStr);
            updateDisplayDate(initialDateStr);
        }

        function updateDisplayDate(dateStr) {
            if (!displaySelectedDate || !dateStr) return;
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                const y = parseInt(parts[0], 10);
                const m = parseInt(parts[1], 10) - 1;
                const d = parseInt(parts[2], 10);
                displaySelectedDate.textContent = `${d} ${monthsShort[m]} ${y}`;
            }
        }

        function renderDateStrip(selectedDateStr) {
            if (!dateStrip) return;
            dateStrip.innerHTML = '';
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let i = 0; i <= 30; i++) {
                const cur = new Date(today);
                cur.setDate(today.getDate() + i);

                const yyyy = cur.getFullYear();
                const mm = String(cur.getMonth() + 1).padStart(2, '0');
                const dd = String(cur.getDate()).padStart(2, '0');
                const dateStr = `${yyyy}-${mm}-${dd}`;

                const monthName = monthsShort[cur.getMonth()];
                const dayNum = cur.getDate();
                let weekdayName = daysShort[cur.getDay()];
                if (i === 0) weekdayName = @json(__('booking.d_today'));

                const card = document.createElement('div');
                card.className = `date-card ${dateStr === selectedDateStr ? 'active' : ''}`;
                card.dataset.date = dateStr;
                card.innerHTML = `
                    <span class="date-month">${monthName}</span>
                    <span class="date-day">${dayNum}</span>
                    <span class="date-weekday">${weekdayName}</span>
                `;

                card.addEventListener('click', () => selectCustomDate(dateStr));
                dateStrip.appendChild(card);
            }

            setTimeout(() => {
                const activeCard = dateStrip.querySelector('.date-card.active');
                if (activeCard) {
                    activeCard.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            }, 50);
        }

        function selectCustomDate(dateStr) {
            if (dateInput.value !== dateStr) {
                dateInput.value = dateStr;
                dateInput.dispatchEvent(new Event('change'));
            }
            updateDisplayDate(dateStr);

            // Update strip highlights
            if (dateStrip) {
                let targetCard = dateStrip.querySelector(`.date-card[data-date="${dateStr}"]`);
                if (!targetCard) {
                    renderDateStrip(dateStr);
                    targetCard = dateStrip.querySelector(`.date-card[data-date="${dateStr}"]`);
                } else {
                    dateStrip.querySelectorAll('.date-card').forEach(card => {
                        card.classList.toggle('active', card.dataset.date === dateStr);
                    });
                }
                if (targetCard) {
                    setTimeout(() => {
                        targetCard.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }, 50);
                }
            }

            // Sync Flatpickr
            if (flatpickrInput && flatpickrInput._flatpickr) {
                const curSel = flatpickrInput._flatpickr.selectedDates[0];
                const curStr = curSel ? `${curSel.getFullYear()}-${String(curSel.getMonth()+1).padStart(2,'0')}-${String(curSel.getDate()).padStart(2,'0')}` : '';
                if (curStr !== dateStr) {
                    flatpickrInput._flatpickr.setDate(dateStr, false);
                }
            }
        }

        // Hook into change event from dateInput if modified programmatically elsewhere
        dateInput.addEventListener('change', function() {
            updateDisplayDate(this.value);
            if (dateStrip) {
                dateStrip.querySelectorAll('.date-card').forEach(card => {
                    card.classList.toggle('active', card.dataset.date === this.value);
                });
            }
        });

        initDateSelector();

        // ── 3-Level Hierarchical Service Selector ──
        const catalogDict = @json($catalog);
        let activeCategory = null;
        let activeBrand = null;

        document.querySelectorAll('.cat-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.cat-btn').forEach(b => {
                    b.classList.remove('btn-dark', 'text-white');
                    b.classList.add('btn-outline-dark', 'bg-white', 'text-dark');
                });
                this.classList.remove('btn-outline-dark', 'bg-white', 'text-dark');
                this.classList.add('btn-dark', 'text-white');

                activeCategory = this.getAttribute('data-category');
                activeBrand = null;

                const brandSection = document.getElementById('brand-section');
                const brandSelector = document.getElementById('brand-selector');
                brandSelector.innerHTML = '';

                if (catalogDict[activeCategory]) {
                    catalogDict[activeCategory].forEach(bName => {
                        const bSlug = bName.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                        const words = bName.split(/\s+|-/);
                        const abbr  = words.length >= 2 ? (words[0][0] + words[1][0]).toUpperCase() : bName.substring(0, bName.length <= 3 ? bName.length : 2).toUpperCase();

                        const bBtn = document.createElement('button');
                        bBtn.type = 'button';
                        bBtn.className = 'btn btn-outline-secondary bg-white text-dark rounded-pill ps-2 pe-3 py-1 brand-btn small fw-bold d-inline-flex align-items-center gap-2 shadow-sm border';
                        bBtn.setAttribute('data-brand', bName);
                        bBtn.innerHTML = `
                            <span class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 bg-light shadow-sm" style="width: 28px; height: 28px; border: 1px solid rgba(0,0,0,0.08);">
                                <img src="/images/brands/${bSlug}.png" alt="${bName}" class="w-100 h-100 p-0.5" style="object-fit: contain;" onerror="this.outerHTML='<span class=\\'fw-bolder\\' style=\\'font-size:0.68rem;color:#E61E25;\\'>${abbr}</span>'">
                            </span>
                            <span>${bName}</span>
                        `;
                        bBtn.addEventListener('click', () => selectBrandHandler(bName, bBtn));
                        brandSelector.appendChild(bBtn);
                    });
                }
                brandSection.style.display = 'block';
                document.getElementById('item-section').style.display = 'none';
                
                document.querySelectorAll('.service-option').forEach(c => c.classList.remove('selected'));
                serviceInput.value = '';
                document.getElementById('step2NextBtn').disabled = true;
            });
        });

        let activeSub = 'All';

        function applyServiceSpecFilters() {
            let visibleCount = 0;
            const currentBranchId = document.getElementById('input-branch-id').value;
            const maxP = parseFloat(document.getElementById('price-range-input').value) || 999999;

            document.querySelectorAll('.service-card-wrapper').forEach(wrapper => {
                const itemCat    = wrapper.getAttribute('data-category');
                const itemBrand  = wrapper.getAttribute('data-brand');
                const itemBranch = wrapper.getAttribute('data-branch');
                const itemSub    = wrapper.getAttribute('data-sub');
                const itemPrice  = parseFloat(wrapper.getAttribute('data-price')) || 0;

                const branchMatch = (!itemBranch || itemBranch === currentBranchId);
                const catMatch    = (itemCat === activeCategory);
                const brandMatch  = (itemBrand === activeBrand);
                const subMatch    = (!activeSub || activeSub === 'All' || itemSub === activeSub);
                const priceMatch  = (itemPrice <= maxP);

                if (branchMatch && catMatch && brandMatch && subMatch && priceMatch) {
                    wrapper.style.display = 'block';
                    visibleCount++;
                } else {
                    wrapper.style.display = 'none';
                }
            });

            const noItemsMsg = document.getElementById('no-brand-items-msg');
            if (visibleCount === 0) {
                if (!noItemsMsg) {
                    const msg = document.createElement('div');
                    msg.id = 'no-brand-items-msg';
                    msg.className = 'col-12 text-center py-4 text-muted fst-italic';
                    msg.innerHTML = '<i class="fa-solid fa-box-open mb-2 fs-4 d-block"></i>No specific service items match the selected filter criteria. Please try adjusting the filter or selecting another brand.';
                    document.getElementById('services-container').appendChild(msg);
                } else {
                    noItemsMsg.style.display = 'block';
                }
            } else if (noItemsMsg) {
                noItemsMsg.style.display = 'none';
            }

            // Recalculate Most Popular badge after visibility changes
            recalcWizardPopularBadge();
        }

        const priceRangeInputElem = document.getElementById('price-range-input');
        if (priceRangeInputElem) {
            priceRangeInputElem.addEventListener('input', function() {
                const valElem = document.getElementById('price-range-val');
                if (valElem) valElem.textContent = 'RM ' + Number(this.value).toLocaleString();
                applyServiceSpecFilters();
            });
        }

        function selectBrandHandler(brandName, btnElement) {
            document.querySelectorAll('.brand-btn').forEach(b => {
                b.classList.remove('btn-brand', 'text-white');
                b.classList.add('btn-outline-secondary', 'bg-white', 'text-dark');
            });
            btnElement.classList.remove('btn-outline-secondary', 'bg-white', 'text-dark');
            btnElement.classList.add('btn-brand', 'text-white');

            activeBrand = brandName;
            activeSub   = 'All';

            const subLabel = document.getElementById('filter-sub-label');
            const subPills = document.getElementById('filter-sub-pills');
            if (subPills) subPills.innerHTML = '';

            let subOptions = ['All'];
            if (activeCategory === 'Tyres') {
                if (subLabel) subLabel.textContent = 'Tyre Size:';
                subOptions = ['All', '15"', '16"', '17"', '18"', '19"'];
            } else if (activeCategory === 'Tinting Films' || activeCategory === 'Car Mats') {
                if (subLabel) subLabel.textContent = 'Vehicle Type:';
                subOptions = ['All', 'Sedan', 'SUV', 'MPV', 'Hatchback'];
            } else if (activeCategory === 'Maintenance') {
                if (subLabel) subLabel.textContent = 'Service Type:';
                subOptions = ['All', 'Fully Synthetic', 'Semi Synthetic', 'Mineral Oil', 'Inspection'];
            } else if (activeCategory === 'Dashcams') {
                if (subLabel) subLabel.textContent = 'Camera Setup:';
                subOptions = ['All', 'Front Only', 'Front & Rear', '4K Ultra HD'];
            } else if (activeCategory === 'Wipers') {
                if (subLabel) subLabel.textContent = 'Blade Type:';
                subOptions = ['All', 'Silicone Blade', 'Aero Flat Blade', 'Standard'];
            } else {
                if (subLabel) subLabel.textContent = 'Specification:';
                subOptions = ['All', 'Standard', 'Premium'];
            }

            if (subPills) {
                subOptions.forEach(opt => {
                    const pBtn = document.createElement('button');
                    pBtn.type = 'button';
                    pBtn.className = `btn btn-sm rounded-pill px-3 py-1 small fw-bold ${opt === 'All' ? 'btn-dark text-white' : 'btn-outline-secondary bg-white text-dark'}`;
                    pBtn.textContent = opt;
                    pBtn.addEventListener('click', () => {
                        subPills.querySelectorAll('button').forEach(b => {
                            b.className = 'btn btn-sm btn-outline-secondary bg-white text-dark rounded-pill px-3 py-1 small fw-bold';
                        });
                        pBtn.className = 'btn btn-sm btn-dark text-white rounded-pill px-3 py-1 small fw-bold';
                        activeSub = opt;
                        applyServiceSpecFilters();
                    });
                    subPills.appendChild(pBtn);
                });
            }

            const pInput = document.getElementById('price-range-input');
            const pVal   = document.getElementById('price-range-val');
            if (pInput && pVal) {
                pInput.value = 2000;
                pVal.textContent = 'RM 2,000';
            }

            document.querySelectorAll('.service-option').forEach(c => c.classList.remove('selected'));
            serviceInput.value = '';
            document.getElementById('step2NextBtn').disabled = true;

            applyServiceSpecFilters();
            document.getElementById('item-section').style.display = 'block';
        }

        // Branch option clicks
        const branchOptions = document.querySelectorAll('.branch-option');
        const branchInput = document.getElementById('input-branch-id');
        const summaryBranch = document.getElementById('summary-branch');

        branchOptions.forEach(card => {
            card.addEventListener('click', function() {
                branchOptions.forEach(c => {
                    c.classList.remove('selected');
                    const chk = c.querySelector('.check-icon');
                    if (chk) chk.style.display = 'none';
                });
                this.classList.add('selected');
                const myChk = this.querySelector('.check-icon');
                if (myChk) myChk.style.display = 'inline-block';

                branchInput.value = this.dataset.id;
                if (summaryBranch) summaryBranch.textContent = this.dataset.name;
                if (this.dataset.capacity) {
                    serviceCapacity = parseInt(this.dataset.capacity, 10) || serviceCapacity;
                }

                // Reset service selections when switching branch
                activeCategory = null;
                activeBrand = null;
                document.querySelectorAll('.cat-btn').forEach(b => {
                    b.classList.remove('btn-dark', 'text-white');
                    b.classList.add('btn-outline-dark');
                });
                document.getElementById('brand-section').style.display = 'none';
                document.getElementById('item-section').style.display = 'none';
                serviceOptions.forEach(c => c.classList.remove('selected'));
                serviceInput.value = '';
                document.getElementById('step2NextBtn').disabled = true;
            });
        });

        // ── Service-Specific Options Panel helpers ──────────────────────────────
        const optionsPanel  = document.getElementById('service-options-panel');
        const optionsInputs = document.getElementById('service-options-inputs');

        function renderServiceOptions(bookingOptions) {
            if (!optionsInputs) return;
            optionsInputs.innerHTML = '';

            if (!bookingOptions || bookingOptions.length === 0) {
                if (optionsPanel) optionsPanel.style.display = 'none';
                return;
            }

            if (optionsPanel) optionsPanel.style.display = 'block';

            bookingOptions.forEach(option => {
                const key      = option.key;
                const label    = option.label || key;
                const type     = option.type || 'select';
                const required = !!option.required;
                const choices  = option.choices || [];

                const wrapper = document.createElement('div');
                wrapper.className = 'mb-3';

                const labelEl = document.createElement('label');
                labelEl.className = 'form-label small fw-bold text-secondary';
                labelEl.textContent = label + (required ? ' *' : '');
                wrapper.appendChild(labelEl);

                if (type === 'select') {
                    const selectContainer = document.createElement('div');
                    selectContainer.className = 'custom-dropdown-container position-relative';
                    
                    // The hidden real select (keeps existing validation & price logic working)
                    const sel = document.createElement('select');
                    sel.name = `booking_options[${key}]`;
                    sel.className = 'd-none'; // HIDE IT
                    if (required) sel.required = true;

                    const placeholder = document.createElement('option');
                    placeholder.value = '';
                    placeholder.textContent = '— Select —';
                    sel.appendChild(placeholder);
                    
                    // The visible display box (Glassmorphism style)
                    const displayBox = document.createElement('div');
                    displayBox.className = 'form-control bg-dark text-white border-secondary shadow-sm d-flex justify-content-between align-items-center';
                    displayBox.style.cursor = 'pointer';
                    displayBox.style.backdropFilter = 'blur(10px)';
                    displayBox.style.background = 'rgba(33, 37, 41, 0.7)';
                    
                    const displayText = document.createElement('span');
                    displayText.textContent = '— Select —';
                    
                    const displayIcon = document.createElement('i');
                    displayIcon.className = 'fa-solid fa-chevron-down text-muted';
                    displayIcon.style.transition = 'transform 0.2s';
                    
                    displayBox.appendChild(displayText);
                    displayBox.appendChild(displayIcon);
                    
                    // The custom dropdown menu
                    const dropdownMenu = document.createElement('div');
                    dropdownMenu.className = 'position-absolute w-100 mt-1 rounded shadow-lg overflow-hidden';
                    dropdownMenu.style.display = 'none';
                    dropdownMenu.style.background = 'rgba(25, 25, 25, 0.95)';
                    dropdownMenu.style.backdropFilter = 'blur(12px)';
                    dropdownMenu.style.border = '1px solid rgba(255,255,255,0.1)';
                    dropdownMenu.style.maxHeight = '250px';
                    dropdownMenu.style.overflowY = 'auto';
                    dropdownMenu.style.zIndex = '1050';

                    choices.forEach(c => {
                        // Native option
                        const opt = document.createElement('option');
                        opt.value = c.value;
                        const optText = c.label + (c.price_add > 0 ? ` (+RM ${parseFloat(c.price_add).toFixed(2)})` : '');
                        opt.textContent = optText;
                        opt.dataset.priceAdd = c.price_add || 0;
                        sel.appendChild(opt);
                        
                        // Custom dropdown item
                        const item = document.createElement('div');
                        item.className = 'p-2 px-3 text-white border-bottom';
                        item.style.borderColor = 'rgba(255,255,255,0.05) !important';
                        item.style.cursor = 'pointer';
                        item.style.transition = 'background 0.2s';
                        item.textContent = optText;
                        
                        item.addEventListener('mouseenter', () => item.style.background = 'rgba(255,255,255,0.1)');
                        item.addEventListener('mouseleave', () => item.style.background = 'transparent');
                        
                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            sel.value = c.value;
                            displayText.textContent = optText;
                            dropdownMenu.style.display = 'none';
                            displayIcon.style.transform = 'rotate(0deg)';
                            displayBox.classList.remove('border-danger'); // Remove error border if any
                            sel.dispatchEvent(new Event('change')); // Trigger price/button logic
                            updateStep2NextBtn(); // Force button update
                        });
                        
                        dropdownMenu.appendChild(item);
                    });

                    // Toggle logic
                    displayBox.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const isOpen = dropdownMenu.style.display === 'block';
                        
                        // Close all other custom dropdowns
                        document.querySelectorAll('.custom-dropdown-container > div:last-child').forEach(m => m.style.display = 'none');
                        document.querySelectorAll('.custom-dropdown-container .fa-chevron-down').forEach(i => i.style.transform = 'rotate(0deg)');
                        
                        if (!isOpen) {
                            dropdownMenu.style.display = 'block';
                            displayIcon.style.transform = 'rotate(180deg)';
                        }
                    });
                    
                    // Close on outside click
                    document.addEventListener('click', () => {
                        if (dropdownMenu.style.display === 'block') {
                            dropdownMenu.style.display = 'none';
                            displayIcon.style.transform = 'rotate(0deg)';
                        }
                    });

                    sel.addEventListener('change', () => updateOptionsPrice());
                    
                    selectContainer.appendChild(sel);
                    selectContainer.appendChild(displayBox);
                    selectContainer.appendChild(dropdownMenu);
                    
                    wrapper.appendChild(selectContainer);

                } else if (type === 'radio') {
                    const radioGroup = document.createElement('div');
                    radioGroup.className = 'd-flex flex-wrap gap-2';

                    choices.forEach(c => {
                        const id = `opt_${key}_${c.value}`;
                        const radioWrapper = document.createElement('div');
                        radioWrapper.className = 'form-check form-check-inline';

                        const radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = `booking_options[${key}]`;
                        radio.value = c.value;
                        radio.id = id;
                        radio.className = 'form-check-input';
                        radio.dataset.priceAdd = c.price_add || 0;
                        if (required) radio.required = true;
                        radio.addEventListener('change', () => updateOptionsPrice());

                        const radioLabel = document.createElement('label');
                        radioLabel.htmlFor = id;
                        radioLabel.className = 'form-check-label small';
                        radioLabel.textContent = c.label + (c.price_add > 0 ? ` (+RM ${parseFloat(c.price_add).toFixed(2)})` : '');

                        radioWrapper.appendChild(radio);
                        radioWrapper.appendChild(radioLabel);
                        radioGroup.appendChild(radioWrapper);
                    });

                    wrapper.appendChild(radioGroup);

                } else if (type === 'number') {
                    const numInput = document.createElement('input');
                    numInput.type = 'number';
                    numInput.name = `booking_options[${key}]`;
                    numInput.className = 'form-control bg-dark text-white border-secondary shadow-sm';
                    numInput.min = option.min ?? 1;
                    numInput.max = option.max ?? 99;
                    numInput.value = option.default ?? '';
                    if (required) numInput.required = true;
                    numInput.addEventListener('input', () => updateOptionsPrice());
                    wrapper.appendChild(numInput);
                }

                optionsInputs.appendChild(wrapper);
            });

            updateOptionsPrice();
            updateStep2NextBtn();
        }

        function getOptionPriceModifier() {
            let modifier = 0;
            if (!optionsInputs) return modifier;

            // Select elements
            optionsInputs.querySelectorAll('select').forEach(sel => {
                const opt = sel.options[sel.selectedIndex];
                if (opt) modifier += parseFloat(opt.dataset.priceAdd || 0);
            });

            // Radio elements
            optionsInputs.querySelectorAll('input[type="radio"]:checked').forEach(r => {
                modifier += parseFloat(r.dataset.priceAdd || 0);
            });

            return modifier;
        }

        function updateOptionsPrice() {
            const selectedCard = document.querySelector('.service-option.selected');
            if (!selectedCard) return;

            const basePrice = parseFloat(selectedCard.dataset.price) || 0;
            const modifier  = getOptionPriceModifier();
            const adjusted  = basePrice + modifier;

            // Update the price badge on the selected card
            const priceBadge = selectedCard.querySelector('.badge.bg-danger-subtle');
            if (priceBadge) priceBadge.textContent = 'RM ' + adjusted.toFixed(2);

            // Update summary and voucher computation
            selectedCard.dataset.adjustedPrice = adjusted;
            updateStep2NextBtn();
        }

        function updateStep2NextBtn() {
            const nextBtn = document.getElementById('step2NextBtn');
            if (!nextBtn || !optionsInputs) return;

            // Check all required option inputs are filled
            let allFilled = true;
            optionsInputs.querySelectorAll('[required]').forEach(el => {
                if (el.type === 'radio') {
                    const group = optionsInputs.querySelectorAll(`input[name="${el.name}"]`);
                    const anyChecked = [...group].some(r => r.checked);
                    if (!anyChecked) allFilled = false;
                } else if (!el.value || el.value.trim() === '') {
                    allFilled = false;
                }
            });

            nextBtn.disabled = !allFilled;
        }
        // ── End options panel helpers ────────────────────────────────────────────

        // ── Most Popular Badge (Wizard) ──────────────────────────────────────────
        function recalcWizardPopularBadge() {
            // Clear all existing badges first
            document.querySelectorAll('.service-card-wrapper .badge-popular-wizard')
                .forEach(b => b.classList.add('d-none'));

            const visibleWrappers = [...document.querySelectorAll('.service-card-wrapper')]
                .filter(w => w.style.display !== 'none');

            if (visibleWrappers.length < 3) return;

            let maxCount = 0;
            let maxWrapper = null;
            let tie = false;

            visibleWrappers.forEach(w => {
                const count = parseInt(w.dataset.bookingCount) || 0;
                if (count > maxCount) {
                    maxCount = count;
                    maxWrapper = w;
                    tie = false;
                } else if (count === maxCount && maxCount > 0) {
                    tie = true;
                }
            });

            if (!tie && maxCount >= 10 && maxWrapper) {
                const badge = maxWrapper.querySelector('.badge-popular-wizard');
                if (badge) badge.classList.remove('d-none');
            }
        }
        // ── End Most Popular Badge (Wizard) ─────────────────────────────────────

        // Service card clicks
        serviceOptions.forEach(card => {
            card.addEventListener('click', function() {
                serviceOptions.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                serviceInput.value = this.dataset.id;

                // Parse booking_options from the card's data attribute
                let bookingOpts = [];
                try {
                    bookingOpts = JSON.parse(this.dataset.bookingOptions || '[]');
                } catch (e) {
                    bookingOpts = [];
                }

                // Render options panel (or hide if none)
                renderServiceOptions(bookingOpts);

                // Enable next button immediately only when no required options exist
                const hasRequiredOptions = bookingOpts.some(o => o.required);
                document.getElementById('step2NextBtn').disabled = hasRequiredOptions;

                if (dateInput && dateInput.value) dateInput.dispatchEvent(new Event('change'));
            });
        });

        carOptions.forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.car-option').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                carInput.value = this.dataset.id;
                const nextBtn = document.getElementById('step3NextBtn');
                if (nextBtn) nextBtn.disabled = false;
            });
        });

        // AJAX Add Vehicle Handler
        const addCarForm = document.querySelector('#addCarModal form');
        if (addCarForm) {
            addCarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.car) {
                        const modalEl = document.getElementById('addCarModal');
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();

                        const placeholder = document.getElementById('noCarsPlaceholder');
                        if (placeholder) placeholder.remove();

                        const container = document.getElementById('carsListContainer');
                        document.querySelectorAll('.car-option').forEach(c => c.classList.remove('selected'));

                        const col = document.createElement('div');
                        col.className = 'col-md-6';
                        col.innerHTML = `
                            <div class="option-card car-option selected" data-id="${data.car.id}" data-name="${data.car.brand} ${data.car.model} [${data.car.car_plate}]">
                                <h5 class="fw-bold text-dark mb-2">${data.car.brand} ${data.car.model}</h5>
                                <div class="badge bg-dark mb-2">${data.car.car_plate}</div>
                                <div class="small text-muted"><i class="fa-solid fa-gauge-high me-1"></i> Mileage ${Number(data.car.mileage).toLocaleString()} km</div>
                                <div class="small text-muted"><i class="fa-solid fa-calendar me-1"></i> Year ${data.car.year}</div>
                            </div>
                        `;
                        container.appendChild(col);

                        const newCard = col.querySelector('.car-option');
                        newCard.addEventListener('click', function() {
                            document.querySelectorAll('.car-option').forEach(c => c.classList.remove('selected'));
                            this.classList.add('selected');
                            carInput.value = this.dataset.id;
                            const nextBtn = document.getElementById('step3NextBtn');
                            if (nextBtn) nextBtn.disabled = false;
                        });

                        carInput.value = data.car.id;
                        const nextBtn = document.getElementById('step3NextBtn');
                        if (nextBtn) nextBtn.disabled = false;
                        addCarForm.reset();
                    }
                })
                .catch(err => alert('{{ __("booking.cb_err_add_vehicle") }}'));
            });
        }

        // ── Wizard step navigation ──
        nextButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = parseInt(this.dataset.step);

                if (currentStep === 1 && !branchInput.value) {
                    alert('{{ __("booking.cb_err_no_branch") }}');
                    return;
                }
                if (currentStep === 2 && !serviceInput.value) {
                    alert('{{ __("booking.cb_err_no_service") }}');
                    return;
                }
                if (currentStep === 3 && !carInput.value) {
                    alert('{{ __("booking.cb_err_no_vehicle") }}');
                    return;
                }
                if (currentStep === 4) {
                    const dateVal = dateInput.value;
                    const timeVal = timeSelect.value;
                    if (!dateVal || !timeVal) {
                        alert('{{ __("booking.cb_err_no_date") }}');
                        return;
                    }
                }

                if (currentStep === 4) {
                    updateSummary();
                }

                goToStep(currentStep + 1);
            });
        });

        prevButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = parseInt(this.dataset.step);
                goToStep(currentStep - 1);
            });
        });

        function goToStep(stepNumber) {
            document.querySelectorAll('.booking-step').forEach(step => {
                step.classList.remove('active');
            });
            document.getElementById(`step-${stepNumber}`).classList.add('active');

            document.querySelectorAll('.step-dot').forEach((dot, idx) => {
                const dotStep = idx + 1;
                dot.classList.remove('active', 'completed');
                if (dotStep < stepNumber) {
                    dot.classList.add('completed');
                } else if (dotStep === stepNumber) {
                    dot.classList.add('active');
                }
            });

            if (stepNumber === 3 && {{ count($cars) }} === 0) {
                const addCarEl = document.getElementById('addCarModal');
                if (addCarEl && typeof bootstrap !== 'undefined') {
                    new bootstrap.Modal(addCarEl).show();
                }
            }

            if (stepNumber === 4) {
                fetchSlotAvailability();
            }
        }

        function updateSummary() {
            const selectedService = document.querySelector('.service-option.selected');
            const selectedCar = document.querySelector('.car-option.selected');
            const dateVal = dateInput.value;
            const timeVal = timeSelect.value;

            document.getElementById('summary-service').innerText = selectedService ? selectedService.dataset.name : 'N/A';
            document.getElementById('summary-car').innerText = selectedCar ? selectedCar.dataset.name : 'N/A';
            document.getElementById('summary-schedule').innerText = dateVal + ' at ' + timeVal;
            
            // Use adjustedPrice if options have been selected, otherwise fall back to base price
            let basePrice = selectedService
                ? (parseFloat(selectedService.dataset.adjustedPrice) || parseFloat(selectedService.dataset.price) || 0)
                : 0;
            const priceEl = document.getElementById('summary-price');
            if (priceEl) priceEl.innerText = 'RM ' + basePrice.toFixed(2);
            
            updateWizardVoucherComputation();
        }

        function updateWizardVoucherComputation() {
            const selectedService = document.querySelector('.service-option.selected');
            // Use adjustedPrice if options have been selected, otherwise fall back to base price
            let basePrice = selectedService
                ? (parseFloat(selectedService.dataset.adjustedPrice) || parseFloat(selectedService.dataset.price) || 0)
                : 0;
            const vSelect = document.getElementById('wizardVoucherSelect');
            const origPriceEl = document.getElementById('wizardOrigPrice');
            const finalPriceEl = document.getElementById('wizardFinalAmount');
            if (!finalPriceEl) return;
            
            let disc = 0;
            if (vSelect && vSelect.selectedIndex > 0) {
                const opt = vSelect.options[vSelect.selectedIndex];
                const type = opt.getAttribute('data-type');
                const val = parseFloat(opt.getAttribute('data-value')) || 0;
                if (type === 'fixed') {
                    disc = val;
                } else {
                    disc = Math.round((basePrice * (val / 100)) * 100) / 100;
                }
                disc = Math.min(disc, basePrice);
            }
            
            let finalAmt = Math.max(0, basePrice - disc);
            finalPriceEl.textContent = finalAmt.toFixed(2);
            
            if (disc > 0 && origPriceEl) {
                origPriceEl.textContent = 'RM ' + basePrice.toFixed(2);
                origPriceEl.classList.remove('d-none');
            } else if (origPriceEl) {
                origPriceEl.classList.add('d-none');
            }
        }

        const wizVoucherSel = document.getElementById('wizardVoucherSelect');
        const voucherCardGrid = document.getElementById('voucherCardGrid');
        if (wizVoucherSel) {
            wizVoucherSel.addEventListener('change', updateWizardVoucherComputation);
        }
        if (voucherCardGrid && wizVoucherSel) {
            voucherCardGrid.addEventListener('click', function(e) {
                if (e.target.closest('.voucher-info-btn')) return;
                const card = e.target.closest('.voucher-card');
                if (!card) return;
                voucherCardGrid.querySelectorAll('.voucher-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                wizVoucherSel.value = card.dataset.id;
                wizVoucherSel.dispatchEvent(new Event('change'));
            });
        }
    });

    // Auto-select Category -> Brand -> Card when arriving from Book Now or validation error
    @php
        $targetId = old('service_id', $preselectedServiceId ?? '');
    @endphp
    @if(!empty($targetId))
    (function() {
        const targetId = '{{ $targetId }}';
        const targetCard = document.querySelector(`.service-option[data-id="${targetId}"]`);
        if (targetCard) {
            const wrapper = targetCard.closest('.service-card-wrapper');
            if (wrapper) {
                const cat = wrapper.getAttribute('data-category');
                const brand = wrapper.getAttribute('data-brand');
                
                const catBtn = document.querySelector(`.cat-btn[data-category="${cat}"]`);
                if (catBtn) catBtn.click();

                setTimeout(() => {
                    const brandBtn = document.querySelector(`.brand-btn[data-brand="${brand}"]`);
                    if (brandBtn) brandBtn.click();

                    setTimeout(() => {
                        targetCard.click();
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetCard.style.transition = 'box-shadow 0.4s';
                        targetCard.style.boxShadow = '0 0 0 4px rgba(236,31,36,0.3)';
                        setTimeout(() => { targetCard.style.boxShadow = ''; }, 1200);
                    }, 100);
                }, 100);
            }
        }
    })();
    @endif
</script>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="addCarModalLabel"><i class="fa-solid fa-car me-2 text-brand"></i>{{ __('dashboard.cars_add_new') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cars.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white text-start">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="brand" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_brand_name') }}</label>
                            <input type="text" name="brand" id="brand" class="form-control bg-light border-0 py-2" placeholder="{{ __('booking.bc_brand_ph') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="model" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_model_name') }}</label>
                            <input type="text" name="model" id="model" class="form-control bg-light border-0 py-2" placeholder="{{ __('booking.bc_model_ph') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="year" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_manufacture_year') }}</label>
                            <input type="number" name="year" id="year" class="form-control bg-light border-0 py-2" placeholder="2020" min="1900" max="{{ date('Y') + 1 }}" required>
                        </div>
                        <div class="col-6">
                            <label for="car_plate" class="form-label small fw-bold text-secondary">{{ __('dashboard.cars_plate_number') }}</label>
                            <input type="text" name="car_plate" id="car_plate" class="form-control bg-light border-0 py-2 text-uppercase" placeholder="{{ __('booking.bc_plate_ph') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mileage" class="form-label small fw-bold text-secondary">{{ __('booking.bc_mileage') }}</label>
                        <input type="number" name="mileage" id="mileage" class="form-control bg-light border-0 py-2" placeholder="45000" min="0" required>
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="bookingAddIsDefault" checked>
                        <label class="form-check-label small fw-semibold text-dark" for="bookingAddIsDefault">
                            {{ __('booking.bc_set_default') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('booking.bc_cancel') }}</button>
                    <button type="submit" class="btn btn-brand rounded-pill px-4">{{ __('dashboard.cars_register') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('partials.voucher-tnc-modal')
@endsection
