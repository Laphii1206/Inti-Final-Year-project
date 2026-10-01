@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-crown text-warning me-2"></i>{{ __('admin.mem_title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin.mem_desc') }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.memberships.analytics') }}" class="btn btn-outline-info rounded-pill px-4">
                <i class="fa-solid fa-chart-line me-2"></i>{{ __('admin.mem_analytics') }}
            </a>
            <a href="{{ route('admin.memberships.scanner') }}" class="btn btn-dark rounded-pill px-4">
                <i class="fa-solid fa-qrcode me-2"></i>{{ __('admin.mem_scan_qr') }}
            </a>
            <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-brand rounded-pill px-4">
                <i class="fa-solid fa-tag me-2"></i>{{ __('admin.mem_promo_codes') }}
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                <div class="fs-3 fw-bold text-dark">{{ $stats['total'] }}</div>
                <div class="text-muted small">{{ __('admin.mem_total_members') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center" style="border-left: 4px solid #cd7f32 !important;">
                <div class="fs-3 fw-bold" style="color:#cd7f32;">{{ $stats['bronze'] }}</div>
                <div class="text-muted small"><i class="fa-solid fa-shield me-1"></i>{{ __('admin.mem_bronze') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center" style="border-left: 4px solid #c0c0c0 !important;">
                <div class="fs-3 fw-bold" style="color:#888;">{{ $stats['silver'] }}</div>
                <div class="text-muted small"><i class="fa-solid fa-medal me-1"></i>{{ __('admin.mem_silver') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 text-center" style="border-left: 4px solid #ffd700 !important;">
                <div class="fs-3 fw-bold text-warning">{{ $stats['gold'] }}</div>
                <div class="text-muted small"><i class="fa-solid fa-crown me-1"></i>{{ __('admin.mem_gold') }}</div>
            </div>
        </div>
    </div>

    <!-- {{ __('admin.mem_filter') }} -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">{{ __('admin.mem_search') }}</label>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin.mem_search_placeholder') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">{{ __('admin.mem_tier') }}</label>
                    <select name="tier" class="form-select">
                        <option value="">All {{ __('admin.mem_tier') }}s</option>
                        <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>{{ __('admin.mem_bronze') }}</option>
                        <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>{{ __('admin.mem_silver') }}</option>
                        <option value="gold"   {{ request('tier') === 'gold'   ? 'selected' : '' }}>{{ __('admin.mem_gold') }}</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-dark px-4">{{ __('admin.mem_filter') }}</button>
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-outline-secondary ms-1">{{ __('admin.mem_reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">{{ __('admin.mem_member') }}</th>
                            <th>{{ __('admin.mem_member') }}ship ID</th>
                            <th>{{ __('admin.mem_tier') }}</th>
                            <th>{{ __('admin.mem_points') }}</th>
                            <th>{{ __('admin.mem_annual_spending') }}</th>
                            <th>{{ __('admin.mem_bookings') }}</th>
                            <th>{{ __('admin.mem_joined') }}</th>
                            <th class="pe-4">{{ __('admin.mem_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        @php $m = $member->membership; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $member->name }}</div>
                                <div class="text-muted small">{{ $member->email }}</div>
                            </td>
                            <td>
                                @if($m)
                                    <span class="badge bg-dark font-monospace">{{ $m->membership_id }}</span>
                                @else
                                    <span class="text-muted small">{{ __('admin.mem_no_membership') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($m)
                                    @php
                                        $tierColors = ['bronze' => '#cd7f32', 'silver' => '#888', 'gold' => '#d4af37'];
                                        $tierIcons  = ['bronze' => 'fa-shield', 'silver' => 'fa-medal', 'gold' => 'fa-crown'];
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $tierColors[$m->tier] ?? '#888' }}; color:#fff;">
                                        <i class="fa-solid {{ $tierIcons[$m->tier] ?? 'fa-shield' }} me-1"></i>
                                        {{ ucfirst($m->tier) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($m)
                                    <span class="fw-bold text-brand">{{ number_format($m->reward_points) }}</span>
                                    <span class="text-muted small"> pts</span>
                                @else — @endif
                            </td>
                            <td>
                                @if($m)
                                    RM {{ number_format($m->cumulative_annual_spending, 2) }}
                                @else — @endif
                            </td>
                            <td>{{ $member->bookings_count }}</td>
                            <td>
                                @if($m)
                                    {{ $m->membership_join_date->format('d M Y') }}
                                @else — @endif
                            </td>
                            <td class="pe-4">
                                <a href="{{ route('admin.memberships.show', $member) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa-solid fa-eye me-1"></i>{{ __('admin.mem_view') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">{{ __('admin.mem_empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($members->hasPages())
        <div class="card-footer bg-transparent border-0 p-3">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
