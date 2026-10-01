@extends('layouts.app')

@section('styles')
<style>
body { background-color: #0a0a0a !important; }
main { background-color: #0a0a0a !important; }
.footer-custom { margin-top: 0 !important; }

.search-dark {
    background-color: #0a0a0a;
    color: #e4e4e7;
    min-height: 80vh;
}
.search-dark h1,.search-dark h2,.search-dark h3,
.search-dark h4,.search-dark h5,.search-dark h6 { color: #ffffff; }
.search-dark .text-red { color: #e5322d !important; }
.search-dark .text-muted-l { color: #9ca3af !important; }

.search-dk-card {
    background-color: #161616;
    border: 1px solid #262626;
    border-radius: 14px;
    transition: transform .3s, box-shadow .3s, border-color .3s;
    overflow: hidden;
}
.search-dk-card:hover {
    transform: translateY(-4px);
    border-color: rgba(229,50,45,.4);
    box-shadow: 0 10px 25px rgba(229,50,45,.15);
}
.search-dk-card .service-card-img {
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.search-dk-card:hover .service-card-img {
    transform: scale(1.06);
}
.btn-red {
    background: #e5322d; color: #fff; border: none;
    border-radius: 10px; font-weight: 600;
    transition: all .25s;
}
.btn-red:hover { background: #cc2a25; color: #fff; transform: translateY(-2px); }
.btn-outline-wh {
    background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.25);
    border-radius: 10px; font-weight: 600;
    transition: all .25s;
}
.btn-outline-wh:hover { border-color: #fff; color: #fff; background: rgba(255,255,255,.06); }

/* Search bar on {{ __('landing.search_result') }}s page */
.search-input-lg {
    background: #161616;
    border: 1px solid #404040;
    border-radius: 10px;
    color: #e4e4e7;
    padding: 12px 20px;
    font-size: 1rem;
    width: 100%;
    transition: all 0.2s;
}
.search-input-lg:focus {
    outline: none;
    border-color: #e5322d;
    box-shadow: 0 0 0 3px rgba(229,50,45,0.15);
    color: #e4e4e7;
    background: #161616;
}
.search-input-lg::placeholder { color: #6b7280; }

.cat-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(229,50,45,0.1);
    border: 1px solid rgba(229,50,45,0.25);
    color: #e5322d;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.cat-chip:hover {
    background: rgba(229,50,45,0.2);
    color: #ff4a4e;
}

.fav-btn-sm {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.2);
    color: #9ca3af;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 0.8rem;
    transition: all 0.2s;
    cursor: pointer;
}
.fav-btn-sm:hover, .fav-btn-sm.active {
    border-color: #e5322d;
    color: #e5322d;
}

/* ---------- Mobile 2x2 Grid Optimization ---------- */
@media (max-width: 575.98px) {
    .search-dk-card .d-flex.justify-content-center {
        height: 135px !important;
    }
    .search-dk-card .p-4 {
        padding: 0.75rem !important;
    }
    .search-dk-card h6 {
        font-size: 0.85rem !important;
        margin-bottom: 0.25rem !important;
    }
    .search-dk-card h5 {
        font-size: 0.95rem !important;
        margin-bottom: 0.5rem !important;
    }
    .search-dk-card p {
        font-size: 0.7rem !important;
        margin-bottom: 0.35rem !important;
    }
    .search-dk-card .btn-red, .search-dk-card .btn-outline-wh {
        padding: 0.4rem 0.3rem !important;
        font-size: 0.72rem !important;
        border-radius: 8px !important;
    }
}
</style>
@endsection

@section('content')
<div class="search-dark py-5">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none; padding:0; margin:0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted-l text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ __('landing.search_bc') }}</li>
            </ol>
        </nav>

        {{-- Search Form (prominent on {{ __('landing.search_result') }}s page) --}}
        <form action="{{ route('search') }}" method="GET" class="mb-5">
            <div class="d-flex gap-2">
                <input type="text" name="q" class="search-input-lg"
                       placeholder="{{ __('landing.search_ph') }}"
                       value="{{ $query }}" autofocus>
                <button type="submit" class="btn-red px-4 py-2 d-flex align-items-center gap-2" style="white-space:nowrap;">
                    <i class="fa-solid fa-magnifying-glass"></i> Search
                </button>
            </div>
        </form>

        @if(strlen($query) < 2)
            {{-- Empty / hint state --}}
            <div class="text-center py-5">
                <i class="fa-solid fa-magnifying-glass fa-3x mb-3" style="color:#404040;"></i>
                <h4 class="text-white">{{ __('landing.search_title') }}</h4>
                <p class="text-muted-l">{{ __('landing.search_hint_desc') }}</p>
            </div>
        @elseif($results->isEmpty() && $categories->isEmpty())
            {{-- No {{ __('landing.search_result') }}s --}}
            <div class="text-center py-5">
                <i class="fa-solid fa-circle-xmark fa-3x mb-3 text-red"></i>
                <h4 class="text-white">{{ __('landing.search_no_results') }} "{{ $query }}"</h4>
                <p class="text-muted-l">{{ __('landing.search_no_results_desc') }}</p>
                <a href="{{ route('products.index') }}" class="btn-red px-4 py-2 mt-2 d-inline-block text-decoration-none">
                    <i class="fa-solid fa-th-large me-2"></i>{{ __('landing.search_browse_all') }}
                </a>
            </div>
        @else
            {{-- Results header --}}
            <div class="mb-4">
                @php
                    $total = $results->count() + $categories->count();
                @endphp
                <p class="text-muted-l">
                    {{ __('landing.search_found') }} <span class="text-white fw-bold">{{ $total }}</span> {{ __('landing.search_result') }}{{ $total != 1 ? 's' : '' }} for
                    "<span class="text-red">{{ $query }}</span>"
                </p>
            </div>

            {{-- Matching Categories --}}
            @if($categories->isNotEmpty())
                <div class="mb-5">
                    <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-folder-open text-red me-2"></i>Categories</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            @php $slug = \Illuminate\Support\Str::slug($cat); @endphp
                            <a href="{{ route('services.show', $slug) }}" class="cat-chip">
                                <i class="fa-solid fa-arrow-right"></i> {{ ucwords($cat) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Matching Items --}}
            @if($results->isNotEmpty())
                <div>
                    <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-screwdriver-wrench text-red me-2"></i>Services</h5>
                    @php
                        $catImgMap = [
                            'tyres' => 'images/categories/tyres.webp',
                            'maintenance' => 'images/categories/maintenance.jpg',
                            'tinting films' => 'images/categories/tinting-films.webp',
                            'wipers' => 'images/categories/wipers.webp',
                            'dashcams' => 'images/categories/dashcams.webp',
                            'car mats' => 'images/categories/car-mats.jpg',
                        ];
                    @endphp
                    <div class="row g-2 g-md-4">
                        @foreach($results as $service)
                            @php
                                $catKey = strtolower(trim($service->category ?? ''));
                                $fallbackImg = asset($catImgMap[$catKey] ?? 'images/product-car.png');
                                $imgUrl = $service->image_path ? Storage::url($service->image_path) : $fallbackImg;
                            @endphp
                            <div class="col-6 col-md-4 col-lg-4">
                                <div class="search-dk-card h-100 d-flex flex-column">
                                    <!-- Service Image -->
                                    <div class="d-flex justify-content-center align-items-center bg-white position-relative flex-shrink-0" style="height: 200px; width: 100%; overflow: hidden;">
                                        <img src="{{ $imgUrl }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: {{ $service->image_path ? 'contain' : 'cover' }}; padding: {{ $service->image_path ? '15px' : '0' }};" class="service-card-img">
                                        <span class="position-absolute top-0 start-0 m-3 badge shadow-sm" style="background:rgba(229,50,45,0.9);color:#fff;font-size:.75rem;backdrop-filter:blur(4px);">
                                            {{ ucwords($service->category) }}
                                        </span>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="p-4 d-flex flex-column flex-grow-1">
                                        <h6 class="fw-bold text-white mb-2 fs-5">{{ $service->name }}</h6>

                                        @if($service->description)
                                            <p class="text-muted-l small mb-3 flex-grow-1"
                                               style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                {{ $service->description }}
                                            </p>
                                        @else
                                            <div class="flex-grow-1 mb-3"></div>
                                        @endif

                                        <h5 class="fw-bold text-red mb-3">RM {{ number_format($service->price, 2) }}</h5>

                                        <div class="d-flex flex-column gap-2 mt-auto">
                                            @auth
                                                <a href="{{ route('bookings.create') }}?service={{ $service->id }}"
                                                   class="btn-red w-100 py-2 text-center text-decoration-none d-block">
                                                    <i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.sv_book_now') }}
                                                </a>
                                                <button type="button"
                                                        class="btn-outline-wh w-100 py-2 fav-btn"
                                                        data-service-id="{{ $service->id }}"
                                                        data-service-name="{{ $service->name }}"
                                                        data-favourited="{{ auth()->user()->favourites()->where('service_id', $service->id)->exists() ? 'true' : 'false' }}">
                                                    <i class="{{ auth()->user()->favourites()->where('service_id', $service->id)->exists() ? 'fa-solid' : 'fa-regular' }} fa-heart me-2 fav-icon"></i>
                                                    <span class="fav-label">{{ auth()->user()->favourites()->where('service_id', $service->id)->exists() ? __('landing.sv_saved') : __('landing.sv_add_fav') }}</span>
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}" class="btn-red w-100 py-2 text-center text-decoration-none d-block">
                                                    <i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.sv_book_now') }}
                                                </a>
                                                <a href="{{ route('login') }}" class="btn-outline-wh w-100 py-2 text-center text-decoration-none d-block">
                                                    <i class="fa-regular fa-heart me-2"></i>{{ __('landing.sv_add_fav') }}
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const toastFavAdded = @json(__('landing.toast_fav_added'));
    const toastFavRemoved = @json(__('landing.toast_fav_removed'));
    const toastError = @json(__('landing.toast_error'));

    document.querySelectorAll('.fav-btn').forEach(function (btn) {
        // Reflect initial state from server
        if (btn.dataset.favourited === 'true') {
            btn.style.borderColor = '#e5322d';
            btn.style.color = '#e5322d';
        }

        btn.addEventListener('click', function () {
            const id   = btn.dataset.serviceId;
            const name = btn.dataset.serviceName;

            fetch('/favourites/' + id + '/toggle', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                const icon  = btn.querySelector('.fav-icon');
                const label = btn.querySelector('.fav-label');
                if (data.favourited) {
                    icon.className = 'fa-solid fa-heart me-2 fav-icon';
                    label.textContent = '{{ __('landing.sv_saved') }}';
                    btn.style.borderColor = '#e5322d';
                    btn.style.color = '#e5322d';
                    btn.dataset.favourited = 'true';
                    showToast(toastFavAdded.replace(':name', '"' + name + '"'), 'danger');
                } else {
                    icon.className = 'fa-regular fa-heart me-2 fav-icon';
                    label.textContent = '{{ __('landing.sv_add_fav') }}';
                    btn.style.borderColor = '';
                    btn.style.color = '';
                    btn.dataset.favourited = 'false';
                    showToast(toastFavRemoved.replace(':name', '"' + name + '"'), 'secondary');
                }
            })
            .catch(() => {
                showToast(toastError, 'danger');
            });
        });
    });

    function showToast(message, variant) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-' + variant + ' border-0 show';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = '<div class="d-flex"><div class="toast-body fw-semibold">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
        container.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }
});
</script>
@endpush
