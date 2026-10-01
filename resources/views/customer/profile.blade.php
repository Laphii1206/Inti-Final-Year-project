@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@include('partials.flatpickr-dark-styles')
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_profile') => null]" />
@endsection

@section('content')
<div class="container my-3 my-md-5 acct-dark">
    @include('partials.mobile-account-tabs')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            @include('partials.customer-sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-12 col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 text-start">
                    <h4 class="fw-bold mb-0"><i class="fa-regular fa-user text-brand me-2"></i>{{ __('account.profile_sidebar_profile') ?? 'Profile' }}</h4>
                    <p class="text-muted small">{{ __('account.profile_subtitle') }}</p>
                </div>
                <div class="card-body p-4 text-start">
                    @if (session('success'))
                        <div class="alert alert-success border-0 bg-success text-white small mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 bg-danger text-white small mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Photo Section -->
                        <div class="mb-4 pb-3 border-bottom text-start">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" id="avatarPreview" class="rounded-circle object-fit-cover shadow-sm border border-2 border-light" style="width: 80px; height: 80px;">
                                <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-brand text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 26px; height: 26px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Change photo">
                                    <i class="fa-solid fa-pencil" style="font-size: 11px;"></i>
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp">
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 border-bottom pb-2">{{ __('account.settings_pi_title') ?? 'Personal Information' }}</h6>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">{{ __('account.settings_label_name') ?? 'Full Name' }}</label>
                                <input type="text" name="name" class="form-control bg-light border-0" value="{{ old('name', $user->name) }}" {{ $user->is_main_admin ? 'readonly' : 'required' }}>
                                @if($user->is_main_admin)
                                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-circle-info me-1"></i>Main Admin cannot change name.</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">{{ __('account.settings_label_email') ?? 'Email Address' }}</label>
                                <input type="email" name="email" class="form-control bg-light border-0" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">{{ __('account.settings_label_phone') ?? 'Phone Number' }}</label>
                                <input type="text" name="phone" class="form-control bg-light border-0" placeholder="e.g. 0123456789" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary d-flex align-items-center justify-content-between">
                                    <span>{{ __('account.settings_label_dob') ?? 'Birthday Date' }}</span>
                                    @if($user->date_of_birth)
                                        <span class="badge bg-secondary text-light px-2 py-1" style="font-size: 0.65rem;" title="Date of birth is locked once set"><i class="fa-solid fa-lock me-1"></i>{{ __('account.settings_dob_locked') ?? 'Locked' }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.65rem;"><i class="fa-solid fa-cake-candles me-1"></i>{{ __('account.settings_dob_badge') ?? '2X Birthday Points' }}</span>
                                    @endif
                                </label>
                                @if($user->date_of_birth)
                                    <div class="input-group" style="background-color: #25262c; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; opacity: 0.85;">
                                        <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="fa-solid fa-calendar-day"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 text-muted ps-1 py-2" value="{{ $user->date_of_birth->format('d/m/Y') }}" disabled>
                                        <span class="input-group-text bg-transparent border-0 text-muted pe-3 small" style="font-size: 0.75rem;"><i class="fa-solid fa-shield-halved me-1"></i>{{ __('account.settings_dob_locked_hint') ?? 'Verified & Locked' }}</span>
                                    </div>
                                @else
                                    <div class="input-group flatpickr-dob-group">
                                        <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                        <input type="text" name="date_of_birth" class="form-control flatpickr-dob" placeholder="{{ __('booking.cb_select_date') ?? 'DD/MM/YYYY' }}" max="{{ date('Y-m-d') }}" value="{{ old('date_of_birth') }}" readonly>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-brand px-4 py-2 fw-bold rounded-3">{{ __('account.profile_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('partials.flatpickr-dob-scripts')
<script>
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush
@endsection
