<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Events\BookingStatusChanged;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    /**
     * Create a Stripe Checkout Session and redirect the customer.
     */
    public function checkout(\Illuminate\Http\Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->isPaid()) {
            return redirect()->route('bookings.show', $booking->uuid)
                ->with('error', __('messages.msg_already_paid'));
        }



        // ---- Voucher discount ----
        $basePrice      = (float) $booking->service_price_at_booking;
        $discountAmount = (float) ($booking->discount_amount ?? 0);
        $finalAmount    = max(0, round($basePrice - $discountAmount, 2));

        if ($finalAmount <= 0) {
            Payment::create([
                'booking_id' => $booking->id,
                'user_id'    => auth()->id(),
                'gateway'    => 'free',
                'reference'  => 'ZERO_AMOUNT_' . strtoupper(\Illuminate\Support\Str::random(10)),
                'amount'     => 0,
                'currency'   => 'MYR',
                'status'     => Payment::STATUS_PAID,
                'paid_at'    => now(),
            ]);

            if ($booking->voucher_id) {
                $voucher = \App\Models\Voucher::find($booking->voucher_id);
                if ($voucher && $voucher->status === 'available') {
                    $voucher->update([
                        'status'             => 'used',
                        'used_at'            => now(),
                        'used_in_booking_id' => $booking->id,
                    ]);
                }
            }

            return redirect()->route('payment.success', $booking->uuid);
        }

        if ($finalAmount < 2.00) {
            return redirect()->route('bookings.show', $booking->uuid)
                ->with('error', __('messages.msg_min_stripe_amount') ?? 'Online card payment requires a minimum amount of RM 2.00. Please pay at counter.');
        }

        // Create a pending payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id'    => auth()->id(),
            'gateway'    => 'stripe',
            'amount'     => $finalAmount,
            'currency'   => 'MYR',
            'status'     => Payment::STATUS_PENDING,
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode'                 => 'payment',
            'currency'             => 'myr',
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'myr',
                    'unit_amount'  => (int) round($finalAmount * 100),
                    'product_data' => [
                        'name' => 'Booking #' . $booking->number,
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'type'       => 'booking',
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
            ],
            'success_url' => route('payment.success', $booking->uuid) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payment.cancel', $booking->uuid),
        ]);

        // Store the Stripe session ID on the payment record
        $payment->update(['reference' => $session->id]);

        return redirect($session->url);
    }

    /**
     * Show the payment success page.
     */
    public function success(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)
            ->with(['service'])
            ->firstOrFail();

        // Verify the authenticated user owns this booking
        abort_if($booking->user_id !== auth()->id(), 403);

        // Confirm the payment immediately on redirect so we do not rely solely
        // on the Stripe webhook (which may not be running in local/demo).
        $sessionId = $request->query('session_id');
        if ($sessionId) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = StripeSession::retrieve($sessionId);
                if (($session->payment_status ?? null) === 'paid') {
                    $this->handleBookingPayment($session);
                }
            } catch (\Exception $e) {
                // Ignore; the webhook remains a fallback.
            }
        }

        $booking->refresh();

        return view('customer.payments.success', compact('booking'));
    }

    /**
     * Handle payment cancellation — redirect back with an error flash.
     */
    public function cancel($uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        $payment = Payment::where('booking_id', $booking->id)
            ->where('gateway', 'stripe')
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if ($payment) {
            $payment->update(['status' => Payment::STATUS_FAILED]);
        }

        return redirect()->route('payment.page', $booking->uuid)
            ->with('error', __('booking.pay_cancelled'));
    }

    public function show($uuid)
    {
        $booking = Booking::where('uuid', $uuid)->with(['service'])->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->isPaid()) {
            return redirect()->route('bookings.show', $booking->uuid)
                ->with('success', __('booking.pay_already_paid'));
        }



        $availableVouchers = \App\Models\Voucher::where('user_id', auth()->id())
            ->where('status', 'available')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at')
            ->get();

        $failedPayment = Payment::where('booking_id', $booking->id)
            ->where('gateway', 'stripe')
            ->where('status', Payment::STATUS_FAILED)
            ->latest()
            ->first();

        $pendingCounter = Payment::where('booking_id', $booking->id)
            ->where('gateway', 'counter')
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        return view('customer.payments.show', compact('booking', 'availableVouchers', 'failedPayment', 'pendingCounter'));
    }

    public function payAtCounter($uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->isPaid()) {
            return redirect()->route('bookings.show', $booking->uuid)
                ->with('success', __('booking.pay_already_paid'));
        }



        $existing = Payment::where('booking_id', $booking->id)
            ->where('gateway', 'counter')
            ->where('status', Payment::STATUS_PENDING)
            ->exists();

        if (!$existing) {
            $amount = max(0, (float) $booking->service_price_at_booking - (float) ($booking->discount_amount ?? 0));

            Payment::create([
                'booking_id' => $booking->id,
                'user_id'    => auth()->id(),
                'gateway'    => 'counter',
                'amount'     => $amount,
                'currency'   => 'MYR',
                'status'     => Payment::STATUS_PENDING,
            ]);
        }

        return redirect()->route('payment.page', $booking->uuid)
            ->with('success', __('booking.pay_counter_selected'));
    }

    public function switchMethod(Request $request, $uuid)
    {
        $request->validate(['method' => 'required|in:counter,card']);
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->isPaid()) {
            return back()->with('error', __('messages.msg_already_paid_alt'));
        }

        Payment::where('booking_id', $booking->id)
            ->where('status', Payment::STATUS_PENDING)
            ->delete();

        $gateway = $request->method === 'card' ? 'stripe' : 'counter';
        $amount = max(0, (float) $booking->service_price_at_booking - (float) ($booking->discount_amount ?? 0));

        Payment::create([
            'booking_id' => $booking->id,
            'user_id'    => auth()->id(),
            'gateway'    => $gateway,
            'amount'     => $amount,
            'currency'   => 'MYR',
            'status'     => Payment::STATUS_PENDING,
        ]);

        if ($request->method === 'card') {
            return redirect()->route('payment.page', $booking->uuid)->with('success', __('messages.msg_switch_online'));
        }

        return redirect()->route('bookings.show', $booking->uuid)->with('success', __('messages.msg_switch_counter'));
    }

    /**
     * Handle Stripe webhook events (source of truth for payment status).
     */
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload.', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature.', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $this->handleBookingPayment($session);
        }

        return response('Webhook handled.', 200);
    }

    /**
     * Handle booking payment via webhook.
     */
    private function handleBookingPayment($session): void
    {
        $paymentId = $session->metadata->payment_id ?? null;

        if (!$paymentId) return;

        $payment = Payment::find($paymentId);

        if (!$payment) return;

        // Idempotent: only process if not already marked paid
        if ($payment->status !== Payment::STATUS_PAID) {
            $payment->update([
                'status'  => Payment::STATUS_PAID,
                'paid_at' => now(),
                'meta'    => [
                    'stripe_payment_intent' => $session->payment_intent ?? null,
                    'stripe_session_id'     => $session->id,
                ],
            ]);

            $booking = $payment->booking;

            // Mark the applied voucher as used (only once, after successful payment)
            if ($booking && $booking->voucher_id) {
                $voucher = \App\Models\Voucher::find($booking->voucher_id);
                if ($voucher && $voucher->status === 'available') {
                    $voucher->update([
                        'status'             => 'used',
                        'used_at'            => now(),
                        'used_in_booking_id' => $booking->id,
                    ]);
                }
            }
        }
    }

}
