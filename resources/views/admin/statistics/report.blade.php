<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TRB Auto Care - Statistics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #111;
            margin-top: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #EC1F24;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #EC1F24;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .summary-box td {
            width: 33.33%;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f9f9f9;
        }
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #EC1F24;
            margin-top: 5px;
        }
        .summary-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
        }
        .section-title {
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: #333;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 13px;
        }
        table.data-table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }
        .bg-success { background-color: #198754; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-info { background-color: #0dcaf0; color: #000; }
        .bg-primary { background-color: #0d6efd; }
        .bg-secondary { background-color: #6c757d; }
    </style>
</head>
<body>

    <div class="header">
        <h1>TRB Auto Care</h1>
        <h3>{{ __('admin.stat_title') }}</h3>
        <p>{{ __('admin.stat_period') }} {{ ucwords(str_replace('_', ' ', $period)) }}</p>
        <p>{{ __('admin.stat_gen_at') }} {{ $generated_at }}</p>
    </div>

    @php
        $successRate = $stats['total_bookings'] > 0 
            ? round(($stats['completed'] / $stats['total_bookings']) * 100, 1) 
            : 0;
    @endphp

    <table class="summary-box">
        <tr>
            <td>
                <div class="summary-label">{{ __('admin.stat_total_revenue') }}</div>
                <div class="summary-value">RM {{ number_format($stats['revenue'] ?? 0, 2) }}</div>
            </td>
            <td>
                <div class="summary-label">{{ __('admin.stat_total_bookings') }}</div>
                <div class="summary-value">{{ number_format($stats['total_bookings'] ?? 0) }}</div>
            </td>
            <td>
                <div class="summary-label">{{ __('admin.stat_completed_jobs') }}</div>
                <div class="summary-value">{{ number_format($stats['completed'] ?? 0) }} <span style="font-size: 12px; color: #198754;">({{ $successRate }}%)</span></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="summary-label">{{ __('admin.stat_cancelled_jobs') }}</div>
                <div class="summary-value" style="color: #dc3545;">{{ number_format($stats['cancelled'] ?? 0) }}</div>
            </td>
            <td>
                <div class="summary-label">{{ __('admin.stat_avg_rating') }}</div>
                <div class="summary-value">{{ number_format($stats['avg_rating'] ?? 0, 1) }} / 5.0</div>
            </td>
            <td>
                <div class="summary-label">{{ __('admin.bk_status_pending') }}</div>
                <div class="summary-value" style="color: #ffc107;">{{ number_format($pendingCount ?? 0) }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title"><strong>{{ __('admin.stat_top_services') }}</strong></div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 55%;">{{ __('admin.stat_col_service_name') }}</th>
                <th style="width: 20%;" class="text-center">{{ __('admin.stat_col_bookings') }}</th>
                <th style="width: 20%;" class="text-right">{{ __('admin.stat_col_price') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topServices as $index => $service)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $service->service_name }}</td>
                    <td class="text-center">{{ number_format($service->count) }}</td>
                    <td class="text-right">RM {{ number_format($service->service_price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{ __('admin.stat_no_services') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title"><strong>{{ __('admin.stat_recent_bookings') }}</strong></div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">{{ __('admin.rev_booking_no') }}</th>
                <th style="width: 20%;">{{ __('admin.stat_col_customer') }}</th>
                <th style="width: 15%;">{{ __('admin.stat_col_vehicle') }}</th>
                <th style="width: 20%;">{{ __('admin.stat_col_date') }}</th>
                <th style="width: 15%;" class="text-center">{{ __('admin.stat_col_status') }}</th>
                <th style="width: 15%;" class="text-right">{{ __('admin.stat_col_amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->number }}</td>
                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $booking->car->car_plate ?? 'N/A' }}</td>
                    <td>
                        {{ $booking->booking_date->format('d M Y') }}<br>
                        <span style="font-size: 11px; color: #666;">{{ $booking->start_time->format('h:i A') }}</span>
                    </td>
                    <td class="text-center">
                        @php
                            $badgeClass = match($booking->status) {
                                'completed' => 'bg-success',
                                'pending' => 'bg-warning',
                                'cancelled', 'rejected', 'no_show' => 'bg-danger',
                                'confirmed' => 'bg-info',
                                'in_progress' => 'bg-primary',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                    </td>
                    <td class="text-right">RM {{ number_format($booking->service_price_at_booking, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('admin.stat_no_recent') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
