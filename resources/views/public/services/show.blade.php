@extends('layouts.app')

@section('styles')
<style>
/* ===== Page-scoped overrides — dark BG edge-to-edge ===== */
body { background-color: #0a0a0a !important; }
main { background-color: #0a0a0a !important; }
.footer-custom { margin-top: 0 !important; }

/* ===== SERVICES-DARK — page-scoped dark theme ===== */
.services-dark {
    background-color: #0a0a0a;
    color: #e4e4e7;
    min-height: 100vh;
}

.services-dark h1,.services-dark h2,.services-dark h3,
.services-dark h4,.services-dark h5,.services-dark h6 { color: #ffffff; }
.services-dark .text-red { color: #e5322d !important; }
.services-dark .text-muted-l { color: #9ca3af !important; }

/* ---------- Dark Card ---------- */
.services-dark .dk-card {
    background-color: #161616;
    border: 1px solid #262626;
    border-radius: 14px;
    transition: transform .3s, box-shadow .3s, border-color .3s;
}
.services-dark .dk-card:hover {
    transform: translateY(-5px);
    border-color: rgba(229,50,45,.4);
    box-shadow: 0 10px 25px rgba(229,50,45,.15);
}

.services-dark .card-img-wrapper {
    background-color: #ffffff;
    border-bottom: 1px solid #262626;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
    overflow: hidden;
}

/* ---------- Buttons ---------- */
.services-dark .btn-red {
    background: #e5322d; color: #fff; border: none;
    border-radius: 10px; font-weight: 600;
    transition: all .25s;
}
.services-dark .btn-red:hover { background: #cc2a25; color: #fff; transform: translateY(-2px); }

.services-dark .btn-outline-wh {
    background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.25);
    border-radius: 10px; font-weight: 600;
    transition: all .25s;
}
.services-dark .btn-outline-wh:hover { border-color: #fff; color: #fff; background: rgba(255,255,255,.06); }

/* ---------- Most Popular Badge ---------- */
.badge-popular {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    border-radius: 20px;
    padding: 3px 9px;
    letter-spacing: 0.03em;
    box-shadow: 0 2px 8px rgba(245,158,11,0.35);
    pointer-events: none;
    transition: opacity 0.2s;
}

/* ---------- Mobile 2x2 Grid Optimization ---------- */
@media (max-width: 575.98px) {
    .services-dark .card-img-wrapper {
        height: 135px !important;
    }
    .services-dark .dk-card .card-body {
        padding: 0.75rem !important;
    }
    .services-dark .dk-card h6 {
        font-size: 0.85rem !important;
        margin-bottom: 0.25rem !important;
    }
    .services-dark .dk-card h5 {
        font-size: 0.95rem !important;
        margin-bottom: 0.5rem !important;
    }
    .services-dark .dk-card p {
        font-size: 0.7rem !important;
        margin-bottom: 0.35rem !important;
    }
    .services-dark .btn-red, .services-dark .btn-outline-wh {
        padding: 0.4rem 0.3rem !important;
        font-size: 0.72rem !important;
        border-radius: 8px !important;
    }
    .services-dark .fav-icon {
        margin-right: 4px !important;
    }
}

@media (max-width: 767.98px) {
    /* 2x2 grid mode on mobile */
    #pub-services-grid .card-img-wrapper { height: 135px !important; }
    #pub-services-grid h6 { font-size: 0.88rem !important; line-height: 1.25; margin-bottom: 4px !important; }
    #pub-services-grid h5 { font-size: 0.95rem !important; margin-bottom: 8px !important; }
}
</style>
@endsection

@section('content')
<div class="services-dark py-5">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background:none;padding:0;margin:0;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-muted-l text-decoration-none">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}" class="text-muted-l text-decoration-none">Services</a>
                </li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>

        <div class="mb-5 border-bottom border-dark pb-3 d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: #262626 !important;">
            <div>
                <h2 class="fw-bold mb-1">{{ $title }} <span class="text-red">Services</span></h2>
                <p class="text-muted-l mb-0">{{ __('products.subtitle') }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn-outline-wh text-decoration-none px-4 py-2"><i class="fa-solid fa-arrow-left me-2"></i>{{ __('products.sv_all_services') }}</a>
        </div>

        @php
            $catImgMap = [
                'Tyres' => 'images/categories/tyres.webp',
                'Maintenance' => 'images/categories/maintenance.jpg',
                'Tinting Films' => 'images/categories/tinting-films.webp',
                'Wipers' => 'images/categories/wipers.webp',
                'Dashcams' => 'images/categories/dashcams.webp',
                'Car Mats' => 'images/categories/car-mats.jpg',
            ];
            $catNameMap = [
                'Tyres' => __('landing.cat_tyres'),
                'Maintenance' => __('landing.cat_maintenance'),
                'Tinting Films' => __('landing.cat_tinting'),
                'Wipers' => __('landing.cat_wipers'),
                'Dashcams' => __('landing.cat_dashcams'),
                'Car Mats' => __('landing.cat_carmats'),
            ];
            $subList = ['All'];
            if ($currentCatName === 'Tyres') $subList = ['All', '15"', '16"', '17"', '18"', '19"'];
            elseif ($currentCatName === 'Tinting Films' || $currentCatName === 'Car Mats') $subList = ['All', 'Sedan', 'SUV', 'MPV', 'Hatchback'];
            elseif ($currentCatName === 'Maintenance') $subList = ['All', 'Fully Synthetic', 'Semi Synthetic', 'Mineral Oil', 'Inspection'];
            elseif ($currentCatName === 'Dashcams') $subList = ['All', 'Front Only', 'Front & Rear', '4K Ultra HD'];
            elseif ($currentCatName === 'Wipers') $subList = ['All', 'Silicone Blade', 'Aero Flat Blade', 'Standard'];
            $subLabelText = ($currentCatName === 'Tyres') ? __('landing.sv_size_spec') : (($currentCatName === 'Maintenance') ? __('landing.sv_type_svc') : __('landing.sv_type_veh'));
        @endphp

        <!-- Category Bar Switcher -->
        <div class="mb-4 overflow-auto pb-2">
            <div class="d-flex gap-2 flex-nowrap">
                @foreach(['Tyres', 'Maintenance', 'Tinting Films', 'Wipers', 'Dashcams', 'Car Mats'] as $cn)
                    @php $cSlug = strtolower(str_replace(' ', '-', $cn)); @endphp
                    <a href="{{ route('services.show', $cSlug) }}" class="btn {{ $cn === $currentCatName ? 'btn-red' : 'dk-card text-white' }} px-3 py-2 rounded-pill d-inline-flex align-items-center gap-2 text-decoration-none small fw-bold flex-shrink-0 border-0" style="font-size:0.85rem;">
                        <img src="{{ asset($catImgMap[$cn] ?? 'images/product-car.png') }}" class="rounded-circle bg-white shadow-sm flex-shrink-0" style="width: 26px; height: 26px; object-fit: cover;" alt="{{ $cn }}">
                        <span>{{ $catNameMap[$cn] ?? $cn }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Dynamic Filter Control Toolbar -->
        <div class="dk-card p-4 mb-5 rounded-4 border" style="background: #111111; border-color: #262626 !important;">
            <!-- Brand Filter Row -->
            <div class="mb-4 pb-3 border-bottom" style="border-color: #222222 !important;">
                <span class="text-muted-l small fw-bold text-uppercase tracking-wider d-block mb-2.5"><i class="fa-solid fa-tag text-red me-1.5"></i>{{ __('products.sv_filter_brand') }}</span>
                <div class="d-flex flex-wrap gap-2" id="pub-brand-filter">
                    <button type="button" class="btn btn-red rounded-pill px-3 py-1 small fw-bold pub-brand-btn" data-brand="All">{{ __('products.sv_all_brands') }}</button>
                    @foreach($brandsForCategory as $bn)
                        @php
                            $bSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $bn));
                            $words = preg_split('/[\s-]+/', $bn);
                            $abbr  = count($words) >= 2 ? strtoupper(substr($words[0],0,1).substr($words[1],0,1)) : strtoupper(substr($bn,0,2));
                        @endphp
                        <button type="button" class="btn dk-card text-white rounded-pill ps-1.5 pe-3 py-1 small fw-bold pub-brand-btn d-inline-flex align-items-center gap-2 border-0" data-brand="{{ $bn }}">
                            <span class="rounded-circle bg-white d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 shadow-sm" style="width:24px;height:24px;">
                                <img src="/images/brands/{{ strtolower(str_replace(' ', '-', $bn)) }}.png" class="w-100 h-100 p-0.5" style="object-fit:contain;" alt="{{ $bn }}" onerror="this.outerHTML='<span class=\\'fw-bolder text-dark\\' style=\\'font-size:0.65rem;color:#E61E25 !important;\\'>{{ $abbr }}</span>'">
                            </span>
                            <span>{{ $bn }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Spec & Price Row -->
            <div class="row g-4 align-items-center">
                <div class="col-md-8 text-start">
                    <span class="text-muted-l small fw-bold text-uppercase tracking-wider d-block mb-2"><i class="fa-solid fa-sliders text-red me-1.5"></i>{{ $subLabelText }}</span>
                    <div class="d-flex flex-wrap gap-1.5" id="pub-sub-filter">
                        @foreach($subList as $subItem)
                            <button type="button" class="btn {{ $subItem === 'All' ? 'btn-red' : 'dk-card text-white' }} rounded-pill px-3 py-1 small fw-bold pub-sub-btn border-0" data-sub="{{ $subItem }}">{{ $subItem === 'All' ? __('products.sv_all_spec') : $subItem }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4 border-start ps-md-4 text-start" style="border-color: #222222 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted-l small fw-bold text-uppercase tracking-wider"><i class="fa-solid fa-tags text-red me-1"></i>{{ __('booking.bc_max_price') }}</span>
                        <span class="badge bg-danger fw-bold" id="pub-price-lbl">RM 2,000</span>
                    </div>
                    <input type="range" class="form-range text-red" id="pub-price-slider" min="50" max="2000" step="50" value="2000">
                </div>
            </div>
        </div>

        <!-- Result Count Bar -->
        <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom border-dark" style="border-color: #262626 !important;">
            <i class="fa-solid fa-layer-group text-red fs-5"></i>
            <span class="text-white fw-bold">{{ $services->count() }} <span class="text-muted-l fw-normal">services available</span></span>
        </div>

        <div class="row g-2 g-md-4" id="pub-services-grid">
            @forelse($services as $service)
                @php
                    $fallbackImg = asset($catImgMap[$currentCatName] ?? 'images/product-car.png');
                    $imgUrl = $service->image_path ? Storage::url($service->image_path) : $fallbackImg;
                @endphp
                <div class="col-6 col-md-4 col-lg-3 pub-card-col"
                     data-brand="{{ $service->mapped_brand ?? '' }}"
                     data-sub="{{ $service->mapped_sub ?? '' }}"
                     data-price="{{ $service->price }}"
                     data-booking-count="{{ $service->completed_count ?? 0 }}">
                    <div class="dk-card h-100 overflow-hidden position-relative d-flex flex-column">
                        <!-- Service Image -->
                        <div class="card-img-wrapper d-flex justify-content-center align-items-center bg-white position-relative" style="height: 190px; width: 100%;">
                            <img src="{{ $imgUrl }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: {{ $service->image_path ? 'contain' : 'cover' }}; padding: {{ $service->image_path ? '15px' : '0' }};">
                            {{-- Most Popular badge (top-left, shown/hidden by JS) --}}
                            <span class="badge-popular position-absolute top-0 start-0 m-2 d-none">🔥 {{ __('landing.filter_pop') }}</span>
                            @if($service->mapped_brand)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-dark text-white shadow-sm" style="font-size:0.68rem;opacity:0.9;">{{ $service->mapped_brand }}</span>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <h6 class="fw-bold text-white mb-1">{{ $service->name }}</h6>
                            @if($service->description)
                                <p class="text-muted-l small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $service->description }}
                                </p>
                            @else
                                <div class="mb-3 flex-grow-1"></div>
                            @endif

                            <!-- Price -->
                            @if(($service->completed_count ?? 0) >= 10)
                                <p class="text-muted-l small mb-2 d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-circle-check" style="color:#22c55e;font-size:0.75rem;"></i>
                                    <span>{{ __('products.booked_times', ['count' => number_format($service->completed_count)]) }}</span>
                                </p>
                            @endif
                            <h5 class="fw-bold text-red mb-3">RM {{ number_format($service->price, 2) }}</h5>

                            <!-- Action Buttons -->
                            <div class="mt-auto d-flex flex-column gap-2">
                                @auth
                                    @php
                                        $isFavourited = auth()->user()->favourites()->where('service_id', $service->id)->exists();
                                    @endphp
                                    <a href="{{ route('bookings.create') }}?service={{ $service->id }}"
                                       class="btn-red w-100 py-2 text-center text-decoration-none d-block">
                                        <i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.sv_book_now') }}
                                    </a>
                                    <button type="button"
                                            class="btn-outline-wh w-100 py-2 fav-btn"
                                            data-service-id="{{ $service->id }}"
                                            data-service-name="{{ $service->name }}"
                                            data-favourited="{{ $isFavourited ? 'true' : 'false' }}"
                                            style="{{ $isFavourited ? 'border-color:#e5322d;color:#e5322d;' : '' }}">
                                        <i class="{{ $isFavourited ? 'fa-solid' : 'fa-regular' }} fa-heart me-2 fav-icon"></i>
                                        <span class="fav-label">{{ $isFavourited ? __('landing.sv_saved') : __('landing.sv_add_fav') }}</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="btn-red w-100 py-2 text-center text-decoration-none d-block">
                                        <i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.sv_book_now') }}
                                    </a>
                                    <a href="{{ route('login') }}"
                                       class="btn-outline-wh w-100 py-2 text-center text-decoration-none d-block">
                                        <i class="fa-regular fa-heart me-2"></i>{{ __('landing.sv_add_fav') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="dk-card p-5 mx-auto" style="max-width: 600px;">
                        <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold text-white">{{ __('landing.sv_no_avail') }}</h5>
                        <p class="text-muted-l mb-0">{{ __('landing.sv_check_back') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Mobile Floating Book Now CTA --}}
<div class="d-lg-none" style="position:fixed;bottom:0;left:0;right:0;z-index:1050;padding:12px 16px;background:linear-gradient(to top,rgba(10,10,10,1) 0%,rgba(10,10,10,0.92) 100%);border-top:1px solid #262626;">
    <a href="{{ route('bookings.create') }}" class="btn-red w-100 py-3 text-center text-decoration-none d-block fw-bold" style="font-size:1rem;border-radius:12px;">
        <i class="fa-solid fa-calendar-check me-2"></i>{{ __('landing.sv_book_now') }}
    </a>
</div>
{{-- Spacer so content isn't hidden behind mobile CTA --}}
<div class="d-lg-none" style="height:72px;"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const toastFavAdded = @json(__('landing.toast_fav_added'));
    const toastFavRemoved = @json(__('landing.toast_fav_removed'));
    const toastError = @json(__('landing.toast_error'));

    document.querySelectorAll('.fav-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = btn.dataset.serviceId;
            const name = btn.dataset.serviceName;

            // Optimistic UI update
            const isFav = btn.dataset.favourited === 'true';
            setFavourited(btn, !isFav);

            @auth
            fetch('/favourites/' + id + '/toggle', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                setFavourited(btn, data.favourited);
                if (data.favourited) {
                    showToast(toastFavAdded.replace(':name', '"' + name + '"'), 'danger');
                } else {
                    showToast(toastFavRemoved.replace(':name', '"' + name + '"'), 'secondary');
                }
            })
            .catch(() => {
                // Revert on failure
                setFavourited(btn, isFav);
                showToast(toastError, 'danger');
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });

    function setFavourited(btn, isFav) {
        const icon  = btn.querySelector('.fav-icon');
        const label = btn.querySelector('.fav-label');
        btn.dataset.favourited = isFav ? 'true' : 'false';
        if (isFav) {
            icon.className = 'fa-solid fa-heart me-2 fav-icon';
            label.textContent = '{{ __('landing.sv_saved') }}';
            btn.style.borderColor = '#e5322d';
            btn.style.color = '#e5322d';
        } else {
            icon.className = 'fa-regular fa-heart me-2 fav-icon';
            label.textContent = '{{ __('landing.sv_add_fav') }}';
            btn.style.borderColor = '';
            btn.style.color = '';
        }
    }

    function showToast(message, variant) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed;bottom:84px;right:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-' + variant + ' border-0 show';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = '<div class="d-flex"><div class="toast-body fw-semibold">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
        container.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3200);
    }

    // ── Public Browse Multi-Criteria Filtering ──
    let selectedBrand = 'All';
    let selectedSub   = 'All';
    let maxPriceVal   = 2000;

    function runPubFilters() {
        let visibleCnt = 0;
        document.querySelectorAll('.pub-card-col').forEach(col => {
            const cBrand = col.getAttribute('data-brand');
            const cSub   = col.getAttribute('data-sub');
            const cPrice = parseFloat(col.getAttribute('data-price')) || 0;

            const bMatch = (selectedBrand === 'All' || cBrand === selectedBrand);
            const sMatch = (selectedSub === 'All' || cSub === selectedSub);
            const pMatch = (cPrice <= maxPriceVal);

            if (bMatch && sMatch && pMatch) {
                col.style.display = 'block';
                visibleCnt++;
            } else {
                col.style.display = 'none';
            }
        });

        let emptyElem = document.getElementById('pub-empty-filter-msg');
        if (visibleCnt === 0) {
            if (!emptyElem) {
                emptyElem = document.createElement('div');
                emptyElem.id = 'pub-empty-filter-msg';
                emptyElem.className = 'col-12 text-center py-5 text-muted-l';
                emptyElem.innerHTML = '<div class="dk-card p-5 mx-auto" style="max-width:500px;"><i class="fa-solid fa-filter-circle-xmark fa-3x text-red mb-3"></i><h5 class="text-white fw-bold">{{ __('products.sv_no_items') }}</h5><p class="mb-0 small">Try selecting "{{ __('products.sv_all_brands') }}" or adjusting the max price slider.</p></div>';
                document.getElementById('pub-services-grid').appendChild(emptyElem);
            } else {
                emptyElem.style.display = 'block';
            }
        } else if (emptyElem) {
            emptyElem.style.display = 'none';
        }
    }

    // ── Most Popular Badge (runs after every filter change) ──
    function recalcPopularBadge() {
        // Clear all existing badges first
        document.querySelectorAll('.pub-card-col .badge-popular').forEach(b => b.classList.add('d-none'));

        const visibleCols = [...document.querySelectorAll('.pub-card-col')]
            .filter(col => col.style.display !== 'none');

        // Need at least 3 visible cards to show a badge
        if (visibleCols.length < 3) return;

        let maxCount = 0;
        let maxCol   = null;
        let tie      = false;

        visibleCols.forEach(col => {
            const count = parseInt(col.dataset.bookingCount) || 0;
            if (count > maxCount) {
                maxCount = count;
                maxCol   = col;
                tie      = false;
            } else if (count === maxCount && maxCount > 0) {
                tie = true;
            }
        });

        // Show badge only if: no tie, meets threshold, winner found
        if (!tie && maxCount >= 10 && maxCol) {
            const badge = maxCol.querySelector('.badge-popular');
            if (badge) badge.classList.remove('d-none');
        }
    }



    // Run badge calculation on page load and hook into all filter events

    recalcPopularBadge();

    document.querySelectorAll('.pub-brand-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.pub-brand-btn').forEach(b => {
                b.className = 'btn dk-card text-white rounded-pill ps-1.5 pe-3 py-1 small fw-bold pub-brand-btn d-inline-flex align-items-center gap-2 border-0';
            });
            this.className = 'btn btn-red rounded-pill ps-1.5 pe-3 py-1 small fw-bold pub-brand-btn d-inline-flex align-items-center gap-2 border-0';
            selectedBrand = this.getAttribute('data-brand');
            runPubFilters();
            recalcPopularBadge();
        });
    });

    // ── Auto-select brand from URL ?brand= param (runs AFTER listeners are attached) ──
    const urlParams = new URLSearchParams(window.location.search);
    const preselectedBrand = urlParams.get('brand');
    if (preselectedBrand) {
        const matchBtn = [...document.querySelectorAll('.pub-brand-btn')]
            .find(b => b.getAttribute('data-brand').toLowerCase() === preselectedBrand.toLowerCase());
        if (matchBtn) {
            document.querySelectorAll('.pub-brand-btn').forEach(b => {
                b.className = 'btn dk-card text-white rounded-pill ps-1.5 pe-3 py-1 small fw-bold pub-brand-btn d-inline-flex align-items-center gap-2 border-0';
            });
            matchBtn.className = 'btn btn-red rounded-pill ps-1.5 pe-3 py-1 small fw-bold pub-brand-btn d-inline-flex align-items-center gap-2 border-0';
            selectedBrand = matchBtn.getAttribute('data-brand');
            runPubFilters();
            recalcPopularBadge();
        }
    }

    document.querySelectorAll('.pub-sub-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.pub-sub-btn').forEach(b => {
                b.className = 'btn dk-card text-white rounded-pill px-3 py-1 small fw-bold pub-sub-btn border-0';
            });
            this.className = 'btn btn-red rounded-pill px-3 py-1 small fw-bold pub-sub-btn border-0';
            selectedSub = this.getAttribute('data-sub');
            runPubFilters();
            recalcPopularBadge();
        });
    });

    const sliderElem = document.getElementById('pub-price-slider');
    if (sliderElem) {
        sliderElem.addEventListener('input', function() {
            maxPriceVal = parseFloat(this.value);
            const lbl = document.getElementById('pub-price-lbl');
            if (lbl) lbl.textContent = 'RM ' + maxPriceVal.toLocaleString();
            runPubFilters();
            recalcPopularBadge();
        });
    }
});
</script>
@endpush
