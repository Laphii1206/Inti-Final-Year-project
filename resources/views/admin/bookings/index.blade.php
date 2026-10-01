@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-calendar-check text-brand me-2"></i>{{ __('admin.bk_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.bk_desc') }}</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label small fw-bold text-secondary">{{ __('admin.bk_search_cust') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" id="search" class="form-control bg-light border-0" placeholder="{{ __('admin.bk_search_ph') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label small fw-bold text-secondary">{{ __('admin.bk_status_filter') }}</label>
                <select name="status" id="status" class="form-select bg-light border-0">
                    <option value="">{{ __('admin.bk_all_statuses') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.bk_status_pending') }}</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('admin.bk_status_confirmed') }}</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('admin.bk_status_in_progress') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('admin.bk_status_completed') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('admin.bk_status_cancelled') }}</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('admin.bk_status_rejected') }}</option>
                    <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>{{ __('admin.bk_status_no_show') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label small fw-bold text-secondary">{{ __('admin.bk_date') }}</label>
                <input type="date" name="date" id="date" class="form-control bg-light border-0" value="{{ request('date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-dark w-100 py-2.5 fw-bold"><i class="fa-solid fa-filter me-1"></i> {{ __('admin.bk_filter_btn') }}</button>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr class="small text-uppercase">
                        <th class="ps-4">{{ __('admin.bk_col_booking') }}</th>
                        <th>{{ __('admin.bk_col_customer') }}</th>
                        <th>{{ __('admin.bk_col_vehicle') }}</th>
                        <th>{{ __('admin.bk_col_service') }}</th>
                        <th>{{ __('admin.bk_col_date') }}</th>
                        <th>{{ __('admin.bk_col_status') }}</th>
                        <th>{{ __('admin.bk_col_staff') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.bk_col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Fetch mechanics and branches dynamically for modal selects
                        $mechanics = \App\Models\User::where('role', \App\Models\User::ROLE_MECHANIC)->get();
                        $branches = \App\Models\Branch::all();
                    @endphp

                    @forelse($bookings as $booking)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ $booking->number }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->user->name ?? __('admin.bk_del_user') }}</div>
                                <div class="small text-muted">{{ $booking->user->phone ?? __('admin.bk_na') }}</div>
                            </td>
                            <td>
                                <div>{{ $booking->car->brand ?? __('admin.bk_unknown') }} {{ $jobTitle ?? ($booking->car->model ?? 'Vehicle') }}</div>
                                <span class="badge bg-light text-dark small border">{{ $booking->car->car_plate ?? __('admin.bk_na') }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $booking->service->name ?? __('admin.bk_del_service') }}</div>
                                <span class="small text-secondary"><i class="fa-solid fa-warehouse text-brand me-1"></i> {{ $booking->branch->name ?? __('admin.bk_del_branch') }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $booking->booking_date->format('d M Y') }}</div>
                                <span class="small text-muted">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-hourglass-half me-1"></i> {{ __('admin.bk_lbl_pending') }}</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-success text-white"><i class="fa-solid fa-check me-1"></i> {{ __('admin.bk_lbl_confirmed') }}</span>
                                @elseif($booking->status === 'in_progress')
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-screwdriver-wrench me-1"></i> {{ __('admin.bk_lbl_in_progress') }}</span>
                                @elseif($booking->status === 'completed')
                                    <span class="badge bg-secondary text-white"><i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.bk_lbl_completed') }}</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger text-white"><i class="fa-solid fa-ban me-1"></i> {{ __('admin.bk_lbl_cancelled') }}</span>
                                @elseif($booking->status === 'rejected')
                                    <span class="badge bg-danger text-white"><i class="fa-solid fa-circle-xmark me-1"></i> {{ __('admin.bk_lbl_rejected') }}</span>
                                @elseif($booking->status === 'no_show')
                                    <span class="badge bg-dark text-white"><i class="fa-solid fa-user-slash me-1"></i> {{ __('admin.bk_lbl_no_show') }}</span>
                                @endif
                                <div class="mt-1">
                                    @if($booking->isPaid())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.bk_paid') }}</span>
                                    @else
                                        <span class="badge bg-light text-muted border"><i class="fa-solid fa-circle-xmark me-1"></i> {{ __('admin.bk_unpaid') }}</span>
                                        @if(!in_array($booking->status, ['cancelled', 'rejected']))
                                        <form action="{{ route('admin.bookings.markPaid', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this booking as PAID?');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm py-0 px-1 ms-1" style="font-size:0.7rem;" title="Mark as Paid">
                                                <i class="fa-solid fa-money-bill-wave me-1"></i> Paid
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($booking->assignedStaff)
                                    <span class="badge bg-dark"><i class="fa-solid fa-user-check me-1 text-brand"></i> {{ $booking->assignedStaff->name }}</span>
                                @else
                                    <span class="text-secondary small italic">{{ __('admin.bk_unassigned') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking->uuid) }}" class="btn btn-sm btn-outline-dark" title="{{ __('admin.bk_view_logs') }}"><i class="fa-solid fa-eye"></i></a>
                                    
                                    @if(in_array($booking->status, ['pending', 'confirmed', 'in_progress']))
                                        <button class="btn btn-sm btn-dark btn-update-booking" 
                                                data-id="{{ $booking->uuid }}" 
                                                data-status="{{ $booking->status }}" 
                                                data-staff="{{ $booking->assigned_staff_id }}"
                                                data-branch="{{ $booking->branch_id }}"
                                                data-date="{{ $booking->booking_date->format('Y-m-d') }}"
                                                data-time="{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}">
                                            <i class="fa-solid fa-user-pen"></i> {{ __('admin.bk_assign_edit') }}
                                        </button>
                                    @endif

                                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                        <button class="btn btn-sm btn-danger btn-cancel-booking" data-id="{{ $booking->uuid }}" data-action="cancel" title="{{ __('admin.bk_cancel_btn') }}"><i class="fa-solid fa-ban"></i></button>
                                        <button class="btn btn-sm btn-outline-danger btn-cancel-booking" data-id="{{ $booking->uuid }}" data-action="reject" title="{{ __('admin.bk_reject_btn') }}"><i class="fa-solid fa-circle-xmark"></i></button>
                                    @endif

                                    @if($booking->status === 'confirmed')
                                        <form action="{{ route('admin.bookings.markNoShow', $booking->uuid) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ __('admin.bk_mark_no_show') }}" onclick="return confirm('{{ __('admin.bk_are_you_sure') }}')"><i class="fa-solid fa-user-slash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-calendar-times fs-2 mb-3"></i>
                                <p class="mb-0">{{ __('admin.bk_empty') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($bookings->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Update Booking / Assign Staff -->
<div class="modal fade" id="updateBookingModal" tabindex="-1" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="updateBookingModalLabel"><i class="fa-solid fa-user-pen text-brand me-2"></i>{{ __('admin.bk_modal_edit_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="updateBookingForm">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label for="modal-status" class="form-label small fw-bold text-secondary">{{ __('admin.bk_modal_status') }}</label>
                        <select name="status" id="modal-status" class="form-select bg-light border-0">
                            <option value="pending">{{ __('admin.bk_lbl_pending') }}</option>
                            <option value="confirmed">{{ __('admin.bk_status_confirmed') }}</option>
                            <option value="in_progress">{{ __('admin.bk_status_in_progress') }}</option>
                            <option value="completed">{{ __('admin.bk_status_completed') }}</option>
                            <option value="cancelled">{{ __('admin.bk_status_cancelled') }}</option>
                            <option value="rejected">{{ __('admin.bk_status_rejected') }}</option>
                            <option value="no_show">{{ __('admin.bk_status_no_show') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal-staff" class="form-label small fw-bold text-secondary">{{ __('admin.bk_modal_staff') }}</label>
                        <select name="assigned_staff_id" id="modal-staff" class="form-select bg-light border-0">
                            <option value="">{{ __('admin.bk_select_mechanic') }}</option>
                            @foreach($mechanics as $mechanic)
                                <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal-branch" class="form-label small fw-bold text-secondary">{{ __('admin.bk_modal_branch') }}</label>
                        <select name="branch_id" id="modal-branch" class="form-select bg-light border-0">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label for="modal-date" class="form-label small fw-bold text-secondary">{{ __('admin.bk_date') }}</label>
                            <input type="date" name="booking_date" id="modal-date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-6">
                            <label for="modal-time" class="form-label small fw-bold text-secondary">{{ __('admin.bk_modal_time') }}</label>
                            <input type="time" name="start_time" id="modal-time" class="form-control bg-light border-0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.bk_modal_cancel') }}</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('admin.bk_modal_save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Cancel/Reject Booking -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="cancelBookingModalLabel"><i class="fa-solid fa-ban me-2"></i><span id="cancel-modal-title-action">{{ __('admin.bk_modal_cancel') }}</span> {{ __('admin.bk_cancel_modal_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="cancelBookingForm">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <p class="small text-secondary mb-3">{!! __('admin.bk_cancel_modal_desc') !!}</p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label small fw-bold text-secondary">{{ __('admin.bk_cancel_reason') }}</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="form-control bg-light border-0" placeholder="{{ __('admin.bk_cancel_ph') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.bk_close') }}</button>
                    <button type="submit" class="btn btn-danger px-4">{{ __('admin.bk_submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit / Assign Modal
        const editButtons = document.querySelectorAll('.btn-update-booking');
        const editModal = new bootstrap.Modal(document.getElementById('updateBookingModal'));
        const editForm = document.getElementById('updateBookingForm');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const bookingId = this.dataset.id;
                
                // Form action URL
                editForm.action = `/admin/bookings/${bookingId}`;

                // Populate modal fields
                document.getElementById('modal-status').value = this.dataset.status;
                document.getElementById('modal-staff').value = this.dataset.staff || '';
                document.getElementById('modal-branch').value = this.dataset.branch;
                document.getElementById('modal-date').value = this.dataset.date;
                document.getElementById('modal-time').value = this.dataset.time;

                editModal.show();
            });
        });

        // Cancel / Reject Modal
        const cancelButtons = document.querySelectorAll('.btn-cancel-booking');
        const cancelModal = new bootstrap.Modal(document.getElementById('cancelBookingModal'));
        const cancelForm = document.getElementById('cancelBookingForm');

        cancelButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const bookingId = this.dataset.id;
                const action = this.dataset.action; // 'cancel' or 'reject'

                // Form action URL
                cancelForm.action = `/admin/bookings/${bookingId}/${action}`;

                // Update text strings depending on cancellation vs rejection
                const actionText = action === 'cancel' ? 'Cancel' : 'Reject';

                document.getElementById('cancel-modal-title-action').innerText = actionText;
                document.getElementById('cancellation_reason').value = '';

                cancelModal.show();
            });
        });
    });
</script>
@endsection
