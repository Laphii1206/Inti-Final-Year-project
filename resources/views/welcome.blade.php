@extends('layouts.app')

@section('styles')
<style>
/* ===== Page-scoped overrides — dark BG edge-to-edge ===== */
body { background-color: #0a0a0a !important; }
main { background-color: #0a0a0a !important; }
.footer-custom { margin-top: 0 !important; }

/* ===== LANDING-DARK — page-scoped dark theme ===== */
.landing-dark {
    background-color: #0a0a0a;
    color: #e4e4e7;
    overflow-x: hidden;
}

/* ---------- Typography ---------- */
.landing-dark h1,.landing-dark h2,.landing-dark h3,
.landing-dark h4,.landing-dark h5,.landing-dark h6 { color: #ffffff; }
.landing-dark .text-red { color: #e5322d !important; }
.landing-dark .text-muted-l { color: #9ca3af; }
.landing-dark .section-label {
    font-size: .75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .12em; color: #e5322d; margin-bottom: .5rem;
}

/* ---------- Dark Card ---------- */
.landing-dark .dk-card {
    background-color: #111;
    border: 1px solid rgba(229,50,45,.1);
    border-radius: 20px;
    transition: transform .3s, box-shadow .3s, border-color .3s;
}
.landing-dark .dk-card:hover {
    transform: translateY(-5px);
    border-color: rgba(229,50,45,.4);
    box-shadow: 0 12px 48px rgba(229,50,45,.15);
}

/* ---------- Red icon circle ---------- */
.landing-dark .icon-circle {
    width: 60px; height: 60px; border-radius: 50%;
    background: rgba(229,50,45,.12); color: #e5322d;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
    transition: box-shadow .3s;
}
.landing-dark .dk-card:hover .icon-circle {
    box-shadow: 0 0 20px rgba(229,50,45,.2);
}

/* ---------- Buttons ---------- */
.landing-dark .btn-red {
    background: #e5322d; color: #fff; border: none;
    padding: 12px 28px; border-radius: 10px; font-weight: 600;
    display: inline-flex; align-items: center; justify-content: center; text-align: center;
    transition: all .25s;
}
.landing-dark .btn-red:hover { background: #cc2a25; color: #fff; transform: translateY(-2px); }
.landing-dark .btn-outline-wh {
    background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.25);
    padding: 12px 28px; border-radius: 10px; font-weight: 600;
    display: inline-flex; align-items: center; justify-content: center; text-align: center;
    transition: all .25s;
}
.landing-dark .btn-outline-wh:hover { border-color: #fff; color: #fff; background: rgba(255,255,255,.06); }

/* ---------- Pill badge ---------- */
.landing-dark .pill-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(229,50,45,.1); color: #e5322d;
    padding: 6px 16px; border-radius: 999px; font-size: .78rem; font-weight: 600;
    border: 1px solid rgba(229,50,45,.2);
}

/* ---------- Feature pill ---------- */
.landing-dark .feat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
    color: #c9ccd1; border-radius: 999px; padding: 8px 16px; font-size: .82rem;
}
.landing-dark .feat-pill i { color: #e5322d; }

/* ===== 1. HERO ===== */
.landing-dark .ld-hero {
    min-height: 85vh;
    display: flex; align-items: center;
    padding: 100px 0 80px;
    position: relative;
    background: linear-gradient(90deg, rgba(10,10,10,.97) 0%, rgba(10,10,10,.88) 28%, rgba(10,10,10,.45) 58%, rgba(10,10,10,.08) 100%),
                url('{{ asset("images/hero-bg.jpg") }}') no-repeat center right;
    background-size: cover;
}
.landing-dark .ld-hero h1 {
    font-size: 4rem; font-weight: 800; line-height: 1.1;
    letter-spacing: -0.02em;
}
.landing-dark .ld-hero .hero-sub {
    font-size: 1.15rem; line-height: 1.8; color: #9ca3af;
}
@media(max-width:991px){
    .landing-dark .ld-hero {
        min-height: 82vh;
        padding: 90px 0 60px;
        background: linear-gradient(180deg, rgba(10,10,10,0.88) 0%, rgba(10,10,10,0.65) 45%, rgba(10,10,10,0.92) 100%),
                    url('{{ asset("images/hero-bg.jpg") }}') no-repeat center center;
        background-size: cover;
    }
    .landing-dark .ld-hero h1 { font-size: 2.5rem; }
}

/* ===== 2. STATS BAR ===== */
.landing-dark .stats-bar {
    background: #111; border: 1px solid rgba(229,50,45,.2);
    border-radius: 20px; padding: 36px 0;
    box-shadow: 0 0 60px rgba(229,50,45,.08);
}
.landing-dark .stats-bar .stat-item { text-align: center; }
.landing-dark .stats-bar .stat-num { font-size: 1.8rem; font-weight: 800; color: #fff; }
.landing-dark .stats-bar .stat-label { font-size: .82rem; color: #9ca3af; margin-top: 2px; }

/* ===== 3. SERVICES GRID ===== */
.landing-dark .svc-card .svc-link {
    color: #e5322d; font-size: .85rem; font-weight: 600;
    text-decoration: none; transition: color .2s;
}
.landing-dark .svc-card .svc-link:hover { color: #ff6b6b; }

/* ===== 4. FEATURED WORKSHOP ===== */
.landing-dark .ws-card {
    background: #111; border: 1px solid rgba(229,50,45,.15);
    border-radius: 24px; overflow: hidden;
    transition: box-shadow .3s;
}
.landing-dark #branches,
.landing-dark #branches-mobile {
    scroll-margin-top: 96px;
}
.landing-dark .ws-card:hover {
    box-shadow: 0 12px 48px rgba(229,50,45,.12);
}
.landing-dark .ws-card .ws-img {
    width: 100%; height: 100%; min-height: 380px;
    object-fit: cover;
}
.landing-dark .ws-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(229,50,45,.1); border: 1px solid rgba(229,50,45,.2);
    color: #e5322d; padding: 6px 16px; border-radius: 10px; font-size: .8rem; font-weight: 600;
}
.landing-dark .ws-check { color: #e5322d; margin-right: 8px; }

/* ===== 7. HOW IT WORKS ===== */
.landing-dark .step-circle {
    width: 52px; height: 52px; border-radius: 50%;
    background: #e5322d; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem; flex-shrink: 0;
    position: relative; z-index: 2;
}
.landing-dark .step-connector {
    flex: 1; height: 0; border-top: 2px dashed rgba(229,50,45,.3);
    margin: 0 -8px; z-index: 1;
}
@media(max-width:767px){
    .landing-dark .step-connector { display: none; }

    /* Vertical timeline layout for steps on mobile */
    .landing-dark .steps-mobile-wrap {
        display: flex;
        flex-direction: column;
        gap: 0;
        position: relative;
        padding-left: 28px;
    }
    .landing-dark .steps-mobile-wrap::before {
        content: '';
        position: absolute;
        left: 22px;
        top: 26px;
        bottom: 26px;
        width: 2px;
        background: rgba(229,50,45,.25);
    }
    .landing-dark .step-mobile-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 0 0 28px 0;
        text-align: left;
    }
    .landing-dark .step-mobile-item:last-child { padding-bottom: 0; }
    .landing-dark .step-circle { flex-shrink: 0; width: 44px; height: 44px; font-size: 0.95rem; }

    /* CTA Banner mobile */
    .landing-dark .cta-banner {
        padding: 40px 28px !important;
        text-align: center !important;
    }
    .landing-dark .cta-banner h2 { font-size: 1.6rem !important; }
    .landing-dark .cta-banner p { font-size: 0.9rem !important; }

    /* Section heading size reduction */
    .landing-dark h2 { font-size: 1.6rem !important; }
    .landing-dark .section-label { font-size: 0.7rem; }

    /* Why Choose cards full-width on mobile */
    .landing-dark .why-card-col { padding: 0 4px; }
}

/* ===== 8. FAQ ===== */
.landing-dark .faq-dark .accordion-item {
    background: #111; border: 1px solid rgba(255,255,255,.08);
    border-radius: 16px !important; margin-bottom: 14px; overflow: hidden;
}
.landing-dark .faq-dark .accordion-button {
    background: #111; color: #e4e4e7; font-weight: 600; font-size: 1rem;
    padding: 18px 24px; box-shadow: none;
}
.landing-dark .faq-dark .accordion-button::after { filter: invert(1); }
.landing-dark .faq-dark .accordion-button:not(.collapsed) {
    background: rgba(229,50,45,.08); color: #e5322d;
}
.landing-dark .faq-dark .accordion-body { background: #111; color: #9ca3af; font-size: .92rem; padding: 16px 24px 20px; }

/* ===== 9. CTA BANNER ===== */
.landing-dark .cta-banner {
    background: linear-gradient(90deg, #e5322d 0%, #e5322d 42%, rgba(229,50,45,0.8) 62%, rgba(229,50,45,0.25) 100%),
                url('{{ asset("images/hero-bg2.jpg") }}') right center / cover no-repeat;
    border-radius: 24px; padding: 70px 60px; position: relative; overflow: hidden;
}
.landing-dark .cta-banner::before {
    content: ''; position: absolute; right: -60px; bottom: -60px;
    width: 340px; height: 340px; border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.landing-dark .cta-banner .btn-cta {
    background: #fff; color: #e5322d; border: none;
    padding: 16px 36px; border-radius: 14px; font-weight: 700; font-size: 1rem;
    transition: transform .2s, box-shadow .2s;
}
.landing-dark .cta-banner .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.2); }

/* ===== WhatsApp FAB ===== */
.whatsapp-float {
    position: fixed; bottom: 30px; right: 30px;
    background: #25d366; color: #fff;
    width: 60px; height: 60px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; z-index: 1000; box-shadow: 0 4px 14px rgba(0,0,0,.3);
    transition: all .3s;
}
.whatsapp-float:hover { background: #128c7e; color: #fff; transform: scale(1.1); }

/* ===== Footer override inside landing ===== */
.landing-dark .footer-custom { background-color: #0a0a0a; border-top: 1px solid rgba(255,255,255,.06); }
.landing-dark .footer-bottom { background-color: #050505; }

/* ===== TRB AUTOMOTIVE MOBILE THEME (ONLY ON MOBILE < 992px) ===== */
@media (max-width: 991.98px) {
    /* Reduce section vertical padding on mobile */
    .landing-dark section[style*="padding:80px"],
    .landing-dark section[style*="padding: 80px"] {
        padding-top: 48px !important;
        padding-bottom: 48px !important;
    }
    /* Section text size */
    .landing-dark h2[style*="font-size:2.2rem"],
    .landing-dark h2[style*="font-size: 2.2rem"] {
        font-size: 1.5rem !important;
    }
    /* Top Trust Header */
    .trb-mob-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .trb-mob-brand-pill {
        background: rgba(229, 50, 45, 0.15); border: 1px solid rgba(229, 50, 45, 0.4);
        color: #fff; font-size: 0.72rem; font-weight: 700; padding: 6px 14px;
        border-radius: 30px; letter-spacing: 0.5px; display: inline-flex; align-items: center;
        backdrop-filter: blur(10px);
    }
    
    /* Hero Title */
    .trb-mob-title { font-size: 2.1rem; font-weight: 900; line-height: 1.15; color: #fff; letter-spacing: -0.5px; margin-bottom: 12px; }
    .trb-mob-subtitle { font-size: 0.9rem; color: #a0aec0; line-height: 1.5; margin-bottom: 24px; }



    /* Mobile Section Titles */
    .trb-sec-title { font-size: 1.35rem; font-weight: 800; color: #fff; margin-bottom: 6px; display: flex; align-items: center; gap: 10px; }
    .trb-sec-sub { font-size: 0.82rem; color: #94a3b8; margin-bottom: 20px; }

    /* 2-Column Automotive Service Cards */
    .trb-svc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 35px; }
    .trb-svc-card {
        background: linear-gradient(160deg, rgba(24, 28, 38, 0.9) 0%, rgba(15, 17, 23, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 18px 14px;
        text-decoration: none; display: flex; flex-direction: column; justify-content: space-between;
        transition: all 0.3s; position: relative; overflow: hidden;
    }
    .trb-svc-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
        background: #e5322d; opacity: 0.8;
    }
    .trb-svc-icon {
        width: 44px; height: 44px; border-radius: 10px; background: rgba(229, 50, 45, 0.12);
        color: #e5322d; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 14px;
    }
    .trb-svc-name { font-size: 0.95rem; font-weight: 800; color: #fff; margin-bottom: 4px; line-height: 1.2; }
    .trb-svc-desc { font-size: 0.72rem; color: #8a99ad; line-height: 1.4; margin-bottom: 12px; }
    .trb-svc-link { font-size: 0.75rem; color: #e5322d; font-weight: 700; display: flex; align-items: center; gap: 4px; }

    /* Workshop Card */
    .trb-ws-card {
        background: rgba(18, 20, 28, 0.95); border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 20px; overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.5); margin-bottom: 30px;
    }
    .trb-ws-img-wrap { position: relative; height: 180px; }
    .trb-ws-img { width: 100%; height: 100%; object-fit: cover; }
    .trb-ws-badge {
        position: absolute; top: 12px; left: 12px; color: #fff;
        font-size: 0.7rem; font-weight: 800; padding: 5px 14px; border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.35); display: inline-flex; align-items: center;
    }
    .trb-ws-badge-open {
        background: rgba(25, 135, 84, 0.95);
    }
    .trb-ws-badge-closed {
        background: rgba(33, 37, 41, 0.95); border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .trb-ws-rating {
        position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,0.75); color: #FBBF24;
        font-size: 0.75rem; font-weight: 800; padding: 4px 10px; border-radius: 12px; backdrop-filter: blur(4px);
    }
    .trb-ws-content { padding: 20px; }
    .trb-ws-name { font-size: 1.15rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
    .trb-ws-info { font-size: 0.8rem; color: #94a3b8; margin-bottom: 6px; display: flex; align-items: flex-start; gap: 8px; }
    .trb-ws-info i { color: #e5322d; margin-top: 3px; }
}
</style>
@endsection

@section('content')
<div class="landing-dark">

{{-- ============================================================
     1. HERO
     ============================================================ --}}
<section class="ld-hero">
    <div class="container">
        <!-- Desktop Hero Content (100% Untouched, Hidden on Mobile via d-none d-lg-flex) -->
        <div class="row align-items-center d-none d-lg-flex">
            <div class="col-lg-7 col-xl-6">
                <span class="pill-badge mb-4 d-inline-flex"><i class="fa-solid fa-shield-check"></i> {{ __('landing.hero_trusted') }}</span>
                <h1 class="mb-4">{{ __('landing.hero_title_1') }} <span class="text-red">{{ __('landing.hero_title_2') }}</span></h1>
                <p class="hero-sub mb-4">
                    {{ __('landing.hero_subtitle') }}
                </p>
                <div class="d-flex flex-wrap gap-2 mb-5">
                    <span class="feat-pill"><i class="fa-solid fa-circle-check"></i> {{ __('landing.hero_feat_1') }}</span>
                    <span class="feat-pill"><i class="fa-solid fa-circle-check"></i> {{ __('landing.hero_feat_2') }}</span>
                    <span class="feat-pill"><i class="fa-solid fa-circle-check"></i> {{ __('landing.hero_feat_3') }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('bookings.create') }}" class="btn-red text-decoration-none"><i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.hero_book_btn') }}</a>
                    <a href="#branches" class="btn-outline-wh text-decoration-none"><i class="fa-solid fa-location-dot me-2"></i>{{ __('landing.hero_find_btn') }}</a>
                </div>
            </div>
        </div>

        <!-- Mobile Minimalist Hero Content (Scheme 1: Apple / Stripe Style, 1:1 Mapping of Desktop) -->
        <div class="d-block d-lg-none py-4">
            <span class="pill-badge mb-3 d-inline-flex"><i class="fa-solid fa-shield-check"></i> {{ __('landing.hero_trusted') }}</span>
            <h1 class="mb-3">{{ __('landing.hero_title_1') }} <span class="text-red">{{ __('landing.hero_title_2') }}</span></h1>
            <p class="hero-sub fs-base mb-4" style="color: #cbd5e1; line-height: 1.6;">
                {{ __('landing.hero_subtitle') }}
            </p>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="feat-pill"><i class="fa-solid fa-circle-check"></i> {{ __('landing.hero_feat_1') }}</span>
                <span class="feat-pill"><i class="fa-solid fa-circle-check"></i> {{ __('landing.hero_feat_2') }}</span>
                <span class="feat-pill"><i class="fa-solid fa-circle-check"></i> {{ __('landing.hero_feat_3') }}</span>
            </div>
            <div class="d-flex flex-column gap-3">
                <a href="{{ route('bookings.create') }}" class="btn-red w-100 d-flex align-items-center justify-content-center text-decoration-none py-3 fs-base shadow-lg"><i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.hero_book_btn') }}</a>
                <a href="#branches-mobile" class="btn-outline-wh w-100 d-flex align-items-center justify-content-center text-decoration-none py-3 fs-base"><i class="fa-solid fa-location-dot me-2"></i>{{ __('landing.hero_find_btn') }}</a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     DESKTOP SECTIONS 2, 3, 4 (100% Untouched, Hidden on Mobile via d-none d-lg-block)
     ============================================================ --}}
<div class="d-none d-lg-block">
{{-- ============================================================
     2. STATS BAR
     ============================================================ --}}
<section class="container mb-5" style="margin-top:-50px;position:relative;z-index:2;">
    <div class="stats-bar">
        <div class="row g-4 justify-content-center px-4">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-num"><i class="fa-solid fa-warehouse text-red me-1" style="font-size:.9em;"></i> 250+</div>
                    <div class="stat-label">{{ __('landing.stat_label_1') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-num"><i class="fa-solid fa-calendar-check text-red me-1" style="font-size:.9em;"></i> 15,000+</div>
                    <div class="stat-label">{{ __('landing.stat_label_2') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-num"><i class="fa-solid fa-star text-red me-1" style="font-size:.9em;"></i> 4.9</div>
                    <div class="stat-label">{{ __('landing.stat_label_3') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-num"><i class="fa-solid fa-percent text-red me-1" style="font-size:.9em;"></i> 100%</div>
                    <div class="stat-label">{{ __('landing.stat_label_4') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     3. OUR SERVICES
     ============================================================ --}}
<section class="container" style="padding:80px 0;" id="services">
    <div class="text-center mb-5">
        <p class="section-label">{{ __('landing.services_label') }}</p>
        <h2 class="fw-bold" style="font-size:2.2rem;">{{ __('landing.services_title') }} <span class="text-red">{{ __('landing.services_title_red') }}</span></h2>
        <p class="text-muted-l mx-auto" style="max-width:560px;">{{ __('landing.services_desc') }}</p>
    </div>
    @php
        $services = [
            ['icon'=>'fa-screwdriver-wrench','name'=>__('landing.cat_maintenance'),'slug'=>'maintenance','sub'=>__('landing.cat_sub_maintenance')],
            ['icon'=>'fa-ring','name'=>__('landing.cat_tyres'),'slug'=>'tyres','sub'=>__('landing.cat_sub_tyres')],
            ['icon'=>'fa-sun-plant-wilt','name'=>__('landing.cat_tinting'),'slug'=>'tinting-films','sub'=>__('landing.cat_sub_tinting')],
            ['icon'=>'fa-video','name'=>__('landing.cat_dashcams'),'slug'=>'dashcams','sub'=>__('landing.cat_sub_dashcams')],
            ['icon'=>'fa-rug','name'=>__('landing.cat_carmats'),'slug'=>'car-mats','sub'=>__('landing.cat_sub_carmats')],
            ['icon'=>'fa-wind','name'=>__('landing.cat_wipers'),'slug'=>'wipers','sub'=>__('landing.cat_sub_wipers')],
        ];
    @endphp
    <div class="row g-4">
        @foreach($services as $svc)
        <div class="col-lg-4 col-md-6">
            <div class="dk-card svc-card p-4 h-100">
                <div class="icon-circle mb-3"><i class="fa-solid {{ $svc['icon'] }}"></i></div>
                <h5 class="fw-bold mb-1">{{ $svc['name'] }}</h5>
                <p class="text-muted-l small mb-3">{{ $svc['sub'] }}</p>
                <a href="{{ route('services.show', $svc['slug']) }}" class="svc-link">{{ __('landing.services_view_btn') }} <i class="fa-solid fa-arrow-right ms-1" style="font-size:.75rem;"></i></a>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ============================================================
     4. FEATURED WORKSHOP (first real branch)
     ============================================================ --}}
@if($branches->count() > 0)
@php $featured = $branches->first(); @endphp
<section class="container" style="padding:80px 0;" id="branches">
    <div class="text-center mb-5">
        <p class="section-label">{{ __('landing.featured_label') }}</p>
        <h2 class="fw-bold" style="font-size:2.2rem;">{{ __('landing.featured_title') }} <span class="text-red">{{ __('landing.featured_title_red') }}</span></h2>
    </div>
    <div class="ws-card">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-5 position-relative">
                <img src="{{ asset('images/workshop.jpg') }}" alt="{{ $featured->name }}" class="ws-img">
                <div class="position-absolute top-0 start-0 p-3">
                    @if($featured->isOpenNow())
                        <span class="badge bg-success shadow px-3 py-2 rounded-pill fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-store me-1"></i> {{ __('booking.cb_open_now') }}</span>
                    @else
                        <span class="badge bg-dark shadow px-3 py-2 rounded-pill fw-bold" style="font-size:0.75rem;"><i class="fa-solid fa-moon me-1"></i> {{ __('booking.cb_closed') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-lg-7 p-4 p-lg-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="text-warning small">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <span class="text-muted-l small">5.0 · {{ $reviews->count() }} {{ __('landing.featured_reviews') }}</span>
                </div>
                <h3 class="fw-bold mb-3">{{ $featured->name }}</h3>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="ws-badge"><i class="fa-solid fa-certificate"></i> {{ __('landing.featured_badge_1') }}</span>
                    <span class="ws-badge"><i class="fa-solid fa-gear"></i> {{ __('landing.hero_feat_2') }}</span>
                </div>
                <div class="d-flex flex-column gap-2 mb-3 text-muted-l small">
                    <span><i class="fa-solid fa-location-dot text-red me-2"></i>{{ $featured->address }}</span>
                    <span><i class="fa-solid fa-clock text-red me-2"></i>{{ \Carbon\Carbon::parse($featured->opening_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($featured->closing_time)->format('h:i A') }}</span>
                    <span><i class="fa-solid fa-phone text-red me-2"></i>{{ $featured->contact_number }}</span>
                </div>
                <div class="row g-2 mb-4">
                    @foreach([__('landing.featured_check_1'),__('landing.featured_check_2'),__('landing.featured_check_3'),__('landing.featured_check_4')] as $item)
                    <div class="col-6 small">
                        <i class="fa-solid fa-circle-check ws-check"></i> {{ $item }}
                    </div>
                    @endforeach
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('branches.show', $featured) }}" class="btn-outline-wh text-decoration-none"><i class="fa-solid fa-eye me-2"></i>{{ __('landing.featured_view_btn') }}</a>
                    <a href="{{ route('bookings.create', ['branch_id'=>$featured->id]) }}" class="btn-red text-decoration-none"><i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.featured_book_btn') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Other branches list --}}
    @if($branches->count() > 1)
    <div class="row g-4 mt-4">
        @foreach($branches->skip(1) as $branch)
        <div class="col-lg-6">
            <div class="dk-card p-4 h-100">
                <div class="d-flex gap-3 align-items-start">
                    <div class="icon-circle"><i class="fa-solid fa-warehouse"></i></div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <a href="{{ route('branches.show', $branch) }}" class="text-decoration-none"><h5 class="fw-bold mb-0" style="color:#fff;">{{ $branch->name }}</h5></a>
                            @if($branch->isOpenNow())
                                <span class="badge bg-success shadow px-2.5 py-1 rounded-pill fw-bold" style="font-size:0.65rem;"><i class="fa-solid fa-store me-1"></i> {{ __('booking.cb_open_now') }}</span>
                            @else
                                <span class="badge bg-dark shadow px-2.5 py-1 rounded-pill fw-bold" style="font-size:0.65rem;"><i class="fa-solid fa-moon me-1"></i> {{ __('booking.cb_closed') }}</span>
                            @endif
                        </div>
                        <div class="text-muted-l small mb-2">
                            <i class="fa-solid fa-clock me-1"></i> {{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}
                            <span class="mx-1">·</span>
                            <i class="fa-solid fa-phone me-1"></i> {{ $branch->contact_number }}
                        </div>
                        <p class="text-muted-l small mb-3"><i class="fa-solid fa-location-dot me-1"></i> {{ $branch->address }}</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('branches.show', $branch) }}" class="btn-outline-wh text-decoration-none" style="padding:8px 18px;font-size:.85rem;"><i class="fa-solid fa-eye me-1"></i> {{ __('landing.featured_view_btn') }}</a>
                            <a href="{{ route('bookings.create', ['branch_id'=>$branch->id]) }}" class="btn-red text-decoration-none" style="padding:8px 18px;font-size:.85rem;"><i class="fa-solid fa-calendar-days me-1"></i> {{ __('landing.workshop_book_btn') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endif
</div>

{{-- ============================================================
     MOBILE-ONLY SECTIONS: Automotive Core Services & Center Card (Hidden on Desktop via d-none d-lg-block)
     ============================================================ --}}
<div class="d-block d-lg-none container py-4">
    <!-- Core Automotive Services -->
    <div class="mb-4">
        <h3 class="trb-sec-title"><i class="fa-solid fa-screwdriver-wrench text-red"></i> {{ __('landing.services_title') }} <span class="text-red">{{ __('landing.services_title_red') }}</span></h3>
        <p class="trb-sec-sub">{{ __('landing.services_desc') }}</p>
    </div>
    
    <div class="trb-svc-grid">
        @foreach([
            ['icon'=>'fa-oil-can','name'=>__('landing.cat_maintenance'),'slug'=>'maintenance','desc'=>__('landing.nav_svc_maintenance_desc')],
            ['icon'=>'fa-ring','name'=>__('landing.cat_tyres'),'slug'=>'tyres','desc'=>__('landing.nav_svc_tyres_desc')],
            ['icon'=>'fa-sun-plant-wilt','name'=>__('landing.cat_tinting'),'slug'=>'tinting-films','desc'=>__('landing.nav_svc_tinting_desc')],
            ['icon'=>'fa-video','name'=>__('landing.cat_dashcams'),'slug'=>'dashcams','desc'=>__('landing.nav_svc_dashcams_desc')],
            ['icon'=>'fa-rug','name'=>__('landing.cat_carmats'),'slug'=>'car-mats','desc'=>__('landing.nav_svc_carmats_desc')],
            ['icon'=>'fa-wind','name'=>__('landing.cat_wipers'),'slug'=>'wipers','desc'=>__('landing.nav_svc_wipers_desc')],
        ] as $svc)
        <a href="{{ route('services.show', $svc['slug']) }}" class="trb-svc-card">
            <div>
                <div class="trb-svc-icon"><i class="fa-solid {{ $svc['icon'] }}"></i></div>
                <div class="trb-svc-name">{{ $svc['name'] }}</div>
                <div class="trb-svc-desc">{{ $svc['desc'] }}</div>
            </div>
            <div class="trb-svc-link">{{ __('landing.featured_view_btn') }} <i class="fa-solid fa-arrow-right fs-xs"></i></div>
        </a>
        @endforeach
    </div>

    <!-- Nearest Workshop Card -->
    @if($branches->count() > 0)
    @php $featured = $branches->first(); @endphp
    <div class="mb-3" id="branches-mobile">
        <h3 class="trb-sec-title"><i class="fa-solid fa-warehouse text-red"></i> {{ __('landing.featured_title') }} <span class="text-red">{{ __('landing.featured_title_red') }}</span></h3>
        <p class="trb-sec-sub">{{ __('landing.workshop_sub') }}</p>
    </div>
    
    <div class="trb-ws-card">
        <div class="trb-ws-img-wrap">
            <img src="{{ asset('images/workshop.jpg') }}" alt="{{ $featured->name }}" class="trb-ws-img">
            @if($featured->isOpenNow())
                <span class="trb-ws-badge trb-ws-badge-open"><i class="fa-solid fa-store me-1"></i> {{ __('booking.cb_open_now') }}</span>
            @else
                <span class="trb-ws-badge trb-ws-badge-closed"><i class="fa-solid fa-moon me-1"></i> {{ __('booking.cb_closed') }}</span>
            @endif
            <span class="trb-ws-rating"><i class="fa-solid fa-star text-warning"></i> 5.0 (128 {{ __('landing.featured_reviews') }})</span>
        </div>
        <div class="trb-ws-content">
            <h4 class="trb-ws-name">{{ $featured->name }}</h4>
            <div class="trb-ws-info">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $featured->address }}</span>
            </div>
            <div class="trb-ws-info">
                <i class="fa-solid fa-clock"></i>
                <span>{{ \Carbon\Carbon::parse($featured->opening_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($featured->closing_time)->format('h:i A') }}</span>
            </div>
            <div class="trb-ws-info mb-4">
                <i class="fa-solid fa-phone"></i>
                <span>{{ $featured->contact_number }}</span>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('branches.show', $featured) }}" class="btn-outline-wh w-100 h-100 text-decoration-none py-2.5 px-2 d-flex align-items-center justify-content-center text-center" style="border-radius: 12px; font-size: 0.88rem; font-weight: 700;">
                        <span>{{ __('landing.featured_view_btn') }}</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('bookings.create', ['branch_id'=>$featured->id]) }}" class="btn-red w-100 h-100 text-decoration-none py-2.5 px-2 d-flex align-items-center justify-content-center text-center" style="border-radius: 12px; font-size: 0.88rem; font-weight: 700;">
                        <span>{{ __('landing.featured_book_btn') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if($branches->count() > 1)
    <div class="row g-3 mt-1">
        @foreach($branches->skip(1) as $branch)
        <div class="col-12">
            <div class="trb-ws-card mb-0">
                <div class="trb-ws-content">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="trb-ws-name mb-0">{{ $branch->name }}</h4>
                        @if($branch->isOpenNow())
                            <span class="badge bg-success shadow px-2.5 py-1 rounded-pill fw-bold" style="font-size:0.65rem;"><i class="fa-solid fa-store me-1"></i> {{ __('booking.cb_open_now') }}</span>
                        @else
                            <span class="badge bg-dark shadow px-2.5 py-1 rounded-pill fw-bold" style="font-size:0.65rem;"><i class="fa-solid fa-moon me-1"></i> {{ __('booking.cb_closed') }}</span>
                        @endif
                    </div>
                    <div class="trb-ws-info">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $branch->address }}</span>
                    </div>
                    <div class="trb-ws-info">
                        <i class="fa-solid fa-clock"></i>
                        <span>{{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</span>
                    </div>
                    <div class="trb-ws-info mb-4">
                        <i class="fa-solid fa-phone"></i>
                        <span>{{ $branch->contact_number }}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('branches.show', $branch) }}" class="btn-outline-wh w-100 h-100 text-decoration-none py-2.5 px-2 d-flex align-items-center justify-content-center text-center" style="border-radius: 12px; font-size: 0.88rem; font-weight: 700;">
                                <span>{{ __('landing.featured_view_btn') }}</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('bookings.create', ['branch_id'=>$branch->id]) }}" class="btn-red w-100 h-100 text-decoration-none py-2.5 px-2 d-flex align-items-center justify-content-center text-center" style="border-radius: 12px; font-size: 0.88rem; font-weight: 700;">
                                <span>{{ __('landing.featured_book_btn') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endif
</div>

{{-- ============================================================
     5. WHY CHOOSE TRB AUTO CAR CARE
     ============================================================ --}}
<section class="container" style="padding:80px 0;">
    <div class="text-center mb-5">
        <p class="section-label">{{ __('landing.why_label') }}</p>
        <h2 class="fw-bold" style="font-size:2.2rem;">{{ __('landing.why_title') }} <span class="text-red">{{ __('landing.why_title_choose') }}</span> {{ __('landing.why_title_suffix') }}</h2>
        <p class="text-muted-l mx-auto" style="max-width:560px;">{{ __('landing.why_subtitle') }}</p>
    </div>
    <div class="row g-3 g-md-4">
        @php
            $whyCards = [
                ['icon'=>'fa-tags','title_b'=>__('landing.why_1_title_brand'),'title'=>__('landing.why_1_title'),'desc'=>__('landing.why_1_desc')],
                ['icon'=>'fa-gear','title_b'=>__('landing.why_2_title_brand'),'title'=>__('landing.why_2_title'),'desc'=>__('landing.why_2_desc')],
                ['icon'=>'fa-medal','title_b'=>__('landing.why_3_title_brand'),'title'=>__('landing.why_3_title'),'desc'=>__('landing.why_3_desc')],
                ['icon'=>'fa-hand-holding-heart','title_b'=>__('landing.why_4_title_brand'),'title'=>__('landing.why_4_title'),'desc'=>__('landing.why_4_desc')],
            ];
        @endphp
        @foreach($whyCards as $wc)
        <div class="col-6 col-lg-3">
            <div class="dk-card p-3 p-md-4 text-center h-100">
                <div class="icon-circle mx-auto mb-3" style="width:48px;height:48px;font-size:1.1rem;"><i class="fa-solid {{ $wc['icon'] }}"></i></div>
                <h5 class="fw-bold mb-2" style="font-size:0.92rem;"><span class="text-red">{{ $wc['title_b'] }}</span> {{ $wc['title'] }}</h5>
                <p class="text-muted-l small mb-0" style="font-size:0.78rem;">{{ $wc['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ============================================================
     6. TESTIMONIALS (real reviews only — hidden if none)
     ============================================================ --}}
@if(isset($reviews) && $reviews->count() > 0)
<section class="container" style="padding:80px 0;" id="reviews">
    <div class="text-center mb-5">
        <p class="section-label">{{ __('landing.reviews_label') }}</p>
        <h2 class="fw-bold" style="font-size:2.2rem;">{{ __('landing.reviews_title') }} <span class="text-red">{{ __('landing.reviews_title_red') }}</span> {{ __('landing.reviews_title_suffix') }}</h2>
        <p class="text-muted-l mx-auto" style="max-width:500px;">{{ __('landing.reviews_subtitle') }}</p>
    </div>
    <div class="row g-4 justify-content-center">
        @foreach($reviews->take(3) as $review)
        <div class="col-lg-4 col-md-6">
            <div class="dk-card p-4 h-100">
                <div class="text-warning mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                    @endfor
                </div>
                <p class="mb-4" style="color:#d1d5db;font-style:italic;">"{{ $review->comment ?? 'Great service and professional staff.' }}"</p>
                <div class="d-flex align-items-center gap-3 mt-auto">
                    <div style="width:44px;height:44px;border-radius:50%;background:#e5322d;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;">
                        {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold small" style="color:#fff;">{{ $review->user->name ?? 'Anonymous User' }}</div>
                        <div class="text-muted-l" style="font-size:.78rem;">{{ $review->booking?->service?->name ?? 'Auto Service' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ============================================================
     7. HOW IT WORKS
     ============================================================ --}}
<section class="container" style="padding:80px 0;">
    <div class="text-center mb-5">
        <p class="section-label">{{ __('landing.process_label') }}</p>
        <h2 class="fw-bold" style="font-size:2.2rem;">{{ __('landing.process_title') }} <span class="text-red">{{ __('landing.process_title_red') }}</span></h2>
    </div>
    @php
        $steps = [
            ['num'=>'1','title'=>__('landing.process_step_1_title'),'desc'=>__('landing.process_step_1_desc')],
            ['num'=>'2','title'=>__('landing.process_step_2_title'),'desc'=>__('landing.process_step_2_desc')],
            ['num'=>'3','title'=>__('landing.process_step_3_title'),'desc'=>__('landing.process_step_3_desc')],
            ['num'=>'4','title'=>__('landing.process_step_4_title'),'desc'=>__('landing.process_step_4_desc')],
            ['num'=>'5','title'=>__('landing.process_step_5_title'),'desc'=>__('landing.process_step_5_desc')],
        ];
    @endphp
    {{-- Desktop: horizontal step row --}}
    <div class="d-none d-md-flex flex-wrap justify-content-center align-items-start gap-0">
        @foreach($steps as $idx => $step)
            <div class="text-center" style="width:160px;">
                <div class="step-circle mx-auto mb-3">{{ $step['num'] }}</div>
                <h6 class="fw-bold mb-1" style="font-size:.9rem;">{{ $step['title'] }}</h6>
                <p class="text-muted-l mb-0" style="font-size:.78rem;">{{ $step['desc'] }}</p>
            </div>
            @if($idx < count($steps) - 1)
                <div class="step-connector align-self-center" style="margin-top:-40px;"></div>
            @endif
        @endforeach
    </div>
    {{-- Mobile: vertical timeline --}}
    <div class="d-md-none steps-mobile-wrap">
        @foreach($steps as $step)
        <div class="step-mobile-item">
            <div class="step-circle">{{ $step['num'] }}</div>
            <div>
                <h6 class="fw-bold mb-1 text-white" style="font-size:.9rem;">{{ $step['title'] }}</h6>
                <p class="text-muted-l mb-0" style="font-size:.8rem;">{{ $step['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ============================================================
     8. FAQ
     ============================================================ --}}
<section class="container" style="padding:80px 0;" id="faq">
    <div class="text-center mb-5">
        <p class="section-label">{{ __('landing.faq_label') }}</p>
        <h2 class="fw-bold" style="font-size:2.2rem;">{{ __('landing.faq_title') }}</h2>
        <p class="text-muted-l">{{ __('landing.faq_subtitle') }}</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">
            <div class="accordion faq-dark" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="true">
                            {{ __('landing.faq_q1') }}
                        </button>
                    </h2>
                    <div id="faqOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{{ __('landing.faq_a1') }}</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
                            {{ __('landing.faq_q2') }}
                        </button>
                    </h2>
                    <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{{ __('landing.faq_a2') }}</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
                            {{ __('landing.faq_q3') }}
                        </button>
                    </h2>
                    <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{{ __('landing.faq_a3') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 offset-lg-1">
            <div class="dk-card p-4 text-center h-100 d-flex flex-column justify-content-center">
                <div class="icon-circle mx-auto mb-3"><i class="fa-solid fa-headset"></i></div>
                <h5 class="fw-bold mb-2">{{ __('landing.faq_still_questions') }}</h5>
                <p class="text-muted-l small mb-4">{{ __('landing.faq_support_desc') }}</p>
                <button type="button" class="btn-red text-decoration-none mx-auto border-0" style="padding:10px 24px;font-size:.9rem;" data-bs-toggle="modal" data-bs-target="#supportTicketModal">
                    <i class="fa-solid fa-headset me-2"></i>{{ __('landing.faq_contact_btn') }}
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     9. CTA BANNER
     ============================================================ --}}
<section class="container" style="padding:80px 0 100px;">
    <div class="cta-banner text-center text-lg-start">
        <div class="row align-items-center position-relative">
            <div class="col-lg-7">
                <h2 class="fw-bold text-white mb-3" style="font-size:2.4rem;">{{ __('landing.cta_title') }}</h2>
                <p class="text-white mb-4" style="opacity:.85;font-size:1.05rem;">{{ __('landing.cta_desc') }}</p>
                <a href="{{ route('bookings.create') }}" class="btn-cta text-decoration-none d-inline-block"><i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.cta_btn') }}</a>
            </div>
        </div>
    </div>
</section>

</div>{{-- /.landing-dark --}}

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/60173673385" target="_blank" class="whatsapp-float shadow-lg" title="{{ __('landing.welcome_whatsapp') }}">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<!-- Support Ticket Modal (Exact Help Centre Match) -->
<style>
#supportTicketModal .chat-widget-card {
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    background: linear-gradient(145deg, #181920 0%, #22242e 100%);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    color: #fff;
    overflow: hidden;
}
#supportTicketModal .form-control,
#supportTicketModal .form-select {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.16);
    color: #fff;
    border-radius: 14px;
    padding: 14px 18px;
    font-size: 0.95rem;
    transition: all 0.25s ease;
}
#supportTicketModal .form-control::placeholder { color: rgba(255,255,255,.35); }
#supportTicketModal .form-control:focus,
#supportTicketModal .form-select:focus {
    background: rgba(255,255,255,.08);
    border-color: #EC1F24;
    box-shadow: 0 0 0 4px rgba(236, 31, 36, 0.25);
    color: #fff;
}
#supportTicketModal label { color: rgba(255,255,255,.8); font-size: .85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

/* ─── Custom SaaS Dropdown Component ─── */
#supportTicketModal .booking-dropdown-trigger {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.14);
    color: #fff;
    border-radius: 14px;
    padding: 13px 18px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none;
}
#supportTicketModal .booking-dropdown-trigger:hover, #supportTicketModal .booking-dropdown-trigger.open {
    background: rgba(255, 255, 255, 0.08);
    border-color: #EC1F24;
    box-shadow: 0 0 0 4px rgba(236, 31, 36, 0.25);
}
#supportTicketModal .booking-dropdown-trigger.open #modal-booking-dropdown-chevron {
    transform: rotate(180deg);
}
#supportTicketModal .booking-dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    left: 0; right: 0;
    background: #181920;
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 16px;
    max-height: 280px;
    overflow-y: auto;
    z-index: 1050;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.8);
}
#supportTicketModal .booking-dropdown-menu::-webkit-scrollbar { width: 5px; }
#supportTicketModal .booking-dropdown-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
#supportTicketModal .booking-dropdown-menu.show { display: block; animation: fadeInDown 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
#supportTicketModal .booking-dropdown-item {
    padding: 12px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    cursor: pointer;
    transition: all 0.15s ease;
}
#supportTicketModal .booking-dropdown-item:last-child { border-bottom: none; }
#supportTicketModal .booking-dropdown-item:hover {
    background: linear-gradient(90deg, rgba(236, 31, 36, 0.18) 0%, rgba(236, 31, 36, 0.02) 100%);
    padding-left: 24px;
}

/* ─── Category Tabs (Tier 1) ─── */
#supportTicketModal .hc-cat-btn {
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.03);
    color: rgba(255, 255, 255, 0.75);
    border-radius: 16px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.92rem;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    outline: none !important;
}
#supportTicketModal .hc-cat-btn:focus { outline: none !important; }
#supportTicketModal .hc-cat-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}
#supportTicketModal .hc-cat-btn.active {
    background: linear-gradient(135deg, rgba(236,31,36,0.3), rgba(236,31,36,0.15));
    border: 1px solid #EC1F24;
    color: #ffffff;
    box-shadow: 0 6px 20px rgba(236,31,36,0.25);
}
#supportTicketModal .hc-cat-btn i { color: #EC1F24; transition: transform 0.2s; font-size: 1.1rem; }
#supportTicketModal .hc-cat-btn.active i { transform: scale(1.15); }

/* ─── Topic Pills ─── */
#supportTicketModal .topic-pill-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.88rem; font-weight: 500;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    outline: none !important;
}
#supportTicketModal .topic-pill-btn:focus { outline: none !important; }
#supportTicketModal .topic-pill-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.28);
    color: #fff; transform: translateY(-1px);
}
#supportTicketModal .topic-pill-btn.active {
    background: linear-gradient(135deg, rgba(236,31,36,0.25), rgba(236,31,36,0.12));
    border: 1px solid #EC1F24; color: #fff; font-weight: 600;
    box-shadow: 0 4px 16px rgba(236,31,36,0.25);
}
#supportTicketModal .topic-pill-btn i { font-size: 1rem; color: #EC1F24; transition: transform 0.2s; }
#supportTicketModal .topic-pill-btn.active i { transform: scale(1.15); }

/* ─── File upload in chat widget ─── */
#supportTicketModal .hc-drop-zone {
    border: 2px dashed rgba(255,255,255,.22);
    border-radius: 18px;
    min-height: 180px;
    padding: 36px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
    background: rgba(255,255,255,.025);
    color: rgba(255,255,255,.65);
    font-size: .9rem;
    transition: all .25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
#supportTicketModal .hc-drop-zone:hover {
    border-color: rgba(236,31,36,.6);
    background: rgba(236,31,36,.04);
    color: #fff;
    transform: translateY(-2px);
}
#supportTicketModal .hc-drop-zone.dragover {
    border: 2px solid #EC1F24 !important;
    background: linear-gradient(135deg, rgba(236,31,36,.25) 0%, rgba(236,31,36,.1) 100%) !important;
    box-shadow: 0 0 40px rgba(236,31,36,.45), inset 0 0 25px rgba(236,31,36,.2) !important;
    transform: scale(1.02);
}
#supportTicketModal .hc-drop-zone.dragover .dz-state-default { display: none !important; }
#supportTicketModal .hc-drop-zone.dragover .dz-state-active { display: flex !important; animation: dzPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
#supportTicketModal .dz-icon-circle {
    width: 60px; height: 60px;
    background: rgba(236,31,36,.12);
    border: 1px solid rgba(236,31,36,.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
    transition: all .25s ease;
}
#supportTicketModal .hc-drop-zone:hover .dz-icon-circle {
    transform: scale(1.1);
    background: rgba(236,31,36,.2);
    box-shadow: 0 0 16px rgba(236,31,36,.3);
}
#supportTicketModal .dz-pulse-circle {
    width: 68px; height: 68px;
    background: #EC1F24;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
    box-shadow: 0 0 25px rgba(236,31,36,.7);
    animation: pulseBounce 1s infinite alternate;
}
@keyframes pulseBounce {
    from { transform: translateY(0) scale(1); }
    to { transform: translateY(-6px) scale(1.08); }
}
#supportTicketModal .pointer-events-none { pointer-events: none; }
#supportTicketModal .hc-file-previews { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
#supportTicketModal .hc-file-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 10px; padding: 6px 12px;
    font-size: .8rem; color: #fff; transition: all .2s;
}
#supportTicketModal .hc-file-chip:hover { background: rgba(255,255,255,.14); border-color: rgba(255,255,255,.28); }
</style>

<div class="modal fade" id="supportTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content chat-widget-card border-0 shadow-lg p-4 p-md-5 position-relative">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10;"></button>
            
            <div class="d-flex align-items-center gap-3 mb-4 pe-4">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(236,31,36,.2);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                    <i class="fa-solid fa-headset" style="color: #EC1F24;"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-white mb-0">{{ __('landing.help_chat_team') }}</h4>
                    <span class="small" style="color:rgba(255,255,255,.5);">{{ __('landing.help_avg_response') }}</span>
                </div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-success d-inline-block" style="width:10px;height:10px;"></span>
                    <span class="small text-success fw-semibold">{{ __('landing.help_online') }}</span>
                </div>
            </div>

            @auth
                @php
                    $userBookings = \App\Models\Booking::with(['service', 'branch'])
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->take(30)
                        ->get();
                @endphp
                <form action="{{ route('support-tickets.store') }}" method="POST"
                      enctype="multipart/form-data" id="modal-ticket-form">
                    @csrf
                    <div class="row g-3">
                        <!-- Tier 1: Category Selection -->
                        <div class="col-12 mb-3">
                            <label class="form-label d-block mb-2">1. @if(app()->getLocale() == 'zh') 选择咨询类别 / Ticket Category @else Ticket Category @endif <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                <button type="button" class="hc-cat-btn active d-flex align-items-center gap-2" data-category="booking">
                                    <i class="fa-solid fa-car"></i>
                                    <span>@if(app()->getLocale() == 'zh') 订单售后与投诉 (Booking Related) @else Booking Related Issue @endif</span>
                                </button>
                                <button type="button" class="hc-cat-btn d-flex align-items-center gap-2" data-category="general">
                                    <i class="fa-regular fa-comments"></i>
                                    <span>@if(app()->getLocale() == 'zh') 常规咨询与建议 (General Inquiry) @else General Inquiry @endif</span>
                                </button>
                            </div>
                        </div>

                        <!-- Dynamic Booking Selector -->
                        <div class="col-12 mb-3" id="modal-hc-booking-select-container">
                            <label class="form-label">2. @if(app()->getLocale() == 'zh') 关联订单 / Select Linked Booking @else Select Linked Booking @endif <span class="text-danger">*</span></label>
                            <input type="hidden" name="booking_id" id="modal-hc-booking-id" value="">
                            @if(isset($userBookings) && $userBookings->isNotEmpty())
                                <div class="position-relative">
                                    <div class="booking-dropdown-trigger d-flex align-items-center justify-content-between" id="modal-booking-dropdown-trigger">
                                        <div class="d-flex align-items-center gap-2 text-truncate pe-2">
                                            <i class="fa-solid fa-car" style="color: #EC1F24;"></i>
                                            <span id="modal-booking-dropdown-text" style="color:rgba(255,255,255,.6);">-- @if(app()->getLocale() == 'zh') 请选择您需咨询的订单 @else Select a booking -- @endif</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down transition-transform flex-shrink-0" style="color: #EC1F24;" id="modal-booking-dropdown-chevron"></i>
                                    </div>
                                    <div class="booking-dropdown-menu shadow-lg" id="modal-booking-dropdown-menu">
                                        <div class="booking-dropdown-item d-flex align-items-center justify-content-between" data-value="" data-number="" data-service="">
                                            <span class="text-muted small">-- @if(app()->getLocale() == 'zh') 请选择您需咨询的订单 @else Select a booking -- @endif --</span>
                                        </div>
                                        @foreach($userBookings as $b)
                                            @php
                                                $statusColors = ['confirmed'=>'primary', 'completed'=>'success', 'cancelled'=>'secondary', 'pending'=>'warning'];
                                                $sc = $statusColors[strtolower($b->status)] ?? 'info';
                                            @endphp
                                            <div class="booking-dropdown-item d-flex align-items-center justify-content-between gap-3" 
                                                 data-value="{{ $b->id }}" data-number="{{ $b->number }}" data-service="{{ $b->service->name ?? '' }}">
                                                <div class="text-truncate">
                                                    <span class="fw-bold text-white font-monospace" style="color: #EC1F24 !important;">#{{ $b->number }}</span>
                                                    <span class="text-muted small ms-1">&middot; {{ $b->service->name ?? 'Service' }} (@if($b->branch){{ $b->branch->name }} &middot; @endif{{ $b->booking_date ? $b->booking_date->format('d M Y') : '' }})</span>
                                                </div>
                                                <span class="badge bg-{{ $sc }} bg-opacity-25 text-{{ $sc }} border border-{{ $sc }} rounded-pill px-2.5 py-1 small flex-shrink-0">{{ strtoupper($b->status) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning border-0 rounded-3 py-3 px-4 small mb-0 d-flex align-items-center gap-3" style="background: rgba(255, 193, 7, 0.15); color: #ffc107;">
                                    <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                                    <span>@if(app()->getLocale() == 'zh') 暂无近期订单记录，如需常规咨询请选择上方“常规咨询与建议”。 @else No recent bookings found. Please select "General Inquiry" above if this is not regarding a specific order. @endif</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tier 2: Issue Sub-Type -->
                        <div class="col-12 mb-1">
                            <label class="form-label d-block mb-2 fw-bold" style="color:rgba(255,255,255,.9);">@if(app()->getLocale() == 'zh') 3. 问题细分 / Issue Type @else Issue Type @endif <span class="text-danger">*</span></label>
                            <input type="hidden" name="type" id="modal-hc-issue-type" value="{{ old('type', 'service_quality') }}" required>
                            
                            <!-- Booking Related Topics -->
                            <div class="d-flex flex-wrap gap-2.5 hc-topic-group" id="modal-hc-group-booking">
                                <button type="button" class="topic-pill-btn active" data-value="service_quality">
                                    <i class="fa-solid fa-wrench"></i>
                                    <span>@if(app()->getLocale() == 'zh') 服务质量问题 @else Service Quality @endif</span>
                                </button>
                                <button type="button" class="topic-pill-btn" data-value="overcharge">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                    <span>@if(app()->getLocale() == 'zh') 收费/发票争议 @else Billing / Invoice @endif</span>
                                </button>
                                <button type="button" class="topic-pill-btn" data-value="parts_issue">
                                    <i class="fa-solid fa-gear"></i>
                                    <span>@if(app()->getLocale() == 'zh') 配件/耗材异常 @else Parts Issue @endif</span>
                                </button>
                            </div>

                            <!-- General Topics -->
                            <div class="d-flex flex-wrap gap-2.5 hc-topic-group d-none" id="modal-hc-group-general">
                                <button type="button" class="topic-pill-btn" data-value="general">
                                    <i class="fa-regular fa-comments"></i>
                                    <span>@if(app()->getLocale() == 'zh') 常规咨询 @else General Inquiry @endif</span>
                                </button>
                                <button type="button" class="topic-pill-btn" data-value="other">
                                    <i class="fa-solid fa-ellipsis"></i>
                                    <span>@if(app()->getLocale() == 'zh') 其它建议/问题 @else Other @endif</span>
                                </button>
                            </div>
                            @error('type')<div class="text-warning small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <i class="fa-solid fa-heading position-absolute top-50 translate-middle-y ms-3" style="color:rgba(255,255,255,.35);font-size:.95rem;"></i>
                                <input type="text" name="subject" class="form-control ps-5 py-2.5"
                                       placeholder="{{ __('landing.help_subject_ph') }}"
                                       value="{{ old('subject') }}" minlength="5" maxlength="255" required>
                            </div>
                            @error('subject')<div class="text-warning small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                <span>Message <span class="text-danger">*</span></span>
                                <span id="modal-hc-char-count" style="font-size:.75rem;color:rgba(255,255,255,.45);font-weight:400;">0 / 3000</span>
                            </label>
                            <textarea name="description" id="modal-hc-description" class="form-control p-3" rows="5"
                                      placeholder="{{ __('landing.help_message_ph') }}"
                                      minlength="10" maxlength="3000" style="resize:vertical;" required>{{ old('description') }}</textarea>
                            @error('description')<div class="text-warning small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Attachments <span style="color:rgba(255,255,255,.45);font-size:.8rem;">{{ __('landing.help_attach_opt') }}</span></label>
                            <div class="hc-drop-zone" id="modal-hc-drop-zone" onclick="document.getElementById('modal-hc-file-input').click()">
                                <div class="dz-state-default d-flex flex-column align-items-center justify-content-center pointer-events-none w-100">
                                    <div class="dz-icon-circle">
                                        <i class="fa-solid fa-cloud-arrow-up fs-4" style="color: #EC1F24;"></i>
                                    </div>
                                    <span class="fw-bold text-white fs-6 mb-1">{{ __('landing.help_drag_files') }}</span>
                                    <span style="font-size:.78rem;opacity:.6;">Supported formats: JPG, PNG, PDF (max 10 MB each, up to 5 files)</span>
                                </div>
                                <div class="dz-state-active d-none flex-column align-items-center justify-content-center pointer-events-none w-100 py-2">
                                    <div class="dz-pulse-circle">
                                        <i class="fa-solid fa-file-arrow-down fs-2 text-white"></i>
                                    </div>
                                    <span class="fw-bolder text-white fs-5 tracking-wide mb-1">@if(app()->getLocale() == 'zh') 释放鼠标即刻添加凭证 @else DROP FILES HERE TO ATTACH @endif</span>
                                    <span class="badge bg-white fw-bold rounded-pill px-3 py-1 shadow-sm mt-1" style="color: #EC1F24;">@if(app()->getLocale() == 'zh') ✨ 准备就绪，松开即可 @else ✨ Ready to attach! @endif</span>
                                </div>
                                <input type="file" id="modal-hc-file-input" name="attachments[]" multiple
                                       accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" style="display:none">
                            </div>
                            <div id="modal-hc-file-previews" class="hc-file-previews"></div>
                            @error('attachments.*')<div class="text-warning small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-4 pt-2">
                            <button type="submit" class="btn fw-bold px-5 py-3 w-100 rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-2" style="font-size:1.05rem; letter-spacing:0.02em; background: #EC1F24; color: #fff; border: none;">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Submit Support Ticket</span>
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-lock fs-1 mb-3 d-block" style="color: #EC1F24;"></i>
                    <p style="color:rgba(255,255,255,.6);">{{ __('landing.help_login_msg') }}</p>
                    <a href="{{ route('login') }}" class="btn fw-bold px-5 py-2.5 rounded-3 shadow" style="background: #EC1F24; color: #fff;">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>Login to Continue
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ─── Modal form: char counter ─── */
    const hcDesc = document.getElementById('modal-hc-description');
    const hcCtr  = document.getElementById('modal-hc-char-count');
    if (hcDesc && hcCtr) {
        hcDesc.addEventListener('input', function() {
            const l = this.value.length;
            hcCtr.textContent = l + ' / 3000';
            hcCtr.style.color = l > 2700 ? '#dc3545' : l > 2400 ? '#fd7e14' : 'rgba(255,255,255,.4)';
        });
    }

    /* ─── Modal form: file upload ─── */
    const hcFi     = document.getElementById('modal-hc-file-input');
    const hcDz     = document.getElementById('modal-hc-drop-zone');
    const hcPreviews = document.getElementById('modal-hc-file-previews');
    let hcFiles = [];

    if (hcFi) hcFi.addEventListener('change', () => hcHandleFiles(Array.from(hcFi.files)));
    if (hcDz) {
        hcDz.addEventListener('dragover', e => { e.preventDefault(); hcDz.classList.add('dragover'); });
        hcDz.addEventListener('dragleave', () => hcDz.classList.remove('dragover'));
        hcDz.addEventListener('drop', function(e) {
            e.preventDefault(); hcDz.classList.remove('dragover');
            hcHandleFiles(Array.from(e.dataTransfer.files));
        });
    }
    async function hcHandleFiles(files) {
        const allowed = ['image/jpeg','image/png','image/gif','image/webp','application/pdf'];
        const max = 10 * 1024 * 1024; let errs = [];
        for (let f of files) {
            if (hcFiles.length >= 5) { errs.push('Max 5 files allowed.'); break; }
            if (!allowed.includes(f.type)) { errs.push(f.name + ': invalid type.'); continue; }
            if (f.size > max) { errs.push(f.name + ': exceeds 10 MB.'); continue; }
            if (window.compressImageFile) f = await window.compressImageFile(f);
            if (!hcFiles.find(s => s.name === f.name && s.size === f.size)) hcFiles.push(f);
        }
        if (errs.length) alert(errs.join('\n'));
        hcRenderPreviews(); hcSyncFi();
    }
    function hcRenderPreviews() {
        if (!hcPreviews) return; hcPreviews.innerHTML = '';
        hcFiles.forEach((f, i) => {
            const d = document.createElement('div');
            d.className = 'hc-file-chip';
            const ico = f.type.startsWith('image/') ? 'fa-image' : 'fa-file-pdf';
            const sizeStr = f.originalSize
                ? `<span style="color:#20c997;font-weight:700;">⚡ ${(f.size/1024).toFixed(0)} KB <s style="opacity:.6;font-weight:400;color:#fff;">(${(f.originalSize/1024/1024).toFixed(1)} MB)</s></span>`
                : `<span style="opacity:.6;font-size:.72rem;">(${(f.size/1024).toFixed(0)} KB)</span>`;
            d.innerHTML = `<i class="fa-solid ${ico} text-brand fs-6" style="color:#EC1F24;"></i><span class="fw-semibold" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</span>${sizeStr}<i class="fa-solid fa-xmark remove-file ms-1" style="cursor:pointer;color:#dc3545;" onclick="hcRemFile(${i})"></i>`;
            hcPreviews.appendChild(d);
        });
    }
    window.hcRemFile = function(i) { hcFiles.splice(i, 1); hcRenderPreviews(); hcSyncFi(); };
    function hcSyncFi() {
        const dt = new DataTransfer();
        hcFiles.forEach(f => dt.items.add(f));
        if (hcFi) hcFi.files = dt.files;
    }

    /* ─── 2-Tier Category & Topic Selection ─── */
    const catBtns = document.querySelectorAll('#supportTicketModal .hc-cat-btn');
    const bookingContainer = document.getElementById('modal-hc-booking-select-container');
    const bookingInput = document.getElementById('modal-hc-booking-id');
    const groupBooking = document.getElementById('modal-hc-group-booking');
    const groupGeneral = document.getElementById('modal-hc-group-general');
    const issueInput = document.getElementById('modal-hc-issue-type');
    const subjectInput = document.querySelector('#supportTicketModal input[name="subject"]');

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            catBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const cat = this.dataset.category;
            if (cat === 'booking') {
                if (bookingContainer) bookingContainer.classList.remove('d-none');
                if (groupBooking) groupBooking.classList.remove('d-none');
                if (groupGeneral) groupGeneral.classList.add('d-none');
                
                const firstBookingTopic = groupBooking?.querySelector('.topic-pill-btn');
                if (firstBookingTopic) {
                    groupBooking.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
                    firstBookingTopic.classList.add('active');
                    if (issueInput) issueInput.value = firstBookingTopic.dataset.value;
                }
            } else {
                if (bookingContainer) bookingContainer.classList.add('d-none');
                if (bookingInput) bookingInput.value = '';
                const triggerText = document.getElementById('modal-booking-dropdown-text');
                if (triggerText) triggerText.innerHTML = '-- Select a booking --';
                if (groupBooking) groupBooking.classList.add('d-none');
                if (groupGeneral) groupGeneral.classList.remove('d-none');

                const firstGenTopic = groupGeneral?.querySelector('.topic-pill-btn');
                if (firstGenTopic) {
                    groupGeneral.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
                    firstGenTopic.classList.add('active');
                    if (issueInput) issueInput.value = firstGenTopic.dataset.value;
                }
            }
        });
    });

    // Custom Booking Dropdown logic
    const bookingTrigger = document.getElementById('modal-booking-dropdown-trigger');
    const bookingMenu = document.getElementById('modal-booking-dropdown-menu');
    const bookingText = document.getElementById('modal-booking-dropdown-text');

    if (bookingTrigger && bookingMenu) {
        bookingTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('open');
            bookingMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!bookingTrigger.contains(e.target) && !bookingMenu.contains(e.target)) {
                bookingTrigger.classList.remove('open');
                bookingMenu.classList.remove('show');
            }
        });

        bookingMenu.querySelectorAll('.booking-dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const val = this.dataset.value;
                const num = this.dataset.number;
                const svc = this.dataset.service;

                if (bookingInput) bookingInput.value = val;
                if (bookingText) {
                    if (val) {
                        bookingText.style.color = '#ffffff';
                        bookingText.innerHTML = `<span class="fw-bold font-monospace" style="color:#EC1F24;">#${num}</span> &middot; ${svc}`;
                    } else {
                        bookingText.style.color = 'rgba(255,255,255,.6)';
                        bookingText.innerHTML = '-- Select a booking --';
                    }
                }
                if (val && subjectInput) {
                    subjectInput.value = `Booking #${num} - ${svc}`;
                }
                bookingTrigger.classList.remove('open');
                bookingMenu.classList.remove('show');
            });
        });
    }

    // Topic pills click behavior
    document.querySelectorAll('#supportTicketModal .topic-pill-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const parent = this.closest('.hc-topic-group');
            if (parent) {
                parent.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
            } else {
                document.querySelectorAll('#supportTicketModal .topic-pill-btn').forEach(b => b.classList.remove('active'));
            }
            this.classList.add('active');
            if (issueInput) issueInput.value = this.dataset.value;
        });
    });
});
</script>
@endsection
