@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-star text-brand me-2"></i>{{ __('admin.rev_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.rev_desc') }}</p>
        </div>
    </div>

    <!-- Analytics Dashboard Header -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 text-center card-stat">
                <span class="small text-muted d-block mb-1">{{ __('admin.rev_avg_rating') }}</span>
                <h2 class="fw-bold text-brand mb-2">{{ number_format($analytics['avg_rating'], 1) }} <i class="fa-solid fa-star text-warning small"></i></h2>
                <span class="small text-secondary">{{ __('admin.rev_across_total', ['count' => $analytics['total_reviews']]) }}</span>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <span class="small text-muted d-block mb-3">{{ __('admin.rev_breakdown') }}</span>
                <div class="d-flex flex-column gap-2">
                    @for($star = 5; $star >= 1; $star--)
                        @php
                            $count = $analytics['by_rating'][$star] ?? 0;
                            $pct = $analytics['total_reviews'] > 0 ? ($count / $analytics['total_reviews']) * 100 : 0;
                        @endphp
                        <div class="d-flex align-items-center gap-2 small">
                            <span style="width: 50px;">{{ $star }} {{ __('admin.rev_stars') }}</span>
                            <div class="progress flex-fill" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-secondary" style="width: 30px; text-align: right;">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 text-center card-stat">
                <span class="small text-muted d-block mb-1">{{ __('admin.rev_recent') }}</span>
                <h2 class="fw-bold text-dark mb-2">{{ $analytics['recent_trend'] ? number_format($analytics['recent_trend'], 1) . ' / 5.0' : __('admin.bk_na') }}</h2>
                <div class="text-success small"><i class="fa-solid fa-circle-check"></i> {{ __('admin.rev_healthy') }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="rating" id="rating" class="form-select bg-light border-0 py-2.5" onchange="this.form.submit()">
                    <option value="">{{ __('admin.rev_all_stars') }}</option>
                    <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>{{ __('admin.rev_5_stars') }}</option>
                    <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>{{ __('admin.rev_4_stars') }}</option>
                    <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>{{ __('admin.rev_3_stars') }}</option>
                    <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>{{ __('admin.rev_2_stars') }}</option>
                    <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>{{ __('admin.rev_1_star') }}</option>
                </select>
            </div>
            @if(request('rating'))
                <div class="col-md-2">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary py-2.5 small"><i class="fa-solid fa-circle-xmark"></i> {{ __('admin.rev_clear_filter') }}</a>
                </div>
            @endif
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr class="small text-uppercase">
                        <th class="ps-4">{{ __('admin.rev_col_id') }}</th>
                        <th>{{ __('admin.rev_col_customer') }}</th>
                        <th>{{ __('admin.rev_col_category') }}</th>
                        <th>{{ __('admin.rev_col_rating') }}</th>
                        <th>{{ __('admin.rev_col_comment') }}</th>
                        <th class="text-center">{{ __('admin.rev_col_published') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.rev_col_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="border-bottom">
                            <td class="ps-4">{{ $review->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $review->booking?->user?->name ?? __('admin.bk_del_user') }}</div>
                                <span class="small text-secondary">{{ __('admin.rev_booking_no') }}{{ $review->booking?->number ?? __('admin.bk_na') }}</span>
                            </td>
                            <td>
                                <div class="small fw-bold text-dark">{{ $review->booking?->service?->name ?? __('admin.bk_del_service') }}</div>
                                <span class="badge bg-light text-dark border">{{ ucfirst($review->booking?->service?->category ?? __('admin.bk_unknown')) }}</span>
                            </td>
                            <td class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </td>
                            <td>
                                <div class="small text-dark" style="max-width: 300px; word-wrap: break-word;">
                                    "{{ $review->comment ?? __('admin.bk_no_comment') }}"
                                </div>
                                <span class="small text-muted d-block mt-1">{{ __('admin.rev_date') }} {{ $review->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="text-center">
                                @if($review->is_visible)
                                    <span class="badge bg-success"><i class="fa-solid fa-eye me-1"></i> {{ __('admin.rev_visible') }}</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fa-solid fa-eye-slash me-1"></i> {{ __('admin.rev_hidden') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $review->is_visible ? 'btn-outline-secondary' : 'btn-dark' }} px-3">
                                        @if($review->is_visible)
                                            <i class="fa-solid fa-eye-slash me-1"></i> Hide
                                        @else
                                            <i class="fa-solid fa-eye me-1"></i> Publish
                                        @endif
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-star-half-stroke fs-2 mb-3"></i>
                                <p class="mb-0">{{ __('admin.rev_empty') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($reviews->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
