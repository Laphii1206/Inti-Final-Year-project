{{-- ================================================================
     Mobile Account Quick-Nav Tabs (ONLY visible on < 992px)
     Shows a horizontal scrollable row of icon+label pill tabs
     so users can switch between account sections without sidebar.
     ================================================================ --}}
<div class="d-md-none mobile-acct-tabs-wrap">
    <div class="mobile-acct-tabs">
        <a href="{{ route('profile.index') }}"
           class="mobile-acct-tab {{ Route::is('profile.index') ? 'active' : '' }}">
            <div class="mob-tab-icon">
                <i class="fa-regular fa-user"></i>
            </div>
            <span>{{ __('account.profile_sidebar_profile') }}</span>
        </a>
        @if(auth()->check() && !auth()->user()->isAdmin())
        <a href="{{ route('bookings.index') }}"
           class="mobile-acct-tab {{ Route::is('bookings.index') || Route::is('bookings.show') ? 'active' : '' }}">
            <div class="mob-tab-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <span>{{ __('account.profile_sidebar_my_bookings') }}</span>
        </a>
        <a href="{{ route('cars.index') }}"
           class="mobile-acct-tab {{ Route::is('cars.index') ? 'active' : '' }}">
            <div class="mob-tab-icon">
                <i class="fa-solid fa-car"></i>
            </div>
            <span>{{ __('account.profile_sidebar_my_cars') }}</span>
        </a>
        <a href="{{ route('vouchers.index') }}"
           class="mobile-acct-tab {{ Route::is('vouchers.index') ? 'active' : '' }}">
            <div class="mob-tab-icon">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <span>{{ __('account.profile_sidebar_my_vouchers') }}</span>
        </a>
        <a href="{{ route('favourites.index') }}"
           class="mobile-acct-tab {{ Route::is('favourites.index') ? 'active' : '' }}">
            <div class="mob-tab-icon">
                <i class="fa-solid fa-heart"></i>
            </div>
            <span>{{ __('account.profile_sidebar_my_likes') }}</span>
        </a>
        @endif
        <a href="{{ route('settings') }}"
           class="mobile-acct-tab {{ Route::is('settings') ? 'active' : '' }}">
            <div class="mob-tab-icon">
                <i class="fa-solid fa-gear"></i>
            </div>
            <span>{{ __('account.profile_sidebar_settings') ?? 'Settings' }}</span>
        </a>
    </div>
</div>

<style>
/* ── Mobile Account Tabs (only renders < 992px via d-md-none) ── */
.mobile-acct-tabs-wrap {
    margin: 0 -12px 20px -12px; /* bleed to screen edges */
    padding: 0 12px;
    overflow: hidden;
}
.mobile-acct-tabs {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 4px 4px 12px 4px;
    scrollbar-width: none;
    -ms-overflow-style: none;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
}
.mobile-acct-tabs::-webkit-scrollbar { display: none; }

.mobile-acct-tab {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    scroll-snap-align: start;
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.09);
    color: #9ca3af;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 72px;
}
.mobile-acct-tab:active {
    transform: scale(0.96);
}
.mobile-acct-tab.active {
    background: rgba(229, 50, 45, 0.15);
    border-color: rgba(229, 50, 45, 0.45);
    color: #e5322d;
}
.mob-tab-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.07);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: background 0.2s;
}
.mobile-acct-tab.active .mob-tab-icon {
    background: rgba(229, 50, 45, 0.2);
    color: #e5322d;
}
.mobile-acct-tab span {
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.01em;
    white-space: nowrap;
    line-height: 1;
}
</style>
