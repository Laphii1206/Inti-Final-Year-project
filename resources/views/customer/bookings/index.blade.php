@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_bookings') => null]" />
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
            {{-- Header Card --}}
            <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 mb-4 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fa-solid fa-calendar-check text-brand me-2"></i>{{ __('account.profile_sidebar_my_bookings') }}</h2>
                    <p class="text-white-50 mb-0">{{ __('account.bk_subtitle') }}</p>
                </div>
                <a href="{{ route('bookings.create') }}" class="btn btn-brand rounded-pill px-4 py-2 fw-semibold shadow-sm">
                    <i class="fa-solid fa-calendar-plus me-2"></i>{{ __('account.bk_new_booking') }}
                </a>
            </div>

            @include('partials.birthday-banner')

            @if(!auth()->user()->phone || !auth()->user()->date_of_birth)
            <div class="card border-0 shadow-sm p-4 rounded-4 mb-4 bg-white border-start border-4 border-warning d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-bell text-warning me-2"></i>{{ __('account.prog_profile_title') ?? 'Complete Your Profile Details' }}</h6>
                    <p class="text-secondary small mb-0">{{ __('account.prog_profile_desc') ?? 'Please add your phone number to receive workshop service alerts, and your birthday date to unlock 2X reward points on your birthday month!' }}</p>
                </div>
                <a href="{{ route('profile.index') }}" class="btn btn-brand text-white rounded-pill px-4 py-2 fw-bold shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-user-pen me-2"></i>{{ __('account.prog_profile_btn') ?? 'Complete Profile Now' }}
                </a>
            </div>
            @endif

            @php
                $activeBooking = $bookings->filter(fn($b) => in_array($b->status, ['pending', 'confirmed', 'in_progress']))->first();
                $trackerKeys = ['pending', 'confirmed', 'in_progress', 'completed'];
            @endphp

            @if($activeBooking)
                <!-- Latest Active Booking Tracker -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white border-start border-4 border-brand">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-spinner fa-spin text-brand me-2"></i>{{ __('account.bk_active_tracker') }}</h6>
                        <span class="badge bg-danger">#{{ $activeBooking->number }}</span>
                    </div>
                    <div class="card-body p-4 pt-0 d-flex flex-column">
                        <div class="mb-4 pb-3 border-bottom">
                            {{-- Row 1: Title & Price + Status Badge --}}
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div class="flex-grow-1 me-2">
                                    <h5 class="fw-bold text-dark mb-1">{{ $activeBooking->service->name }}</h5>
                                    @php
                                        $bClass = match($activeBooking->status) {
                                            'completed' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'cancelled', 'rejected', 'no_show' => 'bg-danger',
                                            'confirmed' => 'bg-info text-dark',
                                            'in_progress' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $bClass }} px-3 py-1.5 rounded-pill shadow-sm">{{ __('booking.db_status_'.$activeBooking->status) }}</span>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <h4 class="fw-bold text-brand mb-0">RM {{ number_format($activeBooking->service_price_at_booking, 2) }}</h4>
                                </div>
                            </div>

                            {{-- Row 2: Vehicle & Date --}}
                            <div class="small text-secondary d-flex flex-wrap align-items-center gap-3 mt-2">
                                <div>
                                    <i class="fa-solid fa-car me-1 text-brand"></i> <span class="fw-medium">{{ $activeBooking->car->brand }} {{ $activeBooking->car->model }} ({{ $activeBooking->car->car_plate }})</span>
                                </div>
                                <div class="text-dark fw-bold">
                                    <i class="fa-solid fa-calendar-days me-1"></i> <span>{{ $activeBooking->booking_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($activeBooking->start_time)->format('h:i A') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Tracker -->
                        <div class="position-relative mt-2 pt-2">
                            <div class="progress" style="height: 4px;">
                                @php
                                    $progress = 0;
                                    if(in_array($activeBooking->status, ['completed'])) $progress = 100;
                                    elseif($activeBooking->status === 'in_progress') $progress = 66;
                                    elseif($activeBooking->status === 'confirmed') $progress = 33;
                                    elseif($activeBooking->status === 'pending') $progress = 0;
                                @endphp
                                <div class="progress-bar bg-brand" role="progressbar" style="width: {{ $progress }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                @foreach($trackerKeys as $index => $key)
                                    @php
                                        $isActive = ($activeBooking->status === $key) || ($progress >= ($index * 33));
                                    @endphp
                                    <div class="text-center position-relative">
                                        <div class="rounded-circle {{ $isActive ? 'bg-brand' : 'bg-secondary' }} d-inline-flex align-items-center justify-content-center text-white mx-auto mb-1" style="width: 24px; height: 24px; font-size: 10px; z-index: 2; position: relative; margin-top: -14px;">
                                            @if($isActive)<i class="fa-solid fa-check"></i>@else<i class="fa-solid fa-circle text-white opacity-25"></i>@endif
                                        </div>
                                        <div class="small fw-semibold {{ $isActive ? 'text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">{{ __('booking.db_status_'.$key) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-end mt-4 d-flex justify-content-end align-items-center gap-2 flex-wrap">
                            @if($activeBooking->hasQrCode())
                                <button type="button" class="btn btn-brand btn-sm px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $activeBooking->uuid }}">
                                    <i class="fa-solid fa-qrcode d-none d-md-inline-block me-md-1"></i><span>{{ __('account.bk_show_qr') }}</span>
                                </button>
                            @endif
                            <a href="{{ route('bookings.show', $activeBooking->uuid) }}" class="btn btn-outline-secondary btn-sm px-4 py-2 fw-medium">
                                <i class="fa-solid fa-circle-info d-none d-md-inline-block me-md-1"></i><span>{{ __('account.bk_view') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking History Row -->
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list text-brand me-2"></i>{{ __('account.bk_appointments_list') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <ul class="nav nav-pills mb-3" id="history-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 fw-medium" id="history-upcoming-tab" data-bs-toggle="pill" data-bs-target="#history-upcoming" type="button" role="tab" aria-selected="true">{{ __('account.bk_tab_upcoming') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-medium" id="history-past-tab" data-bs-toggle="pill" data-bs-target="#history-past" type="button" role="tab" aria-selected="false">{{ __('account.bk_tab_past') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="history-tabContent">
                        <div class="tab-pane fade show active" id="history-upcoming" role="tabpanel" aria-labelledby="history-upcoming-tab">
                            @php $upcomingBookings = $bookings->filter(fn($b) => in_array($b->status, ['pending', 'confirmed', 'in_progress'])); @endphp
                            @if($upcomingBookings->count() > 0)
                                <div class="list-group list-group-flush border-top">
                                    @foreach($upcomingBookings as $booking)
                                        <div class="list-group-item bg-transparent border-bottom py-3 px-0">
                                            {{-- Row 1: Title & Price --}}
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                                <div class="flex-grow-1 me-2">
                                                    <h6 class="fw-bold text-dark mb-1">{{ $booking->service->name }}</h6>
                                                    @php
                                                        $badgeClass = match($booking->status) {
                                                            'pending' => 'bg-warning text-dark',
                                                            'confirmed' => 'bg-info text-dark',
                                                            'in_progress' => 'bg-primary',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ __('booking.db_status_'.$booking->status) }}</span>
                                                </div>
                                                <div class="text-end flex-shrink-0">
                                                    <h6 class="fw-bold text-dark mb-0">RM {{ number_format($booking->service_price_at_booking, 2) }}</h6>
                                                </div>
                                            </div>

                                            {{-- Row 2: Vehicle & Date details --}}
                                            <div class="small text-secondary d-flex flex-wrap align-items-center gap-3 my-2">
                                                <div>
                                                    <i class="fa-solid fa-car me-1 text-brand"></i> <span class="fw-medium">{{ $booking->car->car_plate }}</span>
                                                </div>
                                                <div>
                                                    <i class="fa-solid fa-calendar-days me-1 text-muted"></i> <span>{{ $booking->booking_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                                                </div>
                                            </div>

                                            {{-- Row 3: Action Buttons Bar --}}
                                            <div class="d-flex justify-content-end align-items-center gap-2 mt-3 pt-2 border-top" style="border-color: rgba(128,128,128,0.15) !important;">
                                                @if($booking->hasQrCode())
                                                    <button type="button" class="btn btn-brand btn-sm px-3 py-1.5 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $booking->uuid }}">
                                                        <i class="fa-solid fa-qrcode d-none d-md-inline-block me-md-1"></i><span>{{ __('account.bk_show_qr') }}</span>
                                                    </button>
                                                @endif
                                                <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-medium">
                                                    <i class="fa-solid fa-circle-info d-none d-md-inline-block me-md-1"></i><span>{{ __('account.bk_view') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-calendar-day fs-1 text-muted mb-3 d-block"></i>
                                    <p class="small mb-0">{{ __('account.bk_no_upcoming') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="history-past" role="tabpanel" aria-labelledby="history-past-tab">
                            @php $pastBookings = $bookings->filter(fn($b) => in_array($b->status, ['completed', 'cancelled', 'rejected', 'no_show'])); @endphp
                            @if($pastBookings->count() > 0)
                                <div class="list-group list-group-flush border-top">
                                    @foreach($pastBookings as $booking)
                                        <div class="list-group-item bg-transparent border-bottom py-3 px-0">
                                            {{-- Row 1: Title & Price --}}
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                                <div class="flex-grow-1 me-2">
                                                    <h6 class="fw-bold text-dark mb-1">{{ $booking->service->name }}</h6>
                                                    @php
                                                        $badgeClass = match($booking->status) {
                                                            'completed' => 'bg-success',
                                                            'cancelled', 'no_show' => 'bg-danger',
                                                            'rejected' => 'bg-dark',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ __('booking.db_status_'.$booking->status) }}</span>
                                                </div>
                                                <div class="text-end flex-shrink-0">
                                                    <h6 class="fw-bold text-muted mb-0">RM {{ number_format($booking->service_price_at_booking, 2) }}</h6>
                                                </div>
                                            </div>

                                            {{-- Row 2: Vehicle & Date details --}}
                                            <div class="small text-secondary d-flex flex-wrap align-items-center gap-3 my-2">
                                                <div>
                                                    <i class="fa-solid fa-car me-1 text-muted"></i> <span class="fw-medium">{{ $booking->car->car_plate }}</span>
                                                </div>
                                                <div>
                                                    <i class="fa-solid fa-calendar-days me-1 text-muted"></i> <span>{{ $booking->booking_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                                                </div>
                                            </div>

                                            {{-- Row 3: Action Buttons Bar --}}
                                            <div class="d-flex justify-content-end align-items-center gap-2 mt-3 pt-2 border-top" style="border-color: rgba(128,128,128,0.15) !important;">
                                                <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-medium">
                                                    <i class="fa-solid fa-circle-info d-none d-md-inline-block me-md-1"></i><span>{{ __('account.bk_view') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-clock-rotate-left fs-1 text-muted mb-3 d-block"></i>
                                    <p class="small mb-0">{{ __('account.bk_no_past') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- End col-md-9 --}}
    </div>
</div>

{{-- QR Code Pop-up Modals for Bookings with QR Code --}}
@foreach($bookings as $booking)
    @if($booking->hasQrCode())
        <div class="modal fade acct-dark" id="qrModal-{{ $booking->uuid }}" tabindex="-1" aria-labelledby="qrModalLabel-{{ $booking->uuid }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow text-center p-4 bg-white">
                    <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold text-dark" id="qrModalLabel-{{ $booking->uuid }}">
                            <i class="fa-solid fa-qrcode text-brand me-2"></i>{{ __('account.bk_qr_modal_title') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <span class="badge bg-danger mb-2">#{{ $booking->number }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $booking->service->name }}</h6>
                            <div class="small text-secondary">
                                <i class="fa-solid fa-car me-1 text-brand"></i> {{ $booking->car->brand }} {{ $booking->car->model }} ({{ $booking->car->car_plate }})
                            </div>
                        </div>
                        
                        <p class="small text-muted mb-3">{{ __('account.bk_qr_modal_desc') }}</p>
                        <div class="bg-light p-4 rounded-4 d-inline-block mx-auto mb-3 border shadow-sm" style="min-height: 250px; min-width: 250px; background: #fff !important;">
                            <img src="{{ $booking->getQrCodeUrl() }}" data-base-url="{{ $booking->getQrCodeUrl() }}" alt="Booking QR Code" class="img-fluid rounded modal-qr-img" style="max-height: 250px; background: #fff; transition: opacity 0.3s ease;">
                        </div>
                        
                        <div class="d-flex justify-content-center align-items-center gap-2 small text-muted mb-3">
                            <i class="fa-solid fa-rotate-right text-brand"></i>
                            <span>{{ __('booking.sb_qr_refresh_prefix') }} <span class="fw-bold text-brand modal-qr-timer">60</span>{{ __('booking.sb_qr_refresh_suffix') }}</span>
                        </div>

                        <div class="small text-brand fw-bold mb-0">
                            <i class="fa-solid fa-shield-halved me-1"></i> {{ __('booking.sb_qr_secured') }}
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center">
                        <button type="button" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-bold" data-bs-dismiss="modal">{{ __('account.bk_close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrModals = document.querySelectorAll('[id^="qrModal-"]');
    
    qrModals.forEach(modal => {
        let timerId = null;
        let timeLeft = 60;
        const timerEl = modal.querySelector('.modal-qr-timer');
        const qrImg = modal.querySelector('.modal-qr-img');
        if (!timerEl || !qrImg) return;
        const baseUrl = qrImg.getAttribute('data-base-url');

        // Start timer when modal is shown
        modal.addEventListener('shown.bs.modal', function() {
            timeLeft = 60;
            timerEl.textContent = timeLeft;
            if (timerId) clearInterval(timerId);
            timerId = setInterval(function() {
                if (document.hidden) return;
                timeLeft--;
                if (timeLeft <= 0) {
                    timeLeft = 60;
                    qrImg.style.opacity = '0.3';
                    const sep = baseUrl.includes('?') ? '&' : '?';
                    qrImg.src = baseUrl + sep + 'refresh=' + new Date().getTime();
                    qrImg.onload = function() { qrImg.style.opacity = '1'; };
                    qrImg.onerror = function() { qrImg.style.opacity = '1'; };
                }
                timerEl.textContent = timeLeft;
            }, 1000);
        });

        // Clear timer when modal is hidden
        modal.addEventListener('hidden.bs.modal', function() {
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }
            timeLeft = 60;
            timerEl.textContent = timeLeft;
        });
    });
});
</script>
@endsection

