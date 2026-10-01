@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_bookings') => route('bookings.index'), __('booking.pp_title') => null]" />
@endsection

@section('content')
<div class="container my-5 acct-dark">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('booking.pp_back') }}
            </a>

            @if($failedPayment && !$pendingCounter)
                <div class="alert alert-danger d-flex align-items-start rounded-4 mb-4" role="alert">
                    <i class="fa-solid fa-circle-xmark fs-4 me-3 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">{{ __('booking.pp_failed_title') }}</h6>
                        <span class="small">{{ __('booking.pp_failed_desc') }}</span>
                    </div>
                </div>
            @endif

            @if($pendingCounter)
                <div class="alert alert-info d-flex align-items-start rounded-4 mb-4" role="alert">
                    <i class="fa-solid fa-store fs-4 me-3 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">{{ __('booking.pp_counter_title') }}</h6>
                        <span class="small d-block">{{ __('booking.pp_counter_desc') }}</span>
                        <span class="small text-muted">{{ __('booking.pp_counter_hint') }}</span>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-credit-card text-brand me-2"></i>{{ __('booking.pp_title') }}
                </h3>
                <p class="text-muted mb-4">{{ __('booking.pp_subtitle') }}</p>

                <div class="bg-light rounded-3 p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">{{ __('booking.pp_booking') }}</span>
                        <strong class="text-dark">#{{ $booking->number }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">{{ __('booking.pp_service') }}</span>
                        <strong class="text-dark">{{ optional($booking->service)->name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">@if(app()->getLocale() == 'zh') 支付选项 @else Payment Method @endif</span>
                        <strong class="text-dark">
                            @php
                                $latestPay = $booking->payments()->latest()->first();
                            @endphp
                            @if($latestPay && $latestPay->gateway === 'counter')
                                {{ __('booking.pp_pay_counter') }}
                            @else
                                {{ __('booking.pp_pay_online') }}
                            @endif
                        </strong>
                    </div>
                </div>

                @php 
                    $basePrice = (float) $booking->service_price_at_booking; 
                    $discountAmount = (float) ($booking->discount_amount ?? 0);
                    $finalAmount = max(0, $basePrice - $discountAmount);
                @endphp

                <form action="{{ route('payment.checkout', $booking->uuid) }}" method="POST">
                    @csrf
                    <div class="bg-light rounded-3 p-3 mb-4 text-center">
                        <span class="small text-muted d-block mb-1">{{ __('booking.pp_amount_due') }}</span>
                        @if($discountAmount > 0)
                            <div class="small text-success mb-1">
                                <i class="fa-solid fa-tag me-1"></i>Voucher Discount: -RM {{ number_format($discountAmount, 2) }}
                            </div>
                        @endif
                        <h3 class="fw-bold text-brand mb-0">RM <span id="finalAmount">{{ number_format($finalAmount, 2) }}</span></h3>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold mb-2 rounded-3 shadow-sm">
                        <i class="fa-solid fa-credit-card me-2"></i>@if(app()->getLocale() == 'zh') 立即在线刷卡付款 (Stripe) @else Retry / Pay Online (Stripe) @endif
                    </button>
                </form>

                <form action="{{ route('payment.switch', $booking->uuid) }}" method="POST" class="mb-2">
                    @csrf
                    <input type="hidden" name="method" value="counter">
                    <button type="submit" class="btn btn-dark w-100 py-2.5 fw-semibold rounded-3">
                        <i class="fa-solid fa-store me-2"></i>@if(app()->getLocale() == 'zh') 更改为：到店柜台付款 @else Switch to: Pay at Counter @endif
                    </button>
                </form>

                <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-outline-secondary w-100 py-2 fw-bold rounded-3 d-block text-center">
                    @if(app()->getLocale() == 'zh') 返回预约详情 (暂不支付) @else Back to Booking @endif
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
