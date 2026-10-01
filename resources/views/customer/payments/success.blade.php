@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@endsection

@section('content')
<div class="container my-5 acct-dark">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-circle-check text-success fs-1"></i>
                    </div>
                </div>

                <h3 class="fw-bold text-dark mb-2">{{ __('order.ps_title') }}</h3>
                <p class="text-muted mb-4">
                    {{ __('order.ps_desc_start') }} <strong>{{ __('order.ps_desc_mid') }}{{ $booking->number }}</strong> {{ __('order.ps_desc_end') }}
                    <br>
                    {{ __('order.ps_desc_confirm') }}
                </p>

                <div class="bg-light rounded-3 p-3 mb-4">
                    <div class="row text-start">
                        <div class="col-6">
                            <span class="small text-muted d-block">{{ __('order.ps_amount_paid') }}</span>
                            <strong class="text-brand">RM {{ number_format(max(0, $booking->service_price_at_booking - ($booking->discount_amount ?? 0)), 2) }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="small text-muted d-block">{{ __('order.ps_booking_number') }}</span>
                            <strong class="text-dark">#{{ $booking->number }}</strong>
                        </div>
                    </div>
                </div>

                <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-brand px-4 py-2 fw-bold">
                    <i class="fa-solid fa-arrow-left me-2"></i>{{ __('order.ps_btn_back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
