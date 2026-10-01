@if(auth()->check() && auth()->user()->date_of_birth)
    @php
        $dob = auth()->user()->date_of_birth;
        $isBirthdayToday = ($dob->month === now()->month && $dob->day === now()->day);
        $isBirthdayMonth = ($dob->month === now()->month && !$isBirthdayToday);
        $tier = auth()->user()->membership?->tier ?? 'bronze';
        $isBronze = $tier === 'bronze';
    @endphp

    @if($isBirthdayToday)
    <div class="card border-0 shadow-sm p-4 rounded-4 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #EC1F24 0%, #B01014 60%, #1a1b20 100%); transition: all 0.3s ease;">
        <div class="position-absolute end-0 top-50 translate-middle-y me-n3 opacity-25 d-none d-md-block pointer-events-none">
            <i class="fa-solid fa-cake-candles fa-8x text-white"></i>
        </div>
        <div class="d-flex flex-row justify-content-between align-items-center flex-wrap gap-3 position-relative z-1">
            <div class="pe-md-4">
                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2 text-uppercase d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-star text-danger"></i>
                    @if($isBronze)
                        Birthday Special — 2X Points Month
                    @elseif($tier === 'silver')
                        Silver VIP Birthday Reward
                    @else
                        Gold VIP Birthday Reward
                    @endif
                </span>
                <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span>{{ __('landing.bday_banner_title', ['name' => auth()->user()->name]) }}</span>
                </h4>
                <p class="text-white-75 mb-0 small" style="max-width: 650px; line-height: 1.5;">
                    @if($isBronze)
                        {{ __('landing.bday_banner_bronze_desc') }}
                    @elseif($tier === 'silver')
                        {{ __('landing.bday_banner_silver_desc') }}
                    @else
                        {{ __('landing.bday_banner_gold_desc') }}
                    @endif
                </p>
            </div>
            @if($isBronze)
                <a href="{{ route('bookings.create') }}" class="btn btn-light text-danger rounded-pill px-4 py-2 fw-bold shadow-sm flex-shrink-0 d-inline-flex align-items-center gap-2" style="transition: transform 0.2s ease;">
                    <i class="fa-solid fa-calendar-plus text-danger"></i>
                    <span>{{ __('landing.bday_banner_bronze_btn') }}</span>
                </a>
            @else
                <a href="{{ route('vouchers.index') }}" class="btn btn-light text-danger rounded-pill px-4 py-2 fw-bold shadow-sm flex-shrink-0 d-inline-flex align-items-center gap-2" style="transition: transform 0.2s ease;">
                    <i class="fa-solid fa-gift text-danger"></i>
                    <span>{{ __('landing.bday_banner_btn') }}</span>
                </a>
            @endif
        </div>
    </div>
    @elseif($isBirthdayMonth)
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #2d1f3d 0%, #1a1b20 100%); border-left: 5px solid #FFC107 !important;">
        <div class="d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2 d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;">
                    <i class="fa-solid fa-crown"></i> 2X Double Points Active
                </span>
                <h5 class="fw-bold text-white mb-1"><i class="fa-solid fa-cake-candles text-warning me-2"></i>{{ __('landing.bday_month_banner_title') }}</h5>
                <p class="text-white-50 small mb-0" style="max-width: 650px;">{{ __('landing.bday_month_banner_desc') }}</p>
            </div>
            <a href="{{ route('bookings.create') }}" class="btn btn-outline-light text-white rounded-pill px-4 py-2 fw-bold shadow-sm flex-shrink-0 small">
                <i class="fa-solid fa-calendar-plus me-2"></i>{{ __('account.bk_new_booking') }}
            </a>
        </div>
    </div>
    @endif
@endif
