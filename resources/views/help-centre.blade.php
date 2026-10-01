@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
<style>
    /* ─── Hero (Big Tech / Enterprise SaaS Style) ─── */
    .hc-hero {
        background: radial-gradient(circle at 50% 0%, #2a181e 0%, #121318 55%, #0a0a0c 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    /* Ambient Glow */
    .hc-hero::before {
        content: '';
        position: absolute;
        top: -160px;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 350px;
        background: radial-gradient(ellipse, rgba(236, 31, 36, 0.3) 0%, rgba(255, 107, 107, 0.1) 45%, transparent 75%);
        filter: blur(50px);
        pointer-events: none;
        z-index: 0;
    }
    /* High-Tech Geometric Grid Overlay */
    .hc-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 36px 36px;
        mask-image: radial-gradient(circle at 50% 35%, black 25%, transparent 80%);
        -webkit-mask-image: radial-gradient(circle at 50% 35%, black 25%, transparent 80%);
        pointer-events: none;
        z-index: 1;
    }

    /* Glowing Badge Pill */
    .hc-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 18px;
        border-radius: 9999px;
        background: rgba(236, 31, 36, 0.12);
        border: 1px solid rgba(236, 31, 36, 0.35);
        box-shadow: 0 0 20px rgba(236, 31, 36, 0.2);
        color: #ff6b6b;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    .hc-hero-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #EC1F24;
        box-shadow: 0 0 8px #EC1F24;
    }

    /* Floating Glass Search Palette */
    .hc-search-container {
        max-width: 660px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }
    .hc-search-bar {
        background: rgba(22, 23, 28, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 9999px;
        padding: 8px 8px 8px 24px;
        display: flex;
        align-items: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.05), inset 0 2px 4px rgba(255, 255, 255, 0.08);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }
    .hc-search-bar:focus-within {
        border-color: rgba(236, 31, 36, 0.65);
        box-shadow: 0 25px 50px -10px rgba(0, 0, 0, 0.85), 0 0 35px rgba(236, 31, 36, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    .hc-search-input {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 500;
        width: 100%;
        outline: none;
        padding: 6px 0;
    }
    .hc-search-input::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }
    .hc-search-btn {
        background: linear-gradient(135deg, #EC1F24 0%, #d6181c 100%);
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(236, 31, 36, 0.35);
        cursor: pointer;
    }
    .hc-search-btn:hover {
        background: linear-gradient(135deg, #ff2e34 0%, #EC1F24 100%);
        transform: scale(1.03);
        box-shadow: 0 6px 20px rgba(236, 31, 36, 0.5);
        color: #fff;
    }

    /* Quick Search Chips */
    .hc-quick-chip {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.75);
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .hc-quick-chip:hover {
        background: rgba(236, 31, 36, 0.15);
        border-color: rgba(236, 31, 36, 0.45);
        color: #ffffff;
        transform: translateY(-1.5px);
        box-shadow: 0 4px 12px rgba(236, 31, 36, 0.2);
    }

    /* ─── Category pills ─── */
    .faq-category-btn {
        border: 2px solid rgba(236,31,36,.3);
        color: #EC1F24;
        background: transparent;
        border-radius: 30px;
        padding: 8px 22px;
        font-weight: 600;
        font-size: .875rem;
        transition: all .25s;
        cursor: pointer;
    }
    .faq-category-btn:hover,
    .faq-category-btn.active {
        background: #EC1F24;
        color: #fff;
        border-color: #EC1F24;
    }

    /* ─── FAQ Accordion ─── */
    .faq-section { display: none; }
    .faq-section.active { display: block; }

    .accordion-button:not(.collapsed) {
        background-color: #fff8f8;
        color: #EC1F24;
        box-shadow: none;
    }
    .accordion-button::after {
        filter: none;
    }
    .accordion-button:not(.collapsed)::after {
        filter: invert(20%) sepia(100%) saturate(5000%) hue-rotate(345deg);
    }
    .accordion-item {
        border: 1px solid #f0f0f0;
        border-radius: 12px !important;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .accordion-button {
        font-weight: 600;
        font-size: .95rem;
        border-radius: 12px !important;
    }

    /* ─── Contact cards ─── */
    .contact-card {
        border: none;
        border-radius: 16px;
        transition: transform .25s, box-shadow .25s;
        background: #fff;
    }
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,.08) !important;
    }
    .contact-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, #EC1F24, #ff6b6b);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        margin: 0 auto 1rem;
    }

    /* ─── Chat Widget ─── */
    .chat-widget-card {
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        background: linear-gradient(145deg, #181920 0%, #22242e 100%);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        color: #fff;
        overflow: hidden;
    }
    .chat-widget-card .form-control,
    .chat-widget-card .form-select {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.16);
        color: #fff;
        border-radius: 14px;
        padding: 14px 18px;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }
    .chat-widget-card .form-control::placeholder { color: rgba(255,255,255,.35); }
    .chat-widget-card .form-control:focus,
    .chat-widget-card .form-select:focus {
        background: rgba(255,255,255,.08);
        border-color: #EC1F24;
        box-shadow: 0 0 0 4px rgba(236, 31, 36, 0.25);
        color: #fff;
    }
    .chat-widget-card label { color: rgba(255,255,255,.8); font-size: .85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

    /* ─── Custom SaaS Dropdown Component ─── */
    .booking-dropdown-trigger {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #fff;
        border-radius: 14px;
        padding: 13px 18px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
    }
    .booking-dropdown-trigger:hover, .booking-dropdown-trigger.open {
        background: rgba(255, 255, 255, 0.08);
        border-color: #EC1F24;
        box-shadow: 0 0 0 4px rgba(236, 31, 36, 0.25);
    }
    .booking-dropdown-trigger.open #booking-dropdown-chevron {
        transform: rotate(180deg);
    }
    .booking-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0; right: 0;
        background: #181920;
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 16px;
        max-height: 280px;
        overflow-y: auto;
        z-index: 1050;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.8);
    }
    .booking-dropdown-menu::-webkit-scrollbar { width: 5px; }
    .booking-dropdown-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
    .booking-dropdown-menu.show { display: block; animation: fadeInDown 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .booking-dropdown-item {
        padding: 12px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .booking-dropdown-item:last-child { border-bottom: none; }
    .booking-dropdown-item:hover {
        background: linear-gradient(90deg, rgba(236, 31, 36, 0.18) 0%, rgba(236, 31, 36, 0.02) 100%);
        padding-left: 24px;
    }

    /* ─── Category Tabs (Tier 1) ─── */
    .hc-cat-btn {
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.03);
        color: rgba(255, 255, 255, 0.75);
        border-radius: 16px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        outline: none !important;
    }
    .hc-cat-btn:focus { outline: none !important; }
    .hc-cat-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }
    .hc-cat-btn.active {
        background: linear-gradient(135deg, rgba(236,31,36,0.3), rgba(236,31,36,0.15));
        border: 1px solid #EC1F24;
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(236,31,36,0.25);
    }
    .hc-cat-btn i { color: #EC1F24; transition: transform 0.2s; font-size: 1.1rem; }
    .hc-cat-btn.active i { transform: scale(1.15); }

    /* ─── My Tickets ─── */
    .ticket-badge { font-size: .75rem; padding: 4px 10px; border-radius: 20px; }
    .badge-open        { background:#fff3cd; color:#856404; }
    .badge-in_progress { background:#cce5ff; color:#004085; }
    .badge-resolved    { background:#d4edda; color:#155724; }
    .badge-closed      { background:#e2e3e5; color:#383d41; }

    /* ─── Topic Pills ─── */
    .topic-pill-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.88rem; font-weight: 500;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        outline: none !important;
    }
    .topic-pill-btn:focus { outline: none !important; }
    .topic-pill-btn:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.28);
        color: #fff; transform: translateY(-1px);
    }
    .topic-pill-btn.active {
        background: linear-gradient(135deg, rgba(236,31,36,0.25), rgba(236,31,36,0.12));
        border: 1px solid #EC1F24; color: #fff; font-weight: 600;
        box-shadow: 0 4px 16px rgba(236,31,36,0.25);
    }
    .topic-pill-btn i { font-size: 1rem; color: #EC1F24; transition: transform 0.2s; }
    .topic-pill-btn.active i { transform: scale(1.15); }

    /* ─── File upload in chat widget ─── */
    .hc-drop-zone {
        border: 2px dashed rgba(255,255,255,.22);
        border-radius: 18px;
        min-height: 180px;
        padding: 36px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        background: rgba(255,255,255,.025);
        color: rgba(255,255,255,.65);
        font-size: .9rem;
        transition: all .25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .hc-drop-zone:hover {
        border-color: rgba(236,31,36,.6);
        background: rgba(236,31,36,.04);
        color: #fff;
        transform: translateY(-2px);
    }
    .hc-drop-zone.dragover {
        border: 2px solid #EC1F24 !important;
        background: linear-gradient(135deg, rgba(236,31,36,.25) 0%, rgba(236,31,36,.1) 100%) !important;
        box-shadow: 0 0 40px rgba(236,31,36,.45), inset 0 0 25px rgba(236,31,36,.2) !important;
        transform: scale(1.02);
    }
    .hc-drop-zone.dragover .dz-state-default { display: none !important; }
    .hc-drop-zone.dragover .dz-state-active { display: flex !important; animation: dzPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes dzPopIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .dz-icon-circle {
        width: 60px; height: 60px;
        background: rgba(236,31,36,.12);
        border: 1px solid rgba(236,31,36,.25);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
        transition: all .25s ease;
    }
    .hc-drop-zone:hover .dz-icon-circle {
        transform: scale(1.1);
        background: rgba(236,31,36,.2);
        box-shadow: 0 0 16px rgba(236,31,36,.3);
    }
    .dz-pulse-circle {
        width: 68px; height: 68px;
        background: #EC1F24;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px;
        box-shadow: 0 0 25px rgba(236,31,36,.7);
        animation: pulseBounce 1s infinite alternate;
    }
    @keyframes pulseBounce {
        from { transform: translateY(0) scale(1); }
        to { transform: translateY(-6px) scale(1.08); }
    }
    .pointer-events-none { pointer-events: none; }
    .hc-file-previews { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
    .hc-file-chip {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 10px; padding: 6px 12px;
        font-size: .8rem; color: #fff; transition: all .2s;
    }
    .hc-file-chip:hover { background: rgba(255,255,255,.14); border-color: rgba(255,255,255,.28); }
    main section.bg-light { background:#0a0a0a !important; }
    .accordion-item { background:#111 !important; border-color: rgba(255,255,255,.08) !important; }
    .accordion-button, .accordion-button.bg-white { background:#111 !important; color:#e4e4e7 !important; box-shadow:none !important; }
    .accordion-button:not(.collapsed) { background:#1a1212 !important; color:#EC1F24 !important; }
    .accordion-button::after { filter: invert(.7); }
    .accordion-body { color:#cbd5e1 !important; }
    .accordion-body .text-muted { color:#cbd5e1 !important; }
    section.bg-white { background:#0a0a0a !important; }
    .card { background:#111 !important; border-color: rgba(255,255,255,.08) !important; }
    .card h2, .card h5 { color:#f4f4f5 !important; }
    .card .text-dark { color:#f4f4f5 !important; }
    .card .text-muted, .card .text-body-secondary { color:#9ca3af !important; }
    .card .btn-outline-secondary, .card .btn-outline-dark { color:#e4e4e7 !important; border-color: rgba(255,255,255,.25) !important; }
    .badge.text-dark, .badge.bg-warning, .badge.bg-info { color:#1a1a1a !important; }
    section.bg-white h2,
    section.bg-white h3,
    section.bg-white h5,
    section.bg-white .text-dark { color:#f4f4f5 !important; }
    section.bg-white p,
    section.bg-white .text-muted,
    section.bg-white .text-body-secondary { color:#9ca3af !important; }
    #chat-agent h2, #chat-agent h3, #chat-agent h5, #chat-agent .text-dark { color:#f4f4f5 !important; }
    section.bg-light h2,
    section.bg-light h3,
    section.bg-light h5,
    section.bg-light .text-dark { color:#f4f4f5 !important; }
    section.bg-light p,
    section.bg-light .text-muted,
    section.bg-light .text-body-secondary { color:#9ca3af !important; }

    .card .btn-outline-secondary,
    .card .btn-outline-dark,
    .card .btn-outline-primary {
        background-color:#EC1F24 !important;
        border-color:#EC1F24 !important;
        color:#fff !important;
    }
    .card .btn-outline-secondary:hover,
    .card .btn-outline-dark:hover,
    .card .btn-outline-primary:hover {
        background-color:#c81a1f !important;
        border-color:#c81a1f !important;
        color:#fff !important;
    }
</style>
@endsection

@section('content')

{{-- ─── HERO ─── --}}
<section class="hc-hero">
    <div class="container text-center position-relative">
        <div class="hc-hero-badge mb-4">
            <span class="dot"></span>
            <span>{{ __('landing.help_title') }}</span>
        </div>
        <h1 class="display-4 fw-bolder text-white mb-3 position-relative" style="letter-spacing: -0.03em; z-index: 2;">
            {{ __('landing.help_how_can') }} <span style="background: linear-gradient(135deg, #ff5252 0%, #ff8f8f 50%, #EC1F24 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 4px 16px rgba(236,31,36,0.3));">{{ __('landing.help_you') }}</span>
        </h1>
        <p class="text-white-50 mx-auto mb-5 position-relative" style="max-width: 560px; font-size: 1.15rem; line-height: 1.6; z-index: 2;">
            {{ __('landing.help_search_desc') }}
        </p>

        <div class="hc-search-container">
            <div class="hc-search-bar">
                <i class="fa-solid fa-magnifying-glass text-white-50 me-3 fs-5"></i>
                <input type="text" id="faq-search" class="hc-search-input" placeholder="{{ __('landing.help_search_ph') }}">
                <button class="hc-search-btn" onclick="searchFAQ()">
                    <span>{{ __('landing.help_search_btn') }}</span>
                    <i class="fa-solid fa-arrow-right small"></i>
                </button>
            </div>
            <div id="no-results-msg" class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning small mt-3 rounded-3 py-2 px-3 shadow-sm" style="display:none; backdrop-filter: blur(10px);">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ __('landing.help_no_topics') }}
            </div>

            <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mt-4 position-relative" style="z-index: 2;">
                <span class="text-white-50 small me-1 fw-semibold"><i class="fa-solid fa-fire text-danger me-1"></i> {{ __('landing.help_popular') }}</span>
                <a href="javascript:void(0)" onclick="quickSearch('booking')" class="hc-quick-chip">{{ __('landing.faq_cat_booking') }}</a>
                <a href="javascript:void(0)" onclick="quickSearch('payment')" class="hc-quick-chip">{{ __('landing.faq_cat_payment') }}</a>
                <a href="javascript:void(0)" onclick="quickSearch('reschedule')" class="hc-quick-chip">{{ __('landing.help_tag_reschedule') }}</a>
                <a href="javascript:void(0)" onclick="quickSearch('warranty')" class="hc-quick-chip">{{ __('landing.help_tag_warranty') }}</a>
            </div>
        </div>
    </div>
</section>

{{-- ─── FAQ ─── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-1">{{ __('landing.help_faq_title') }}</h2>
            <p class="text-muted small">{{ __('landing.help_browse_cat') }}</p>
        </div>

        {{-- Category Pills --}}
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" id="faq-categories">
            @foreach($faqs as $category => $questions)
                <button class="faq-category-btn {{ $loop->first ? 'active' : '' }}"
                        onclick="showCategory('{{ $category }}', this)"
                        data-category="{{ $category }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>

        {{-- FAQ Accordion Sections --}}
        @foreach($faqs as $category => $questions)
            <div class="faq-section {{ $loop->first ? 'active' : '' }}" id="faq-{{ $category }}" data-category="{{ $category }}">
                <div class="accordion" id="accordion-{{ $category }}">
                    @foreach($questions as $i => $faq)
                        <div class="accordion-item border-0 shadow-sm faq-item" data-text="{{ strtolower($faq['q'] . ' ' . $faq['a']) }}">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} bg-white" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $category }}-{{ $i }}"
                                        aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                    <i class="fa-solid fa-circle-question text-brand me-2"></i>
                                    {{ $faq['q'] }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $category }}-{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}">
                                <div class="accordion-body text-muted small lh-lg">{{ $faq['a'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ─── CONTACT CARDS ─── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-1">{{ __('landing.help_still_need') }}</h2>
            <p class="text-muted small">{{ __('landing.help_team_ready') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="contact-card card shadow-sm text-center p-4 h-100">
                    <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{ __('landing.help_call_us') }}</h5>
                    <p class="text-muted small mb-3">{{ __('landing.help_hours') }}</p>
                    <a href="tel:+60173673385" class="btn btn-brand btn-sm px-4 fw-bold">017-367 3385</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card card shadow-sm text-center p-4 h-100">
                    <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{ __('landing.help_email_support') }}</h5>
                    <p class="text-muted small mb-3">{{ __('landing.help_reply_time') }}</p>
                    <a href="mailto:trbautocarcare@gmail.com" class="btn btn-brand btn-sm px-4 fw-bold">trbautocarcare@gmail.com</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card card shadow-sm text-center p-4 h-100">
                    <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <h5 class="fw-bold text-dark mb-1">{{ __('landing.help_visit_us') }}</h5>
                    <p class="text-muted small mb-3">70, Jalan PU 7/3, Taman Puchong Utama</p>
                    <a href="https://maps.app.goo.gl/WQsk6vup7nvio19c6" target="_blank" class="btn btn-outline-secondary btn-sm px-4 fw-bold">{{ __('landing.help_get_directions') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── CHAT WITH AGENT (Submit Ticket) ─── --}}
<section class="py-5 bg-light" id="chat-agent">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="chat-widget-card shadow-lg p-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:52px;height:52px;border-radius:14px;background:rgba(236,31,36,.2);display:flex;align-items:center;justify-content:center;font-size:1.4rem;">
                            <i class="fa-solid fa-headset text-brand"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-0">{{ __('landing.help_chat_team') }}</h4>
                            <span class="small" style="color:rgba(255,255,255,.5);">{{ __('landing.help_avg_response') }}</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="rounded-circle bg-success d-inline-block" style="width:10px;height:10px;"></span>
                            <span class="small text-success fw-semibold">{{ __('landing.help_online') }}</span>
                        </div>
                    </div>

                    @if(session('success') && str_contains(session('success'), 'ticket'))
                        <div class="alert alert-success border-0 rounded-3 mb-4">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @auth
                        <form action="{{ route('support-tickets.store') }}" method="POST"
                              enctype="multipart/form-data" id="ticket-form">
                            @csrf
                            <div class="row g-3">
                                <!-- Tier 1: Category Selection -->
                                <div class="col-12 mb-3">
                                    <label class="form-label d-block mb-2">1. @if(app()->getLocale() == 'zh') 选择咨询类别 / Ticket Category @else Ticket Category @endif <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="button" class="hc-cat-btn active d-flex align-items-center gap-2" data-category="booking">
                                            <i class="fa-solid fa-car"></i>
                                            <span>@if(app()->getLocale() == 'zh') 订单售后与投诉 (Booking Related) @else Booking Related Issue @endif</span>
                                        </button>
                                        <button type="button" class="hc-cat-btn d-flex align-items-center gap-2" data-category="general">
                                            <i class="fa-regular fa-comments"></i>
                                            <span>@if(app()->getLocale() == 'zh') 常规咨询与建议 (General Inquiry) @else General Inquiry @endif</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Dynamic Booking Selector -->
                                <div class="col-12 mb-3" id="hc-booking-select-container">
                                    <label class="form-label">2. @if(app()->getLocale() == 'zh') 关联订单 / Select Linked Booking @else Select Linked Booking @endif <span class="text-danger">*</span></label>
                                    <input type="hidden" name="booking_id" id="hc-booking-id" value="">
                                    @if(isset($userBookings) && $userBookings->isNotEmpty())
                                        <div class="position-relative">
                                            <div class="booking-dropdown-trigger d-flex align-items-center justify-content-between" id="booking-dropdown-trigger">
                                                <div class="d-flex align-items-center gap-2 text-truncate pe-2">
                                                    <i class="fa-solid fa-car text-brand"></i>
                                                    <span id="booking-dropdown-text" style="color:rgba(255,255,255,.6);">-- @if(app()->getLocale() == 'zh') 请选择您需咨询的订单 @else Select a booking -- @endif</span>
                                                </div>
                                                <i class="fa-solid fa-chevron-down text-brand transition-transform flex-shrink-0" id="booking-dropdown-chevron"></i>
                                            </div>
                                            <div class="booking-dropdown-menu shadow-lg" id="booking-dropdown-menu">
                                                <div class="booking-dropdown-item d-flex align-items-center justify-content-between" data-value="" data-number="" data-service="">
                                                    <span class="text-muted small">-- @if(app()->getLocale() == 'zh') 请选择您需咨询的订单 @else Select a booking -- @endif --</span>
                                                </div>
                                                @foreach($userBookings as $b)
                                                    @php
                                                        $statusColors = ['confirmed'=>'primary', 'completed'=>'success', 'cancelled'=>'secondary', 'pending'=>'warning'];
                                                        $sc = $statusColors[strtolower($b->status)] ?? 'info';
                                                    @endphp
                                                    <div class="booking-dropdown-item d-flex align-items-center justify-content-between gap-3" 
                                                         data-value="{{ $b->id }}" data-number="{{ $b->number }}" data-service="{{ $b->service->name ?? '' }}">
                                                        <div class="text-truncate">
                                                            <span class="fw-bold text-white font-monospace">#{{ $b->number }}</span>
                                                            <span class="text-muted small ms-1">&middot; {{ $b->service->name ?? 'Service' }} (@if($b->branch){{ $b->branch->name }} &middot; @endif{{ $b->booking_date ? $b->booking_date->format('d M Y') : '' }})</span>
                                                        </div>
                                                        <span class="badge bg-{{ $sc }} bg-opacity-25 text-{{ $sc }} border border-{{ $sc }} rounded-pill px-2.5 py-1 small flex-shrink-0">{{ strtoupper($b->status) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning border-0 rounded-3 py-3 px-4 small mb-0 d-flex align-items-center gap-3" style="background: rgba(255, 193, 7, 0.15); color: #ffc107;">
                                            <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                                            <span>@if(app()->getLocale() == 'zh') 暂无近期订单记录，如需常规咨询请选择上方“常规咨询与建议”。 @else No recent bookings found. Please select "General Inquiry" above if this is not regarding a specific order. @endif</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tier 2: Issue Sub-Type -->
                                <div class="col-12 mb-1">
                                    <label class="form-label d-block mb-2 fw-bold" style="color:rgba(255,255,255,.9);">@if(app()->getLocale() == 'zh') 3. 问题细分 / Issue Type @else Issue Type @endif <span class="text-danger">*</span></label>
                                    <input type="hidden" name="type" id="hc-issue-type" value="{{ old('type', 'service_quality') }}" required>
                                    
                                    <!-- Booking Related Topics -->
                                    <div class="d-flex flex-wrap gap-2.5 hc-topic-group" id="hc-group-booking">
                                        <button type="button" class="topic-pill-btn active" data-value="service_quality">
                                            <i class="fa-solid fa-wrench"></i>
                                            <span>@if(app()->getLocale() == 'zh') 服务质量问题 @else Service Quality @endif</span>
                                        </button>
                                        <button type="button" class="topic-pill-btn" data-value="overcharge">
                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                            <span>@if(app()->getLocale() == 'zh') 收费/发票争议 @else Billing / Invoice @endif</span>
                                        </button>
                                        <button type="button" class="topic-pill-btn" data-value="parts_issue">
                                            <i class="fa-solid fa-gear"></i>
                                            <span>@if(app()->getLocale() == 'zh') 配件/耗材异常 @else Parts Issue @endif</span>
                                        </button>
                                    </div>

                                    <!-- General Topics -->
                                    <div class="d-flex flex-wrap gap-2.5 hc-topic-group d-none" id="hc-group-general">
                                        <button type="button" class="topic-pill-btn" data-value="general">
                                            <i class="fa-regular fa-comments"></i>
                                            <span>@if(app()->getLocale() == 'zh') 常规咨询 @else General Inquiry @endif</span>
                                        </button>
                                        <button type="button" class="topic-pill-btn" data-value="other">
                                            <i class="fa-solid fa-ellipsis"></i>
                                            <span>@if(app()->getLocale() == 'zh') 其它建议/问题 @else Other @endif</span>
                                        </button>
                                    </div>
                                    @error('type')<div class="text-warning small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <i class="fa-solid fa-heading position-absolute top-50 translate-middle-y ms-3" style="color:rgba(255,255,255,.35);font-size:.95rem;"></i>
                                        <input type="text" name="subject" class="form-control ps-5 py-2.5"
                                               placeholder="{{ __('landing.help_subject_ph') }}"
                                               value="{{ old('subject') }}" minlength="5" maxlength="255" required>
                                    </div>
                                    @error('subject')<div class="text-warning small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Message <span class="text-danger">*</span></span>
                                        <span id="hc-char-count" style="font-size:.75rem;color:rgba(255,255,255,.45);font-weight:400;">0 / 3000</span>
                                    </label>
                                    <textarea name="description" id="hc-description" class="form-control p-3" rows="5"
                                              placeholder="{{ __('landing.help_message_ph') }}"
                                              minlength="10" maxlength="3000" style="resize:vertical;" required>{{ old('description') }}</textarea>
                                    @error('description')<div class="text-warning small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Attachments <span style="color:rgba(255,255,255,.45);font-size:.8rem;">{{ __('landing.help_attach_opt') }}</span></label>
                                    <div class="hc-drop-zone" id="hc-drop-zone" onclick="document.getElementById('hc-file-input').click()">
                                        <div class="dz-state-default d-flex flex-column align-items-center justify-content-center pointer-events-none w-100">
                                            <div class="dz-icon-circle">
                                                <i class="fa-solid fa-cloud-arrow-up fs-4 text-brand"></i>
                                            </div>
                                            <span class="fw-bold text-white fs-6 mb-1">{{ __('landing.help_drag_files') }}</span>
                                            <span style="font-size:.78rem;opacity:.6;">Supported formats: JPG, PNG, PDF (max 10 MB each, up to 5 files)</span>
                                        </div>
                                        <div class="dz-state-active d-none flex-column align-items-center justify-content-center pointer-events-none w-100 py-2">
                                            <div class="dz-pulse-circle">
                                                <i class="fa-solid fa-file-arrow-down fs-2 text-white"></i>
                                            </div>
                                            <span class="fw-bolder text-white fs-5 tracking-wide mb-1">@if(app()->getLocale() == 'zh') 释放鼠标即刻添加凭证 @else DROP FILES HERE TO ATTACH @endif</span>
                                            <span class="badge bg-white text-brand fw-bold rounded-pill px-3 py-1 shadow-sm mt-1">@if(app()->getLocale() == 'zh') ✨ 准备就绪，松开即可 @else ✨ Ready to attach! @endif</span>
                                        </div>
                                        <input type="file" id="hc-file-input" name="attachments[]" multiple
                                               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" style="display:none">
                                    </div>
                                    <div id="hc-file-previews" class="hc-file-previews"></div>
                                    @error('attachments.*')<div class="text-warning small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mt-4 pt-2">
                                    <button type="submit" class="btn btn-brand fw-bold px-5 py-3 w-100 rounded-3 shadow-lg d-flex align-items-center justify-content-center gap-2" style="font-size:1.05rem; letter-spacing:0.02em;">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        <span>Submit Support Ticket</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <i class="fa-solid fa-lock text-brand fs-1 mb-3 d-block"></i>
                            <p style="color:rgba(255,255,255,.6);">{{ __('landing.help_login_msg') }}</p>
                            <a href="{{ route('login') }}" class="btn btn-brand px-5 fw-bold">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Login to Continue
                            </a>
                        </div>
                    @endauth
                </div>

                @auth
                    @if($myTickets->isNotEmpty())
                        <div class="mt-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-ticket text-brand me-2"></i>{{ __('landing.help_my_tickets') }}</h5>
                            <div class="d-flex flex-column gap-3">
                                @foreach($myTickets as $ticket)
                                    @php
                                        $statusMap = ['open'=>'badge-open','in_progress'=>'badge-in_progress','resolved'=>'badge-resolved','closed'=>'badge-closed'];
                                        $lastCustomerMsgTime = $ticket->messages()->where('sender_role', 'customer')->max('created_at') ?? '2000-01-01';
                                        $hasNewMsg = $ticket->messages()->where('sender_role', 'admin')->where('created_at', '>', $lastCustomerMsgTime)->exists();
                                    @endphp
                                    @php
                                        $route = auth()->user()->isAdmin()
                                            ? route('admin.support-tickets.show', $ticket)
                                            : route('support-tickets.show', $ticket->ticket_number);
                                    @endphp
                                    <a href="{{ $route }}"
                                       class="card border-0 shadow-sm rounded-3 p-3 text-decoration-none"
                                       style="transition:box-shadow .2s;">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="fw-bold text-dark small">{{ $ticket->subject }}</span>
                                                    @if($hasNewMsg)
                                                        <span class="badge bg-danger rounded-pill" style="font-size:0.65rem;">{{ __('landing.help_badge_new') }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted" style="font-size:.78rem;">
                                                    <code>Ticket #{{ $ticket->ticket_number }}</code>
                                                    &middot; {{ $ticket->getTypeLabel() }}
                                                    &middot; {{ $ticket->messages_count }} message{{ $ticket->messages_count !== 1 ? 's' : '' }}
                                                    &middot; {{ $ticket->updated_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                                <span class="ticket-badge badge-{{ $ticket->status }}">{{ $ticket->getStatusLabel() }}</span>
                                                <i class="fa-solid fa-chevron-right text-muted" style="font-size:.75rem;"></i>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    function showCategory(category, btn) {
        document.querySelectorAll('.faq-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.faq-category-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('faq-' + category)?.classList.add('active');
        btn.classList.add('active');
    }

    function searchFAQ() {
        const query = document.getElementById('faq-search').value.toLowerCase().trim();
        const noResultsMsg = document.getElementById('no-results-msg');

        if (!query) {
            // Reset to first category
            document.querySelectorAll('.faq-section').forEach((s, i) => {
                s.classList.toggle('active', i === 0);
            });
            document.querySelectorAll('.faq-category-btn').forEach((b, i) => {
                b.classList.toggle('active', i === 0);
            });
            document.querySelectorAll('.faq-item').forEach(item => item.style.display = '');
            noResultsMsg.style.display = 'none';
            return;
        }

        // Show all sections when searching
        document.querySelectorAll('.faq-section').forEach(s => s.classList.add('active'));
        document.querySelectorAll('.faq-category-btn').forEach(b => b.classList.remove('active'));

        let found = 0;
        document.querySelectorAll('.faq-item').forEach(item => {
            const text = item.dataset.text || '';
            const match = text.includes(query);
            item.style.display = match ? '' : 'none';
            if (match) {
                found++;
                // Auto-expand matching accordion
                const collapse = item.querySelector('.accordion-collapse');
                if (collapse) {
                    new bootstrap.Collapse(collapse, { show: true });
                }
            }
        });

        // Hide empty sections
        document.querySelectorAll('.faq-section').forEach(section => {
            const visible = section.querySelectorAll('.faq-item:not([style*="none"])').length;
            section.style.display = visible > 0 ? '' : 'none';
        });

        noResultsMsg.style.display = found === 0 ? 'block' : 'none';
    }

    // Allow Enter key to trigger search
    document.getElementById('faq-search').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') searchFAQ();
        if (!this.value.trim()) searchFAQ(); // Reset on clear
    });

    function quickSearch(tag) {
        const input = document.getElementById('faq-search');
        if (input) {
            input.value = tag;
            searchFAQ();
            document.getElementById('faq-categories')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    /* ─── Help Centre form: char counter ─── */
    const hcDesc = document.getElementById('hc-description');
    const hcCtr  = document.getElementById('hc-char-count');
    if (hcDesc && hcCtr) {
        hcDesc.addEventListener('input', function() {
            const l = this.value.length;
            hcCtr.textContent = l + ' / 3000';
            hcCtr.style.color = l > 2700 ? '#dc3545' : l > 2400 ? '#fd7e14' : 'rgba(255,255,255,.4)';
        });
    }

    /* ─── Help Centre form: file upload ─── */
    const hcFi     = document.getElementById('hc-file-input');
    const hcDz     = document.getElementById('hc-drop-zone');
    const hcPreviews = document.getElementById('hc-file-previews');
    let hcFiles = [];

    if (hcFi) hcFi.addEventListener('change', () => hcHandleFiles(Array.from(hcFi.files)));
    if (hcDz) {
        hcDz.addEventListener('dragover', e => { e.preventDefault(); hcDz.classList.add('dragover'); });
        hcDz.addEventListener('dragleave', () => hcDz.classList.remove('dragover'));
        hcDz.addEventListener('drop', function(e) {
            e.preventDefault(); hcDz.classList.remove('dragover');
            hcHandleFiles(Array.from(e.dataTransfer.files));
        });
    }
    async function hcHandleFiles(files) {
        const allowed = ['image/jpeg','image/png','image/gif','image/webp','application/pdf'];
        const max = 10 * 1024 * 1024; let errs = [];
        for (let f of files) {
            if (hcFiles.length >= 5) { errs.push('Max 5 files allowed.'); break; }
            if (!allowed.includes(f.type)) { errs.push(f.name + ': invalid type.'); continue; }
            if (f.size > max) { errs.push(f.name + ': exceeds 10 MB.'); continue; }
            if (window.compressImageFile) f = await window.compressImageFile(f);
            if (!hcFiles.find(s => s.name === f.name && s.size === f.size)) hcFiles.push(f);
        }
        if (errs.length) alert(errs.join('\n'));
        hcRenderPreviews(); hcSyncFi();
    }
    function hcRenderPreviews() {
        if (!hcPreviews) return; hcPreviews.innerHTML = '';
        hcFiles.forEach((f, i) => {
            const d = document.createElement('div');
            d.className = 'hc-file-chip';
            const ico = f.type.startsWith('image/') ? 'fa-image' : 'fa-file-pdf';
            const sizeStr = f.originalSize
                ? `<span style="color:#20c997;font-weight:700;">⚡ ${(f.size/1024).toFixed(0)} KB <s style="opacity:.6;font-weight:400;color:#fff;">(${(f.originalSize/1024/1024).toFixed(1)} MB)</s></span>`
                : `<span style="opacity:.6;font-size:.72rem;">(${(f.size/1024).toFixed(0)} KB)</span>`;
            d.innerHTML = `<i class="fa-solid ${ico} text-brand fs-6"></i><span class="fw-semibold" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</span>${sizeStr}<i class="fa-solid fa-xmark remove-file ms-1" style="cursor:pointer;color:#dc3545;" onclick="hcRemFile(${i})"></i>`;
            hcPreviews.appendChild(d);
        });
    }
    function hcRemFile(i) { hcFiles.splice(i, 1); hcRenderPreviews(); hcSyncFi(); }
    function hcSyncFi() {
        const dt = new DataTransfer();
        hcFiles.forEach(f => dt.items.add(f));
        if (hcFi) hcFi.files = dt.files;
    }

    /* ─── 2-Tier Category & Topic Selection ─── */
    const catBtns = document.querySelectorAll('.hc-cat-btn');
    const bookingContainer = document.getElementById('hc-booking-select-container');
    const bookingSelect = document.getElementById('hc-booking-id');
    const groupBooking = document.getElementById('hc-group-booking');
    const groupGeneral = document.getElementById('hc-group-general');
    const issueInput = document.getElementById('hc-issue-type');
    const subjectInput = document.querySelector('input[name="subject"]');

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            catBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const cat = this.dataset.category;
            if (cat === 'booking') {
                if (bookingContainer) bookingContainer.classList.remove('d-none');
                if (groupBooking) groupBooking.classList.remove('d-none');
                if (groupGeneral) groupGeneral.classList.add('d-none');
                
                const firstBookingTopic = groupBooking?.querySelector('.topic-pill-btn');
                if (firstBookingTopic) {
                    groupBooking.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
                    firstBookingTopic.classList.add('active');
                    if (issueInput) issueInput.value = firstBookingTopic.dataset.value;
                }
            } else {
                if (bookingContainer) bookingContainer.classList.add('d-none');
                if (bookingInput) bookingInput.value = '';
                const triggerText = document.getElementById('booking-dropdown-text');
                if (triggerText) triggerText.innerHTML = '-- Select a booking --';
                if (groupBooking) groupBooking.classList.add('d-none');
                if (groupGeneral) groupGeneral.classList.remove('d-none');

                const firstGenTopic = groupGeneral?.querySelector('.topic-pill-btn');
                if (firstGenTopic) {
                    groupGeneral.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
                    firstGenTopic.classList.add('active');
                    if (issueInput) issueInput.value = firstGenTopic.dataset.value;
                }
            }
        });
    });

    // Custom Booking Dropdown logic
    const bookingTrigger = document.getElementById('booking-dropdown-trigger');
    const bookingMenu = document.getElementById('booking-dropdown-menu');
    const bookingInput = document.getElementById('hc-booking-id');
    const bookingText = document.getElementById('booking-dropdown-text');

    if (bookingTrigger && bookingMenu) {
        bookingTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('open');
            bookingMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!bookingTrigger.contains(e.target) && !bookingMenu.contains(e.target)) {
                bookingTrigger.classList.remove('open');
                bookingMenu.classList.remove('show');
            }
        });

        bookingMenu.querySelectorAll('.booking-dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const val = this.dataset.value;
                const num = this.dataset.number;
                const svc = this.dataset.service;

                if (bookingInput) bookingInput.value = val;
                if (bookingText) {
                    if (val) {
                        bookingText.style.color = '#ffffff';
                        bookingText.innerHTML = `<span class="fw-bold font-monospace text-brand">#${num}</span> &middot; ${svc}`;
                    } else {
                        bookingText.style.color = 'rgba(255,255,255,.6)';
                        bookingText.innerHTML = '-- Select a booking --';
                    }
                }
                if (val && subjectInput) {
                    subjectInput.value = `Booking #${num} - ${svc}`;
                }
                bookingTrigger.classList.remove('open');
                bookingMenu.classList.remove('show');
            });
        });
    }

    // Topic pills click behavior
    document.querySelectorAll('.topic-pill-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const parent = this.closest('.hc-topic-group');
            if (parent) {
                parent.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
            } else {
                document.querySelectorAll('.topic-pill-btn').forEach(b => b.classList.remove('active'));
            }
            this.classList.add('active');
            if (issueInput) issueInput.value = this.dataset.value;
        });
    });
</script>
@endsection
