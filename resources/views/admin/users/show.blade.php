@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('admin.usr_title') ?? 'Back to Users' }}
            </a>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-user-circle text-brand me-2"></i>{{ $user->name }}</h2>
            <p class="text-muted mb-0"><i class="fa-solid fa-envelope me-1"></i> {{ $user->email }} · <i class="fa-solid fa-phone me-1"></i> {{ $user->phone ?? 'No Phone' }}</p>
        </div>
        <div>
            @if($user->isAdmin())
                @if($user->is_main_admin)
                    <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-user-shield me-1"></i> Main Admin</span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-user-shield me-1"></i> Sub Admin</span>
                @endif
            @elseif($user->isMechanic())
                <span class="badge bg-dark px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-wrench me-1 text-brand"></i> Mechanic</span>
            @else
                <span class="badge bg-light text-secondary border px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-user me-1 text-muted"></i> Customer</span>
            @endif

            @if($user->trashed())
                <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill ms-1">Archived</span>
            @else
                <span class="badge bg-success px-3 py-2 fs-6 rounded-pill ms-1">Active</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: Profile & Cars -->
        <div class="col-lg-4">
            <!-- Profile Summary Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-id-card text-brand me-2"></i>Profile Details</h5>
                <ul class="list-unstyled mb-0 small">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Account ID:</span>
                        <span class="fw-bold font-monospace">#{{ $user->id }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Registered Date:</span>
                        <span class="fw-bold">{{ $user->created_at->format('d M Y, h:i A') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Passkeys Registered:</span>
                        <span class="fw-bold">{{ $user->webAuthnCredentials()->count() }}</span>
                    </li>
                    @if($user->membership)
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Membership Tier:</span>
                        <span class="badge bg-dark text-warning">{{ ucfirst($user->membership->tier) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Reward Points:</span>
                        <span class="fw-bold text-brand">{{ number_format($user->membership->reward_points) }} pts</span>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- Vehicles Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-car text-brand me-2"></i>Registered Vehicles ({{ $user->cars->count() }})</h5>
                @forelse($user->cars as $car)
                <div class="p-3 bg-light rounded-3 mb-2 d-flex justify-content-between align-items-center border">
                    <div>
                        <div class="fw-bold text-dark">{{ $car->brand }} {{ $car->model }} @if($car->year)({{ $car->year }})@endif</div>
                        <span class="badge bg-white text-dark border font-monospace mt-1">{{ $car->car_plate }}</span>
                        @if($car->is_default)
                            <span class="badge bg-brand text-white small ms-1">Default</span>
                        @endif
                    </div>
                    <div class="text-end small text-muted">
                        <div><i class="fa-solid fa-gauge-high me-1"></i>{{ number_format($car->mileage ?? 0) }} km</div>
                    </div>
                </div>
                @empty
                <p class="text-muted small mb-0 italic">No registered vehicles found.</p>
                @endforelse
            </div>
        </div>

        <!-- RIGHT COLUMN: Bookings & Activity Logs -->
        <div class="col-lg-8">
            <!-- Bookings History -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-calendar-check text-brand me-2"></i>Booking History ({{ $user->bookings->count() }})</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-dark small text-uppercase">
                            <tr>
                                <th class="ps-4">Booking</th>
                                <th>Service</th>
                                <th>Date & Time</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->bookings->sortByDesc('booking_date') as $booking)
                            <tr class="border-bottom">
                                <td class="ps-4 fw-bold">#{{ $booking->number }}</td>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $booking->service->name ?? 'Deleted Service' }}</div>
                                    @if($booking->review)
                                        <div class="text-warning small" style="font-size: 0.75rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-{{ $i <= $booking->review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $booking->booking_date->format('d M Y') }}</div>
                                    <span class="small text-muted">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                                </td>
                                <td class="fw-bold text-brand small">RM {{ number_format($booking->service_price_at_booking, 2) }}</td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="badge bg-success text-white">Confirmed</span>
                                    @elseif($booking->status === 'in_progress')
                                        <span class="badge bg-info text-dark">In Progress</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-secondary text-white">Completed</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger text-white">Cancelled</span>
                                    @elseif($booking->status === 'rejected')
                                        <span class="badge bg-danger text-white">Rejected</span>
                                    @elseif($booking->status === 'no_show')
                                        <span class="badge bg-dark text-white">No Show</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.bookings.show', $booking->uuid) }}" class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">No booking history available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left text-brand me-2"></i>Recent Activity Logs</h5>
                <div style="max-height: 400px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0 small ps-2 border-start border-2 border-light position-relative">
                        @forelse($user->activityLogs as $log)
                        <li class="mb-3 position-relative">
                            <span class="position-absolute bg-brand rounded-circle" style="left: -14px; top: 4px; width: 8px; height: 8px;"></span>
                            <div class="fw-bold text-dark">{{ $log->action }}</div>
                            <p class="text-secondary mb-1">{{ $log->description }}</p>
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span>{{ $log->created_at->format('d M Y, h:i A') }}</span>
                                @if($log->ip_address)
                                <span class="font-monospace"><i class="fa-solid fa-network-wired me-1"></i>{{ $log->ip_address }}</span>
                                @endif
                            </div>
                        </li>
                        @empty
                        <li class="text-muted small italic py-2">No recent activities logged for this account.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
