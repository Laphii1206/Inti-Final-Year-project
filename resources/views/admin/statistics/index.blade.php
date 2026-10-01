@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header with Filter -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-hand-wave text-brand me-2"></i>{{ __('admin.stat_welcome') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.stat_welcome_sub') }}</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <form action="{{ route('admin.statistics.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <label for="period" class="small fw-bold text-secondary text-nowrap mb-0">{{ __('admin.stat_select_period') }}</label>
                <select name="period" id="period" class="form-select border-0 shadow-sm px-3" onchange="this.form.submit()">
                    <option value="today" {{ $period === 'today' ? 'selected' : '' }}>{{ __('admin.stat_today') }}</option>
                    <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>{{ __('admin.stat_this_week') }}</option>
                    <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>{{ __('admin.stat_this_month') }}</option>
                    <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>{{ __('admin.stat_this_year') }}</option>
                    <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>{{ __('admin.stat_last_month') }}</option>
                    <option value="last_year" {{ $period === 'last_year' ? 'selected' : '' }}>{{ __('admin.stat_last_year') }}</option>
                </select>
            </form>
            <a href="{{ route('admin.statistics.report', ['period' => $period]) }}" class="btn btn-outline-secondary shadow-sm bg-white border-0"><i class="fa-solid fa-file-pdf text-danger me-2"></i>{{ __('admin.stat_download_report') }}</a>
        </div>
    </div>

    <!-- Hero Banner -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #131417 0%, #1e1f24 100%);">
        <div class="row g-0">
            <div class="col-lg-8 p-5">
                <h3 class="text-white fw-bold mb-2">{{ __('admin.stat_hero_title') }}</h3>
                <p class="text-white-50 mb-4 fs-5"><strong class="text-brand fs-4">{{ $stats['total_bookings'] }}</strong> {{ __('admin.stat_hero_desc') }}</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-brand px-4 py-2 fw-semibold shadow-sm"><i class="fa-solid fa-list-check me-2"></i>{{ __('admin.stat_btn_view_bookings') }}</a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-size: cover; background-position: center; opacity: 0.6; border-left: 4px solid #EC1F24;"></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i>{{ __('admin.stat_quick_actions') }}</h6>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.services.create') }}" class="btn btn-sm btn-light border fw-medium"><i class="fa-solid fa-wrench text-brand me-2"></i>{{ __('admin.stat_qa_add_service') }}</a>

            <a href="{{ route('admin.branches.index') }}" class="btn btn-sm btn-light border fw-medium"><i class="fa-solid fa-store text-brand me-2"></i>{{ __('admin.stat_qa_branches') }}</a>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-light border fw-medium"><i class="fa-solid fa-star text-brand me-2"></i>{{ __('admin.stat_qa_reviews') }}</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light border fw-medium"><i class="fa-solid fa-users text-brand me-2"></i>{{ __('admin.stat_qa_users') }}</a>
        </div>
    </div>

    <!-- Numerical Summary Cards (5 cards) -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 card-stat position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 bg-brand" style="height: 4px;"></div>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-danger-subtle text-brand" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-money-bill-wave fs-4"></i></div>
                    <div>
                        <span class="small text-muted d-block text-uppercase fw-semibold">{{ __('admin.stat_col_total_revenue') }}</span>
                        <h4 class="fw-bold mb-0 text-dark">RM {{ number_format($stats['revenue'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 card-stat position-relative overflow-hidden">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-secondary-subtle text-secondary" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-calendar-check fs-4"></i></div>
                    <div>
                        <span class="small text-muted d-block text-uppercase fw-semibold">{{ __('admin.stat_total_bookings') }}</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_bookings']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 card-stat position-relative overflow-hidden">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-success-subtle text-success" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-circle-check fs-4"></i></div>
                    <div>
                        <span class="small text-muted d-block text-uppercase fw-semibold">{{ __('admin.stat_completed_jobs') }}</span>
                        <div class="d-flex align-items-baseline gap-2">
                            <h4 class="fw-bold mb-0 text-dark">{{ number_format($stats['completed']) }}</h4>
                            @php
                                $successRate = $stats['total_bookings'] > 0 ? round(($stats['completed'] / $stats['total_bookings']) * 100, 1) : 0;
                            @endphp
                            <span class="badge bg-success-subtle text-success small">{{ $successRate }}% {{ __('admin.stat_success_rate') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 card-stat position-relative overflow-hidden">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-danger-subtle text-danger" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-ban fs-4"></i></div>
                    <div>
                        <span class="small text-muted d-block text-uppercase fw-semibold">{{ __('admin.stat_cancelled_jobs') }}</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($stats['cancelled']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 card-stat position-relative overflow-hidden">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-warning-subtle text-warning" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-star fs-4"></i></div>
                    <div>
                        <span class="small text-muted d-block text-uppercase fw-semibold">{{ __('admin.stat_avg_rating') }}</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['avg_rating'] ? number_format($stats['avg_rating'], 1) . ' / 5.0' : 'N/A' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3-Column Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Trend -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <h6 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-arrow-trend-up text-brand me-2"></i>{{ __('admin.stat_revenue_trend') }}</h6>
                <div style="height: 250px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Booking Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <h6 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-chart-pie text-brand me-2"></i>{{ __('admin.stat_booking_status') }}</h6>
                <div style="height: 250px; display:flex; justify-content:center;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Top Services -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <h6 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-chart-bar text-brand me-2"></i>{{ __('admin.stat_chart_title') }}</h6>
                <div style="height: 250px;">
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="row g-4 mb-4">
        <!-- Recent Bookings Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clock-rotate-left text-brand me-2"></i>{{ __('admin.stat_recent_bookings') }}</h6>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-brand">{{ __('admin.stat_btn_view_bookings') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>#</th>
                                <th>{{ __('admin.stat_col_customer') }}</th>
                                <th>{{ __('admin.stat_col_vehicle') }}</th>
                                <th>{{ __('admin.stat_col_date') }}</th>
                                <th>{{ __('admin.stat_col_status') }}</th>
                                <th class="text-end">{{ __('admin.stat_col_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td><a href="{{ route('admin.bookings.show', $booking) }}" class="fw-bold text-decoration-none text-brand">{{ $booking->number }}</a></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                                <i class="fa-solid fa-user text-secondary small"></i>
                                            </div>
                                            <span class="small fw-medium">{{ $booking->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td><span class="small text-muted">{{ $booking->car->car_plate ?? '-' }}</span></td>
                                    <td>
                                        <div class="small">{{ $booking->booking_date->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $booking->start_time->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($booking->status) {
                                                'completed' => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'cancelled', 'rejected', 'no_show' => 'bg-danger',
                                                'confirmed' => 'bg-info text-dark',
                                                'in_progress' => 'bg-primary',
                                                default => 'bg-secondary'
                                            };
                                            $transKey = "admin.stat_status_" . $booking->status;
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ \Lang::has($transKey) ? __($transKey) : ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="small fw-bold">RM {{ number_format($booking->service_price_at_booking, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-secondary small">{{ __('admin.stat_no_services') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-flex flex-column gap-4">
            <!-- Monthly Bookings Chart -->
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 flex-grow-1">
                <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-calendar-days text-brand me-2"></i>{{ __('admin.stat_monthly_bookings') }}</h6>
                <div style="height: 180px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
            

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shared options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1E1F24', titleFont: { weight: 'bold' }, padding: 12, cornerRadius: 8 }
            }
        };

        // 1. Revenue Trend (Line Chart)
        const revenueData = @json($revenueTrend);
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: revenueData.labels,
                datasets: [{
                    label: 'Revenue (RM)',
                    data: revenueData.values,
                    borderColor: '#EC1F24',
                    backgroundColor: 'rgba(236, 31, 36, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#EC1F24',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                ...commonOptions,
                scales: {
                    x: { grid: { display: false }, ticks: { font: { weight: '600' } } },
                    y: { beginAtZero: true, grid: { borderDash: [3, 3] }, ticks: { font: { weight: '500' } } }
                }
            }
        });

        // 2. Booking Status (Doughnut Chart)
        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['{{ __("admin.stat_status_completed") }}', '{{ __("admin.stat_status_pending") }}', '{{ __("admin.stat_status_cancelled") }}'],
                datasets: [{
                    data: [{{ $stats['completed'] }}, {{ $pendingCount }}, {{ $stats['cancelled'] }}],
                    backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: '600' } } },
                    tooltip: commonOptions.plugins.tooltip
                }
            }
        });

        // 3. Top Services (Bar Chart)
        const serviceData = @json($chartData);
        const barColors = ['#EC1F24', '#1E1F24', '#FF6B6B', '#4ECDC4', '#45B7D1'];
        new Chart(document.getElementById('serviceChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: serviceData.map(item => item.service_name),
                datasets: [{
                    label: 'Bookings',
                    data: serviceData.map(item => item.count),
                    backgroundColor: barColors,
                    borderRadius: 6,
                    barPercentage: 0.6,
                }]
            },
            options: {
                ...commonOptions,
                scales: {
                    x: { grid: { display: false }, ticks: { display: false } }, // Hide x labels for clean look
                    y: { beginAtZero: true, grid: { borderDash: [3, 3] } }
                }
            }
        });

        // 4. Monthly Bookings (Line Chart)
        const monthlyData = @json($monthlyBookings);
        new Chart(document.getElementById('monthlyChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [{
                    label: 'Bookings',
                    data: monthlyData.values,
                    borderColor: '#1E1F24',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#1E1F24',
                }]
            },
            options: {
                ...commonOptions,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endsection