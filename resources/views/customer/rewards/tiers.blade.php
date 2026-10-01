@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
<style>
    .tiers-hero {
        background: linear-gradient(135deg, #1a1b20 0%, #2d1f3d 100%);
        border-radius: 1.25rem;
        padding: 4rem 2rem;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .tiers-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(236,31,36,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Hit-Area Buffer: anchors hover to a static transparent zone around the grid column to prevent edge bouncing */
    .row.g-4 .col-md-4 { position: relative; }
    .row.g-4 .col-md-4::before {
        content: '';
        position: absolute;
        top: -18px; bottom: -18px; left: -18px; right: -18px;
        z-index: 1;
        pointer-events: auto;
    }
    .tier-card {
        background: #fff;
        border-radius: 1.25rem;
        padding: 2.5rem 2rem;
        height: 100%;
        transition: transform 0.28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.28s ease, border-color 0.28s ease;
        will-change: transform, box-shadow;
        backface-visibility: hidden;
        -webkit-font-smoothing: antialiased;
        border: 1px solid #f0f0f0;
        position: relative;
        z-index: 2;
        overflow: hidden;
    }
    .col-md-4:hover .tier-card, .tier-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    
    .tier-card.bronze { border-top: 6px solid #cd7f32; }
    .col-md-4:hover .tier-card.bronze, .tier-card.bronze:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(205, 127, 50, 0.28) !important; border-color: rgba(205, 127, 50, 0.4) !important; }
    .tier-card.silver { border-top: 6px solid #c0c0c0; }
    .col-md-4:hover .tier-card.silver, .tier-card.silver:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(192, 192, 192, 0.28) !important; border-color: rgba(192, 192, 192, 0.4) !important; }
    
    .tier-card.gold { border-top: 6px solid #ffd700; z-index: 10; box-shadow: 0 10px 30px rgba(255,215,0,0.15); }
    @media (min-width: 768px) {
        .tier-card.gold { padding: 3.1rem 2.2rem; margin: -14px 0; }
    }
    .col-md-4:hover .tier-card.gold, .tier-card.gold:hover { transform: translateY(-6px); box-shadow: 0 22px 45px rgba(255, 215, 0, 0.35) !important; border-color: rgba(255, 215, 0, 0.5) !important; }

    .tier-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: #fff;
    }
    .bronze .tier-icon-wrapper { background: linear-gradient(135deg, #cd7f32, #8b5a2b); }
    .silver .tier-icon-wrapper { background: linear-gradient(135deg, #c0c0c0, #7a7a7a); }
    .gold .tier-icon-wrapper   { background: linear-gradient(135deg, #ffd700, #b8860b); }

    .tier-price {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    .tier-subtitle {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    .benefit-list {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }
    .benefit-list li {
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        color: #374151;
    }
    .benefit-list li i {
        color: #10b981;
        margin-top: 0.25rem;
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    .current-tier-badge {
        position: absolute;
        top: 15px;
        right: -35px;
        background: #111827;
        color: #fff;
        font-size: 0.75rem;
        font-weight: bold;
        padding: 5px 40px;
        transform: rotate(45deg);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (prefers-color-scheme: dark) {
        .tier-card { background: #1e293b; border-color: rgba(255,255,255,0.05); }
        .tier-price { color: #f9fafb; }
        .benefit-list li { color: #cbd5e1; }
    }
    [data-bs-theme="dark"] .tier-card { background: #1e293b; border-color: rgba(255,255,255,0.05); }
    [data-bs-theme="dark"] .tier-price { color: #f9fafb; }
    [data-bs-theme="dark"] .benefit-list li { color: #cbd5e1; }
    .acct-dark .tier-card { background:#111 !important; border-color: rgba(255,255,255,.08) !important; }
    .acct-dark .tier-card.bronze { border-top-color:#cd7f32 !important; }
    .acct-dark .tier-card.silver { border-top-color:#c0c0c0 !important; }
    .acct-dark .tier-card.gold { border-top-color:#ffd700 !important; }
    .acct-dark .tier-price { color:#f9fafb !important; }
    .acct-dark .tier-name { color:#f4f4f5 !important; }
    .acct-dark .benefit-list li { color:#cbd5e1 !important; }

    /* ===== MOBILE-SPECIFIC TIERS OVERHAUL ===== */
    @media (max-width: 767.98px) {
        .tiers-hero { padding: 2.25rem 1.25rem !important; border-radius: 1rem !important; }
        .tier-card { padding: 1.5rem 1.25rem !important; border-radius: 1rem !important; cursor: pointer; }
        .tier-card.gold { transform: none; box-shadow: 0 10px 25px rgba(255,215,0,0.12) !important; margin: 0 !important; }
        .tier-card:active, .tier-card:hover { transform: translateY(-6px) !important; }
        .tier-card.bronze:active, .tier-card.bronze:hover { box-shadow: 0 16px 38px rgba(205, 127, 50, 0.35) !important; border-color: rgba(205, 127, 50, 0.5) !important; border-top-color: #cd7f32 !important; }
        .tier-card.silver:active, .tier-card.silver:hover { box-shadow: 0 16px 38px rgba(192, 192, 192, 0.35) !important; border-color: rgba(192, 192, 192, 0.5) !important; border-top-color: #c0c0c0 !important; }
        .tier-card.gold:active, .tier-card.gold:hover { transform: translateY(-6px) !important; box-shadow: 0 18px 42px rgba(255, 215, 0, 0.38) !important; border-color: rgba(255, 215, 0, 0.55) !important; border-top-color: #ffd700 !important; }
        .tier-icon-wrapper { width: 56px !important; height: 56px !important; font-size: 1.6rem !important; margin-bottom: 1rem !important; }
        .tier-price { font-size: 1.35rem !important; }
        .tier-price span { position: static !important; display: inline-block !important; margin-left: 0.25rem !important; font-size: 0.8rem !important; }
        .benefit-list li { margin-bottom: 0.75rem !important; font-size: 0.88rem !important; }
        .current-tier-badge { top: 12px; right: -30px; font-size: 0.65rem; padding: 4px 35px; }
    }
</style>
@endsection

@section('content')
<div class="container my-5 acct-dark">
    {{-- Back to Rewards --}}
    <div class="mb-4">
        <a href="{{ route('rewards.index') }}" class="text-decoration-none text-muted">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('landing.tier_back_rewards') }}
        </a>
    </div>

    {{-- Hero --}}
    <div class="tiers-hero mb-5">
        <h1 class="fw-bold mb-3">{{ __('landing.tier_unlock_benefits') }}</h1>
        <p class="lead mb-0 opacity-75 max-w-2xl mx-auto" style="max-width: 600px;">
            {{ __('landing.tier_hero_desc') }}
        </p>
    </div>

    {{-- Pricing/Tiers Cards --}}
    <div class="row g-4 justify-content-center align-items-stretch mb-5">
        
        {{-- Bronze --}}
        <div class="col-md-4 order-1 order-md-1">
            <div class="tier-card bronze text-center h-100 d-flex flex-column {{ $membership->tier === 'bronze' ? 'current' : '' }}">
                @if($membership->tier === 'bronze')
                    <div class="current-tier-badge">{{ __('landing.tier_current') }}</div>
                @endif
                <div class="tier-icon-wrapper">
                    <i class="fa-solid fa-medal"></i>
                </div>
                <h3 class="fw-bold">{{ __('landing.tier_bronze') }}</h3>
                <div class="tier-price mb-2">{{ __('landing.tier_free_for_all') }}</div>
                <div class="tier-subtitle">{{ __('landing.tier_bronze_subtitle') }}</div>
                
                <hr class="text-muted opacity-25 my-4">
                
                <ul class="benefit-list">
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_earn') }} 1 {{ __('landing.tier_reward_point') }} {{ __('landing.tier_for_every_rm1') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_bronze_b2') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_bronze_b3') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_bronze_b4') }}</span></li>
                </ul>
            </div>
        </div>

        {{-- Gold (Center on desktop, 3rd on mobile) --}}
        <div class="col-md-4 order-3 order-md-2">
            <div class="tier-card gold text-center h-100 d-flex flex-column {{ $membership->tier === 'gold' ? 'current' : '' }}">
                @if($membership->tier === 'gold')
                    <div class="current-tier-badge">{{ __('landing.tier_current') }}</div>
                @endif
                <div class="tier-icon-wrapper">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <h3 class="fw-bold">{{ __('landing.tier_gold') }}</h3>
                <div class="tier-price mb-2">
                    <div class="position-relative d-inline-block">
                        RM 1,500
                        <span class="text-muted fs-6 fw-normal position-absolute" style="bottom: 0.2rem; right: -3.5rem; white-space: nowrap;">{{ __('landing.tier_per_year') }}</span>
                    </div>
                </div>
                <div class="tier-subtitle">{{ __('landing.tier_gold_subtitle') }}</div>
                
                <hr class="text-muted opacity-25 my-4">
                
                <ul class="benefit-list">
                    <li><i class="fa-solid fa-check-circle"></i> <span><strong>{{ __('landing.tier_earn') }} 1.5× {{ __('landing.tier_reward_points') }}</strong> {{ __('landing.tier_for_every_rm1') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_receive') }} <strong>3</strong> {{ __('landing.tier_birthday_vouchers') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_gold_b3') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_gold_b4') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_unlock') }} <strong>{{ __('landing.tier_gold_exclusive') }}</strong> {{ __('landing.tier_high_value_vouchers') }}</span></li>
                </ul>
                
                @if($membership->tier !== 'gold')
                    <div class="mt-auto pt-4 text-warning small fw-bold">
                        RM {{ number_format(max(0, 1500 - $membership->cumulative_annual_spending), 0) }} {{ __('landing.tier_more_to_unlock') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Silver (Right on desktop, 2nd on mobile) --}}
        <div class="col-md-4 order-2 order-md-3">
            <div class="tier-card silver text-center h-100 d-flex flex-column {{ $membership->tier === 'silver' ? 'current' : '' }}">
                @if($membership->tier === 'silver')
                    <div class="current-tier-badge">{{ __('landing.tier_current') }}</div>
                @endif
                <div class="tier-icon-wrapper">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3 class="fw-bold">{{ __('landing.tier_silver') }}</h3>
                <div class="tier-price mb-2">
                    <div class="position-relative d-inline-block">
                        RM 500
                        <span class="text-muted fs-6 fw-normal position-absolute" style="bottom: 0.2rem; right: -3.5rem; white-space: nowrap;">{{ __('landing.tier_per_year') }}</span>
                    </div>
                </div>
                <div class="tier-subtitle">{{ __('landing.tier_silver_subtitle') }}</div>
                
                <hr class="text-muted opacity-25 my-4">
                
                <ul class="benefit-list">
                    <li><i class="fa-solid fa-check-circle"></i> <span><strong>{{ __('landing.tier_earn') }} 1.25× {{ __('landing.tier_reward_points') }}</strong> {{ __('landing.tier_for_every_rm1') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_receive') }} 1 {{ __('landing.tier_birthday_voucher_single') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_silver_b3') }}</span></li>
                    <li><i class="fa-solid fa-check-circle"></i> <span>{{ __('landing.tier_unlock') }} <strong>{{ __('landing.tier_silver_exclusive') }}</strong> {{ __('landing.tier_high_value_vouchers') }}</span></li>
                </ul>
                
                @if($membership->tier === 'bronze')
                    <div class="mt-auto pt-4 text-secondary small fw-bold">
                        RM {{ number_format(max(0, 500 - $membership->cumulative_annual_spending), 0) }} {{ __('landing.tier_more_to_unlock') }}
                    </div>
                @endif
            </div>
        </div>

    </div>
    
    {{-- FAQs --}}
    <div class="card border-0 shadow-sm rounded-4 mt-5">
        <div class="card-body p-4 p-md-5">
            <h3 class="fw-bold mb-4 text-center">{{ __('landing.help_faq_title') }}</h3>
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="fw-bold fs-6">{{ __('landing.tier_faq_q1') }}</h5>
                    <p class="text-muted small">{{ __('landing.tier_faq_a1') }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold fs-6">{{ __('landing.tier_faq_q2') }}</h5>
                    <p class="text-muted small">{{ __('landing.tier_faq_a2') }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold fs-6">{{ __('landing.tier_faq_q3') }}</h5>
                    <p class="text-muted small">{{ __('landing.tier_faq_a3') }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold fs-6">{{ __('landing.tier_faq_q4') }}</h5>
                    <p class="text-muted small">{{ __('landing.tier_faq_a4') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
