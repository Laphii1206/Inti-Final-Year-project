@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">{{ __('admin.mem_analytics_title') }}</h3>
            <p class="text-muted mb-0">{{ __('admin.mem_analytics_desc') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.memberships.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>{{ __('admin.mem_back') }}
            </a>
        </div>
    </div>

    {{-- TOP KPIs --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-left: 4px solid #3b82f6 !important;">
                <div class="text-muted small fw-bold text-uppercase mb-1">{{ __('admin.mem_total_members') }}</div>
                <div class="fs-2 fw-bold text-dark">{{ number_format($totalMembers) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-left: 4px solid #10b981 !important;">
                <div class="text-muted small fw-bold text-uppercase mb-1">{{ __('admin.mem_points_issued') }}</div>
                <div class="fs-2 fw-bold text-success">{{ number_format($totalPointsIssued) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-left: 4px solid #f59e0b !important;">
                <div class="text-muted small fw-bold text-uppercase mb-1">{{ __('admin.mem_points_redeemed') }}</div>
                <div class="fs-2 fw-bold text-warning">{{ number_format($totalPointsRedeemed) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3" style="border-left: 4px solid #8b5cf6 !important;">
                <div class="text-muted small fw-bold text-uppercase mb-1">{{ __('admin.mem_redemption_rate') }}</div>
                <div class="fs-2 fw-bold text-purple">
                    {{ $totalPointsIssued > 0 ? round(($totalPointsRedeemed / $totalPointsIssued) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- CHARTS --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fa-solid fa-chart-line text-brand me-2"></i>{{ __('admin.mem_monthly_points') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="pointsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- TIER DISTRIBUTION --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fa-solid fa-ranking-star text-warning me-2"></i>{{ __('admin.mem_tier_breakdown') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="tierChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- TOP PROMO CODES --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fa-solid fa-ticket text-info me-2"></i>{{ __('admin.mem_top_promo_codes') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('admin.mem_code') }}</th>
                                    <th>{{ __('admin.promo_type') }}</th>
                                    <th>{{ __('admin.promo_value') }}</th>
                                    <th>{{ __('admin.promo_times_used') }}</th>
                                    <th>{{ __('admin.promo_max_uses') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPromoCodes as $code)
                                <tr>
                                    <td class="fw-bold">{{ $code->code }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($code->type) }}</span></td>
                                    <td>{{ $code->type === 'fixed' ? 'RM'.$code->value : $code->value.'%' }}</td>
                                    <td class="text-success fw-bold">{{ $code->times_used }}</td>
                                    <td>{{ $code->max_uses }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('admin.promo_empty_active') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prepare Data for Line Chart
        const monthlyData = @json($monthlyPoints);
        const labels = monthlyData.map(item => item.month);
        const pointsData = monthlyData.map(item => item.total);

        new Chart(document.getElementById('pointsChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Points Earned',
                    data: pointsData,
                    borderColor: '#EC1F24',
                    backgroundColor: 'rgba(236, 31, 36, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Prepare Data for Pie Chart
        const tierData = @json($tierBreakdown);
        const tierLabels = ['Bronze', 'Silver', 'Gold'];
        const tierCounts = [
            tierData['bronze'] || 0,
            tierData['silver'] || 0,
            tierData['gold'] || 0
        ];

        new Chart(document.getElementById('tierChart'), {
            type: 'doughnut',
            data: {
                labels: tierLabels,
                datasets: [{
                    data: tierCounts,
                    backgroundColor: ['#cd7f32', '#c0c0c0', '#ffd700'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endsection
