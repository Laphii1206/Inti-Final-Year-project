@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@endsection

@section('content')
<div class="container py-4 acct-dark">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('account.profile_sidebar_notifications') }}</h2>
        @if(auth()->user()->unreadNotifications()->exists())
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">{{ __('account.notif_mark_all_read') }}</button>
        </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="list-group">
            @foreach($notifications as $notification)
                @php $fNotif = \App\Helpers\NotificationHelper::format($notification); @endphp
                <div class="list-group-item list-group-item-action {{ empty($notification->read_at) ? 'list-group-item-light fw-bold' : '' }}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $fNotif['title'] }}</h5>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $fNotif['message'] }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        @if(!empty($fNotif['url']) && $fNotif['url'] !== '#')
                            <a href="{{ $fNotif['url'] }}" class="btn btn-sm btn-outline-primary">{{ __('landing.featured_view_btn') }}</a>
                        @else
                            <span></span>
                        @endif

                        @if(empty($notification->read_at))
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-decoration-none">{{ __('account.notif_mark_read') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4">
            <div class="card-body py-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 border shadow-sm" style="width: 80px; height: 80px;">
                    <i class="fa-regular fa-bell-slash fa-2x text-muted opacity-75"></i>
                </div>
                <h5 class="fw-bold text-secondary mb-2">{{ __('account.notif_none_yet') }}</h5>
                <p class="text-muted small mb-4 mx-auto" style="max-width: 420px;">{{ __('landing.notif_empty_desc') }}</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-brand rounded-3 px-4 py-2 fw-semibold">{{ __('account.notif_book_service') }}</a>
            </div>
        </div>
    @endif
</div>
@endsection
