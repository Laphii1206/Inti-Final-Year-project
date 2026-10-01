@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
    @include('partials.flatpickr-dark-styles')
    <style>
        .acct-dark #booking-qr-image { background:#fff !important; padding:8px; border-radius:8px; }
        
        /* Date Strip & Cards */
        .date-strip::-webkit-scrollbar { height: 8px; }
        .date-strip::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 99px; margin: 0 10px; }
        .acct-dark .date-strip::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
        .date-strip::-webkit-scrollbar-thumb { background: rgba(150, 150, 150, 0.4); border-radius: 99px; cursor: pointer; }
        .date-strip::-webkit-scrollbar-thumb:hover { background: rgba(236, 31, 36, 0.7); }
        .date-card {
            min-width: 80px;
            padding: 12px 10px;
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
        }
        .date-card:hover {
            transform: translateY(-3px);
            border-color: #EC1F24;
            box-shadow: 0 8px 16px rgba(236, 31, 36, 0.12);
        }
        .date-card.active {
            background: linear-gradient(135deg, #EC1F24 0%, #b81418 100%) !important;
            border-color: #EC1F24 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(236, 31, 36, 0.35) !important;
            transform: translateY(-2px);
        }
        .date-card .date-month {
            font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; opacity: 0.7; margin-bottom: 2px;
        }
        .date-card.active .date-month { opacity: 0.95; color: #ffffff; }
        .date-card .date-day {
            font-size: 1.4rem; font-weight: 800; line-height: 1.1; margin-bottom: 4px;
        }
        .date-card .date-weekday {
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; padding: 3px 8px; border-radius: 6px; background: rgba(0, 0, 0, 0.06);
        }
        .acct-dark .date-card .date-weekday { background: rgba(255, 255, 255, 0.1); }
        .date-card.active .date-weekday { background: rgba(255, 255, 255, 0.25); color: #ffffff; }

        /* {{ __('booking.sb_label_timeslot') }} Pills */
        .slot-pill {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #1f2937;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 140px;
        }
        .acct-dark .slot-pill {
            background: #1b1b1b;
            border-color: rgba(255, 255, 255, 0.12);
            color: #e4e4e7;
        }
        .slot-pill:hover:not(.pe-none) {
            border-color: #EC1F24;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(236, 31, 36, 0.15);
        }
        .slot-pill.active {
            background: #111827 !important;
            border-color: #111827 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .acct-dark .slot-pill.active {
            background: #EC1F24 !important;
            border-color: #EC1F24 !important;
        }
    .acct-dark .modal-content { background:#141414 !important; color:#e8e8ea !important; border:1px solid rgba(255,255,255,.08) !important; }
    .acct-dark .modal-content .bg-white { background:#141414 !important; }
    .acct-dark .modal-content .bg-light { background:#1b1b1b !important; }
    .acct-dark .modal-content h1, .acct-dark .modal-content h2, .acct-dark .modal-content h3, .acct-dark .modal-content h4, .acct-dark .modal-content h5, .acct-dark .modal-content h6, .acct-dark .modal-title, .acct-dark .modal-content .text-dark { color:#f4f4f5 !important; }
    .acct-dark .modal-content .text-muted, .acct-dark .modal-content .text-secondary { color:#9ca3af !important; }
    .acct-dark .modal-content .form-control { background:#1b1b1b !important; color:#f4f4f5 !important; border-color: rgba(255,255,255,.15) !important; }
    .acct-dark .modal-content input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
    .acct-dark .modal-content .alert-info { background: rgba(13,202,240,.12) !important; color:#9ddff0 !important; border-color: rgba(13,202,240,.25) !important; }
    .acct-dark .modal-content .btn-outline-secondary { color:#d4d4d8 !important; border-color: rgba(255,255,255,.25) !important; background-color:transparent !important; }
    .acct-dark .modal-content .btn-close { filter: invert(1) grayscale(1) brightness(1.6); }
    .acct-dark .modal-content .badge.bg-success-subtle { background: rgba(34,197,94,.18) !important; color:#86efac !important; }
    .acct-dark .modal-content .badge.bg-danger-subtle { background: rgba(239,68,68,.18) !important; color:#fca5a5 !important; }
    </style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_bookings') => route('bookings.index'), '#' . $booking->number => null]" />
@endsection

@section('content')
<div class="container my-3 my-md-5 acct-dark">
    @include('partials.mobile-account-tabs')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('bookings.index') }}" class="btn-action-neutral"><i class="fa-solid fa-arrow-left"></i> {{ __('booking.sb_btn_back') }}</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Booking Details Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="bg-dark text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <span class="badge bg-brand mb-1">{{ __('booking.sb_badge_details') }}</span>
                        <h4 class="fw-bold mb-0">{{ __('booking.sb_booking_no') }}{{ $booking->number }}</h4>
                        <div class="small text-white-50 mt-1" style="font-size: 0.75rem;">UUID: {{ $booking->uuid }}</div>
                    </div>
                    <div>
                        @if($booking->status === 'pending')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-hourglass-half me-1"></i> {{ __('booking.db_status_pending') }}</span>
                        @elseif($booking->status === 'confirmed')
                            <span class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> {{ __('booking.db_status_confirmed') }}</span>
                        @elseif($booking->status === 'in_progress')
                            <span class="badge bg-info text-dark fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-screwdriver-wrench me-1"></i> {{ __('booking.db_status_in_progress') }}</span>
                        @elseif($booking->status === 'completed')
                            <span class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> {{ __('booking.db_status_completed') }}</span>
                        @elseif($booking->status === 'cancelled')
                            <span class="badge bg-danger text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-ban me-1"></i> {{ __('booking.db_status_cancelled') }}</span>
                        @elseif($booking->status === 'rejected')
                            <span class="badge bg-danger text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-xmark me-1"></i> {{ __('booking.db_status_rejected') }}</span>
                        @elseif($booking->status === 'no_show')
                            <span class="badge bg-secondary text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-user-slash me-1"></i> {{ __('booking.db_status_no_show') }}</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4 bg-white">
                    <div class="row g-4 border-bottom pb-4 mb-4">
                        <div class="col-12 col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('booking.sb_label_service') }}</span>
                            <h5 class="fw-bold text-dark mb-1">{{ $booking->service->name }}</h5>
                            <span class="badge bg-secondary mb-2">{{ ucfirst($booking->service->category) }}</span>
                            <div class="small text-secondary">{{ $booking->service->description }}</div>
                            @if(!empty($booking->booking_options))
                                <div class="mt-3 p-3 rounded" style="background: rgba(0,0,0,0.03); border: 1px dashed rgba(0,0,0,0.1);">
                                    <strong class="d-block small text-dark mb-2"><i class="fa-solid fa-sliders text-muted me-1"></i> {{ __('booking.sb_service_options') }}</strong>
                                    @php
                                        $serviceOptionsConfig = collect($booking->service->meta_data['booking_options'] ?? []);
                                    @endphp
                                    @foreach($booking->booking_options as $key => $val)
                                        @php
                                            $config = $serviceOptionsConfig->firstWhere('key', $key);
                                            $label = $config['label'] ?? ucfirst(str_replace('_', ' ', $key));
                                            $valLabel = $val;
                                            if ($config && isset($config['choices'])) {
                                                $choice = collect($config['choices'])->firstWhere('value', (string)$val);
                                                if ($choice) {
                                                    $valLabel = $choice['label'] ?? $val;
                                                }
                                            }
                                        @endphp
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span class="text-secondary">{{ $label }}:</span>
                                            <span class="fw-medium text-dark">{{ $valLabel }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-sm-6 text-sm-end">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('booking.sb_label_cost') }}</span>
                            <h3 class="fw-bold text-brand mb-0">RM {{ number_format($booking->service_price_at_booking, 2) }}</h3>
                            <span class="small text-muted">{{ __('booking.sb_label_duration') }} {{ $booking->service->estimated_duration }} {{ __('booking.sb_mins') }}</span>
                        </div>
                    </div>

                    <div class="row g-4 border-bottom pb-4 mb-4">
                        <div class="col-12 col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1"><i class="fa-solid fa-warehouse text-brand me-1"></i> {{ __('booking.sb_label_location') }}</span>
                            <strong class="text-dark d-block mb-1">{{ $booking->branch->name }}</strong>
                            <div class="small text-secondary mb-2">{{ $booking->branch->address }}</div>
                            <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i> {{ __('booking.sb_label_contact') }} {{ $booking->branch->contact_number }}</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1"><i class="fa-solid fa-car text-brand me-1"></i> {{ __('booking.sb_label_vehicle') }}</span>
                            <strong class="text-dark d-block mb-1">{{ $booking->car->brand }} {{ $booking->car->model }}</strong>
                            <div class="small text-secondary mb-2"><i class="fa-solid fa-rectangle-ad me-1"></i> {{ __('booking.sb_label_plate') }} {{ $booking->car->car_plate }}</div>
                            @if($booking->recorded_mileage)
                                <div class="small text-muted"><i class="fa-solid fa-gauge-high me-1"></i> {{ __('booking.sb_label_mileage_done') }} {{ number_format($booking->recorded_mileage) }} km</div>
                            @else
                                <div class="small text-muted"><i class="fa-solid fa-gauge-high me-1"></i> {{ __('booking.sb_label_mileage_reg') }} {{ number_format($booking->car->mileage) }} km</div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-12 col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1"><i class="fa-solid fa-calendar text-brand me-1"></i> {{ __('booking.sb_label_datetime') }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $booking->booking_date->format('d F Y') }}</h6>
                            <div class="small text-secondary">{{ __('booking.sb_label_timeslot') }} {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1"><i class="fa-solid fa-user-check text-brand me-1"></i> {{ __('booking.sb_label_mechanic') }}</span>
                            @if($booking->assignedStaff)
                                <strong class="text-dark">{{ $booking->assignedStaff->name }}</strong>
                                <div class="small text-muted">{{ __('booking.sb_label_staff_email') }} {{ $booking->assignedStaff->email }}</div>
                            @else
                                <span class="text-secondary small italic">{{ __('booking.sb_not_assigned') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($booking->customer_remark)
                        <div class="bg-light p-3 rounded-3 mt-4">
                            <span class="small text-muted d-block mb-1"><i class="fa-solid fa-comment-dots text-brand me-1"></i> {{ __('booking.sb_label_remarks') }}</span>
                            <p class="mb-0 small text-dark">{{ $booking->customer_remark }}</p>
                        </div>
                    @endif

                    @if($booking->is_rescheduled)
                        <div class="bg-info-subtle border border-info-subtle text-info-emphasis p-3 rounded-3 mt-4">
                            <span class="small d-block fw-bold mb-1"><i class="fa-solid fa-calendar-check me-1"></i> {{ __('booking.sb_rescheduled_badge') }}</span>
                            <p class="mb-0 small">{{ __('booking.sb_rescheduled_note') }}</p>
                        </div>
                    @endif

                    @if($booking->cancellation_reason)
                        <div class="bg-danger-subtle border border-danger-subtle text-danger-emphasis p-3 rounded-3 mt-4">
                            <span class="small d-block mb-1 fw-bold"><i class="fa-solid fa-circle-exclamation me-1"></i> {{ __('booking.sb_label_reason') }}</span>
                            <p class="mb-0 small">{{ $booking->cancellation_reason }}</p>
                        </div>
                    @endif

                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        @php
                            $bookingDt = \Carbon\Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time->format('H:i:s'));
                            $canReschedule = !$booking->is_rescheduled && now()->addHours(24)->lte($bookingDt);
                        @endphp
                        <div class="mt-4 pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(230, 0, 18, 0.08); color: #e60012;">
                                    <i class="fa-solid fa-sliders fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.95rem;">{{ __('booking.sb_manage_title') }}</h6>
                                    <span class="small text-muted d-block" style="font-size: 0.78rem;">{{ __('booking.sb_manage_desc') }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2.5 flex-wrap">
                                @if($canReschedule)
                                    <button type="button" class="btn btn-outline-primary px-4 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="modal" data-bs-target="#rescheduleBookingModal" style="border-width: 1.5px; font-size: 0.88rem;">
                                        <i class="fa-solid fa-calendar-days"></i> <span>{{ __('booking.sb_btn_reschedule') }}</span>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-danger px-4 py-2.5 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="modal" data-bs-target="#cancelBookingModal" style="border-width: 1.5px; font-size: 0.88rem;">
                                    <i class="fa-solid fa-ban"></i> <span>{{ __('booking.sb_btn_cancel') }}</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Card (For Completed Bookings) -->
            @if($booking->status === 'completed')
                <div class="card border-0 shadow-sm rounded-4 mt-4 bg-white">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-star text-brand me-2"></i>{{ __('booking.sb_title_feedback') }}</h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        @if($booking->review)
                            <div class="bg-light p-3 rounded-3">
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $booking->review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                    <span class="text-muted small ms-2">{{ __('booking.sb_submitted_on') }} {{ $booking->review->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="mb-0 text-dark small">{{ $booking->review->comment ?? __('booking.sb_no_comment') }}</p>
                            </div>
                        @else
                            <form action="{{ route('bookings.review.store', $booking->uuid) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">{{ __('booking.sb_rate_q') }}</label>
                                    <div class="d-flex gap-3 text-warning fs-3 mb-2" id="star-rating-wrapper">
                                        <i class="fa-regular fa-star cursor-pointer star-input" data-value="1"></i>
                                        <i class="fa-regular fa-star cursor-pointer star-input" data-value="2"></i>
                                        <i class="fa-regular fa-star cursor-pointer star-input" data-value="3"></i>
                                        <i class="fa-regular fa-star cursor-pointer star-input" data-value="4"></i>
                                        <i class="fa-regular fa-star cursor-pointer star-input" data-value="5"></i>
                                    </div>
                                    <input type="hidden" name="rating" id="rating-value" value="" required>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label small fw-bold text-secondary">{{ __('booking.sb_label_comments') }}</label>
                                    <textarea name="comment" id="comment" rows="3" class="form-control bg-light border-0" placeholder="{{ __('booking.sb_comments_ph') }}"></textarea>
                                </div>
                                <button type="submit" class="btn btn-brand px-4 py-2 small fw-bold" id="submit-review-btn" disabled>{{ __('booking.sb_btn_submit_feedback') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar / Payment & QR Code Cards -->
        <div class="col-lg-4">
            <!-- Payment Section -->
            @if($booking->isPaid())
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                        <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('booking.sb_title_paid') }}</h6>
                            <span class="small">RM {{ number_format($booking->service_price_at_booking, 2) }} {{ __('booking.sb_paid_desc') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('bookings.invoice', $booking) }}" target="_blank" rel="noopener" class="btn btn-brand w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm">
                        <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                        <span>{{ __('booking.sb_btn_receipt') }}</span>
                    </a>
                </div>
            @elseif(!in_array($booking->status, ['cancelled', 'rejected']))
                @php
                    $pendingCounter = $booking->payments()->where('gateway', 'counter')->where('status', 'pending')->latest()->first();
                @endphp
                @if($pendingCounter)
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                            <i class="fa-solid fa-store fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">{{ __('booking.pp_counter_title') }}</h6>
                                <span class="small">{{ __('booking.pp_counter_desc') }}</span>
                            </div>
                        </div>
                        <form action="{{ route('payment.switch', $booking->uuid) }}" method="POST">
                            @csrf
                            <input type="hidden" name="method" value="card">
                            <button type="submit" class="btn btn-outline-dark w-100 py-2 fw-bold small">
                                <i class="fa-solid fa-credit-card me-2"></i>{{ __('booking.pp_switch_online') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-credit-card text-brand me-2"></i>{{ __('booking.sb_title_payment') }}</h5>
                        <p class="small text-muted mb-3">{{ __('booking.sb_payment_desc') }}</p>
                        <div class="bg-light rounded-3 p-3 mb-3 text-center">
                            <span class="small text-muted d-block mb-1">{{ __('booking.sb_label_amount_due') }}</span>
                            <h3 class="fw-bold text-brand mb-0">RM {{ number_format(max(0, (float)$booking->service_price_at_booking - (float)($booking->discount_amount ?? 0)), 2) }}</h3>
                        </div>
                        <a href="{{ route('payment.page', $booking->uuid) }}" class="btn btn-success w-100 py-2 fw-bold mb-2">
                            <i class="fa-solid fa-credit-card me-2"></i>{{ __('booking.sb_go_to_payment') }}
                        </a>
                        <form action="{{ route('payment.switch', $booking->uuid) }}" method="POST">
                            @csrf
                            <input type="hidden" name="method" value="counter">
                            <button type="submit" class="btn btn-outline-secondary w-100 py-2 fw-bold small">
                                <i class="fa-solid fa-store me-2"></i>{{ __('booking.pp_switch_counter') }}
                            </button>
                        </form>
                    </div>
                @endif
            @endif

            <!-- QR Code Section -->
            @if($booking->status === 'completed')
                <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white mb-4">
                    <i class="fa-solid fa-circle-check text-success fs-1 mb-3"></i>
                    <h5 class="fw-bold text-dark mb-2">{{ __('booking.sb_completed_title') }}</h5>
                    <p class="small text-muted mb-3">{{ __('booking.sb_completed_desc') }}</p>
                    <div>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-4 py-2 mt-1 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#reportIssueModal">
                            <i class="fa-solid fa-headset me-1"></i> {{ __('booking.sb_btn_report_issue') }}
                        </button>
                    </div>
                </div>
            @elseif(!$booking->isPaid() && !in_array($booking->status, ['cancelled', 'rejected']))
                <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white mb-4">
                    <i class="fa-solid fa-lock text-muted fs-1 mb-3"></i>
                    <h6 class="fw-bold text-dark mb-2">{{ __('booking.sb_qr_locked_title') }}</h6>
                    <p class="small text-muted mb-0">{{ __('booking.sb_qr_locked_desc') }}</p>
                </div>
            @elseif($booking->isPaid() && !in_array($booking->status, ['cancelled', 'rejected']))
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-qrcode text-brand me-1"></i>{{ __('booking.sb_title_qr') }}</h5>
                    <p class="small text-muted mb-3">{{ __('booking.sb_qr_desc') }}</p>
                    
                    <div class="bg-light p-4 rounded-3 d-inline-block mx-auto mb-3" style="min-height: 250px; min-width: 250px;">
                        <img src="{{ $qrCodeUrl }}" data-base-url="{{ $qrCodeUrl }}" id="booking-qr-image" alt="Booking QR Code" class="img-fluid" style="max-height: 250px; transition: opacity 0.3s ease;">
                    </div>
                    
                    <div class="d-flex justify-content-center align-items-center gap-2 small text-muted mb-3">
                        <i class="fa-solid fa-rotate-right text-brand"></i>
                        <span>{{ __('booking.sb_qr_refresh_prefix') }} <span id="qr-timer" class="fw-bold text-brand">60</span>{{ __('booking.sb_qr_refresh_suffix') }}</span>
                    </div>

                    <div class="small text-brand fw-bold mb-0"><i class="fa-solid fa-shield-halved me-1"></i> {{ __('booking.sb_qr_secured') }}</div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white mb-4">
                    <i class="fa-solid fa-ticket-simple text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold text-dark mb-1">{{ __('booking.sb_title_expired') }}</h5>
                    <p class="small text-muted mb-0">{{ __('booking.sb_expired_desc1') }} ({{ $booking->status }}), {{ __('booking.sb_expired_desc2') }}</p>
                </div>
            @endif

            <!-- Help Section -->
            <div class="card border-0 shadow-sm rounded-4 mt-4 p-4 bg-white">
                <h6 class="fw-bold mb-3">{{ __('booking.sb_title_help') }}</h6>
                <p class="small text-muted mb-3">{{ __('booking.sb_help_desc') }}</p>
                <div class="d-flex align-items-center gap-3">
                    <a href="tel:{{ $booking->branch->contact_number }}" class="btn btn-brand btn-sm flex-fill"><i class="fa-solid fa-phone me-1"></i> {{ __('booking.sb_btn_call') }}</a>
                    <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#reportIssueModal"><i class="fa-solid fa-headset me-1"></i> {{ __('booking.sb_btn_live_chat') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(in_array($booking->status, ['pending', 'confirmed']))
<div class="modal fade acct-dark" id="cancelBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('bookings.cancel', $booking->uuid) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">{{ __('booking.sb_cancel_modal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small">{{ __('booking.sb_cancel_modal_desc') }}</p>
                    <label class="form-label fw-semibold small">{{ __('booking.sb_cancel_reason_label') }}</label>
                    <textarea name="cancellation_reason" class="form-control rounded-3" rows="3" maxlength="500" required placeholder="{{ __('booking.sb_cancel_reason_ph') }}"></textarea>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex flex-nowrap gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill py-2.5 flex-fill text-nowrap fw-semibold" data-bs-dismiss="modal">{{ __('booking.sb_cancel_keep') }}</button>
                    <button type="submit" class="btn btn-danger rounded-pill py-2.5 flex-fill text-nowrap fw-bold">{{ __('booking.sb_cancel_confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade acct-dark" id="rescheduleBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden bg-white">
            <form action="{{ route('bookings.reschedule', $booking->uuid) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h4 class="modal-title fw-bold text-dark mb-1"><i class="fa-solid fa-calendar-days text-danger me-2"></i>{{ __('booking.rs_modal_title') }}</h4>
                        <p class="text-muted small mb-0">{{ __('booking.rs_modal_subtitle') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2.5 px-3 small rounded-3 mb-4 d-flex align-items-center gap-2 border-0 shadow-sm" style="background: rgba(13, 202, 240, 0.1); color: #087990;">
                        <i class="fa-solid fa-circle-info fs-5 text-info"></i>
                        <span>{{ __('booking.rs_notice') }}</span>
                    </div>

                    <input type="hidden" name="booking_date" id="reschedule_date" value="{{ old('booking_date', now()->addDay()->toDateString()) }}" required>

                    <!-- Quick Select Date Strip & Calendar Picker -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <label class="form-label small fw-bold text-secondary mb-0 d-flex align-items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-danger fs-6"></i>
                                <span class="text-uppercase tracking-wider">{{ __('booking.rs_step1_label') }}</span>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2.5 py-1 fw-normal ms-1" style="font-size:0.7rem;">{{ __('booking.bc_quick_select') }}</span>
                            </label>
                            <div class="position-relative">
                                <input type="text" id="reschedule_flatpickr_input" class="form-control bg-transparent border-0 position-absolute" style="width: 1px; height: 1px; opacity: 0; pointer-events: none;">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-2 shadow-sm" style="border-color: rgba(255,255,255,0.2);" onclick="document.querySelector('#reschedule_flatpickr_input')._flatpickr.open()">
                                    <i class="fa-solid fa-calendar-plus text-danger"></i>
                                    <span id="reschedule_display_selected_date">{{ now()->addDay()->format('d M Y') }}</span>
                                    <i class="fa-solid fa-chevron-down small text-muted ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Horizontal Date Strip Container -->
                        <div class="date-strip d-flex gap-2 overflow-auto pb-3 pt-1 px-1" id="reschedule_date_strip">
                            <!-- JS renders upcoming 14 date cards -->
                        </div>
                    </div>

                    <div class="mb-2 border-top pt-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <label class="form-label small fw-bold text-secondary mb-0 d-flex align-items-center gap-2">
                                <i class="fa-regular fa-clock text-danger fs-6"></i>
                                <span class="text-uppercase tracking-wider">{{ __('booking.rs_step2_label') }}</span>
                            </label>
                            <span class="badge bg-light text-secondary border fw-normal px-3 py-1.5 rounded-pill" id="reschedule-slot-status">{{ __('booking.rs_checking_slots') }}</span>
                        </div>
                        
                        <select name="start_time" id="reschedule_start_time" class="d-none" required>
                            <option value="">-- {{ __('booking.cb_select_time') }} --</option>
                        </select>

                        <div id="reschedule-time-pills" class="d-flex flex-wrap gap-2.5">
                            <!-- Dynamically populated via JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex flex-nowrap gap-2">
                    <button type="button" class="btn btn-outline-secondary py-2.5 rounded-pill fw-semibold flex-fill text-nowrap" data-bs-dismiss="modal">{{ __('booking.bc_cancel') }}</button>
                    <button type="submit" class="btn btn-danger py-2.5 rounded-pill fw-bold shadow-sm flex-fill text-nowrap d-flex align-items-center justify-content-center gap-1.5" id="btn-confirm-reschedule" disabled>
                        <i class="fa-solid fa-check"></i> <span>{{ __('booking.rs_btn_confirm') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Report Issue & Live Chat Modal (Premium UI) -->
<style>
.modal-acct-dark .modal-content {
    background: #181920;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff;
}
.modal-acct-dark .form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    border-radius: 12px;
}
.modal-acct-dark .form-control:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #EC1F24;
    box-shadow: 0 0 0 3px rgba(236, 31, 36, 0.25);
    color: #ffffff;
}
.modal-acct-dark .modal-topic-btn {
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.03);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 14px;
    padding: 10px 16px;
    font-weight: 600;
    font-size: 0.88rem;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    outline: none !important;
}
.modal-acct-dark .modal-topic-btn:focus { outline: none !important; }
.modal-acct-dark .modal-topic-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.3);
}
.modal-acct-dark .modal-topic-btn.active {
    background: #EC1F24;
    color: #ffffff;
    border-color: #EC1F24;
    box-shadow: 0 4px 15px rgba(236, 31, 36, 0.35);
}
.modal-dropzone {
    border: 2px dashed rgba(255, 255, 255, 0.22);
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.025);
    min-height: 160px;
    padding: 32px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
.modal-dropzone:hover {
    border-color: rgba(236, 31, 36, 0.6);
    background: rgba(236, 31, 36, 0.04);
    transform: translateY(-2px);
}
.modal-dropzone.dragover {
    border: 2px solid #EC1F24 !important;
    background: linear-gradient(135deg, rgba(236, 31, 36, 0.25) 0%, rgba(236, 31, 36, 0.1) 100%) !important;
    box-shadow: 0 0 35px rgba(236, 31, 36, 0.45), inset 0 0 20px rgba(236, 31, 36, 0.2) !important;
    transform: scale(1.02);
}
.modal-dropzone.dragover .dz-state-default { display: none !important; }
.modal-dropzone.dragover .dz-state-active { display: flex !important; animation: dzPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
.pointer-events-none { pointer-events: none !important; }
.modal-dropzone * { pointer-events: none !important; }
</style>
<div class="modal fade modal-acct-dark" id="reportIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 mb-0">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-25 p-2 text-danger" style="width:38px;height:38px;">
                        <i class="fa-solid fa-headset"></i>
                    </span>
                    <span>{{ __('booking.ri_modal_title') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('support-tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="modal-body p-4">
                    <!-- Linked Booking Card -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="fs-4 text-brand"><i class="fa-solid fa-link"></i></div>
                        <div>
                            <div class="small text-muted mb-0">{{ __('booking.ri_linked_booking') }}</div>
                            <div class="fw-bold text-white fs-6">#{{ $booking->number }} &middot; {{ $booking->service->name }}</div>
                        </div>
                    </div>

                    <!-- Interactive Issue Type Pills -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-2 d-block">{{ __('booking.ri_issue_type_label') }} <span class="text-danger">*</span></label>
                        <input type="hidden" name="type" id="modal-issue-type" value="service_quality" required>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="modal-topic-btn active d-flex align-items-center gap-2" data-value="service_quality">
                                <i class="fa-solid fa-wrench"></i>
                                <span>{{ __('booking.ri_type_service') }}</span>
                            </button>
                            <button type="button" class="modal-topic-btn d-flex align-items-center gap-2" data-value="overcharge">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>{{ __('booking.ri_type_billing') }}</span>
                            </button>
                            <button type="button" class="modal-topic-btn d-flex align-items-center gap-2" data-value="parts_issue">
                                <i class="fa-solid fa-gear"></i>
                                <span>{{ __('booking.ri_type_parts') }}</span>
                            </button>
                            <button type="button" class="modal-topic-btn d-flex align-items-center gap-2" data-value="general">
                                <i class="fa-regular fa-comments"></i>
                                <span>{{ __('booking.ri_type_general') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Subject Input -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">{{ __('booking.ri_subject_label') }} <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control px-3 py-2.5" value="Booking #{{ $booking->number }} - {{ $booking->service->name }}" minlength="5" maxlength="255" required>
                    </div>

                    <!-- Description Textarea -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">{{ __('booking.ri_desc_label') }} <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control p-3" rows="4" placeholder="{{ __('booking.ri_desc_ph') }}" minlength="10" maxlength="3000" style="resize: vertical;" required></textarea>
                    </div>

                    <!-- Sleek Drag & Drop File Box -->
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider mb-1">{{ __('booking.ri_attach_label') }}</label>
                        <div class="modal-dropzone" id="modal-dropzone" onclick="document.getElementById('modal-file-input').click()">
                            <div class="dz-state-default d-flex flex-column align-items-center justify-content-center pointer-events-none w-100">
                                <i class="fa-solid fa-cloud-arrow-up fs-3 text-brand mb-2 d-block"></i>
                                <div class="fw-semibold small text-white mb-1">{{ __('booking.ri_drop_hint') }}</div>
                                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.4);">{{ __('booking.ri_drop_limits') }}</div>
                            </div>
                            <div class="dz-state-active d-none flex-column align-items-center justify-content-center pointer-events-none w-100 py-1">
                                <i class="fa-solid fa-file-arrow-down fs-2 text-white mb-2"></i>
                                <div class="fw-bold text-white fs-6 mb-1">{{ __('booking.ri_drop_active') }}</div>
                                <span class="badge bg-white text-brand fw-bold rounded-pill px-3 py-1 mt-1">{{ __('booking.ri_drop_ready') }}</span>
                            </div>
                            <input type="file" id="modal-file-input" name="attachments[]" class="d-none" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf">
                        </div>
                        <div id="modal-file-previews" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex flex-nowrap gap-2">
                    <button type="button" class="btn btn-outline-light rounded-pill py-2 flex-fill text-nowrap fw-semibold" style="border-color: rgba(255,255,255,0.2);" data-bs-dismiss="modal">{{ __('booking.bc_cancel') }}</button>
                    <button type="submit" class="btn btn-brand rounded-pill py-2 flex-fill text-nowrap fw-bold shadow-lg d-flex align-items-center justify-content-center gap-1.5">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>{{ __('booking.ri_btn_submit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reschedModalEl = document.getElementById('rescheduleBookingModal');
    if (reschedModalEl) {
        const dateStrip = document.getElementById('reschedule_date_strip');
        const dateHiddenInput = document.getElementById('reschedule_date');
        const flatpickrInput = document.getElementById('reschedule_flatpickr_input');
        const displaySelectedDate = document.getElementById('reschedule_display_selected_date');
        const timeSelect = document.getElementById('reschedule_start_time');
        const timePillsContainer = document.getElementById('reschedule-time-pills');
        const slotStatusBadge = document.getElementById('reschedule-slot-status');
        const confirmBtn = document.getElementById('btn-confirm-reschedule');

        const branchId = '{{ $booking->branch_id }}';
        const serviceId = '{{ $booking->service_id ?? "" }}';
        const serviceCapacity = {{ $booking->branch->service_capacity ?? 3 }};
        const slotAvailabilityUrl = '{{ route("bookings.slotAvailability") }}';

        const monthsShort = [
            @json(__('booking.m_jan')), @json(__('booking.m_feb')), @json(__('booking.m_mar')), @json(__('booking.m_apr')),
            @json(__('booking.m_may')), @json(__('booking.m_jun')), @json(__('booking.m_jul')), @json(__('booking.m_aug')),
            @json(__('booking.m_sep')), @json(__('booking.m_oct')), @json(__('booking.m_nov')), @json(__('booking.m_dec'))
        ];
        const daysShort = [
            @json(__('booking.d_sun')), @json(__('booking.d_mon')), @json(__('booking.d_tue')), @json(__('booking.d_wed')),
            @json(__('booking.d_thu')), @json(__('booking.d_fri')), @json(__('booking.d_sat'))
        ];

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

        function initDateStrip() {
            if (!dateStrip) return;
            dateStrip.innerHTML = '';
            const startDay = new Date();
            startDay.setDate(startDay.getDate() + 1); // Start tomorrow
            startDay.setHours(0, 0, 0, 0);

            for (let i = 0; i < 30; i++) {
                const cur = new Date(startDay);
                cur.setDate(startDay.getDate() + i);

                const yyyy = cur.getFullYear();
                const mm = String(cur.getMonth() + 1).padStart(2, '0');
                const dd = String(cur.getDate()).padStart(2, '0');
                const dateStr = `${yyyy}-${mm}-${dd}`;

                const monthName = monthsShort[cur.getMonth()];
                const dayNum = cur.getDate();
                const weekdayName = daysShort[cur.getDay()];

                const card = document.createElement('div');
                card.className = `date-card ${dateStr === dateHiddenInput.value ? 'active' : ''}`;
                card.dataset.date = dateStr;
                card.innerHTML = `
                    <span class="date-month">${monthName}</span>
                    <span class="date-day">${dayNum}</span>
                    <span class="date-weekday">${weekdayName}</span>
                `;
                card.addEventListener('click', () => selectDate(dateStr));
                dateStrip.appendChild(card);
            }

            setTimeout(() => {
                const activeCard = dateStrip.querySelector('.date-card.active');
                if (activeCard) {
                    activeCard.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            }, 50);
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

        function selectDate(dateStr) {
            dateHiddenInput.value = dateStr;
            updateDisplayDate(dateStr);
            if (flatpickrInput && flatpickrInput._flatpickr) {
                const curSel = flatpickrInput._flatpickr.selectedDates[0];
                const curStr = curSel ? `${curSel.getFullYear()}-${String(curSel.getMonth()+1).padStart(2,'0')}-${String(curSel.getDate()).padStart(2,'0')}` : '';
                if (curStr !== dateStr) {
                    flatpickrInput._flatpickr.setDate(dateStr, false);
                }
            }
            
            if (dateStrip) {
                let targetCard = dateStrip.querySelector(`.date-card[data-date="${dateStr}"]`);
                if (!targetCard) {
                    initDateStrip();
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

            fetchSlots(dateStr);
        }

        if (window.flatpickr && flatpickrInput) {
            flatpickrInput._flatpickr = flatpickr(flatpickrInput, {
                defaultDate: dateHiddenInput.value,
                minDate: new Date().fp_incr(1),
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
                    selectDate(dateStr);
                }
            });
        }

        function fetchSlots(dateStr) {
            if (!timePillsContainer) return;
            timePillsContainer.innerHTML = '<div class="text-muted small py-3"><i class="fa-solid fa-spinner fa-spin me-2"></i>' + @json(__('booking.rs_loading_avail')) + '</div>';
            if (slotStatusBadge) slotStatusBadge.textContent = @json(__('booking.rs_checking_slots'));
            confirmBtn.disabled = true;
            timeSelect.value = '';

            fetch(slotAvailabilityUrl + '?date=' + encodeURIComponent(dateStr) + '&branch_id=' + encodeURIComponent(branchId) + '&service_id=' + encodeURIComponent(serviceId), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                renderTimePills(data);
            })
            .catch(() => {
                renderTimePills({});
            });
        }

        function renderTimePills(counts) {
            if (!timePillsContainer) return;
            timePillsContainer.innerHTML = '';
            timeSelect.innerHTML = '<option value="">-- Select {{ __('booking.sb_label_timeslot') }} --</option>';

            let availCount = 0;

            timeSlots.forEach(slot => {
                const count = counts[slot.value] || 0;
                const remaining = serviceCapacity - count;
                const isFull = remaining <= 0;

                const opt = document.createElement('option');
                opt.value = slot.value;
                const leftStr = @json(__('booking.rs_slot_left')).replace(':count', remaining);
                opt.textContent = `${slot.label} (${isFull ? @json(__('booking.sb_slot_full')) : leftStr})`;
                opt.disabled = isFull;
                timeSelect.appendChild(opt);

                const pill = document.createElement('button');
                pill.type = 'button';
                pill.className = `btn rounded-pill px-3 py-2.5 small fw-semibold text-start d-flex align-items-center justify-content-between transition-all slot-pill ${isFull ? 'btn-light text-muted opacity-50 pe-none' : 'btn-outline-secondary bg-white'}`;
                
                let pillText = slot.label;
                if (isFull) {
                    pillText += ` <span class="badge bg-secondary rounded-pill ms-2 fw-normal" style="font-size:10px;">` + @json(__('booking.sb_slot_full')) + `</span>`;
                } else {
                    availCount++;
                    pillText += ` <span class="badge bg-light text-dark border rounded-pill ms-2 fw-normal" style="font-size:10px;">${leftStr}</span>`;
                }

                pill.innerHTML = `<span>${pillText}</span>`;

                if (!isFull) {
                    pill.addEventListener('click', function() {
                        timePillsContainer.querySelectorAll('.slot-pill').forEach(p => {
                            p.classList.remove('active');
                            const badge = p.querySelector('.badge');
                            if (badge) badge.className = 'badge bg-light text-dark border rounded-pill ms-2 fw-normal';
                        });
                        this.classList.add('active');
                        const myBadge = this.querySelector('.badge');
                        if (myBadge) myBadge.className = 'badge bg-white text-dark rounded-pill ms-2 fw-bold shadow-sm';

                        timeSelect.value = slot.value;
                        confirmBtn.disabled = false;
                    });
                }

                timePillsContainer.appendChild(pill);
            });

            if (slotStatusBadge) {
                slotStatusBadge.textContent = @json(__('booking.rs_slots_avail')).replace(':count', availCount);
                slotStatusBadge.className = availCount > 0 ? 'badge bg-success-subtle text-success border border-success-subtle fw-normal px-3 py-1.5 rounded-pill' : 'badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-3 py-1.5 rounded-pill';
            }
        }

        reschedModalEl.addEventListener('show.bs.modal', function() {
            initDateStrip();
            updateDisplayDate(dateHiddenInput.value);
            fetchSlots(dateHiddenInput.value);
        });
    }

    const stars = document.querySelectorAll('.star-input');
        const ratingValue = document.getElementById('rating-value');
        const submitBtn = document.getElementById('submit-review-btn');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const val = parseInt(this.dataset.value);
                ratingValue.value = val;
                submitBtn.removeAttribute('disabled');

                // Color stars
                stars.forEach((s, idx) => {
                    if (idx < val) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                    } else {
                        s.classList.remove('fa-solid');
                        s.classList.add('fa-regular');
                    }
                });
            });
            
            // Hover styling
            star.addEventListener('mouseover', function() {
                const val = parseInt(this.dataset.value);
                stars.forEach((s, idx) => {
                    if (idx < val) {
                        s.classList.add('text-warning');
                    }
                });
            });

            star.addEventListener('mouseout', function() {
                const currentVal = parseInt(ratingValue.value) || 0;
                stars.forEach((s, idx) => {
                    if (idx >= currentVal) {
                        s.classList.remove('text-warning');
                    }
                });
            });
        });
    });
</script>

@if($booking->isPaid() && !in_array($booking->status, ['cancelled', 'rejected']))
<script>
document.addEventListener('DOMContentLoaded', function() {
    let timeLeft = 60;
    const timerEl = document.getElementById('qr-timer');
    const qrImg = document.getElementById('booking-qr-image');
    if (!timerEl || !qrImg) return;
    const baseUrl = qrImg.getAttribute('data-base-url');

    let timerId = setInterval(function() {
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

    window.addEventListener('beforeunload', () => clearInterval(timerId));
});
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal topic buttons
    const topicBtns = document.querySelectorAll('.modal-topic-btn');
    const issueInput = document.getElementById('modal-issue-type');
    topicBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            topicBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            if (issueInput) issueInput.value = this.dataset.value;
        });
    });

    // Modal file previews and dragover events
    const fi = document.getElementById('modal-file-input');
    const dz = document.getElementById('modal-dropzone');
    const previews = document.getElementById('modal-file-previews');
    async function modalHandleFiles(rawFiles) {
        if (!previews || !fi) return;
        previews.innerHTML = '<span class="text-white small"><i class="fa-solid fa-spinner fa-spin me-1"></i> Processing & compressing...</span>';
        const dt = new DataTransfer();
        previews.innerHTML = '';
        for (let f of rawFiles) {
            if (window.compressImageFile) f = await window.compressImageFile(f);
            dt.items.add(f);
            const chip = document.createElement('div');
            chip.className = 'badge bg-secondary bg-opacity-25 text-white border p-2 d-flex align-items-center gap-2';
            const sizeBadge = f.originalSize
                ? `<span class="text-success fw-bold">⚡ ${(f.size/1024).toFixed(0)}KB <s class="text-light opacity-50">(${(f.originalSize/1024/1024).toFixed(1)}MB)</s></span>`
                : `<span class="opacity-75">(${(f.size/1024).toFixed(0)}KB)</span>`;
            chip.innerHTML = `<i class="fa-solid fa-file"></i><span>${f.name}</span> ${sizeBadge}`;
            previews.appendChild(chip);
        }
        fi.files = dt.files;
    }
    if (fi && previews) {
        fi.addEventListener('change', function() { modalHandleFiles(Array.from(this.files)); });
    }
    if (dz && fi) {
        dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('dragover'); });
        dz.addEventListener('dragleave', e => {
            if (!dz.contains(e.relatedTarget)) {
                dz.classList.remove('dragover');
            }
        });
        dz.addEventListener('drop', function(e) {
            e.preventDefault(); dz.classList.remove('dragover');
            modalHandleFiles(Array.from(e.dataTransfer.files));
        });
    }
});
</script>
@endsection
