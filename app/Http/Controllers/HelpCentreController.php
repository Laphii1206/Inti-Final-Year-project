<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpCentreController extends Controller
{
    public function index()
    {
        $faqs = $this->getFaqs();
        $userBookings = collect();
        $myTickets = collect();
        if (auth()->check()) {
            $userBookings = \App\Models\Booking::with(['service', 'branch'])
                ->where('user_id', auth()->id())
                ->latest()
                ->take(30)
                ->get();

            $myTickets = \App\Models\SupportTicket::where('user_id', auth()->id())
                ->withCount('messages')
                ->latest()
                ->take(10)
                ->get();
        }
        return view('help-centre', compact('faqs', 'userBookings', 'myTickets'));
    }

    private function getFaqs(): array
    {
        return [
            __('landing.faq_cat_booking') => [
                ['q' => __('landing.faq_q1'), 'a' => __('landing.faq_a1')],
                ['q' => __('landing.faq_q2'), 'a' => __('landing.faq_a2')],
                ['q' => __('landing.faq_q3'), 'a' => __('landing.faq_a3')],
                ['q' => __('landing.faq_q4'), 'a' => __('landing.faq_a4')],
            ],
            __('landing.faq_cat_payment') => [
                ['q' => __('landing.faq_q5'), 'a' => __('landing.faq_a5')],
                ['q' => __('landing.faq_q6'), 'a' => __('landing.faq_a6')],
                ['q' => __('landing.faq_q7'), 'a' => __('landing.faq_a7')],
            ],
            __('landing.faq_cat_service') => [
                ['q' => __('landing.faq_q8'), 'a' => __('landing.faq_a8')],
                ['q' => __('landing.faq_q9'), 'a' => __('landing.faq_a9')],
                ['q' => __('landing.faq_q10'), 'a' => __('landing.faq_a10')],
            ],
            __('landing.faq_cat_account') => [
                ['q' => __('landing.faq_q11'), 'a' => __('landing.faq_a11')],
                ['q' => __('landing.faq_q12'), 'a' => __('landing.faq_a12')],
                ['q' => __('landing.faq_q13'), 'a' => __('landing.faq_a13')],
            ],
        ];
    }
}
