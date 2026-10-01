<style>
    body, main { background-color: #0a0a0a !important; }

    .footer-custom { background-color: #0a0a0a !important; border-top: 1px solid rgba(255,255,255,.06) !important; }
    .footer-bottom { background-color: #050505 !important; }

    .breadcrumb-item.active { color: #9ca3af !important; }
    .breadcrumb-item a { color: #EC1F24 !important; }
    .breadcrumb-item + .breadcrumb-item::before { color: #6b7280 !important; }

    .acct-dark .card,
    .acct-dark .card-header.bg-white,
    .acct-dark .bg-white {
        background-color: #111 !important;
        border: 1px solid rgba(236,31,36,.15) !important;
        color: #e4e4e7;
    }
    .acct-dark .card-header { border-bottom: 1px solid rgba(255,255,255,.06) !important; }
    .acct-dark h1, .acct-dark h2, .acct-dark h3,
    .acct-dark h4, .acct-dark h5, .acct-dark h6 { color: #f4f4f5 !important; }
    .acct-dark p, .acct-dark label, .acct-dark .text-dark { color: #d4d4d8 !important; }
    .acct-dark .text-muted,
    .acct-dark .text-secondary,
    .acct-dark .text-body-secondary { color: #a1a1aa !important; }
    .acct-dark .text-body-emphasis { color: #f4f4f5 !important; }

    .acct-dark .form-control,
    .acct-dark .form-select,
    .acct-dark .bg-light {
        background-color: #1b1b1b !important;
        color: #f4f4f5 !important;
        border: 1px solid rgba(255,255,255,.10) !important;
    }
    .acct-dark .form-control::placeholder { color: #6b7280 !important; }
    .acct-dark .form-control:focus,
    .acct-dark .form-select:focus {
        background-color: #1b1b1b !important;
        border-color: #EC1F24 !important;
        box-shadow: 0 0 0 .2rem rgba(236,31,36,.20) !important;
        color: #f4f4f5 !important;
    }

    .acct-dark .border-bottom { border-color: rgba(255,255,255,.08) !important; }

    .acct-dark .list-group-item {
        background-color: transparent !important;
        color: #d4d4d8 !important;
        border-color: rgba(255,255,255,.06) !important;
    }
    .acct-dark .list-group-item-action:hover { background-color: #1c1c1c !important; color: #fff !important; }
    .acct-dark .list-group-item.active {
        background-color: #EC1F24 !important;
        border-color: #EC1F24 !important;
        color: #fff !important;
    }

    .acct-dark .text-brand { color: #EC1F24 !important; }
    .acct-dark .btn-brand { background-color: #EC1F24 !important; border-color: #EC1F24 !important; color: #fff !important; }
    .acct-dark .btn-brand:hover { background-color: #cc2a25 !important; border-color: #cc2a25 !important; }

    .acct-dark .rounded-circle.bg-light { background-color: #1b1b1b !important; }
    .acct-dark .bg-light { background-color: #1b1b1b !important; }
    .acct-dark .badge.text-dark,
    .acct-dark .badge.bg-warning,
    .acct-dark .badge.bg-info { color: #1a1a1a !important; }
    .acct-dark .alert h1, .acct-dark .alert h2, .acct-dark .alert h3,
    .acct-dark .alert h4, .acct-dark .alert h5, .acct-dark .alert h6,
    .acct-dark .alert p, .acct-dark .alert span { color: inherit !important; }

    .acct-dark .btn-light { background-color:#1b1b1b !important; border-color:rgba(255,255,255,.15) !important; color:#e4e4e7 !important; }
    .acct-dark .btn-light:hover { background-color:#262626 !important; color:#fff !important; }
    .acct-dark .btn-outline-secondary { color:#d4d4d8 !important; border-color:rgba(255,255,255,.22) !important; }
    .acct-dark .btn-outline-secondary:hover { background-color:#1c1c1c !important; color:#fff !important; border-color:rgba(255,255,255,.3) !important; }
    .acct-dark .btn-outline-secondary { color:#d4d4d8 !important; border-color:rgba(255,255,255,.25) !important; background-color:transparent !important; }
    .acct-dark .btn-outline-secondary:hover { background-color:#1c1c1c !important; color:#fff !important; border-color:rgba(255,255,255,.4) !important; }
    .acct-dark .btn-light, .acct-dark .btn-outline-light { background-color:#1b1b1b !important; border-color:rgba(255,255,255,.2) !important; color:#e4e4e7 !important; }
    .acct-dark .btn-light:hover, .acct-dark .btn-outline-light:hover { background-color:#262626 !important; color:#fff !important; }
    .acct-dark .slot-pill .badge.bg-white { background:rgba(255,255,255,.22) !important; color:#fff !important; }
    .acct-dark hr { border-color: rgba(255,255,255,.12) !important; }

    .acct-dark .modal-content { background:#141414 !important; color:#e4e4e7 !important; border:1px solid rgba(255,255,255,.08) !important; }
    .acct-dark .modal-header, .acct-dark .modal-footer { border-color: rgba(255,255,255,.08) !important; }

    .acct-dark .dropdown-menu { background:#1b1b1b !important; border-color: rgba(255,255,255,.1) !important; }
    .acct-dark .dropdown-item { color:#d4d4d8 !important; }
    .acct-dark .dropdown-item:hover, .acct-dark .dropdown-item:focus { background:#262626 !important; color:#fff !important; }

    .acct-dark .table { color:#e4e4e7 !important; }
    .acct-dark .table > :not(caption) > * > * { background:transparent !important; border-color: rgba(255,255,255,.08) !important; }

    .acct-dark .input-group-text { background:#1b1b1b !important; color:#d4d4d8 !important; border-color: rgba(255,255,255,.12) !important; }

    .acct-dark .page-link { background:#1b1b1b !important; border-color: rgba(255,255,255,.12) !important; color:#d4d4d8 !important; }
    .acct-dark .page-item.active .page-link { background:#EC1F24 !important; border-color:#EC1F24 !important; color:#fff !important; }
    .acct-dark .page-item.disabled .page-link { background:#141414 !important; color:#6b7280 !important; }

    .acct-dark .badge.bg-success-subtle { background: rgba(34,197,94,.18) !important; color:#86efac !important; }
    .acct-dark .badge.bg-info-subtle { background: rgba(13,110,253,.18) !important; color:#9ec5ff !important; }
    .acct-dark .badge.bg-warning-subtle { background: rgba(255,193,7,.18) !important; color:#ffd966 !important; }
    .acct-dark .badge.bg-danger-subtle { background: rgba(239,68,68,.18) !important; color:#fca5a5 !important; }
    .acct-dark .badge.bg-secondary-subtle { background: rgba(255,255,255,.1) !important; color:#cbd5e1 !important; }

    .acct-dark .btn-close { filter: invert(1) grayscale(1) brightness(1.6); }

    .acct-dark .list-group-item { background:transparent !important; }

    /* Big Tech / Enterprise SSO Button Styles */
    .btn-sso {
        background-color: #ffffff;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.7rem 1rem;
        border-radius: 0.6rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    .btn-sso:hover {
        background-color: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
        transform: translateY(-1.5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .btn-sso:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    .btn-sso .sso-icon {
        flex-shrink: 0;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-sso:hover .sso-icon {
        transform: scale(1.08);
    }

    /* Dark Mode Overrides */
    .acct-dark .btn-sso {
        background-color: #1a1a1c !important;
        color: #e2e8f0 !important;
        border: 1px solid rgba(255, 255, 255, 0.14) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4) !important;
    }
    /* Big Tech / Premium Action Buttons (Apple / Linear / Vercel style) */
    .btn-action-neutral {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        color: #e2e8f0 !important;
        font-weight: 600;
        font-size: 0.885rem;
        padding: 0.6rem 1.25rem;
        border-radius: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        text-decoration: none;
        cursor: pointer;
        backdrop-filter: blur(12px);
    }
    .btn-action-neutral:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.06) 100%) !important;
        border-color: rgba(255, 255, 255, 0.32) !important;
        color: #ffffff !important;
        transform: translateY(-1.5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
    .btn-action-neutral:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
    }

    .btn-action-reschedule {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.18) 0%, rgba(37, 99, 235, 0.08) 100%) !important;
        border: 1px solid rgba(96, 165, 250, 0.35) !important;
        color: #60a5fa !important;
        font-weight: 600;
        font-size: 0.885rem;
        padding: 0.6rem 1.35rem;
        border-radius: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.12);
        text-decoration: none;
        cursor: pointer;
        backdrop-filter: blur(12px);
    }
    .btn-action-reschedule:hover {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.28) 0%, rgba(37, 99, 235, 0.16) 100%) !important;
        border-color: rgba(96, 165, 250, 0.65) !important;
        color: #93c5fd !important;
        transform: translateY(-1.5px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.22);
    }
    .btn-action-reschedule:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);
    }
    .btn-action-reschedule i {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-action-reschedule:hover i {
        transform: rotate(-8deg) scale(1.12);
    }

    .btn-action-cancel {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.16) 0%, rgba(220, 38, 38, 0.06) 100%) !important;
        border: 1px solid rgba(248, 113, 113, 0.32) !important;
        color: #f87171 !important;
        font-weight: 600;
        font-size: 0.885rem;
        padding: 0.6rem 1.35rem;
        border-radius: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        text-decoration: none;
        cursor: pointer;
        backdrop-filter: blur(12px);
    }
    .btn-action-cancel:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.26) 0%, rgba(220, 38, 38, 0.14) 100%) !important;
        border-color: rgba(248, 113, 113, 0.6) !important;
        color: #fca5a5 !important;
        transform: translateY(-1.5px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    }
    .btn-action-cancel:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.15);
    }
    .btn-action-cancel i {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-action-cancel:hover i {
        transform: scale(1.12) rotate(8deg);
    }

    /* Premium Language Dropdown Styles */
    .custom-lang-btn {
        background: linear-gradient(145deg, #1c1c1e 0%, #141416 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        padding: 0.85rem 1.15rem !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.05) !important;
        cursor: pointer;
        outline: none !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .custom-lang-btn:hover {
        background: linear-gradient(145deg, #222226 0%, #1a1a1c 100%) !important;
        border-color: rgba(236, 31, 36, 0.5) !important;
        transform: translateY(-1.5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.45), 0 0 0 3px rgba(236, 31, 36, 0.15) !important;
    }
    .custom-lang-btn[aria-expanded="true"] {
        background: #1f1f23 !important;
        border-color: #EC1F24 !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5), 0 0 0 4px rgba(236, 31, 36, 0.2) !important;
    }
    .custom-lang-btn .lang-chevron {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #a1a1aa;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .custom-lang-btn:hover .lang-chevron {
        background: rgba(236, 31, 36, 0.15);
        border-color: rgba(236, 31, 36, 0.3);
        color: #ff4d4d;
    }
    .custom-lang-btn[aria-expanded="true"] .lang-chevron {
        transform: rotate(180deg);
        background: #EC1F24;
        color: #ffffff;
        border-color: #EC1F24;
    }

    .custom-lang-menu {
        background: rgba(20, 20, 23, 0.96) !important;
        backdrop-filter: blur(20px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        margin-top: 8px !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
        z-index: 1060 !important;
        animation: dropdownFadeScale 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes dropdownFadeScale {
        0% { opacity: 0; transform: translateY(-8px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    .lang-option-card {
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .lang-option-card:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.1);
        transform: translateX(4px);
    }
    .lang-option-card.active {
        background: linear-gradient(135deg, rgba(229, 50, 45, 0.18) 0%, rgba(229, 50, 45, 0.06) 100%) !important;
        border: 1px solid rgba(229, 50, 45, 0.45) !important;
        box-shadow: 0 4px 15px rgba(229, 50, 45, 0.12);
    }
    .lang-option-card .lang-empty-circle {
        width: 22px;
        height: 22px;
        border: 1.5px solid rgba(255, 255, 255, 0.2);
        transition: all 0.2s ease;
    }
    .lang-option-card:hover .lang-empty-circle {
        border-color: rgba(255, 255, 255, 0.5);
        background: rgba(255, 255, 255, 0.08);
    }
    .lang-option-card.active .lang-status-indicator {
        width: 26px;
        height: 26px;
        background: rgba(229, 50, 45, 0.2);
        border: 1px solid rgba(229, 50, 45, 0.5);
        color: #ff4d4d;
    }

    /* ============================================================
       MOBILE-SPECIFIC ACCOUNT PAGE OVERRIDES (< 768px)
       Makes account pages feel like a native app, not a website
    ============================================================ */
    @media (max-width: 767.98px) {
        /* Tighten up the breadcrumb on mobile */
        .breadcrumb { font-size: 0.75rem; margin-bottom: 0 !important; }
        nav[aria-label="breadcrumb"] { padding: 6px 0 0 0 !important; }

        /* Header cards (dark bg cards at top of each account page) */
        .acct-dark .card.bg-dark h2,
        .acct-dark .card[style*="background"] h2,
        .acct-dark .card.bg-dark .fw-bold {
            font-size: 1.15rem !important;
        }
        .acct-dark .card.bg-dark p,
        .acct-dark .card.bg-dark .text-white-50 {
            font-size: 0.78rem !important;
        }
        /* Reduce header card padding on mobile */
        .acct-dark .card.bg-dark.p-4,
        .acct-dark .card.p-4.bg-dark {
            padding: 16px !important;
        }
        .acct-dark .card.bg-dark.rounded-4.mb-4 {
            border-radius: 16px !important;
            margin-bottom: 14px !important;
        }

        /* Action buttons in booking-show top row — stack on mobile */
        .acct-dark .btn-action-neutral,
        .acct-dark .btn-action-reschedule,
        .acct-dark .btn-action-cancel {
            font-size: 0.8rem !important;
            padding: 0.5rem 1rem !important;
        }

        /* Inner card body padding */
        .acct-dark .card-body.p-4 { padding: 16px !important; }
        .acct-dark .card-header.px-4 { padding-left: 16px !important; padding-right: 16px !important; }

        /* Table text smaller on mobile */
        .acct-dark .table { font-size: 0.82rem; }

        /* btn-brand pill in header */
        .acct-dark .btn-brand.rounded-pill {
            padding: 8px 16px !important;
            font-size: 0.82rem !important;
        }

        /* Tracking stepper font + spacing */
        .acct-dark .card-body .small { font-size: 0.78rem !important; }
    }
</style>
