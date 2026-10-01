@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
<style>
    .voucher-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }
    .voucher-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .voucher-card .voucher-notch-left,
    .voucher-card .voucher-notch-right {
        position: absolute;
        top: 50%;
        width: 20px;
        height: 20px;
        background: #f8f9fa;
        border-radius: 50%;
        transform: translateY(-50%);
        z-index: 2;
    }
    .voucher-card .voucher-notch-left { left: -10px; }
    .voucher-card .voucher-notch-right { right: -10px; }

    .voucher-left {
        background: linear-gradient(135deg, #EC1F24, #c81519);
        min-width: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 1.25rem 0.75rem;
        border-right: 2px dashed rgba(255,255,255,0.3);
    }
    .voucher-left .value-text {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .voucher-left .unit-text {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.8);
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .voucher-right {
        padding: 1rem 1.25rem;
        flex: 1;
        background: #fff;
        position: relative;
    }
    .voucher-status-corner {
        position: absolute;
        top: 14px;
        right: 14px;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        z-index: 3;
    }
    .voucher-top-info {
        padding-right: 110px;
    }
    @media (max-width: 575.98px) {
        .voucher-right { padding: 0.85rem 1rem !important; }
        .voucher-status-corner { top: 10px !important; right: 10px !important; }
        .voucher-top-info { padding-right: 100px !important; }
    }
    .voucher-used .voucher-left { background: linear-gradient(135deg, #6b7280, #4b5563); }
    .voucher-used .voucher-right { background: #f9fafb; }
    .voucher-expired .voucher-left { background: linear-gradient(135deg, #ef4444, #b91c1c); opacity: 0.6; }
    .voucher-expired .voucher-right { background: #fef2f2; opacity: 0.7; }

    .voucher-code {
        font-family: monospace;
        font-size: 0.85rem;
        background: #f3f4f6;
        padding: 3px 8px;
        border-radius: 6px;
        color: #374151;
        font-weight: 600;
        letter-spacing: 1px;
    }
    [data-bs-theme="dark"] .voucher-right { background: #1e293b; }
    [data-bs-theme="dark"] .voucher-code { background: #374151; color: #e2e8f0; }
    [data-bs-theme="dark"] .voucher-card .voucher-notch-left,
    [data-bs-theme="dark"] .voucher-card .voucher-notch-right { background: #0f172a; }

    @keyframes pulse-danger {
        0% { opacity: 1; }
        50% { opacity: 0.5; color: #dc3545; }
        100% { opacity: 1; }
    }
    .expiring-pulse {
        animation: pulse-danger 1.5s infinite;
        background: #f8d7da;
        color: #842029;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }
    .acct-dark .voucher-card .voucher-notch-left,
    .acct-dark .voucher-card .voucher-notch-right { background: #0a0a0a !important; }
    .acct-dark .voucher-right { background: #1b1b1b !important; color: #e4e4e7 !important; }
    .acct-dark .voucher-used .voucher-right { background: #161616 !important; }
    .acct-dark .voucher-expired .voucher-right { background: #241313 !important; }
    .acct-dark .voucher-code { background: #2a2a2a !important; color: #e2e8f0 !important; }
    .acct-dark .nav-tabs { border: 0 !important; }
    .acct-dark .nav-tabs .nav-link { color: #d4d4d8 !important; }
    .acct-dark .nav-tabs .nav-link.active { color: #fff !important; background-color: #e5322d !important; }
</style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_vouchers') => null]" />
@endsection

@section('content')
<div class="container my-3 my-md-5 acct-dark">
    @include('partials.mobile-account-tabs')
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 mb-4 order-2 order-md-1">
            @include('partials.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-12 col-md-9 order-1 order-md-2">
            {{-- Header --}}
            <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 h-100 d-flex flex-column justify-content-center">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-1"><i class="fa-solid fa-ticket text-brand me-2"></i>{{ __('dashboard.rw_my_vouchers') }}</h2>
                        <p class="text-white-50 mb-0">{{ __('account.vouchers_subtitle') }}</p>
                    </div>
                    <a href="{{ route('rewards.index') }}" class="btn btn-brand rounded-pill px-4">
                        <i class="fa-solid fa-gift me-2"></i>{{ __('rewards.idx_earn_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Add a Voucher (Redeem Promo Code) --}}
    <div class="row mb-4">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 d-flex flex-column justify-content-center">
                <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-tag text-brand me-2"></i>{{ __('dashboard.rw_add_voucher') }}</h6>
                <form action="{{ route('rewards.redeemCode') }}" method="POST" class="mb-0">
                    @csrf
                    <div class="input-group shadow-sm" style="border-radius: 8px;">
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="{{ __('dashboard.rw_enter_promo') }}" maxlength="20" required style="border-radius: 8px 0 0 8px; border: 1px solid #dee2e6; border-right: none;">
                        <button type="submit" class="btn btn-brand px-4" style="border-radius: 0 8px 8px 0;">
                            {{ __('dashboard.rw_claim_code') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs border-0 mb-4 bg-white shadow-sm p-1 rounded-pill d-inline-flex gap-1" id="voucherTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active border-0 rounded-pill fw-bold px-4" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                {{ __('rewards.idx_subtab_active') }} <span class="badge bg-brand rounded-pill ms-1">{{ $availableVouchers->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link border-0 rounded-pill fw-bold text-secondary px-4" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                {{ __('rewards.idx_subtab_past') }} <span class="badge bg-secondary rounded-pill ms-1">{{ $usedVouchers->count() + $expiredVouchers->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="voucherTabsContent">

        {{-- Active --}}
        <div class="tab-pane fade show active" id="active" role="tabpanel">
            @forelse($availableVouchers as $voucher)
            <div class="card voucher-card border-0 shadow-sm mb-3 d-flex flex-row">
                <div class="voucher-notch-left"></div>
                <div class="voucher-left">
                    @if($voucher->type === 'fixed')
                        <div class="unit-text">{{ __('rewards.vouchers_rm') }}</div>
                        <div class="value-text">{{ number_format($voucher->value, 0) }}</div>
                    @else
                        <div class="value-text">{{ number_format($voucher->value, 0) }}%</div>
                        <div class="unit-text">{{ __('rewards.vouchers_off') }}</div>
                    @endif
                    <div class="unit-text mt-1" style="opacity:0.7;">{{ __('dashboard.rw_badge_voucher') }}</div>
                </div>
                <div class="voucher-right d-flex flex-column justify-content-center">
                    <div class="voucher-status-corner">
                         <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">{{ __('rewards.modal_active') }}</span>
                        <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm voucher-info-btn d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;"
                                data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                data-code="{{ $voucher->code }}"
                                data-discount="{{ $voucher->getDiscountLabel() }}"
                                data-source="{{ $voucher->getSourceLabel() }}"
                                data-status="{{ __('rewards.modal_active') }}"
                                data-status-class="bg-success"
                                data-expiry="{{ $voucher->expires_at ? $voucher->expires_at->format('d M Y') : __('dashboard.rw_no_expiry') }}"
                                data-tnc="{{ $voucher->getTermsAndConditions() }}"
                                title="{{ __('rewards.modal_voucher_details_tnc') }}">
                            <i class="fa-solid fa-circle-info text-brand fs-6"></i>
                        </button>
                    </div>
                    <div class="voucher-top-info mb-2">
                        <div class="fw-bold">{{ $voucher->getDiscountLabel() }}</div>
                        <div class="text-muted small">{{ $voucher->getSourceLabel() }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top border-secondary-subtle border-opacity-10">
                        <div>
                            <span class="voucher-code">{{ $voucher->code }}</span>
                        </div>
                        <div class="text-end">
                            @if($voucher->expires_at)
                                @php $days = $voucher->getDaysUntilExpiry(); @endphp
                                <div class="small {{ $days <= 7 ? 'expiring-pulse fw-bold' : 'text-muted' }}">
                                    @if($days <= 0)
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i>{{ __('rewards.idx_expires_today') }}
                                    @elseif($days <= 7)
                                        <i class="fa-solid fa-clock me-1"></i>{{ __('rewards.idx_expires_in_days', ['days' => $days]) }}
                                    @else
                                        {{ __('rewards.idx_expires_on', ['date' => $voucher->expires_at->format('d M Y')]) }}
                                    @endif
                                </div>
                            @else
                                <div class="text-muted small">{{ __('dashboard.rw_no_expiry') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="voucher-notch-right"></div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-ticket fa-3x mb-3 d-block"></i>
                <p>{{ __('rewards.idx_no_active_vouchers') }} <a href="{{ route('rewards.index') }}">{{ __('rewards.idx_go_earn_some') }}</a></p>
            </div>
            @endforelse
        </div>

        {{-- Past --}}
        <div class="tab-pane fade" id="past" role="tabpanel">
            @if($usedVouchers->count() > 0 || $expiredVouchers->count() > 0)
                @foreach($usedVouchers as $voucher)
                <div class="card voucher-card voucher-used border-0 shadow-sm mb-3 d-flex flex-row">
                    <div class="voucher-notch-left"></div>
                    <div class="voucher-left">
                        @if($voucher->type === 'fixed')
                            <div class="unit-text">{{ __('rewards.vouchers_rm') }}</div>
                            <div class="value-text">{{ number_format($voucher->value, 0) }}</div>
                        @else
                            <div class="value-text">{{ number_format($voucher->value, 0) }}%</div>
                            <div class="unit-text">{{ __('rewards.vouchers_off') }}</div>
                        @endif
                        <div class="unit-text mt-1" style="opacity:0.7;">{{ __('rewards.vouchers_used') }}</div>
                    </div>
                    <div class="voucher-right d-flex flex-column justify-content-center">
                        <div class="voucher-status-corner">
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1">{{ __('rewards.vouchers_used_badge') }}</span>
                            <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm voucher-info-btn d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;"
                                    data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                    data-code="{{ $voucher->code }}"
                                    data-discount="{{ $voucher->getDiscountLabel() }}"
                                    data-source="{{ $voucher->getSourceLabel() }}"
                                    data-status="{{ __('rewards.vouchers_used_badge') }}"
                                    data-status-class="bg-secondary"
                                    data-expiry="{{ $voucher->used_at ? __('rewards.vouchers_used_on', ['date' => $voucher->used_at->format('d M Y')]) : 'N/A' }}"
                                    data-tnc="{{ $voucher->getTermsAndConditions() }}"
                                    title="{{ __('rewards.modal_voucher_details_tnc') }}">
                                <i class="fa-solid fa-circle-info text-secondary fs-6"></i>
                            </button>
                        </div>
                        <div class="voucher-top-info mb-2">
                            <div class="fw-bold text-muted">{{ $voucher->getDiscountLabel() }}</div>
                            <div class="text-muted small">{{ $voucher->getSourceLabel() }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top border-secondary-subtle border-opacity-10">
                            <div>
                                <span class="voucher-code">{{ $voucher->code }}</span>
                            </div>
                            <div class="text-end">
                                @if($voucher->used_at)
                                    <div class="text-muted small">{{ __('rewards.vouchers_used_on', ['date' => $voucher->used_at->format('d M Y')]) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="voucher-notch-right"></div>
                </div>
                @endforeach

                @foreach($expiredVouchers as $voucher)
                <div class="card voucher-card voucher-expired border-0 shadow-sm mb-3 d-flex flex-row" style="opacity:0.65;">
                    <div class="voucher-notch-left"></div>
                    <div class="voucher-left">
                        @if($voucher->type === 'fixed')
                            <div class="unit-text">{{ __('rewards.vouchers_rm') }}</div>
                            <div class="value-text">{{ number_format($voucher->value, 0) }}</div>
                        @else
                            <div class="value-text">{{ number_format($voucher->value, 0) }}%</div>
                            <div class="unit-text">{{ __('rewards.vouchers_off') }}</div>
                        @endif
                    </div>
                    <div class="voucher-right d-flex flex-column justify-content-center">
                        <div class="voucher-status-corner">
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">{{ __('rewards.vouchers_expired_badge') }}</span>
                            <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm voucher-info-btn d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;"
                                    data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                    data-code="{{ $voucher->code }}"
                                    data-discount="{{ $voucher->getDiscountLabel() }}"
                                    data-source="{{ $voucher->getSourceLabel() }}"
                                    data-status="{{ __('rewards.vouchers_expired_badge') }}"
                                    data-status-class="bg-danger"
                                    data-expiry="{{ $voucher->expires_at ? __('rewards.vouchers_expired_on', ['date' => $voucher->expires_at->format('d M Y')]) : 'N/A' }}"
                                    data-tnc="{{ $voucher->getTermsAndConditions() }}"
                                    title="{{ __('rewards.modal_voucher_details_tnc') }}">
                                <i class="fa-solid fa-circle-info text-danger fs-6"></i>
                            </button>
                        </div>
                        <div class="voucher-top-info mb-2">
                            <div class="fw-bold text-danger">{{ $voucher->getDiscountLabel() }}</div>
                            <div class="text-muted small">{{ $voucher->getSourceLabel() }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top border-secondary-subtle border-opacity-10">
                            <div>
                                <span class="voucher-code">{{ $voucher->code }}</span>
                            </div>
                            <div class="text-end">
                                @if($voucher->expires_at)
                                    <div class="text-muted small">{{ __('rewards.vouchers_expired_on', ['date' => $voucher->expires_at->format('d M Y')]) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="voucher-notch-right"></div>
                </div>
                @endforeach
            @else
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-clock fa-3x mb-3 d-block"></i>
                <p>{{ __('rewards.idx_no_past_vouchers') }}</p>
            </div>
            @endif
        </div>

        </div> {{-- End col-md-9 --}}
    </div> {{-- End row --}}
</div>
@include('partials.voucher-tnc-modal')
@endsection
