@extends('layouts.app')

@section('styles')
<style>
/* ===== Page-scoped overrides — dark BG edge-to-edge ===== */
body { background-color: #0a0a0a !important; }
main { background-color: #0a0a0a !important; }
.footer-custom { margin-top: 0 !important; }

/* ===== BRANCH-DARK — page-scoped dark theme ===== */
.branch-dark {
    background-color: #0a0a0a;
    color: #e4e4e7;
    min-height: 100vh;
}

.branch-dark .text-red { color: #e5322d !important; }
.branch-dark .text-muted-l { color: #9ca3af !important; }

.branch-dark .branch-detail-hero {
    background: linear-gradient(135deg, rgba(10, 10, 10, 0.95) 0%, rgba(10, 10, 10, 0.8) 100%),
                url('https://img.magnific.com/free-vector/blue-abstract-background_1393-339.jpg?semt=ais_hybrid&w=740&q=80') no-repeat center center;
    background-size: cover;
    padding: 80px 0;
    color: #ffffff;
}

.branch-dark .info-card {
    background-color: #161616;
    border: 1px solid #262626;
    border-radius: 14px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: box-shadow .3s, border-color .3s;
}
.branch-dark .info-card:hover {
    border-color: rgba(229,50,45,.3);
    box-shadow: 0 10px 25px rgba(229,50,45,.15);
}

.branch-dark .info-card .card-header-bar {
    height: 4px;
    background: #e5322d;
}

.branch-dark .hours-block {
    background-color: #111;
    border: 1px solid #262626;
    border-radius: 12px;
    padding: 20px 24px;
    text-align: center;
    flex: 1;
}

.branch-dark .hours-block .time {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
}

.branch-dark .hours-block .label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #9ca3af;
    margin-bottom: 4px;
}

.branch-dark .contact-row {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 0;
}

.branch-dark .contact-row:not(:last-child) {
    border-bottom: 1px solid #262626;
}

.branch-dark .contact-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background-color: rgba(229,50,45,.12);
    color: #e5322d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.branch-dark .map-wrapper {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #262626;
}

.branch-dark .map-wrapper iframe {
    display: block;
}

/* ---------- Buttons ---------- */
.branch-dark .btn-red {
    background: #e5322d; color: #fff; border: none;
    border-radius: 10px; font-weight: 600;
    transition: all .25s;
}
.branch-dark .btn-red:hover { background: #cc2a25; color: #fff; transform: translateY(-2px); }

.branch-dark .btn-outline-wh {
    background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.25);
    border-radius: 10px; font-weight: 600;
    transition: all .25s;
}
.branch-dark .btn-outline-wh:hover { border-color: #fff; color: #fff; background: rgba(255,255,255,.06); }
</style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), $branch->name => null]" />
@endsection

@section('content')
<div class="branch-dark">
    {{-- Hero banner --}}
    <section class="branch-detail-hero">
        <div class="container">
            <a href="{{ route('home') }}#branches" class="text-white text-decoration-none small d-inline-flex align-items-center mb-3 opacity-75">
                <i class="fa-solid fa-arrow-left me-2"></i> {{ __('account.br_back_all') }}
            </a>
            <h1 class="fw-bold text-white mb-2">{{ $branch->name }}</h1>
            <p class="mb-0 lead opacity-75">
                <i class="fa-solid fa-map-location-dot me-2"></i>{{ $branch->address }}
            </p>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-4">

            {{-- Left column --}}
            <div class="col-lg-5">

                {{-- Operating Hours --}}
                <div class="info-card mb-4">
                    <div class="card-header-bar"></div>
                    <div class="p-4">
                        <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-clock text-red me-2"></i>{{ __('account.br_operating_hours') }}</h5>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="hours-block">
                                <div class="label">{{ __('account.br_opens') }}</div>
                                <div class="time">{{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }}</div>
                            </div>
                            <span class="text-muted fs-4">—</span>
                            <div class="hours-block">
                                <div class="label">{{ __('account.br_closes') }}</div>
                                <div class="time">{{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact & Address --}}
                <div class="info-card mb-4">
                    <div class="card-header-bar"></div>
                    <div class="p-4">
                        <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-address-book text-red me-2"></i>{{ __('account.br_contact_address') }}</h5>

                        <div class="contact-row">
                            <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <div class="small text-muted-l fw-bold text-uppercase">{{ __('account.br_phone_number') }}</div>
                                <a href="tel:{{ $branch->contact_number }}" class="text-white text-decoration-none fw-semibold">{{ $branch->contact_number }}</a>
                            </div>
                        </div>

                        <div class="contact-row">
                            <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <div class="small text-muted-l fw-bold text-uppercase">{{ __('account.br_full_address') }}</div>
                                <span class="text-white">{{ $branch->address }}</span>
                            </div>
                        </div>

                        @if($branch->google_map_link)
                            <div class="mt-4">
                                <a href="{{ $branch->google_map_link }}" target="_blank" rel="noopener" class="btn-red d-inline-block px-4 py-2 text-decoration-none">
                                    <i class="fa-solid fa-diamond-turn-right me-1"></i> {{ __('account.br_get_directions') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick book CTA --}}
                <div class="info-card">
                    <div class="card-header-bar"></div>
                    <div class="p-4 text-center">
                        <h5 class="fw-bold text-white mb-2">{{ __('account.br_ready_to_book') }}</h5>
                        <p class="small text-muted-l mb-4">{{ __('account.br_schedule_sub') }}</p>
                        <a href="{{ route('bookings.create', ['branch_id' => $branch->id]) }}" class="btn-red btn-lg px-5 py-2 d-inline-block text-decoration-none w-100">
                            <i class="fa-solid fa-calendar-check me-2"></i> {{ __('account.br_book_now') }}
                        </a>
                    </div>
                </div>

            </div>

            {{-- Right column: Map --}}
            <div class="col-lg-7">
                <div class="info-card h-100 d-flex flex-column">
                    <div class="card-header-bar"></div>
                    <div class="p-4 flex-grow-1 d-flex flex-column">
                        <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-map-location-dot text-red me-2"></i>{{ __('account.br_location') }}</h5>
                        <div class="map-wrapper flex-grow-1 mb-3">
                            <iframe
                                src="https://maps.google.com/maps?q={{ urlencode($branch->address) }}&output=embed"
                                width="100%"
                                height="100%"
                                style="border:0; min-height: 400px;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                        <div class="small text-muted-l mt-auto">
                            <i class="fa-solid fa-map-pin text-red me-1"></i> {{ $branch->address }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
