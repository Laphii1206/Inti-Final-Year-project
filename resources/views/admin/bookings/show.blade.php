@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> {{ __('admin.bk_show_back') }}</a>
    </div>

    <div class="row g-4">
        <!-- Main Booking Panel -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                <div class="bg-dark text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <span class="badge bg-brand mb-1">{{ __('admin.bk_show_info') }}</span>
                        <h4 class="fw-bold mb-0">{{ __('admin.bk_col_booking') }}{{ $booking->number }}</h4>
                    </div>
                    <div>
                        @if($booking->status === 'pending')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-hourglass-half me-1"></i> {{ __('admin.bk_lbl_pending') }}</span>
                        @elseif($booking->status === 'confirmed')
                            <span class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.bk_lbl_confirmed') }}</span>
                        @elseif($booking->status === 'in_progress')
                            <span class="badge bg-info text-dark fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-screwdriver-wrench me-1"></i> {{ __('admin.bk_lbl_in_progress') }}</span>
                        @elseif($booking->status === 'completed')
                            <span class="badge bg-secondary text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-check me-1"></i> {{ __('admin.bk_lbl_completed') }}</span>
                        @elseif($booking->status === 'cancelled')
                            <span class="badge bg-danger text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-ban me-1"></i> {{ __('admin.bk_lbl_cancelled') }}</span>
                        @elseif($booking->status === 'rejected')
                            <span class="badge bg-danger text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-xmark me-1"></i> {{ __('admin.bk_lbl_rejected') }}</span>
                        @elseif($booking->status === 'no_show')
                            <span class="badge bg-dark text-white fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-user-slash me-1"></i> {{ __('admin.bk_lbl_no_show') }}</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3 border-bottom pb-3 mb-3">
                        <div class="col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('admin.bk_req_service') }}</span>
                            <h5 class="fw-bold text-dark mb-0">{{ $booking->service->name ?? __('admin.bk_del_service') }}</h5>
                            <span class="badge bg-secondary mt-1">{{ ucfirst($booking->service->category ?? __('admin.bk_unknown')) }}</span>
                            @if(!empty($booking->booking_options))
                                <div class="mt-3 p-3 rounded" style="background: rgba(0,0,0,0.02); border: 1px dashed rgba(0,0,0,0.1);">
                                    <strong class="d-block small text-dark mb-2"><i class="fa-solid fa-sliders text-muted me-1"></i> {{ __('Service Options') }}</strong>
                                    @php
                                        $serviceOptionsConfig = collect(optional($booking->service)->meta_data['booking_options'] ?? []);
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
                        <div class="col-sm-6 text-sm-end">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('admin.bk_cost_details') }}</span>
                            <h4 class="fw-bold text-brand mb-0">RM {{ number_format($booking->service_price_at_booking, 2) }}</h4>
                            <div class="mt-2">
                                @if($booking->isPaid())
                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.bk_paid') }}</span>
                                @else
                                    <span class="badge bg-light text-muted border"><i class="fa-solid fa-circle-xmark me-1"></i> {{ __('admin.bk_unpaid') }}</span>
                                @endif
                            </div>
                            @if(!$booking->isPaid() && !in_array($booking->status, [\App\Models\Booking::STATUS_CANCELLED, \App\Models\Booking::STATUS_REJECTED]))
                                <form action="{{ route('admin.bookings.markPaid', $booking) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i> {{ __('admin.bk_mark_paid') }}
                                    </button>
                                </form>
                            @endif
                            <span class="small text-muted">{{ __('admin.bk_duration') }} {{ $booking->service->estimated_duration ?? __('admin.bk_na') }} {{ __('admin.bk_mins') }}</span>
                        </div>
                    </div>

                    <div class="row g-3 border-bottom pb-3 mb-3">
                        <div class="col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('admin.bk_ws_branch') }}</span>
                            <strong class="text-dark">{{ $booking->branch->name ?? __('admin.bk_del_branch') }}</strong>
                            <div class="small text-secondary mt-1">{{ $booking->branch->address ?? __('admin.bk_na') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('admin.bk_veh_serviced') }}</span>
                            <strong class="text-dark">{{ $booking->car->brand ?? __('admin.bk_unknown') }} {{ $booking->car->model ?? 'Vehicle' }}</strong>
                            <div class="small text-secondary mt-1">{{ __('admin.bk_plate') }} {{ $booking->car->car_plate ?? __('admin.bk_na') }}</div>
                            @if($booking->recorded_mileage)
                                <div class="small text-muted">{{ __('admin.bk_odo_final') }} {{ number_format($booking->recorded_mileage) }} {{ __('admin.bk_km') }}</div>
                            @else
                                <div class="small text-muted">{{ __('admin.bk_odo_start') }} {{ number_format($booking->car->mileage ?? 0) }} {{ __('admin.bk_km') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('admin.bk_sched_datetime') }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $booking->booking_date->format('d F Y') }}</h6>
                            <div class="small text-secondary">{{ __('admin.bk_slot') }} {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <span class="small text-muted d-block uppercase mb-1">{{ __('admin.bk_ass_mechanic') }}</span>
                            @if($booking->assignedStaff)
                                <strong class="text-dark">{{ $booking->assignedStaff->name }}</strong>
                                <div class="small text-muted">{{ __('admin.bk_email') }} {{ $booking->assignedStaff->email }}</div>
                            @else
                                <span class="text-secondary small italic">{{ __('admin.bk_not_assigned') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($booking->customer_remark)
                        <div class="bg-light p-3 rounded-3 mt-4">
                            <span class="small text-muted d-block mb-1">{{ __('admin.bk_cust_remarks') }}</span>
                            <p class="mb-0 small text-dark">"{{ $booking->customer_remark }}"</p>
                        </div>
                    @endif

                    @if($booking->cancellation_reason)
                        <div class="bg-danger-subtle text-danger-emphasis p-3 rounded-3 mt-4">
                            <span class="small d-block mb-1 fw-bold">{{ __('admin.bk_reason_cancel') }}</span>
                            <p class="mb-0 small">{{ $booking->cancellation_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Rating Feedbacks -->
            @if($booking->review)
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-star text-brand me-2"></i>{{ __('admin.bk_review_rating') }}</h5>
                    <div class="bg-light p-3 rounded-3">
                        <div class="text-warning mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $booking->review->rating ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                            <span class="text-muted small ms-2">{{ __('admin.bk_rating') }} {{ $booking->review->rating }} {{ __('admin.bk_out_of_5') }}</span>
                        </div>
                        <p class="mb-0 text-dark small">"{{ $booking->review->comment ?? __('admin.bk_no_comment') }}"</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Activity Logs -->
        <div class="col-lg-4">
            <!-- Customer Details Profile -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-user text-brand me-2"></i>{{ __('admin.bk_cust_profile') }}</h5>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light p-3 text-secondary"><i class="fa-solid fa-user fs-4"></i></div>
                    <div>
                        <strong class="text-dark d-block">{{ $booking->user->name ?? __('admin.bk_del_user') }}</strong>
                        <span class="small text-muted d-block"><i class="fa-solid fa-envelope me-1"></i> {{ $booking->user->email ?? __('admin.bk_na') }}</span>
                        <span class="small text-muted d-block"><i class="fa-solid fa-phone me-1"></i> {{ $booking->user->phone ?? __('admin.bk_na') }}</span>
                    </div>
                </div>
            </div>

            <!-- System Activity Logs -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clock-rotate-left text-brand me-2"></i>{{ __('admin.bk_act_log') }}</h5>
                <div style="max-height: 400px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0 small ps-2 border-start border-2 border-light position-relative">
                        @forelse($booking->activityLogs as $log)
                            <li class="mb-3 position-relative">
                                <span class="position-absolute bg-brand rounded-circle" style="left: -14px; top: 4px; width: 8px; height: 8px;"></span>
                                <div class="fw-bold text-dark">{{ $log->action }}</div>
                                <p class="text-secondary mb-1">{{ $log->description }}</p>
                                <span class="text-muted small d-block">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                            </li>
                        @empty
                            <li class="text-secondary small italic py-2">{{ __('admin.bk_no_events') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
