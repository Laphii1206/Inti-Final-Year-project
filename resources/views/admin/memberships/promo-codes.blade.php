@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.memberships.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('admin.mem_back') }}
            </a>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-tag text-brand me-2"></i>{{ __('admin.promo_title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin.promo_desc') }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Create Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-plus text-brand me-2"></i>{{ __('admin.promo_create') }}</h6>
                <form action="{{ route('admin.promo-codes.store') }}" method="POST">
                    @csrf
                        @if(session('success'))
                            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger py-2 small mb-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('admin.promo_code_label') }}</label>
                        <input type="text" name="code" class="form-control font-monospace text-uppercase" placeholder="SUMMER10" maxlength="20" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">{{ __('admin.promo_type') }}</label>
                            <select name="type" class="form-select" required>
                                <option value="fixed">{{ __('admin.promo_fixed') }}</option>
                                <option value="percentage">{{ __('admin.promo_percent') }}</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">{{ __('admin.promo_value') }}</label>
                            <input type="number" name="value" class="form-control" placeholder="10" step="0.01" min="1" max="100" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('admin.promo_max_uses') }}</label>
                        <input type="number" name="max_uses" class="form-control" value="100" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('admin.promo_expiry') }}</label>
                        <input type="date" name="expires_at" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold">{{ __('admin.promo_tnc') }}</label>
                        <textarea name="terms_conditions" class="form-control" rows="2" placeholder="{{ __('admin.promo_ph_tnc') }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 rounded-pill">
                        <i class="fa-solid fa-plus me-2"></i>{{ __('admin.promo_create_btn') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Codes Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">{{ __('admin.promo_table_code') }}</th>
                                    <th>{{ __('admin.promo_type') }}</th>
                                    <th>{{ __('admin.promo_value') }}</th>
                                    <th>{{ __('admin.promo_table_uses') }}</th>
                                    <th>{{ __('admin.promo_table_expiry') }}</th>
                                    <th class="pe-4">{{ __('admin.promo_table_status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($codes as $code)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-dark font-monospace fs-6 px-3">{{ $code->code }}</span>
                                            <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm d-flex align-items-center justify-content-center p-0" style="width: 28px; height: 28px;"
                                                    data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                                    data-code="{{ $code->code }}"
                                                    data-discount="{{ $code->type === 'fixed' ? 'RM '.number_format($code->value,0) : number_format($code->value,0).'%' }} Off"
                                                    data-source="Promo Code"
                                                    data-status="{{ $code->status ?? 'Available' }}"
                                                    data-status-class="bg-success"
                                                    data-expiry="{{ $code->expires_at ? $code->expires_at->format('d M Y') : 'No Expiry' }}"
                                                    data-tnc="{{ $code->getTermsAndConditions() }}"
                                                    title="View Promo Code Details & T&C">
                                                <i class="fa-solid fa-circle-info text-brand" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $code->type === 'fixed' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }} rounded-pill">
                                            {{ $code->type === 'fixed' ? 'RM Fixed' : '% Discount' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $code->type === 'fixed' ? 'RM '.number_format($code->value,2) : number_format($code->value,0).'%' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-fill" style="height:6px;width:80px;">
                                                <div class="progress-bar bg-brand" style="width:{{ $code->max_uses > 0 ? min(100, ($code->current_uses / $code->max_uses) * 100) : 0 }}%"></div>
                                            </div>
                                            <span class="small text-muted">{{ $code->current_uses }}/{{ $code->max_uses }}</span>
                                        </div>
                                    </td>
                                    <td class="small">
                                        @if($code->expires_at)
                                            {{ $code->expires_at->format('d M Y') }}
                                            @if($code->isExpired())
                                                <span class="badge bg-danger-subtle text-danger ms-1">{{ __('admin.promo_expired') }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('admin.promo_no_expiry') }}</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        @if(!$code->isExpired() && $code->current_uses < $code->max_uses)
                                            <span class="badge bg-success-subtle text-success rounded-pill">{{ __('admin.promo_active') }}</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ __('admin.promo_inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">{{ __('admin.promo_no_codes') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($codes->hasPages())
                <div class="card-footer bg-transparent border-0 p-3">{{ $codes->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('partials.voucher-tnc-modal')
@endsection
