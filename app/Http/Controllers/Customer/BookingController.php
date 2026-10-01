<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Car;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Voucher;
use App\Services\ActivityLogger;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bookings = $user->bookings()
            ->with(['car', 'service', 'branch', 'assignedStaff'])
            ->latest()
            ->get();
        return view('customer.bookings.index', compact('user', 'bookings'));
    }

    public function create(Request $request)
    {
        $branches = Branch::where('is_active', true)->get();
        $branch = $branches->first();
        $cars = auth()->user()->cars()->latest()->get();
        
        // Load active services
        $services = Service::where('is_active', true)->get();

        // Preload completed booking counts in a single grouped query (no N+1)
        $completedCounts = Booking::select('service_id', DB::raw('count(*) as count'))
            ->where('status', Booking::STATUS_COMPLETED)
            ->whereIn('service_id', $services->pluck('id'))
            ->groupBy('service_id')
            ->pluck('count', 'service_id');
        foreach ($services as $service) {
            $service->completed_count = (int) ($completedCounts[$service->id] ?? 0);
        }

        $catalog = [
            'Tyres' => ['Continental', 'Michelin', 'Bridgestone', 'Goodyear', 'Pirelli', 'Dunlop'],
            'Maintenance' => ['Bosch', 'Castrol', 'Shell Helix', 'Mobil 1', 'Motul', 'Petronas'],
            'Tinting Films' => ['ClearShield', '3M', 'V-Kool', 'LLumar', 'Solar Gard', 'Ray-Ban Auto'],
            'Dashcams' => ['70mai', 'DDPAI', 'Thinkware', 'BlackVue', 'Garmin', 'Mio'],
            'Wipers' => ['Bosch', 'PIAA', 'Valeo', 'Michelin Wiper', 'Denso', 'Trico'],
            'Car Mats' => ['Trapo', '3M Mats', 'Dodomat', 'Carmat.my', 'Enzo', 'Maxpider'],
        ];

        foreach ($services as $service) {
            $rawCat = trim($service->category);
            $cat = 'Maintenance'; // default
            foreach (array_keys($catalog) as $cName) {
                if (stripos($rawCat, $cName) !== false || stripos($cName, $rawCat) !== false) {
                    $cat = $cName;
                    break;
                }
            }
            if ($cat === 'Maintenance') {
                if (stripos($rawCat, 'tyre') !== false) $cat = 'Tyres';
                elseif (stripos($rawCat, 'wiper') !== false) $cat = 'Wipers';
                elseif (stripos($rawCat, 'film') !== false || stripos($rawCat, 'tint') !== false) $cat = 'Tinting Films';
                elseif (stripos($rawCat, 'cam') !== false) $cat = 'Dashcams';
                elseif (stripos($rawCat, 'mat') !== false) $cat = 'Car Mats';
            }
            $service->mapped_category = $cat;

            $brand = $catalog[$cat][0]; // default to first brand of category
            foreach ($catalog[$cat] as $bName) {
                if (stripos($service->name, $bName) !== false || stripos($service->description ?? '', $bName) !== false) {
                    $brand = $bName;
                    break;
                }
            }
            $service->mapped_brand = $brand;

            $sub = '';
            if ($cat === 'Tyres') {
                $sub = $service->meta_data['tyre_size'] ?? '15"';
            } elseif ($cat === 'Tinting Films' || $cat === 'Car Mats') {
                $sub = $service->meta_data['vehicle_type'] ?? 'Sedan';
            } elseif ($cat === 'Maintenance') {
                $sub = $service->meta_data['service_type'] ?? 'Fully Synthetic';
            } elseif ($cat === 'Dashcams') {
                $sub = $service->meta_data['camera_type'] ?? 'Front Only';
            } elseif ($cat === 'Wipers') {
                $sub = $service->meta_data['wiper_type'] ?? 'Standard';
            }
            $service->mapped_sub = $sub;
        }

        // Build per-slot booking counts for active branch using overlap detection
        $slotCounts = [];
        $bookings = Booking::where('branch_id', $branch->id)
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->get(['booking_date', 'start_time', 'end_time']);

        $candidateTimes = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        foreach ($bookings->groupBy(fn($b) => Carbon::parse($b->booking_date)->format('Y-m-d')) as $dateStr => $dayBookings) {
            foreach ($candidateTimes as $time) {
                $slotStart = $time . ':00';
                $slotEnd = Carbon::parse($slotStart)->addMinutes(60)->format('H:i:s');
                $cnt = $dayBookings->filter(fn($b) => $b->start_time->format('H:i:s') < $slotEnd && $b->end_time->format('H:i:s') > $slotStart)->count();
                if ($cnt > 0) {
                    $slotCounts["{$dateStr}|{$time}"] = $cnt;
                }
            }
        }

        $serviceCapacity = $branch->service_capacity;
        $preselectedServiceId = $request->query('service');
        $preselectedCarId = $request->query('car');

        $availableVouchers = \App\Models\Voucher::where('user_id', auth()->id())
            ->where('status', 'available')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at')
            ->get();

        return view('customer.bookings.create', compact(
            'branch', 'branches', 'cars', 'services', 'slotCounts', 'serviceCapacity', 'preselectedServiceId', 'preselectedCarId', 'catalog', 'availableVouchers'
        ));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'branch_id'       => 'required|exists:branches,id',
            'service_id'      => 'required|exists:services,id',
            'car_id'          => 'required|exists:cars,id',
            'booking_date'    => 'required|date|after_or_equal:today|before_or_equal:+30 days',
            'start_time'      => 'required|date_format:H:i',
            'customer_remark' => 'nullable|string|max:500',
            'payment_method'  => 'required|in:counter,card',
            'voucher_id'      => 'nullable|exists:vouchers,id',
            'booking_options' => 'nullable|array',
            'date_of_birth'   => 'nullable|date|before_or_equal:today',
        ];

        if (!auth()->user()->phone) {
            $validationRules['phone'] = 'required|string|max:20|unique:users,phone,' . auth()->id();
        }

        $validated = $request->validate($validationRules);

        $profileUpdates = [];
        if (!auth()->user()->phone && $request->filled('phone')) {
            $profileUpdates['phone'] = $request->phone;
        }
        if (!auth()->user()->date_of_birth && $request->filled('date_of_birth')) {
            $profileUpdates['date_of_birth'] = $request->date_of_birth;
        }
        if (!empty($profileUpdates)) {
            auth()->user()->update($profileUpdates);
        }

        // Validate branch is active
        $branch = Branch::where('id', $request->branch_id)->where('is_active', true)->first();
        if (!$branch) {
            return back()->withErrors(['branch_id' => __('messages.msg_branch_not_avail')])->withInput();
        }

        // Validate service is active
        $service = Service::where('id', $request->service_id)->where('is_active', true)->first();
        if (!$service) {
            return back()->withErrors(['service_id' => __('messages.msg_service_not_avail')])->withInput();
        }

        // Past-time validation: prevent booking a slot that has already passed
        $bookingDateTime = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        if ($bookingDateTime->lte(now())) {
            return back()->withErrors(['start_time' => __('messages.msg_time_past')])->withInput();
        }

        // Verify car ownership
        $car = Car::where('id', $request->car_id)->where('user_id', auth()->id())->first();
        if (!$car) {
            return back()->withErrors(['car_id' => __('messages.msg_invalid_vehicle')])->withInput();
        }

        // ── Service-specific booking options ──────────────────────────────────────
        // Resolve configured options from the service's meta_data and validate that
        // all required options have been submitted. Compute any price modifier.
        $optionPriceModifier = 0.0;
        $bookingOptionsSnapshot = null;
        $configuredOptions = $service->meta_data['booking_options'] ?? [];

        if (!empty($configuredOptions)) {
            $submittedOptions = $request->input('booking_options', []);
            $snapshot = [];

            foreach ($configuredOptions as $option) {
                $key   = $option['key'] ?? null;
                $label = $option['label'] ?? $key;
                $type  = $option['type'] ?? 'select';
                $required = (bool) ($option['required'] ?? false);

                if (!$key) continue;

                $submitted = $submittedOptions[$key] ?? null;

                if ($required && ($submitted === null || $submitted === '')) {
                    return back()->withErrors(['booking_options' => __('booking.err_select_option', ['label' => $label]) ?? "Please select a value for: {$label}."])->withInput();
                }

                if ($submitted !== null && $submitted !== '') {
                    $snapshot[$key] = $submitted;

                    // Find matching choice and accumulate price modifier
                    foreach ($option['choices'] ?? [] as $choice) {
                        if ((string) $choice['value'] === (string) $submitted) {
                            $optionPriceModifier += (float) ($choice['price_add'] ?? 0);
                            break;
                        }
                    }
                }
            }

            $bookingOptionsSnapshot = $snapshot ?: null;
        }
        // ─────────────────────────────────────────────────────────────────────────

        // Calculate end time
        $startTime = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        $endTime = $startTime->copy()->addMinutes($service->estimated_duration);

        // Wrap all writes in a DB transaction with pessimistic locking
        try {
            $result = DB::transaction(function () use ($request, $branch, $service, $car, $startTime, $endTime, $optionPriceModifier, $bookingOptionsSnapshot) {
                // Max 3 active bookings per customer limit
                $activeBookingsCount = Booking::where('user_id', auth()->id())
                    ->whereIn('status', Booking::ACTIVE_STATUSES)
                    ->count();

                if ($activeBookingsCount >= 3) {
                    return ['error' => 'booking_limit', 'message' => __('booking.err_booking_limit') ?? 'You have reached the maximum limit of 3 active bookings. Please complete or cancel an existing booking before making another reservation.'];
                }

                // Same vehicle overlapping appointment times check
                $carOverlapExists = Booking::where('car_id', $car->id)
                    ->whereDate('booking_date', $request->booking_date)
                    ->where('start_time', '<', $endTime->format('H:i:s'))
                    ->where('end_time', '>', $startTime->format('H:i:s'))
                    ->whereIn('status', Booking::ACTIVE_STATUSES)
                    ->exists();

                if ($carOverlapExists) {
                    return ['error' => 'car_id', 'message' => __('booking.err_car_overlap') ?? 'This vehicle already has an active booking with overlapping appointment times. Please select a different time or vehicle.'];
                }

                // Lock the branch row to prevent concurrent overbooking
                $lockedBranch = Branch::where('id', $branch->id)->lockForUpdate()->first();

                // Capacity overlap check — correct algorithm: existing.start < newEnd AND existing.end > newStart
                $overlapCount = Booking::where('branch_id', $lockedBranch->id)
                    ->whereDate('booking_date', $request->booking_date)
                    ->where('start_time', '<', $endTime->format('H:i:s'))
                    ->where('end_time', '>', $startTime->format('H:i:s'))
                    ->whereIn('status', Booking::ACTIVE_STATUSES)
                    ->count();

                if ($overlapCount >= $lockedBranch->service_capacity) {
                    return ['error' => 'start_time', 'message' => __('booking.err_slot_full') ?? 'This time slot is fully booked for this branch. Please choose another time.'];
                }

                $requiresInspection = in_array(strtolower($service->category), ['repair', 'diagnostic', 'inspection', 'custom']);
                $initialStatus = $requiresInspection ? Booking::STATUS_PENDING : Booking::STATUS_CONFIRMED;

                // Voucher discount computation with pessimistic locking
                // Base price = service price + any option price modifier
                $basePrice      = (float) $service->price + $optionPriceModifier;
                $discountAmount = 0.0;
                $appliedVoucher = null;

                if ($request->filled('voucher_id')) {
                    // Lock the voucher row to prevent concurrent double-use
                    $appliedVoucher = Voucher::where('id', $request->voucher_id)
                        ->where('user_id', auth()->id())
                        ->where('status', 'available')
                        ->where(function ($q) {
                            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                        })
                        ->lockForUpdate()
                        ->first();

                    if ($appliedVoucher) {
                        if ($appliedVoucher->type === 'fixed') {
                            $discountAmount = (float) $appliedVoucher->value;
                        } else {
                            $discountAmount = round($basePrice * ((float) $appliedVoucher->value / 100), 2);
                        }
                        $discountAmount = min($discountAmount, $basePrice);

                        // Atomically mark voucher as used
                        $appliedVoucher->update([
                            'status'             => 'used',
                            'used_at'            => now(),
                            'used_in_booking_id' => null, // will be updated after booking creation
                            'current_uses'       => $appliedVoucher->current_uses + 1,
                        ]);
                    }
                }

                $finalAmount = max(0, round($basePrice - $discountAmount, 2));

                $booking = Booking::create([
                    'user_id'                  => auth()->id(),
                    'car_id'                   => $request->car_id,
                    'service_id'               => $request->service_id,
                    'branch_id'                => $lockedBranch->id,
                    'service_price_at_booking' => $basePrice,
                    'booking_date'             => $request->booking_date,
                    'start_time'               => $startTime->format('H:i:s'),
                    'end_time'                 => $endTime->format('H:i:s'),
                    'status'                   => $initialStatus,
                    'customer_remark'          => $request->customer_remark,
                    'voucher_id'               => $appliedVoucher?->id,
                    'discount_amount'          => $discountAmount,
                    'booking_options'          => $bookingOptionsSnapshot,
                ]);

                // Update voucher with booking reference
                if ($appliedVoucher) {
                    $appliedVoucher->update(['used_in_booking_id' => $booking->id]);
                }

                ActivityLogger::booking($booking, 'created', 'Customer booked service: ' . $service->name);

                // Only create Payment for counter payments; card payments are created in PaymentController::checkout()
                if ($request->payment_method === 'counter') {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'user_id'    => auth()->id(),
                        'gateway'    => 'counter',
                        'amount'     => $finalAmount,
                        'currency'   => 'MYR',
                        'status'     => Payment::STATUS_PENDING,
                    ]);
                }

                return ['booking' => $booking];
            });
        } catch (\Exception $e) {
            return back()->withErrors(['booking' => __('messages.msg_booking_error')])->withInput();
        }

        // Handle transaction result
        if (isset($result['error'])) {
            return back()->withErrors([$result['error'] => $result['message']])->withInput();
        }

        $booking = $result['booking'];

        if ($request->payment_method === 'card') {
            return redirect()->route('payment.page', $booking->uuid)->with('success', __('messages.msg_booking_submitted_online'));
        }

        return redirect()->route('bookings.show', $booking->uuid)->with('success', __('messages.msg_booking_submitted_counter'));
    }

    /**
     * AJAX endpoint: return booking counts per time-slot for a given date & branch.
     * Response: { "09:00": 2, "10:00": 0, … }
     */
    public function slotAvailability(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'branch_id'  => 'nullable|exists:branches,id',
            'service_id' => 'nullable|exists:services,id',
        ]);

        // Use specified branch or fall back to first active branch
        $branch = $request->branch_id
            ? Branch::where('id', $request->branch_id)->where('is_active', true)->first()
            : Branch::where('is_active', true)->first();

        if (!$branch) {
            return response()->json([]);
        }

        $duration = 60;
        if ($request->filled('service_id')) {
            $service = Service::find($request->service_id);
            if ($service) {
                $duration = (int) ($service->estimated_duration ?: 60);
            }
        }

        $bookings = Booking::where('branch_id', $branch->id)
            ->whereDate('booking_date', $request->date)
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->get(['start_time', 'end_time']);

        $counts = [];
        $candidateTimes = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        foreach ($candidateTimes as $time) {
            $slotStart = $time . ':00';
            $slotEnd = Carbon::parse($slotStart)->addMinutes($duration)->format('H:i:s');
            $cnt = $bookings->filter(fn($b) => $b->start_time->format('H:i:s') < $slotEnd && $b->end_time->format('H:i:s') > $slotStart)->count();
            if ($cnt > 0) {
                $counts[$time] = $cnt;
            }
        }

        return response()->json($counts);
    }

    public function show($uuid)
    {
        $booking = Booking::where('uuid', $uuid)
            ->with(['user', 'car', 'service', 'branch', 'assignedStaff', 'review'])
            ->firstOrFail();

        abort_if($booking->user_id !== auth()->id(), 403);

        $qrCodeUrl = (new QrCodeService())->generate($booking);

        $availableVouchers = \App\Models\Voucher::where('user_id', auth()->id())
            ->where('status', 'available')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at')
            ->get();

        return view('customer.bookings.show', compact('booking', 'qrCodeUrl', 'availableVouchers'));
    }

    public function invoice(\App\Models\Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if(!$booking->isPaid(), 403, __('booking.err_receipt_paid_only') ?? 'Receipt is only available for paid bookings.');

        $booking->load('user', 'car', 'service', 'branch');

        return view('customer.bookings.invoice', compact('booking'));
    }

    public function submitReview(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        // Only allow reviews on completed bookings
        if ($booking->status !== Booking::STATUS_COMPLETED) {
            return back()->with('error', __('messages.msg_review_only_completed') ?? 'Reviews can only be submitted for completed bookings.');
        }

        if ($booking->review()->exists()) {
            return back()->with('error', __('messages.msg_review_already'));
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $isVisible = $request->rating <= 2 ? false : true;

        Review::create([
            'booking_id' => $booking->id,
            'user_id'    => auth()->id(),
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'is_visible' => $isVisible,
        ]);

        return back()->with('success', __('messages.msg_review_thanks'));
    }

    public function cancel(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        if (!in_array($booking->status, Booking::CANCELLABLE_STATUSES)) {
            return back()->with('error', __('messages.msg_cancel_only_pending'));
        }

        $bookingDateTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time->format('H:i:s'));
        
        if (now()->addHours(24)->greaterThan($bookingDateTime)) {
            return back()->with('error', __('messages.msg_cancel_24h'));
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'cancellation_reason' => 'Customer cancellation: ' . $request->cancellation_reason,
            ]);

            $booking->restoreVoucher();
            $booking->processRefund();

            ActivityLogger::booking($booking, 'cancelled', 'Customer cancelled booking. Reason: ' . $request->cancellation_reason);
        });

        return back()->with('success', __('messages.msg_cancel_success'));
    }

    public function reschedule(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        if (!in_array($booking->status, Booking::CANCELLABLE_STATUSES)) {
            return back()->with('error', __('messages.msg_reschedule_only_pending'));
        }

        if ($booking->is_rescheduled) {
            return back()->with('error', __('messages.msg_reschedule_once'));
        }

        $bookingDateTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time->format('H:i:s'));
        if (now()->addHours(24)->greaterThan($bookingDateTime)) {
            return back()->with('error', __('messages.msg_reschedule_24h'));
        }

        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today|before_or_equal:+30 days',
            'start_time'   => 'required|date_format:H:i',
        ]);

        $duration = 60;
        if ($booking->service) {
            $duration = (int) ($booking->service->estimated_duration ?: 60);
        }
        $newStartTime = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        $newEndTime   = (clone $newStartTime)->addMinutes($duration);

        if ($newStartTime->lt(now())) {
            return back()->with('error', __('messages.msg_reschedule_past'));
        }

        DB::transaction(function () use ($booking, $request, $newStartTime, $newEndTime) {
            // Same vehicle overlapping appointment times check for reschedule
            $carOverlapExists = Booking::where('car_id', $booking->car_id)
                ->where('id', '!=', $booking->id)
                ->whereDate('booking_date', $request->booking_date)
                ->where('start_time', '<', $newEndTime->format('H:i:s'))
                ->where('end_time', '>', $newStartTime->format('H:i:s'))
                ->whereIn('status', Booking::ACTIVE_STATUSES)
                ->exists();

            if ($carOverlapExists) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_time' => [__('booking.err_car_overlap') ?? 'This vehicle already has an active booking with overlapping appointment times.'],
                ]);
            }

            $lockedBranch = Branch::where('id', $booking->branch_id)->lockForUpdate()->first();

            $overlapCount = Booking::where('branch_id', $lockedBranch->id)
                ->where('id', '!=', $booking->id)
                ->whereDate('booking_date', $request->booking_date)
                ->where('start_time', '<', $newEndTime->format('H:i:s'))
                ->where('end_time', '>', $newStartTime->format('H:i:s'))
                ->whereIn('status', Booking::ACTIVE_STATUSES)
                ->count();

            $capacity = $lockedBranch->service_capacity ?: 3;
            if ($overlapCount >= $capacity) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_time' => [__('booking.err_slot_full') ?? 'The selected time slot is fully booked. Please choose another time.'],
                ]);
            }

            $oldDate = $booking->booking_date->format('Y-m-d');
            $oldTime = $booking->start_time->format('H:i');

            $booking->update([
                'booking_date'   => $request->booking_date,
                'start_time'     => $newStartTime->format('H:i:s'),
                'end_time'       => $newEndTime->format('H:i:s'),
                'is_rescheduled' => true,
            ]);

            ActivityLogger::booking($booking, 'rescheduled', "Customer rescheduled from {$oldDate} {$oldTime} to {$request->booking_date} {$request->start_time}");
        });

        return back()->with('success', __('messages.msg_reschedule_success'));
    }
}

