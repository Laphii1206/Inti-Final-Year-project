@extends('layouts.app')

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.breadcrumb_services') => null]" />
@endsection

@section('content')
<section class="hero-products text-white position-relative d-flex align-items-center" style="background: url('{{ asset('images/product-car.png') }}') center right/cover no-repeat;">
    <div class="hero-products-overlay"></div>
    <div class="container position-relative py-5">
        <div class="col-lg-7">
            <h1 class="fw-bold display-4 mb-3 lh-sm">{{ __('products.hero_title_1') }}<br><span class="text-brand">{{ __('products.hero_title_2') }}</span></h1>
            <p class="fs-5 text-white-50 mb-4">{{ __('products.hero_subtitle') }}</p>
            <a href="#categories" class="btn btn-brand btn-lg px-4 fw-bold rounded-3">{{ __('products.hero_btn_book') }} <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<section class="py-5" id="categories" style="background-color:#0a0a0a;">
    <div class="container">
        <h2 class="fw-bold mb-4 text-white">{{ __('products.browse_heading') }}</h2>

        @if($categories->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-box-open fs-1 text-white-50 mb-3"></i>
                <p class="text-white-50">{{ __('products.empty_state') }}</p>
            </div>
        @else
            <div class="row g-3 g-md-4">
                @php
                    $imageMap = [
                        'car-mats' => 'car-mats.jpg',
                        'dashcams' => 'dashcams.webp',
                        'maintenance' => 'maintenance.jpg',
                        'tinting-films' => 'tinting-films.webp',
                        'tyres' => 'tyres.webp',
                        'wipers' => 'wipers.webp',
                    ];
                @endphp
                @foreach($categories as $cat)
                    @php
                        $slug = \Illuminate\Support\Str::slug($cat);
                        $img = $imageMap[$slug] ?? null;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-4">
                        <a href="{{ route('services.show', $slug) }}" class="text-decoration-none">
                            <div class="cat-card rounded-4 overflow-hidden shadow-sm position-relative">
                                @if($img)
                                    <img src="{{ asset('images/categories/' . $img) }}" alt="{{ ucwords($cat) }}" class="cat-card-img">
                                @else
                                    <div class="cat-card-img d-flex align-items-center justify-content-center bg-dark">
                                        <i class="fa-solid fa-box-open text-white-50 fs-1"></i>
                                    </div>
                                @endif
                                <div class="cat-card-gradient"></div>
                                <div class="cat-card-body">
                                    <h5 class="fw-bold text-white mb-1">{{ ucwords($cat) }}</h5>
                                    <span class="small text-white-50">{{ __('products.explore') }} <i class="fa-solid fa-arrow-right ms-1"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="py-4" style="background-color:#111; border-top:1px solid rgba(255,255,255,.08);">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3 d-flex align-items-center gap-3">
                <i class="fa-solid fa-calendar-check text-brand fs-3"></i>
                <div><div class="fw-bold small text-white">{{ __('products.trust_easy_booking') }}</div><div class="text-white-50" style="font-size:.75rem;">{{ __('products.trust_easy_booking_sub') }}</div></div>
            </div>
            <div class="col-6 col-md-3 d-flex align-items-center gap-3">
                <i class="fa-solid fa-shield-halved text-brand fs-3"></i>
                <div><div class="fw-bold small text-white">{{ __('products.trust_authentic') }}</div><div class="text-white-50" style="font-size:.75rem;">{{ __('products.trust_authentic_sub') }}</div></div>
            </div>
            <div class="col-6 col-md-3 d-flex align-items-center gap-3">
                <i class="fa-solid fa-star text-brand fs-3"></i>
                <div><div class="fw-bold small text-white">{{ __('products.trust_rewards') }}</div><div class="text-white-50" style="font-size:.75rem;">{{ __('products.trust_rewards_sub') }}</div></div>
            </div>
            <div class="col-6 col-md-3 d-flex align-items-center gap-3">
                <i class="fa-solid fa-headset text-brand fs-3"></i>
                <div><div class="fw-bold small text-white">{{ __('products.trust_support') }}</div><div class="text-white-50" style="font-size:.75rem;">{{ __('products.trust_support_sub') }}</div></div>
            </div>
        </div>
    </div>
</section>

<style>
body { background-color: #0a0a0a !important; }
.hero-products { min-height: 380px; }
.hero-products-overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(10,10,10,.97) 0%, rgba(10,10,10,.88) 28%, rgba(10,10,10,.45) 58%, rgba(10,10,10,.08) 100%); }
.cat-card { height: 220px; }
.cat-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.cat-card:hover .cat-card-img { transform: scale(1.06); }
.cat-card-gradient { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.85) 0%, rgba(0,0,0,.15) 55%, rgba(0,0,0,0) 100%); }
.cat-card-body { position: absolute; left: 0; bottom: 0; padding: 1.25rem; width: 100%; }

@media (max-width: 767.98px) {
    .cat-card { height: 155px; border-radius: 14px !important; }
    .cat-card-body { padding: 0.75rem; }
    .cat-card-body h5 { font-size: 0.95rem !important; margin-bottom: 2px !important; line-height: 1.2; }
    .cat-card-body span { font-size: 0.72rem !important; display: block; }
}
</style>
@endsection
