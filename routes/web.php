<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\WebAuthnController;

// Customer Controllers
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\PaymentController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminBranchController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminStatisticsController;
use App\Http\Controllers\Admin\AdminDeleteRequestController;
use App\Http\Controllers\Admin\AdminReviewController;

use App\Http\Controllers\Admin\UserManagementController;

// Mechanic Controllers
use App\Http\Controllers\Mechanic\CheckInController;
use App\Http\Controllers\Mechanic\MechanicDashboardController;

// Landing Page
Route::get('/', function () {
    $branches = \App\Models\Branch::where('is_active', true)->get();
    $reviews = \App\Models\Review::with(['user', 'booking.service'])
                ->visible()
                ->where('rating', '>=', 4)
                ->latest()
                ->take(6)
                ->get();
    return view('welcome', compact('branches', 'reviews'));
})->name('home');

// Public Services Category Pages
Route::get('/products', [\App\Http\Controllers\PublicServiceController::class, 'index'])->name('products.index');
Route::get('/services/{category}', [\App\Http\Controllers\PublicServiceController::class, 'show'])->name('services.show');
Route::get('/search', [\App\Http\Controllers\PublicServiceController::class, 'search'])->name('search');

Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');
Route::view('/return-policy', 'legal.returns')->name('legal.returns');
Route::view('/terms-and-conditions', 'legal.terms')->name('legal.terms');

Route::get('/help-centre', [\App\Http\Controllers\HelpCentreController::class, 'index'])->name('help-centre.index');

// Public Branch Detail Page
Route::get('/branches/{branch}', function (\App\Models\Branch $branch) {
    abort_if(!$branch->is_active, 404);
    return view('public.branches.show', compact('branch'));
})->name('branches.show');

// Language Switcher — POST to prevent CSRF-based session hijacking via GET
Route::post('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'zh', 'ms', 'ta'])) {
        session(['locale' => $locale]);
        if (auth()->check()) {
            auth()->user()->update(['language' => $locale]);
        }
    }
    return redirect()->back();
})->name('lang.switch');

// Theme Switcher — POST to prevent CSRF-based session hijacking via GET
Route::post('/theme/toggle', function () {
    $theme = session('theme', 'light');
    $newTheme = $theme === 'light' ? 'dark' : 'light';
    session(['theme' => $newTheme]);

    if (auth()->check()) {
        auth()->user()->update(['theme' => $newTheme]);
    }
    return redirect()->back();
})->name('theme.toggle');

// GUEST ROUTES (Auth)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:3,1');

    Route::get('/auth/google', [\App\Http\Controllers\Auth\AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\AuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/facebook', [\App\Http\Controllers\Auth\AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [\App\Http\Controllers\Auth\AuthController::class, 'handleFacebookCallback']);

    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:3,1');
    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');

    // WebAuthn Passkey Login (Guest)
    Route::post('webauthn/login/options', [WebAuthnController::class, 'loginOptions'])->name('webauthn.login.options');
    Route::post('webauthn/login', [WebAuthnController::class, 'login'])->name('webauthn.login');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // WebAuthn Passkey Registration (Authenticated)
    Route::post('webauthn/register/options', [WebAuthnController::class, 'registerOptions'])->name('webauthn.register.options');
    Route::post('webauthn/register', [WebAuthnController::class, 'register'])->name('webauthn.register');
    Route::delete('webauthn/destroy', [WebAuthnController::class, 'destroy'])->name('webauthn.destroy');

    // Support Ticket Attachments (Accessible by Customer & Admin)
    Route::get('/attachments/{attachment}/download', [\App\Http\Controllers\TicketAttachmentController::class, 'download'])->name('attachments.download');
});

// CUSTOMER ROUTES (Protected)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('bookings.index'))->name('dashboard');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/my-cars', [CustomerDashboardController::class, 'carsIndex'])->name('cars.index');
    Route::post('/dashboard/cars', [CustomerDashboardController::class, 'addCar'])->name('cars.store');
    Route::put('/dashboard/cars/{car}', [CustomerDashboardController::class, 'updateCar'])->name('cars.update');
    Route::delete('/dashboard/cars/{car}', [CustomerDashboardController::class, 'destroyCar'])->name('cars.destroy');
    Route::post('/dashboard/cars/{car}/default', [CustomerDashboardController::class, 'setDefaultCar'])->name('cars.setDefault');
    Route::post('/dashboard/delete-request', [CustomerDashboardController::class, 'deleteAccountRequest'])->name('delete-request.store');
    
    // Customer Profile


    // Customer Booking Wizard
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/slot-availability', [BookingController::class, 'slotAvailability'])->name('bookings.slotAvailability');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/receipt', [BookingController::class, 'invoice'])->name('bookings.invoice');
    Route::post('/bookings/{booking}/review', [BookingController::class, 'submitReview'])->name('bookings.review.store');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/reschedule', [BookingController::class, 'reschedule'])->name('bookings.reschedule');

    // Customer Payment Routes
    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/bookings/{booking}/pay/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/bookings/{booking}/pay/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('payment.page');
    Route::post('/bookings/{booking}/pay/counter', [PaymentController::class, 'payAtCounter'])->name('payment.counter');
    Route::post('/bookings/{booking}/pay/switch', [PaymentController::class, 'switchMethod'])->name('payment.switch');


    Route::get('/rewards', [\App\Http\Controllers\Customer\MembershipController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/tiers', [\App\Http\Controllers\Customer\MembershipController::class, 'tiers'])->name('rewards.tiers');
    Route::get('/rewards/qr', [\App\Http\Controllers\Customer\MembershipController::class, 'getQrCode'])->name('rewards.qr');
    Route::post('/rewards/checkin', [\App\Http\Controllers\Customer\MembershipController::class, 'checkin'])->name('rewards.checkin');
    Route::post('/rewards/redeem-points', [\App\Http\Controllers\Customer\MembershipController::class, 'redeemPoints'])->name('rewards.redeemPoints');
    Route::post('/rewards/redeem-code', [\App\Http\Controllers\Customer\MembershipController::class, 'redeemCode'])->name('rewards.redeemCode');
    Route::post('/rewards/apply-referral', [\App\Http\Controllers\Customer\MembershipController::class, 'applyReferral'])->name('rewards.applyReferral');
    Route::post('/rewards/spin', [\App\Http\Controllers\Customer\MembershipController::class, 'spinWheel'])->name('rewards.spin');
    Route::get('/vouchers', [\App\Http\Controllers\Customer\VoucherController::class, 'index'])->name('vouchers.index');

    Route::post('/support-tickets', [\App\Http\Controllers\Customer\SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('/support-tickets/{ticketNumber}', [\App\Http\Controllers\Customer\SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('/support-tickets/{ticketNumber}/message', [\App\Http\Controllers\Customer\SupportTicketController::class, 'sendMessage'])->name('support-tickets.message');
    Route::post('/support-tickets/{ticketNumber}/resolve', [\App\Http\Controllers\Customer\SupportTicketController::class, 'markAsResolved'])->name('support-tickets.resolve');
    Route::post('/support-tickets/{ticketNumber}/close', [\App\Http\Controllers\Customer\SupportTicketController::class, 'close'])->name('support-tickets.close');
    Route::post('/support-tickets/{ticketNumber}/rate', [\App\Http\Controllers\Customer\SupportTicketController::class, 'rate'])->name('support-tickets.rate');

    // Favourites
    Route::get('/favourites', [\App\Http\Controllers\Customer\FavouritesController::class, 'index'])->name('favourites.index');
    Route::post('/favourites/{service}/toggle', [\App\Http\Controllers\Customer\FavouritesController::class, 'toggle'])->name('favourites.toggle');
});


// ADMIN ROUTES (Protected by Auth + Admin Role Check)
Route::prefix('admin')->middleware(['auth', 'role:admin', 'restrict.subadmin'])->name('admin.')->group(function () {
    
    Route::resource('branches', AdminBranchController::class);
    Route::resource('services', AdminServiceController::class);



    
    // Booking 路由
    Route::resource('bookings', AdminBookingController::class);
    Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::post('bookings/{booking}/mark-no-show', [AdminBookingController::class, 'markNoShow'])->name('bookings.markNoShow');
    Route::post('bookings/{booking}/complete', [AdminBookingController::class, 'complete'])->name('bookings.complete');
    Route::post('bookings/{booking}/generate-qr', [AdminBookingController::class, 'generateQr'])->name('bookings.generateQr');
    Route::post('bookings/{booking}/mark-paid', [AdminBookingController::class, 'markPaid'])->name('bookings.markPaid');
    
    // 统计 & 审核 & 评价 
    Route::get('statistics', [AdminStatisticsController::class, 'index'])->name('statistics.index');
    Route::get('statistics/report', [AdminStatisticsController::class, 'downloadReport'])->name('statistics.report');
    Route::resource('reviews', AdminReviewController::class);
    Route::post('reviews/{review}/toggle', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggle');
    
    // 删除账号请求 delete 
    Route::get('delete-requests', [AdminDeleteRequestController::class, 'index'])->name('delete-requests.index');
    Route::post('delete-requests/{deleteRequest}/approve', [AdminDeleteRequestController::class, 'approve'])->name('delete-requests.approve');
    Route::post('delete-requests/{deleteRequest}/reject', [AdminDeleteRequestController::class, 'reject'])->name('delete-requests.reject');
    
    // 用户管理 user manager
    Route::resource('users', UserManagementController::class);
    Route::post('users/{user}/upgrade', [UserManagementController::class, 'upgradeToMechanic'])->name('users.upgrade');
    Route::post('users/{user}/downgrade', [UserManagementController::class, 'downgradeRole'])->name('users.downgrade');
    Route::post('users/{user}/restore', [UserManagementController::class, 'restore'])->name('users.restore')->withTrashed();
    Route::delete('users/{user}/passkey', [UserManagementController::class, 'removePasskey'])->name('users.removePasskey');
    Route::post('users/subadmin', [UserManagementController::class, 'createSubAdmin'])->name('users.createSubAdmin');

    Route::get('memberships', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'index'])->name('memberships.index');
    Route::get('memberships/analytics', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'analytics'])->name('memberships.analytics');
    Route::get('memberships/scanner', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'scanner'])->name('memberships.scanner');
    Route::get('memberships/{user}/scan', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'resolveScan'])->name('memberships.scan_resolve')->middleware('signed');
    Route::get('memberships/{user}', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'show'])->name('memberships.show');
    Route::post('memberships/{user}/adjust', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'adjustPoints'])->name('memberships.adjust');
    Route::get('promo-codes', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'promoCodes'])->name('promo-codes.index');
    Route::post('promo-codes', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'createPromoCode'])->name('promo-codes.store');

    Route::get('support-tickets', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::post('support-tickets/bulk', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'bulkAction'])->name('support-tickets.bulk');
    Route::get('support-tickets/{supportTicket}', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('support-tickets/{supportTicket}/tags', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'updateTags'])->name('support-tickets.tags');
    Route::post('support-tickets/{supportTicket}/toggle-lock', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'toggleLock'])->name('support-tickets.toggle-lock');
    Route::post('support-tickets/{supportTicket}/message', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'sendMessage'])->name('support-tickets.message');
    Route::put('support-tickets/messages/{message}/edit', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'editMessage'])->name('support-tickets.message.edit');
    Route::post('support-tickets/{supportTicket}/status', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'updateStatus'])->name('support-tickets.status');
    Route::post('support-tickets/{supportTicket}/priority', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'updatePriority'])->name('support-tickets.priority');
    Route::post('support-tickets/{supportTicket}/assign', [\App\Http\Controllers\Admin\AdminSupportTicketController::class, 'assign'])->name('support-tickets.assign');
});

// MECHANIC ROUTES (Protected by Auth + Mechanic Role Check)
Route::prefix('mechanic')->middleware(['auth', 'role:mechanic'])->name('mechanic.')->group(function () {
    Route::get('dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');
    Route::get('jobs', [MechanicDashboardController::class, 'jobs'])->name('jobs.index');
    Route::post('jobs/{booking}/complete', [MechanicDashboardController::class, 'complete'])->name('jobs.complete');
    
    Route::get('checkin', [CheckInController::class, 'scan'])->name('checkin.scan');
    Route::post('checkin/process', [CheckInController::class, 'process'])->name('checkin.process');
});

// STRIPE WEBHOOK (no auth, CSRF-exempt)
Route::post('stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// STORAGE FALLBACK (For environments where public/storage is not a symlink)
Route::get('/storage/{path}', function ($path) {
    // Prevent path traversal attacks (e.g. /../../../.env)
    if (str_contains($path, '..') || str_contains($path, "\0")) {
        abort(403);
    }
    $base     = realpath(storage_path('app/public'));
    $filePath = realpath(storage_path('app/public/' . $path));
    if (!$filePath || !str_starts_with($filePath, $base . DIRECTORY_SEPARATOR)) {
        abort(403);
    }
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');