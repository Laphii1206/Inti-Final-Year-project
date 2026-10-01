@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.memberships.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('admin.mem_back') }}
            </a>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-user-circle text-brand me-2"></i>{{ $user->name }}</h2>
            <p class="text-muted mb-0">{{ $user->email }} · {{ $user->phone }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT: Membership Card + {{ __('admin.mem_adjust_points') }} -->
        <div class="col-lg-4">
            <!-- Membership Summary -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4"
                 style="background: linear-gradient(135deg, #1a1b20, #2d2d35);">
                @php
                    $tierColors = ['bronze' => '#cd7f32', 'silver' => '#c0c0c0', 'gold' => '#ffd700'];
                    $tierIcons  = ['bronze' => 'fa-shield', 'silver' => 'fa-medal', 'gold' => 'fa-crown'];
                @endphp
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold"
                          style="background:{{ $tierColors[$membership->tier] }}; color:#000; font-size:0.75rem;">
                        <i class="fa-solid {{ $tierIcons[$membership->tier] }} me-1"></i>
                        {{ ucfirst($membership->tier) }}
                    </span>
                    <span class="text-white-50 small font-monospace">{{ $membership->membership_id }}</span>
                </div>
                <div class="text-center mb-3">
                    <div style="font-size:3rem;font-weight:800;color:#fff;text-shadow:0 0 20px rgba(236,31,36,0.3);">
                        {{ number_format($membership->reward_points) }}
                    </div>
                    <div class="text-white-50 small">{{ __('admin.mem_reward_points') }}</div>
                </div>
                <hr style="border-color:rgba(255,255,255,0.1);">
                <div class="row text-center g-2">
                    <div class="col-6">
                        <div class="text-white-50 small">{{ __('admin.mem_annual_spending') }}</div>
                        <div class="text-white fw-bold">RM {{ number_format($membership->cumulative_annual_spending, 2) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-white-50 small">{{ __('admin.mem_member_since') }}</div>
                        <div class="text-white fw-bold">{{ $membership->membership_join_date->format('M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- {{ __('admin.mem_adjust_points') }} -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-user-shield text-brand me-2"></i>{{ __('admin.mem_adjust_points') }}</h6>
                <form action="{{ route('admin.memberships.adjust', $user) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('admin.mem_points_ph') }}</label>
                        <input type="number" name="points" class="form-control" placeholder="{{ __('admin.mem_points_ph2') }}" required>
                        <div class="form-text">{{ __('admin.mem_points_help') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('admin.mem_reason') }}</label>
                        <input type="text" name="description" class="form-control" placeholder="{{ __('admin.mem_reason_ph') }}" maxlength="255" required>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 rounded-pill">
                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i>{{ __('admin.mem_apply_adj') }}
                    </button>
                </form>
            </div>

            <!-- Vouchers -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-ticket text-brand me-2"></i>{{ __('admin.mem_vouchers') }} ({{ $vouchers->count() }})</h6>
                @forelse($vouchers->take(5) as $voucher)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="small fw-semibold">{{ $voucher->getDiscountLabel() }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">{{ $voucher->code }}</div>
                    </div>
                    <span class="badge bg-{{ $voucher->status === 'available' ? 'success' : 'secondary' }}-subtle text-{{ $voucher->status === 'available' ? 'success' : 'secondary' }} rounded-pill small">
                        {{ $voucher->status === 'available' ? __('rewards.modal_active') : ($voucher->status === 'used' ? __('rewards.vouchers_used_badge') : __('rewards.vouchers_expired_badge')) }}
                    </span>
                </div>
                @empty
                <p class="text-muted small">{{ __('admin.mem_no_vouchers') }}</p>
                @endforelse
            </div>
        </div>

        <!-- RIGHT: {{ __('admin.mem_pt_history') }} -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left text-brand me-2"></i>{{ __('admin.mem_pt_history') }}</h5>
                    <span class="badge bg-secondary rounded-pill">{{ $transactions->total() }} {{ __('admin.mem_records') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">{{ __('admin.mem_type') }}</th>
                                    <th>{{ __('admin.mem_desc') }}</th>
                                    <th>{{ __('admin.mem_points') }}</th>
                                    <th class="pe-4">{{ __('admin.mem_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $tx)
                                <tr>
                                    <td class="ps-4">
                                        @php
                                            $typeColors = ['earn'=>'success','bonus'=>'warning','redeem'=>'primary','spin'=>'info','expire'=>'danger','admin_adjust'=>'secondary'];
                                            $typeIcons  = ['earn'=>'fa-circle-plus','bonus'=>'fa-star','redeem'=>'fa-ticket','spin'=>'fa-rotate','expire'=>'fa-clock','admin_adjust'=>'fa-user-shield'];
                                        @endphp
                                        <span class="badge bg-{{ $typeColors[$tx->type] ?? 'secondary' }}-subtle text-{{ $typeColors[$tx->type] ?? 'secondary' }} rounded-pill px-2">
                                            <i class="fa-solid {{ $typeIcons[$tx->type] ?? 'fa-circle' }} me-1"></i>{{ ucfirst(str_replace('_',' ',$tx->type)) }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $tx->description }}</td>
                                    <td>
                                        <span class="fw-bold {{ $tx->points >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $tx->points >= 0 ? '+' : '' }}{{ number_format($tx->points) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-muted small">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">{{ __('admin.mem_no_tx') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                <div class="card-footer bg-transparent border-0 p-3">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
