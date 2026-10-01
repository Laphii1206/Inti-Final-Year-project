@extends('layouts.app')

@section('styles')
@include('partials.account-dark')
<style>
.fav-page { min-height: 60vh; }

/* Sidebar active state */
.list-group-item.active {
    background-color: #EC1F24;
    border-color: #EC1F24;
    color: #fff;
}

.fav-card {
    border-radius: 14px;
    border: 1px solid #e9ecef;
    transition: transform .25s, box-shadow .25s;
    overflow: hidden;
}
.fav-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(236,31,36,.1);
}
.fav-card-img-wrap {
    height: 160px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.fav-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 12px;
}
.btn-brand { background-color: #EC1F24; color:#fff; border:none; transition:all .25s; }
.btn-brand:hover { background-color: #c81519; color:#fff; transform:translateY(-1px); }

.empty-state {
    text-align: center;
    padding: 80px 20px;
}
.empty-state .empty-hero-icon { font-size: 4.5rem; color: #ffb3b8; margin-bottom: 1.5rem; }
.acct-dark .fav-card { background:#111 !important; border:1px solid rgba(229,50,45,.15) !important; }
.acct-dark .badge.bg-danger-subtle { background-color: rgba(229,50,45,.18) !important; color:#f87171 !important; }

@media (max-width: 767.98px) {
    /* 2x2 grid mode on mobile: compact and tight */
    .fav-card-img-wrap { height: 125px; }
    .fav-item-title { font-size: 0.88rem !important; line-height: 1.25; margin-bottom: 4px !important; }
    .fav-item-desc { font-size: 0.75rem !important; margin-bottom: 8px !important; }
    .fav-item-price { font-size: 0.95rem !important; margin-bottom: 10px !important; }
    .btn-brand { font-size: 0.78rem !important; padding: 0.35rem 0.5rem !important; }
}
</style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.profile_sidebar_my_likes') => null]" />
@endsection

@section('content')
<div class="container my-3 my-md-5 fav-page acct-dark">
    @include('partials.mobile-account-tabs')
    <div class="row">

        {{-- Sidebar --}}
        <div class="col-md-3 mb-4 order-2 order-md-1">
            @include('partials.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-12 col-md-9 order-1 order-md-2">
            {{-- Header Card --}}
            <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 mb-4 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center text-danger flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-heart fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-white">{{ __('account.profile_sidebar_my_likes') }}</h4>
                        <p class="text-white-50 small mb-0">{{ __('account.fav_subtitle') }}</p>
                    </div>
                </div>
                
                <div class="ms-auto">
                    <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">{{ $favourites->count() }}</span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
            @endif

            @if($favourites->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-dark">
                    <div class="empty-hero-icon mb-3">
                        <i class="fa-solid fa-heart-circle-bolt text-brand fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">{{ __('account.fav_empty_title') }}</h4>
                    <p class="text-white-50 small mb-4 mx-auto" style="max-width: 420px; line-height: 1.6;">
                        {{ __('account.fav_empty_desc') }}
                    </p>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-brand px-5 py-3 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-2 text-decoration-none">
                            <i class="fa-solid fa-compass fs-5" style="color: #fff;"></i>
                            <span>{{ __('account.fav_browse_btn') }}</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="row g-3 g-md-4">
                    @foreach($favourites as $service)
                        <div class="col-6 col-md-6 col-xl-4">
                            <div class="card fav-card border-0 shadow-sm h-100 d-flex flex-column">
                                {{-- Image --}}
                                <div class="fav-card-img-wrap position-relative">
                                    @if($service->image_path)
                                        <img src="{{ Storage::url($service->image_path) }}" alt="{{ $service->name }}">
                                    @else
                                        <i class="fa-solid fa-screwdriver-wrench fa-3x text-muted opacity-25"></i>
                                    @endif
                                    <span class="badge bg-danger-subtle text-danger position-absolute top-0 start-0 m-2 px-2 py-1" style="font-size:0.68rem; z-index:2;">
                                        {{ ucwords($service->category) }}
                                    </span>
                                </div>

                                <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                    <h6 class="fw-bold text-white mb-1 fav-item-title">{{ $service->name }}</h6>
                                    @if($service->description)
                                        <p class="text-white-50 small mb-2 flex-grow-1 fav-item-desc"
                                           style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; font-size:0.78rem;">
                                            {{ $service->description }}
                                        </p>
                                    @else
                                        <div class="flex-grow-1 mb-2"></div>
                                    @endif
                                    <h5 class="fw-bold text-danger mb-3 fav-item-price">RM {{ number_format($service->price, 2) }}</h5>

                                    <div class="d-flex gap-2 mt-auto">
                                        <a href="{{ route('bookings.create') }}?service={{ $service->id }}"
                                           class="btn btn-brand btn-sm flex-fill fw-semibold d-flex align-items-center justify-content-center gap-1">
                                            <i class="fa-solid fa-calendar-check"></i> <span>{{ __('landing.sv_book_now') }}</span>
                                        </a>
                                        <form action="{{ route('favourites.toggle', $service->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-2.5"
                                                    title="{{ __('landing.sv_remove_fav') }}">
                                                <i class="fa-solid fa-heart-crack"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>



@endsection
