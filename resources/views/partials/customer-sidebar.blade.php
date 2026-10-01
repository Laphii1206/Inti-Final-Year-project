<div class="d-none d-md-block">
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 text-center">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 overflow-hidden shadow-sm border" style="width: 80px; height: 80px;">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-100 h-100 object-fit-cover">
        </div>
        <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
        <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
    </div>
    <div class="list-group list-group-flush rounded-bottom-4 text-start">
        <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action py-3 border-0 {{ Route::is('profile.index') ? 'active' : '' }}">
            <i class="fa-regular fa-user me-2 w-20px text-center"></i> {{ __('account.profile_sidebar_profile') }}
        </a>
        @if(!auth()->user()->isAdmin())
        <a href="{{ route('bookings.index') }}" class="list-group-item list-group-item-action py-3 border-0 {{ Route::is('bookings.index') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-check me-2 w-20px text-center"></i> {{ __('account.profile_sidebar_my_bookings') }}
        </a>
        <a href="{{ route('cars.index') }}" class="list-group-item list-group-item-action py-3 border-0 {{ Route::is('cars.index') ? 'active' : '' }}">
            <i class="fa-solid fa-car me-2 w-20px text-center"></i> {{ __('account.profile_sidebar_my_cars') }}
        </a>
        <a href="{{ route('vouchers.index') }}" class="list-group-item list-group-item-action py-3 border-0 {{ Route::is('vouchers.index') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket me-2 w-20px text-center"></i> {{ __('account.profile_sidebar_my_vouchers') }}
        </a>

        <a href="{{ route('favourites.index') }}" class="list-group-item list-group-item-action py-3 border-0 {{ Route::is('favourites.index') ? 'active' : '' }}">
            <i class="fa-solid fa-heart me-2 w-20px text-center"></i> {{ __('account.profile_sidebar_my_likes') }}
        </a>
        @endif
        <a href="{{ route('settings') }}" class="list-group-item list-group-item-action py-3 border-0 {{ Route::is('settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear me-2 w-20px text-center"></i> {{ __('account.profile_sidebar_settings') ?? 'Settings' }}
        </a>
    </div>
</div>
</div>
