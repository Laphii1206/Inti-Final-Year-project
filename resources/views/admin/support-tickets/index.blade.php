@extends('layouts.admin')

@section('styles')
<style>
/* ─── Admin Ticket Dashboard Premium SaaS Styling ─── */
.stat-card {
    background: #ffffff;
    border: 1px solid #eaeeef;
    border-radius: 18px;
    padding: 22px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
    border-color: #cbd5e1;
}
.stat-card.active-open { border: 2px solid #ffc107; background: #fffdf5; }
.stat-card.active-in_prog { border: 2px solid #0d6efd; background: #f5f9ff; }
.stat-card.active-resolved { border: 2px solid #198754; background: #f5fbf7; }
.stat-card.active-closed { border: 2px solid #6c757d; background: #f8f9fa; }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}

.filter-card {
    background: #ffffff;
    border: 1px solid #eaeeef;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
}

.table-card {
    background: #ffffff;
    border: 1px solid #eaeeef;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}

.table > :not(caption) > * > * {
    padding: 16px 20px;
    vertical-align: middle;
}

.table-hover tbody tr {
    transition: background-color 0.15s ease;
}
.table-hover tbody tr:hover {
    background-color: #f8fafc !important;
}

.badge-pill {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.76rem;
    letter-spacing: 0.02em;
}

.avatar-sm {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.78rem;
    overflow: hidden; padding: 0; flex-shrink: 0;
}
</style>
@endsection

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark">{{ __('admin.tk_title') }}</h3>
        <p class="text-muted small mb-0">{{ __('admin.tk_desc') }}</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm small">
            <i class="fa-solid fa-bell text-warning me-1"></i> SLA Tracking Active
        </span>
    </div>
</div>

{{-- Status Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.support-tickets.index', ['status' => 'open']) }}" class="text-decoration-none text-dark">
            <div class="stat-card d-flex align-items-center justify-content-between {{ $status === 'open' ? 'active-open' : '' }}">
                <div>
                    <div class="small fw-semibold text-muted text-uppercase tracking-wider mb-1">{{ __('admin.tk_open') }}</div>
                    <div class="fw-bold fs-2 text-warning mb-0">{{ $counts['open'] }}</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-inbox"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.support-tickets.index', ['status' => 'in_progress']) }}" class="text-decoration-none text-dark">
            <div class="stat-card d-flex align-items-center justify-content-between {{ $status === 'in_progress' ? 'active-in_prog' : '' }}">
                <div>
                    <div class="small fw-semibold text-muted text-uppercase tracking-wider mb-1">{{ __('admin.tk_in_prog') }}</div>
                    <div class="fw-bold fs-2 text-primary mb-0">{{ $counts['in_progress'] }}</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-spinner fa-spin-pulse"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.support-tickets.index', ['status' => 'resolved']) }}" class="text-decoration-none text-dark">
            <div class="stat-card d-flex align-items-center justify-content-between {{ $status === 'resolved' ? 'active-resolved' : '' }}">
                <div>
                    <div class="small fw-semibold text-muted text-uppercase tracking-wider mb-1">{{ __('admin.tk_resolved') }}</div>
                    <div class="fw-bold fs-2 text-success mb-0">{{ $counts['resolved'] }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.support-tickets.index', ['status' => 'closed']) }}" class="text-decoration-none text-dark">
            <div class="stat-card d-flex align-items-center justify-content-between {{ $status === 'closed' ? 'active-closed' : '' }}">
                <div>
                    <div class="small fw-semibold text-muted text-uppercase tracking-wider mb-1">{{ __('admin.tk_closed') }}</div>
                    <div class="fw-bold fs-2 text-secondary mb-0">{{ $counts['closed'] }}</div>
                </div>
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="fa-solid fa-archive"></i>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filter Card --}}
<div class="filter-card p-4 mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.tk_search') }}</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 py-2"
                       placeholder="{{ __('admin.tk_search_ph') }}"
                       value="{{ $search }}">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.tk_status') }}</label>
            <select name="status" class="form-select form-select-sm py-2">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{ __('admin.tk_all') }}</option>
                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>{{ __('admin.tk_open') }}</option>
                <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>{{ __('admin.tk_in_prog') }}</option>
                <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>{{ __('admin.tk_resolved') }}</option>
                <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>{{ __('admin.tk_closed') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.tk_type') }}</label>
            <select name="type" class="form-select form-select-sm py-2">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>{{ __('admin.tk_all_types') }}</option>
                <option value="service_quality" {{ $type === 'service_quality' ? 'selected' : '' }}>{{ __('admin.tk_type_svc') }}</option>
                <option value="overcharge" {{ $type === 'overcharge' ? 'selected' : '' }}>{{ __('admin.tk_type_bill') }}</option>
                <option value="parts_issue" {{ $type === 'parts_issue' ? 'selected' : '' }}>{{ __('admin.tk_type_parts') }}</option>
                <option value="general" {{ $type === 'general' ? 'selected' : '' }}>{{ __('admin.tk_type_gen') }}</option>
                <option value="other" {{ $type === 'other' ? 'selected' : '' }}>{{ __('admin.tk_type_other') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.tk_priority') }}</label>
            <select name="priority" class="form-select form-select-sm py-2">
                <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>{{ __('admin.tk_all') }}</option>
                <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>🔴 {{ __('admin.tk_urg') }}</option>
                <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>🟠 {{ __('admin.tk_high') }}</option>
                <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>🟡 {{ __('admin.tk_med') }}</option>
                <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>🔵 {{ __('admin.tk_low') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.tk_assign') }}</label>
            <select name="assigned_to" class="form-select form-select-sm py-2">
                <option value="all" {{ $assignedTo === 'all' ? 'selected' : '' }}>{{ __('admin.tk_all_adm') }}</option>
                <option value="unassigned" {{ $assignedTo === 'unassigned' ? 'selected' : '' }}>{{ __('admin.tk_unassign') }}</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ (string)$assignedTo === (string)$admin->id ? 'selected' : '' }}>
                        {{ $admin->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-dark btn-sm py-2 px-3 fw-bold flex-fill shadow-sm">{{ __('admin.tk_filter') }}</button>
            @if($search || $status !== 'all' || $type !== 'all' || $priority !== 'all' || $assignedTo !== 'all')
                <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-outline-secondary btn-sm py-2" title="{{ __('admin.tk_clear') }}"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </div>
    </form>
</div>

{{-- Tickets Table Card --}}
<form action="{{ route('admin.support-tickets.bulk') }}" method="POST" id="bulk-form">
    @csrf
<div class="table-card">
    @if($tickets->isEmpty())
        <div class="text-center py-5 my-3 text-muted">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light p-4 mb-3" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-ticket fs-2 text-secondary opacity-50"></i>
            </div>
            <h6 class="fw-bold text-dark">{{ __('admin.tk_empty') }}</h6>
            <p class="small mb-0">No support tickets match your filter criteria.</p>
        </div>
    @else
        <div class="table-responsive">
            <div class="bg-light p-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check ms-2 mb-0">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label small fw-bold text-dark" for="selectAll">{{ __('admin.tk_sel_all') }}</label>
                    </div>
                    <span class="badge bg-white text-muted border px-3 py-1 small">Total: {{ $tickets->total() }} tickets</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select name="action" class="form-select form-select-sm w-auto py-1" id="bulk-action" required>
                        <option value="">{{ __('admin.tk_bulk') }}</option>
                        <option value="assign">{{ __('admin.tk_assign_to') }}</option>
                        <option value="resolve">{{ __('admin.tk_mark_res') }}</option>
                        <option value="close">{{ __('admin.tk_close') }}</option>
                    </select>
                    <select name="assigned_to" class="form-select form-select-sm w-auto d-none py-1" id="bulk-assign-to">
                        <option value="">{{ __('admin.tk_sel_adm') }}</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-dark btn-sm px-3 fw-bold">{{ __('admin.tk_apply') }}</button>
                </div>
            </div>
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase tracking-wider">
                    <tr>
                        <th class="ps-4 py-3" style="width: 40px;"></th>
                        <th class="py-3">{{ __('admin.tk_col_t') }}</th>
                        <th class="py-3">{{ __('admin.tk_col_c') }}</th>
                        <th class="py-3">{{ __('admin.tk_col_ty') }}</th>
                        <th class="py-3">{{ __('admin.tk_col_p') }}</th>
                        <th class="py-3">{{ __('admin.tk_col_s') }}</th>
                        <th class="py-3">{{ __('admin.tk_col_a') }}</th>
                        <th class="py-3">{{ __('admin.tk_col_l') }}</th>
                        <th class="py-3 text-end pe-4">{{ __('admin.tk_col_ac') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        @php
                            $statusColors = ['open'=>'warning','in_progress'=>'primary','resolved'=>'success','closed'=>'secondary'];
                            $priorityColors = ['low'=>'info','medium'=>'warning','high'=>'danger','urgent'=>'danger'];
                            $sc = $statusColors[$ticket->status] ?? 'secondary';
                            $pc = $priorityColors[$ticket->priority ?? 'medium'] ?? 'warning';
                            $hasUnread = ($ticket->unread_count ?? 0) > 0;
                            $isOverdue = in_array($ticket->status, ['open', 'in_progress']) && $ticket->updated_at->diffInHours(now()) > 24;
                        @endphp
                        <tr class="{{ $hasUnread ? 'bg-warning bg-opacity-10' : '' }}">
                            <td class="ps-4">
                                <input type="checkbox" name="ticket_ids[]" value="{{ $ticket->id }}" class="form-check-input ticket-cb">
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    @if($isOverdue)
                                        <span class="badge bg-danger rounded-pill" style="font-size:0.65rem;">{{ __('admin.tk_overdue') }}</span>
                                    @endif
                                    @if($hasUnread)
                                        <span class="badge bg-danger rounded-pill animate-pulse" style="font-size:0.65rem;">NEW</span>
                                    @endif
                                    <span class="fw-bold text-dark font-monospace">#{{ $ticket->ticket_number }}</span>
                                </div>
                                <div class="fw-semibold text-dark" style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $ticket->subject }}
                                </div>
                                @if($ticket->booking)
                                    <div class="text-muted small mt-1"><i class="fa-solid fa-link me-1 text-brand"></i>{{ __('admin.tk_booking_no') }} #{{ $ticket->booking->number }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-dark text-white flex-shrink-0">
                                        <img src="{{ $ticket->user?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($ticket->user?->name ?? 'User').'&background=212529&color=fff&size=64' }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $ticket->user->name ?? '—' }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $ticket->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill small fw-semibold">{{ __('admin.ticket_type_'.$ticket->type) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-pill bg-{{ $pc }} {{ in_array($ticket->priority, ['low','medium']) ? 'text-dark bg-opacity-25 border border-'.$pc : 'text-white' }}">
                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.45rem;"></i> {{ __('admin.ticket_priority_'.($ticket->priority ?? 'medium')) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-pill bg-{{ $sc }} {{ in_array($ticket->status, ['open']) ? 'text-dark bg-opacity-25 border border-warning' : 'text-white' }}">
                                    {{ __('admin.ticket_status_'.$ticket->status) }}
                                </span>
                            </td>
                            <td>
                                @if($ticket->assignedAdmin)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-primary text-white">
                                            <img src="{{ $ticket->assignedAdmin?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($ticket->assignedAdmin?->name ?? 'Admin').'&background=0d6efd&color=fff&size=64' }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                                        </div>
                                        <span class="small fw-semibold text-dark">{{ $ticket->assignedAdmin->name }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted border small fst-italic px-3 py-1">{{ __('admin.tk_unassign') }}</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                <div>{{ $ticket->updated_at->diffForHumans() }}</div>
                                @if($hasUnread)
                                    <span class="badge bg-danger ms-1 mt-1" style="font-size:.65rem;">+{{ $ticket->unread_count }} msg</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.support-tickets.show', $ticket) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                    <span>Open Workspace</span> <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-white">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
</form>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.ticket-cb').forEach(cb => cb.checked = this.checked);
    });
    document.getElementById('bulk-action')?.addEventListener('change', function() {
        const assignSelect = document.getElementById('bulk-assign-to');
        if (this.value === 'assign') {
            assignSelect.classList.remove('d-none');
            assignSelect.required = true;
        } else {
            assignSelect.classList.add('d-none');
            assignSelect.required = false;
        }
    });
</script>
@endsection
