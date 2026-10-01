<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'this_month');
        [$start, $end] = $this->resolvePeriod($period);

        $data = $this->getReportData($start, $end);
        $data['period'] = $period;

        return view('admin.statistics.index', $data);
    }

    public function downloadReport(Request $request)
    {
        $period = $request->get('period', 'this_month');
        [$start, $end] = $this->resolvePeriod($period);

        $data = $this->getReportData($start, $end);
        $data['period'] = $period;
        $data['generated_at'] = now()->format('d M Y, h:i A');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.statistics.report', $data);
        return $pdf->download('TRB-AutoCare-Report-'.now()->format('Y-m-d').'.pdf');
    }

    private function getReportData($start, $end)
    {
        $stats = [
            'total_bookings' => Booking::whereBetween('booking_date', [$start, $end])->count(),

            'completed'      => Booking::whereBetween('booking_date', [$start, $end])
                                    ->where('status', 'completed')
                                    ->count(),

            'cancelled'      => Booking::whereBetween('booking_date', [$start, $end])
                                    ->where('status', 'cancelled')
                                    ->count(),

            'avg_rating'     => Review::whereHas('booking', fn($q) =>
                                    $q->whereBetween('booking_date', [$start, $end])
                                )->avg('rating'),
        ];

        $bookingRevenue = Booking::whereBetween('booking_date', [$start, $end])
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->sum('service_price_at_booking');

        $stats['revenue'] = $bookingRevenue;

        // Service Name vs Booking Count for bar chart
        $chartData = Booking::whereBetween('booking_date', [$start, $end])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.name as service_name, COUNT(bookings.id) as count')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        $topServices = Booking::whereBetween('booking_date', [$start, $end])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.name as service_name, services.price as service_price, COUNT(bookings.id) as count')
            ->groupBy('services.id', 'services.name', 'services.price')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $pendingCount = Booking::whereBetween('booking_date', [$start, $end])
            ->where('status', 'pending')
            ->count();

        $recentBookings = Booking::with(['user', 'car', 'service'])
            ->latest('created_at')
            ->limit(5)
            ->get();

        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();

        $sixMonthsBookings = Booking::where('created_at', '>=', $sixMonthsAgo)
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->get();
            
        $revenueTrendData = [];
        $monthlyBookingsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M');
            $revenueTrendData[$month] = 0;
            $monthlyBookingsData[$month] = 0;
        }

        foreach ($sixMonthsBookings as $b) {
            $month = $b->created_at->format('M');
            if (isset($revenueTrendData[$month])) {
                $revenueTrendData[$month] += $b->service_price_at_booking;
            }
        }

        $allSixMonthsBookings = Booking::where('created_at', '>=', $sixMonthsAgo)->get();
        foreach ($allSixMonthsBookings as $b) {
            $month = $b->created_at->format('M');
            if (isset($monthlyBookingsData[$month])) {
                $monthlyBookingsData[$month]++;
            }
        }

        $revenueTrend = [
            'labels' => array_keys($revenueTrendData),
            'values' => array_values($revenueTrendData),
        ];

        $monthlyBookings = [
            'labels' => array_keys($monthlyBookingsData),
            'values' => array_values($monthlyBookingsData),
        ];

        return compact('stats', 'chartData', 'topServices', 'pendingCount', 'recentBookings', 'revenueTrend', 'monthlyBookings');
    }

    private function resolvePeriod(string $period): array
    {
        return match ($period) {
            'today'      => [now()->startOfDay(), now()->endOfDay()],
            'this_week'  => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'this_year'  => [now()->startOfYear(), now()->endOfYear()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'last_year'  => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            default      => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

}
