@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
<style>
    /* ========== REWARDS PAGE STYLES ========== */
    .rewards-hero {
        background: linear-gradient(135deg, #1a1b20 0%, #2d1f3d 50%, #1a1b20 100%);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    .rewards-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(236,31,36,0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Membership Card */
    .membership-card {
        background: linear-gradient(135deg, #1a1b20 0%, #2d2d35 100%);
        border-radius: 1.25rem;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        min-height: 200px;
        height: 100%;
    }

    /* Flip Card Styles */
    .membership-flip-container {
        perspective: 1000px;
        width: 100%;
    }
    .membership-flipper {
        transition: transform 0.6s cubic-bezier(0.4, 0.2, 0.2, 1);
        transform-style: preserve-3d;
        position: relative;
        width: 100%;
    }
    .membership-flip-container.flipped .membership-flipper {
        transform: rotateY(180deg);
    }
    .membership-flip-container:hover:not(.flipped) .membership-flipper {
        transform: translateY(-4px);
    }
    .membership-flip-container.flipped:hover .membership-flipper {
        transform: rotateY(180deg) translateY(-4px);
    }
    .membership-front, .membership-back {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        width: 100%;
        border-radius: 1.25rem;
    }
    .membership-back {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        transform: rotateY(180deg);
    }
    .membership-card.bronze { border: 1px solid rgba(205,127,50,0.4); }
    .membership-card.bronze::after {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(205,127,50,0.15) 0%, transparent 70%);
    }
    .membership-card.silver { border: 1px solid rgba(192,192,192,0.4); }
    .membership-card.silver::after {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(192,192,192,0.15) 0%, transparent 70%);
    }
    .membership-card.gold { border: 1px solid rgba(255,215,0,0.5); }
    .membership-card.gold::after {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(255,215,0,0.2) 0%, transparent 70%);
    }

    .tier-badge-bronze { background: linear-gradient(135deg, #cd7f32, #8b5a2b); }
    .tier-badge-silver { background: linear-gradient(135deg, #c0c0c0, #7a7a7a); }
    .tier-badge-gold   { background: linear-gradient(135deg, #ffd700, #b8860b); }

    .points-display {
        font-size: 3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        text-shadow: 0 0 30px rgba(236,31,36,0.4);
    }

    /* ========== TECH GIANT FLAGSHIP CHECK-IN CARD ========== */
    .checkin-card {
        background: rgba(22, 24, 30, 0.88) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 1.5rem;
        box-shadow: 0 12px 36px -10px rgba(0, 0, 0, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .checkin-card:hover { 
        transform: translateY(-3px);
        border-color: rgba(255, 255, 255, 0.18) !important;
        box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.6);
    }

    /* Squircle Day Items */
    .streak-dot {
        width: 38px; height: 38px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .streak-dot.active {
        background: linear-gradient(135deg, #FF2A43 0%, #D80F29 100%);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        box-shadow: 0 4px 14px rgba(216, 15, 41, 0.45),
                    inset 0 1px 1px rgba(255, 255, 255, 0.35);
    }
    .streak-dot.today {
        background: rgba(255, 42, 67, 0.15);
        border: 1px solid rgba(255, 42, 67, 0.6);
        color: #FF2A43;
        box-shadow: 0 0 16px rgba(255, 42, 67, 0.35);
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0%, 100% { box-shadow: 0 0 10px rgba(255, 42, 67, 0.3); border-color: rgba(255, 42, 67, 0.6); }
        50% { box-shadow: 0 0 20px rgba(255, 42, 67, 0.6); border-color: rgba(255, 42, 67, 0.9); }
    }

    .btn-checkin {
        background: linear-gradient(135deg, #FF2A43 0%, #D80F29 100%);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        padding: 0.85rem 1.5rem;
        border-radius: 50px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(216, 15, 41, 0.45);
    }
    .btn-checkin:hover:not(:disabled) {
        background: linear-gradient(135deg, #ff3b53 0%, #e6172e 100%);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(216, 15, 41, 0.6);
    }
    .btn-checkin:disabled {
        background: rgba(255, 255, 255, 0.06);
        color: #64748b;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: none;
        cursor: not-allowed;
    }

    /* Mission Cards */
    .mission-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .mission-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    .mission-card.completed {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-color: rgba(34,197,94,0.3);
    }
    .mission-card.completed::after {
        content: '✓';
        position: absolute;
        top: 12px; right: 12px;
        width: 24px; height: 24px;
        background: #22c55e;
        color: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .mission-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* ========== TECH GIANT FLAGSHIP SPIN WHEEL ========== */
    .spin-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0;
    }
    #spinWheel {
        width: 280px;
        height: 280px;
        border-radius: 50%;
        border: 8px solid rgba(22, 24, 32, 0.95);
        box-shadow: 0 0 50px rgba(255, 42, 67, 0.25), 
                    inset 0 0 25px rgba(0, 0, 0, 0.8),
                    0 10px 30px rgba(0, 0, 0, 0.6);
        transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
        position: relative;
    }
    .spin-arrow {
        position: absolute;
        top: 8px;
        left: 50%;
        transform: translateX(-50%) rotate(0deg);
        transform-origin: 50% 7px;
        width: 30px;
        height: 42px;
        z-index: 15;
        pointer-events: none;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.8)) drop-shadow(0 0 12px rgba(255, 42, 67, 0.8));
    }
    .prize-showcase-card:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.07) !important;
        border-color: rgba(255, 42, 67, 0.4) !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
    }
    .spin-center {
        position: absolute;
        width: 58px; height: 58px;
        background: linear-gradient(135deg, #1e222d, #111318);
        border-radius: 50%;
        border: 3px solid #FF2A43;
        z-index: 5;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 0.68rem;
        letter-spacing: 1px;
        box-shadow: 0 0 25px rgba(255, 42, 67, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.2);
    }
    .btn-spin {
        background: linear-gradient(135deg, #FF2A43 0%, #D80F29 100%);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-size: 1.05rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(216, 15, 41, 0.45);
    }
    .btn-spin:hover:not(:disabled) {
        background: linear-gradient(135deg, #ff3b53 0%, #e6172e 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(216, 15, 41, 0.65);
        color: #fff;
    }
    .btn-spin:disabled {
        background: rgba(255, 255, 255, 0.06);
        color: #64748b;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: none;
        cursor: not-allowed;
    }

    /* Voucher Catalogue */
    .voucher-item {
        background: linear-gradient(135deg, #fff8f0, #fff);
        border: 2px dashed rgba(236,31,36,0.3);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .voucher-item:hover:not(.disabled) {
        border-color: #EC1F24;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(236,31,36,0.15);
    }
    .voucher-item.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        filter: grayscale(0.5);
    }
    .voucher-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.75);
        z-index: 10; backdrop-filter: blur(2px);
        border-radius: 1rem;
    }

    /* Progress Bar */
    .tier-progress-bar {
        height: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 100px;
        overflow: hidden;
    }
    .tier-progress-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 1.5s ease;
    }
    .bronze .tier-progress-fill { background: linear-gradient(90deg, #cd7f32, #e8a87c); }
    .silver .tier-progress-fill { background: linear-gradient(90deg, #c0c0c0, #e8e8e8); }
    .gold .tier-progress-fill   { background: linear-gradient(90deg, #ffd700, #ffe566); }

    /* Dark section */
    .section-dark {
        background: rgba(22, 24, 30, 0.88) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 1.5rem;
        box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.5);
    }

    /* Promo code input */
    .promo-input {
        background: rgba(255,255,255,0.07);
        border: 1.5px solid rgba(255,255,255,0.15);
        color: #fff;
        border-radius: 12px 0 0 12px;
        padding: 0.875rem 1.25rem;
        font-size: 1rem;
        letter-spacing: 2px;
        font-weight: 600;
    }
    .promo-input::placeholder { color: rgba(255,255,255,0.3); letter-spacing: 1px; }
    .promo-input:focus {
        background: rgba(255,255,255,0.1);
        border-color: rgba(236,31,36,0.6);
        color: #fff;
        box-shadow: none;
    }

    /* Section headings */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    /* Transaction list */
    .tx-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .tx-row:last-child { border-bottom: none; }
    .tx-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    /* Spin result overlay */
    .spin-result-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
    }
    .spin-result-card {
        background: linear-gradient(135deg, #1a1b20, #2d2d35);
        border-radius: 1.5rem;
        padding: 3rem;
        text-align: center;
        max-width: 380px;
        width: 90%;
        border: 1px solid rgba(255,255,255,0.1);
        animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes popIn {
        from { transform: scale(0.5); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }

    /* ========== FLAGSHIP TECH-GIANT HUD METRIC BAR ========== */
    .zus-stats-hud {
        display: inline-flex;
        align-items: center;
        background: rgba(18, 20, 28, 0.82);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 60px;
        padding: 0.45rem 1.1rem;
        box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.7),
                    inset 0 1px 1px rgba(255, 255, 255, 0.18);
        gap: 0.8rem;
    }
    .zus-stat-card {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    .stat-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #ffffff;
        flex-shrink: 0;
    }
    .badge-flame {
        background: linear-gradient(135deg, #FF512F 0%, #F09819 100%);
        box-shadow: 0 4px 14px rgba(255, 81, 47, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .badge-mission {
        background: linear-gradient(135deg, #4776E6 0%, #8E54E9 100%);
        box-shadow: 0 4px 14px rgba(71, 118, 230, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .stat-content {
        text-align: left;
    }
    .stat-value {
        font-family: 'Outfit', 'Inter', -apple-system, sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1;
        letter-spacing: 0.3px;
    }
    .stat-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: rgba(255, 255, 255, 0.55);
        margin-top: 3px;
    }
    .stat-divider {
        width: 1px;
        height: 32px;
        background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.22), transparent);
    }

    /* ========== TECH GIANT FLAGSHIP NAVIGATION BAR ========== */
    #zusRewardsTab {
        background: rgba(22, 24, 30, 0.88) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        padding: 0.4rem !important;
        border-radius: 60px !important;
        box-shadow: 0 12px 36px -10px rgba(0, 0, 0, 0.6), 
                    inset 0 1px 0 rgba(255, 255, 255, 0.12) !important;
        display: inline-flex;
        gap: 0.35rem;
        position: relative;
        z-index: 10;
        max-width: 100%;
        overflow-x: auto;
    }
    #zusRewardsTab::-webkit-scrollbar { display: none; }
    
    #zusRewardsTab .nav-item {
        margin: 0;
    }
    
    #zusRewardsTab .nav-link {
        background: transparent !important;
        color: #94a3b8 !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        letter-spacing: 0.3px;
        padding: 0.75rem 1.8rem !important;
        border-radius: 50px !important;
        border: 1px solid transparent !important;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        white-space: nowrap;
        position: relative;
    }
    
    #zusRewardsTab .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #f8fafc !important;
        transform: translateY(-1px);
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    
    #zusRewardsTab .nav-link.active {
        background: linear-gradient(135deg, #FF2A43 0%, #D80F29 100%) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 6px 20px rgba(216, 15, 41, 0.5),
                    inset 0 1px 1px rgba(255, 255, 255, 0.4) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        transform: translateY(-2px);
    }

    #zusRewardsTab .nav-link .tab-icon {
        font-size: 1.05rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        opacity: 0.75;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #zusRewardsTab .nav-link:hover .tab-icon {
        opacity: 1;
        transform: translateY(-1px);
    }
    #zusRewardsTab .nav-link.active .tab-icon {
        opacity: 1;
        transform: scale(1.1);
    }

    #zusRewardsTab .nav-link .nav-badge {
        background: rgba(255, 255, 255, 0.14) !important;
        color: #e2e8f0 !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        padding: 0.25em 0.7em !important;
        border-radius: 30px !important;
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.3s ease;
        line-height: 1.2;
    }
    #zusRewardsTab .nav-link.active .nav-badge {
        background: #ffffff !important;
        color: #D80F29 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25) !important;
        border-color: #ffffff !important;
        font-weight: 800 !important;
    }
    #zusRewardsTab .nav-link:hover:not(.active) .nav-badge {
        background: rgba(255, 42, 67, 0.25) !important;
        color: #ff8093 !important;
        border-color: rgba(255, 42, 67, 0.4);
    }

    /* Secondary Voucher Sub-tabs */
    #voucherSubTabs {
        background: rgba(26, 28, 35, 0.75) !important;
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        padding: 0.3rem !important;
        border-radius: 40px !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.3) !important;
    }
    #voucherSubTabs .nav-link {
        background: transparent !important;
        color: #94a3b8 !important;
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        padding: 0.55rem 1.4rem !important;
        border-radius: 30px !important;
        transition: all 0.3s ease !important;
    }
    #voucherSubTabs .nav-link:hover:not(.active) {
        color: #f8fafc !important;
        background: rgba(255, 255, 255, 0.06) !important;
    }
    #voucherSubTabs .nav-link.active {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
    }
    #voucherSubTabs .nav-link .badge {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
    }
    #voucherSubTabs .nav-link.active .badge {
        background: #FF2A43 !important;
        color: #fff !important;
    }
    
    @media (prefers-color-scheme: dark) {
        .mission-card:not(.completed) { background: #1e293b; border-color: rgba(255,255,255,0.1); }
        .mission-card.completed { background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.4); }
        .voucher-item { background: linear-gradient(135deg, #1e1b2e, #1e293b); }
        .tx-row { border-color: rgba(255,255,255,0.05); }
    }

    [data-bs-theme="dark"] .mission-card:not(.completed) { background: #1e293b; border-color: rgba(255,255,255,0.1); }
    [data-bs-theme="dark"] .mission-card.completed { background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.4); }
    [data-bs-theme="dark"] .mission-card { color: #fff; }
    [data-bs-theme="dark"] .voucher-item { background: linear-gradient(135deg, #1e1b2e, #1e293b); }
    [data-bs-theme="dark"] .voucher-overlay { background: rgba(15, 23, 42, 0.75); }
    [data-bs-theme="dark"] .tx-row { border-color: rgba(255,255,255,0.05); }

    /* Voucher Card Styles for Tab 3 */
    .voucher-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        border: 1px solid rgba(0,0,0,0.08);
    }
    .voucher-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .voucher-card .voucher-notch-left,
    .voucher-card .voucher-notch-right {
        position: absolute;
        top: 50%;
        width: 20px;
        height: 20px;
        background: #f8f9fa;
        border-radius: 50%;
        transform: translateY(-50%);
        z-index: 2;
    }
    .voucher-card .voucher-notch-left { left: -10px; }
    .voucher-card .voucher-notch-right { right: -10px; }

    .voucher-left {
        background: linear-gradient(135deg, #EC1F24, #c81519);
        min-width: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 1.25rem 0.75rem;
        border-right: 2px dashed rgba(255,255,255,0.3);
    }
    .voucher-left .value-text {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .voucher-left .unit-text {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.8);
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .voucher-right {
        padding: 1.25rem 1.5rem;
        flex: 1;
        background: #fff;
        position: relative;
    }
    .voucher-status-corner {
        position: absolute;
        top: 14px;
        right: 14px;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        z-index: 3;
    }
    .voucher-top-info {
        padding-right: 110px;
    }
    .voucher-used .voucher-left { background: linear-gradient(135deg, #6b7280, #4b5563); }
    .voucher-used .voucher-right { background: #f9fafb; }
    .voucher-expired .voucher-left { background: linear-gradient(135deg, #ef4444, #b91c1c); opacity: 0.6; }
    .voucher-expired .voucher-right { background: #fef2f2; opacity: 0.7; }

    .voucher-code {
        font-family: monospace;
        font-size: 0.85rem;
        background: #f3f4f6;
        padding: 4px 10px;
        border-radius: 6px;
        color: #374151;
        font-weight: 700;
        letter-spacing: 1px;
    }
    [data-bs-theme="dark"] .voucher-right { background: #1e293b; }
    [data-bs-theme="dark"] .voucher-code { background: #374151; color: #e2e8f0; }
    [data-bs-theme="dark"] .voucher-card .voucher-notch-left,
    [data-bs-theme="dark"] .voucher-card .voucher-notch-right { background: #0f172a; }

    /* ZUS Style Circular Dial */
    .zus-dial-container {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto;
    }
    .zus-dial-ring {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }
    .zus-dial-bg {
        fill: none;
        stroke: rgba(255,255,255,0.1);
        stroke-width: 10;
    }
    .zus-dial-progress {
        fill: none;
        stroke-width: 10;
        stroke-linecap: round;
        transition: stroke-dashoffset 1.5s ease;
    }
    .zus-dial-center {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .acct-dark .mission-card { background:#111 !important; border-color: rgba(229,50,45,.15) !important; color:#e4e4e7 !important; }
    .acct-dark .mission-card.completed { background: rgba(34,197,94,0.12) !important; border-color: rgba(34,197,94,0.4) !important; }
    .acct-dark .voucher-card .voucher-notch-left,
    .acct-dark .voucher-card .voucher-notch-right { background:#0a0a0a !important; }
    .acct-dark .voucher-right { background:#1b1b1b !important; color:#e4e4e7 !important; }
    .acct-dark .voucher-used .voucher-right { background:#161616 !important; }
    .acct-dark .voucher-expired .voucher-right { background:#241313 !important; }
    .acct-dark .voucher-code { background:#2a2a2a !important; color:#e2e8f0 !important; }
    .acct-dark .voucher-item .text-body-emphasis { color: #000 !important; }
    .acct-dark .voucher-item .text-muted { color: #333 !important; }
    .acct-dark .membership-card .bg-white { background:#fff !important; }
    .acct-dark .user-status-bar, [data-bs-theme="dark"] .user-status-bar { background: #161616 !important; border-color: rgba(255,255,255,0.08) !important; }
    .acct-dark .user-status-bar .status-bar-divider, [data-bs-theme="dark"] .user-status-bar .status-bar-divider { border-color: rgba(255,255,255,0.08) !important; }

    /* ===== MOBILE-SPECIFIC REWARDS OVERHAUL ===== */
    @media (max-width: 767.98px) {
        /* ---- Hero: compact horizontal layout ---- */
        /* ---- Hero: compact vertical dashboard stack on mobile ---- */
        .rewards-hero { padding: 1.25rem !important; border-radius: 1.25rem !important; }
        .mob-hero-row { display: flex !important; flex-direction: column !important; align-items: stretch !important; gap: 1.15rem !important; width: 100% !important; }
        .mob-hero-left { width: 100% !important; flex: none !important; }
        .mob-hero-right { width: 100% !important; flex: none !important; }

        /* Membership card compact */
        .membership-card { min-height: 165px !important; padding: 1.25rem !important; border-radius: 1.15rem !important; }
        .points-display { font-size: 2.1rem !important; }
        .mob-hero-left .tier-progress-bar { display: block !important; margin-top: 6px !important; }

        /* Tap-to-flip hint: smaller and inline */
        .mob-flip-hint { font-size: 0.72rem !important; margin-top: 0.5rem !important; }

        /* Dial: compact */
        .zus-dial-container { width: 78px !important; height: 78px !important; margin: 0 !important; }
        .mob-hero-right .points-display { font-size: 1.4rem !important; }

        /* Stats HUD: compact pill side-by-side */
        .zus-stats-hud {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            border-radius: 14px !important;
            padding: 0.4rem 0.5rem !important;
            gap: 0.35rem !important;
            justify-content: space-around !important;
            margin: 0 !important;
            overflow: hidden !important;
        }
        .zus-stat-card { padding: 0.15rem 0.25rem; gap: 0.35rem; }
        .stat-icon-badge { width: 26px; height: 26px; font-size: 0.75rem; border-radius: 7px; flex-shrink: 0; }
        .stat-value { font-size: 0.95rem !important; }
        .stat-label { font-size: 0.56rem !important; letter-spacing: 0.5px !important; }
        .stat-divider { height: 24px; flex-shrink: 0; }

        /* Missions-heading hide on mobile (shown in hero already) */
        .mob-hide-hero-heading { display: none !important; }

        /* ---- Sticky Tab Bar ---- */
        #zusRewardsTab-wrap {
            position: sticky;
            top: 56px;
            z-index: 100;
            background: rgba(10, 10, 14, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 0.5rem 12px;
            margin-left: -12px;
            margin-right: -12px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        #zusRewardsTab-wrap::-webkit-scrollbar { display: none; }
        /* Auto-fit tabs: fill 100% width and distribute equally */
        #zusRewardsTab {
            width: 100% !important;
            border-radius: 40px !important;
            padding: 0.25rem !important;
            flex-wrap: nowrap !important;
            display: flex !important;
        }
        #zusRewardsTab .nav-item {
            flex: 1 1 0% !important;
            min-width: 0 !important;
        }
        #zusRewardsTab .nav-link {
            width: 100% !important;
            padding: 0.55rem 0.25rem !important;
            font-size: 0.78rem !important;
            white-space: nowrap !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        /* Always show label text — no icon-only mode */
        #zusRewardsTab .nav-link .nav-tab-label { display: inline; }  /* don't override Bootstrap d-none etc. */
        /* Hide icons on mobile to save space */
        #zusRewardsTab .nav-link .tab-icon { display: none !important; }

        /* ---- Streak dots: use flex-wrap + smaller dots ---- */
        .streak-dots-row { flex-wrap: nowrap !important; gap: 4px !important; }
        .streak-dot { width: 34px !important; height: 34px !important; border-radius: 10px !important; font-size: 0.68rem !important; }
        .streak-dot-wrap .mt-2 { font-size: 0.6rem !important; margin-top: 4px !important; }

        /* ---- Checkin card: tighten padding ---- */
        .checkin-card { padding: 1.1rem !important; border-radius: 1rem !important; }

        /* ---- Mission cards: single column, compact ---- */
        .mission-card { padding: 0.9rem !important; }
        .mission-icon { width: 38px !important; height: 38px !important; font-size: 1rem !important; }

        /* ---- Voucher left column: narrower ---- */
        .voucher-left { min-width: 80px !important; padding: 0.75rem 0.5rem !important; }
        .voucher-left .value-text { font-size: 1.25rem !important; }
        .voucher-right { padding: 0.85rem 1rem !important; }
        .voucher-status-corner { top: 10px !important; right: 10px !important; }
        .voucher-top-info { padding-right: 100px !important; }

        /* ---- Spin wheel & section: neat mobile layout ---- */
        .spin-section { padding: 1.25rem !important; border-radius: 1rem !important; }
        #spinWheel { width: 230px !important; height: 230px !important; margin: 0 auto; display: block; }
        .spin-container { padding-top: 16px !important; padding-bottom: 8px !important; }
        .btn-spin { border-radius: 14px !important; font-size: 1rem !important; padding: 0.85rem 1.2rem !important; }
        .prize-showcase-card { padding: 0.55rem 0.65rem !important; }

        /* ---- Promo input: stack vertically ---- */
        .promo-input-group { flex-direction: column !important; }
        .promo-input { border-radius: 12px !important; }
        .promo-input-group .btn { border-radius: 12px !important; width: 100%; margin-top: 0.5rem; }

        /* ---- TRB Rewards Catalogue: sleek horizontal ticket cards ---- */
        .catalogue-card-body { padding: 1rem !important; }
        .catalogue-coupon-item { padding: 0.85rem !important; border-radius: 14px !important; }
        .catalogue-coupon-icon { width: 44px !important; height: 44px !important; font-size: 1.15rem !important; }

        /* ---- User Status Bar (Redeem tab header): clean 2-row dashboard ---- */
        .user-status-bar { padding: 0.95rem !important; border-radius: 1.25rem !important; gap: 0.75rem !important; width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; overflow: hidden !important; }
        .status-points-val { font-size: 1.22rem !important; }
        .status-voucher-btn { font-size: 0.82rem !important; padding: 0.65rem 1.1rem !important; }
        .status-bar-divider { border-top: 1px solid rgba(255,255,255,0.08) !important; padding-top: 0.85rem !important; margin-top: 0.25rem !important; }
    }
</style>
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.breadcrumb_rewards') => null]" />
@endsection

@section('content')
<div class="container my-3 my-md-5 acct-dark">
    @include('partials.birthday-banner')

    {{-- ==================== HERO + MEMBERSHIP CARD ==================== --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="rewards-hero p-4 p-md-5">

                {{-- Pre-compute dial values for both mobile and desktop --}}
                @php
                    $dialPct = min($membership->reward_points / 10000, 1);
                    $circumference = 2 * pi() * 42;
                    $dashoffset = $circumference * (1 - $dialPct);
                @endphp

                {{-- ===== MOBILE COMPACT HERO (< 768px): side-by-side ===== --}}
                <div class="d-flex d-md-none mob-hero-row">
                    {{-- Top Header Bar: Greeting + Full Tiers Button --}}
                    <div class="d-flex justify-content-between align-items-center w-100 mb-1">
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="badge tier-badge-{{ $membership->tier }} text-white px-2.5 py-1 rounded-pill text-uppercase fw-bold d-inline-flex align-items-center" style="font-size:0.68rem;letter-spacing:0.5px;">
                                <i class="fa-solid {{ $membership->getTierIcon() }} me-1"></i>
                                {{ $membership->getTierLabel() }} {{ __('rewards.idx_club') }}
                            </span>
                        </div>
                        <a href="{{ route('rewards.tiers') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" style="font-size:0.75rem;">
                            <i class="fa-solid fa-gem text-warning"></i>
                            <span>{{ __('rewards.idx_tiers_benefits') }}</span>
                        </a>
                    </div>

                    {{-- Center: Full-Width Membership Flip Card --}}
                    <div class="mob-hero-left w-100">
                        <div class="membership-flip-container w-100" onclick="this.classList.toggle('flipped')">
                            <div class="membership-flipper">
                                <div class="membership-front">
                                    <div class="membership-card {{ $membership->tier }} w-100 shadow-lg">
                                        <div class="d-flex justify-content-between align-items-start mb-2" style="position:relative;z-index:1;">
                                            <span class="badge tier-badge-{{ $membership->tier }} text-white px-2 py-1 rounded-pill text-uppercase fw-bold d-inline-flex align-items-center" style="font-size:0.65rem;letter-spacing:0.5px;">
                                                <i class="fa-solid {{ $membership->getTierIcon() }} me-1"></i>
                                                {{ $membership->getTierLabel() }}
                                            </span>
                                            <div class="text-white-50 font-monospace fw-bold" style="font-size:0.75rem;letter-spacing:1px;">{{ $membership->membership_id }}</div>
                                        </div>
                                        <div class="my-3" style="position:relative;z-index:1;">
                                            <div class="text-white-50 text-uppercase fw-semibold" style="font-size:0.62rem;letter-spacing:1px;">{{ __('rewards.idx_rewards_balance') }}</div>
                                            <div class="d-flex align-items-baseline gap-1.5">
                                                <span class="points-display text-white fw-bold" style="font-size:2.1rem !important;line-height:1;">{{ number_format($membership->reward_points) }}</span>
                                                <span class="text-white-50 fw-bold" style="font-size:0.78rem;">{{ __('rewards.idx_pts') }}</span>
                                            </div>
                                        </div>
                                        {{-- Compact progress --}}
                                        @if($membership->tier !== 'gold')
                                        <div class="mt-auto" style="position:relative;z-index:1;">
                                            <div class="d-flex justify-content-between align-items-center" style="font-size:0.65rem;color:rgba(255,255,255,0.6);margin-bottom:4px;">
                                                <span class="fw-semibold">{{ __('rewards.idx_next_tier', ['tier' => $membership->getNextTierLabel()]) }}</span>
                                                <span class="fw-bold text-white">{{ $membership->getTierProgress() }}%</span>
                                            </div>
                                            <div class="tier-progress-bar bg-white bg-opacity-25" style="height:5px;border-radius:10px;overflow:hidden;">
                                                <div class="tier-progress-fill bg-white" style="width:{{ $membership->getTierProgress() }}%;height:100%;border-radius:10px;"></div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="mt-auto" style="position:relative;z-index:1;">
                                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" style="font-size:0.65rem;"><i class="fa-solid fa-crown me-1"></i>{{ __('rewards.idx_vip_max') }}</span>
                                        </div>
                                        @endif
                                        <div class="mt-2 pt-1 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center" style="position:relative;z-index:1;font-size:0.62rem;color:rgba(255,255,255,0.65);">
                                            <span><i class="fa-solid fa-star me-1 text-warning"></i><strong>{{ $membership->getMultiplier() }}×</strong> {{ __('rewards.idx_multiplier') }}</span>
                                            <span class="text-white-50"><i class="fa-solid fa-qrcode me-1"></i>{{ __('rewards.idx_show_qr') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="membership-back">
                                    <div class="membership-card {{ $membership->tier }} d-flex flex-column align-items-center justify-content-center text-center w-100 shadow-lg">
                                        <div class="bg-white p-2 rounded-4 mb-2 shadow" style="position:relative;z-index:1;">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode(URL::temporarySignedRoute('admin.memberships.scan_resolve', now()->addSeconds(60), ['user' => $user->id])) }}" alt="QR" width="110" height="110">
                                        </div>
                                        <div class="text-white fw-bold font-monospace mb-1" style="font-size:0.85rem;position:relative;z-index:1;letter-spacing:1px;">{{ $membership->membership_id }}</div>
                                        <div class="text-white-50" style="font-size:0.65rem;position:relative;z-index:1;"><i class="fa-solid fa-counter me-1"></i>{{ __('rewards.idx_scan_qr') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-2 text-white-50 mob-flip-hint" style="cursor:pointer;font-size:0.68rem;" onclick="document.querySelector('.membership-flip-container').classList.toggle('flipped')">
                            <i class="fa-solid fa-arrows-rotate me-1 text-brand"></i>{{ __('rewards.idx_tap_flip') }}
                        </div>
                    </div>

                    {{-- Bottom: Dial + Stats Console stacked across 100% width --}}
                    <div class="mob-hero-right w-100 mt-1">
                        <div class="d-flex flex-column gap-2 w-100">
                            {{-- Row 1 (Full 100% width): Dial progress + Points Balance --}}
                            <div class="d-flex align-items-center justify-content-center gap-3.5 p-3 rounded-4 bg-dark bg-opacity-60 border border-light border-opacity-10 shadow-sm w-100">
                                <div class="zus-dial-container flex-shrink-0" style="width:68px;height:68px;margin:0;">
                                    <svg class="zus-dial-ring" viewBox="0 0 100 100">
                                        <defs>
                                            <linearGradient id="goldGradientMob" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#FFD700" />
                                                <stop offset="100%" stop-color="#FF8C00" />
                                            </linearGradient>
                                        </defs>
                                        <circle class="zus-dial-bg" cx="50" cy="50" r="42" />
                                        <circle class="zus-dial-progress" cx="50" cy="50" r="42"
                                            stroke="url(#goldGradientMob)"
                                            stroke-dasharray="{{ $circumference }}"
                                            stroke-dashoffset="{{ $dashoffset }}" />
                                    </svg>
                                    <div class="zus-dial-center">
                                        <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:28px;height:28px;font-size:0.75rem;">
                                            <i class="fa-solid fa-car"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <div class="text-white fw-bold mb-0" style="font-size:1.4rem;line-height:1.1;">{{ number_format($membership->reward_points) }}</div>
                                    <div class="text-white-50 fw-bold text-uppercase" style="font-size:0.62rem;letter-spacing:1px;">{{ __('rewards.idx_trb_balance') }}</div>
                                </div>
                            </div>

                            {{-- Row 2 (Full 100% width): Streak & Done HUD --}}
                            <div class="zus-stats-hud w-100 d-flex justify-content-around align-items-center p-3 rounded-4 bg-dark bg-opacity-60 border border-light border-opacity-10 shadow-sm m-0">
                                <div class="zus-stat-card d-flex align-items-center justify-content-center gap-2 flex-fill m-0 p-1">
                                    <div class="stat-icon-badge badge-flame flex-shrink-0" style="width:32px;height:32px;font-size:0.85rem;"><i class="fa-solid fa-fire-flame-curved"></i></div>
                                    <div class="stat-content text-start">
                                        <div class="stat-value text-white fw-bold" style="font-size:1.1rem;line-height:1;">{{ $currentStreak }}</div>
                                        <div class="stat-label text-white-50 text-uppercase" style="font-size:0.62rem;letter-spacing:0.8px;">{{ __('rewards.idx_streak') }}</div>
                                    </div>
                                </div>
                                <div class="stat-divider bg-light bg-opacity-10 flex-shrink-0" style="height:32px;width:1px;"></div>
                                <div class="zus-stat-card d-flex align-items-center justify-content-center gap-2 flex-fill m-0 p-1">
                                    <div class="stat-icon-badge badge-mission flex-shrink-0" style="width:32px;height:32px;font-size:0.85rem;"><i class="fa-solid fa-bullseye"></i></div>
                                    <div class="stat-content text-start">
                                        <div class="stat-value text-white fw-bold" style="font-size:1.1rem;line-height:1;">{{ count($completedMissions) }}</div>
                                        <div class="stat-label text-white-50 text-uppercase" style="font-size:0.62rem;letter-spacing:0.8px;">{{ __('rewards.idx_done') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== DESKTOP HERO (≥ 768px): original layout ===== --}}
                <div class="d-none d-md-block">
                    <div class="row align-items-center g-4">
                        <div class="col-md-6">
                            <div class="membership-flip-container" onclick="this.classList.toggle('flipped')">
                                <div class="membership-flipper">
                                    <div class="membership-front">
                                        <div class="membership-card {{ $membership->tier }}">
                                            <div class="d-flex justify-content-between align-items-start mb-3" style="position:relative;z-index:1;">
                                                <div>
                                                    <a href="{{ route('rewards.tiers') }}" onclick="event.stopPropagation();" class="badge tier-badge-{{ $membership->tier }} text-white px-3 py-2 rounded-pill mb-2 text-uppercase fw-bold text-decoration-none d-inline-block" style="font-size:0.75rem;letter-spacing:1px; cursor:pointer;">
                                                        <i class="fa-solid {{ $membership->getTierIcon() }} me-1"></i>
                                                        {{ $membership->getTierLabel() }} {{ __('rewards.idx_member') }} <i class="fa-solid fa-angle-right ms-1"></i>
                                                    </a>
                                                    <div class="text-white-50 small">{{ $membership->membership_id }}</div>
                                                </div>
                                                <div class="text-white-50 small text-end">
                                                    <div>{{ __('rewards.idx_joined') }}</div>
                                                    <div class="text-white fw-bold">{{ $membership->membership_join_date->format('M Y') }}</div>
                                                </div>
                                            </div>
                                            <div class="mb-3" style="position:relative;z-index:1;">
                                                <div class="text-white-50 small mb-1">{{ __('dashboard.rw_reward_points') }}</div>
                                                <div class="points-display" id="pointsDisplay">{{ number_format($membership->reward_points) }}</div>
                                                <div class="text-white-50 small mt-1">{{ __('rewards.idx_pts') }}</div>
                                            </div>
                                            @if($membership->tier !== 'gold')
                                            <div style="position:relative;z-index:1;">
                                                <div class="d-flex justify-content-between text-white-50 small mb-1">
                                                    <span>{{ __('rewards.idx_progress_to', ['tier' => $membership->getNextTierLabel()]) }}</span>
                                                    <span>{{ $membership->getTierProgress() }}%</span>
                                                </div>
                                                <div class="tier-progress-bar"><div class="tier-progress-fill" style="width: {{ $membership->getTierProgress() }}%"></div></div>
                                                <div class="text-white-50 small mt-1">
                                                    {{ __('rewards.idx_spent_progress', ['current' => number_format($membership->cumulative_annual_spending, 0), 'target' => $membership->tier === 'bronze' ? '500' : '1,500']) }}
                                                    @if($membership->getSpendingToNextTier() > 0)
                                                        &nbsp;·&nbsp; {{ __('rewards.idx_more_to_tier', ['more' => number_format($membership->getSpendingToNextTier(), 0), 'tier' => $membership->getNextTierLabel()]) }}
                                                    @endif
                                                </div>
                                            </div>
                                            @else
                                            <div class="mt-2" style="position:relative;z-index:1;">
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-crown me-1"></i>{{ __('rewards.idx_highest_tier_enjoy') }}</span>
                                            </div>
                                            @endif
                                            <div class="mt-3 text-white-50 small" style="position:relative;z-index:1;">
                                                <i class="fa-solid fa-star me-1 text-warning"></i>{{ $membership->getMultiplier() }}× {{ __('rewards.idx_multiplier') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="membership-back">
                                        <div class="membership-card {{ $membership->tier }} d-flex flex-column align-items-center justify-content-center text-center">
                                            <div class="bg-white p-2 rounded-3 mb-3" style="position:relative;z-index:1;">
                                                <img id="membershipQrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(URL::temporarySignedRoute('admin.memberships.scan_resolve', now()->addSeconds(60), ['user' => $user->id])) }}" alt="Membership QR" width="120" height="120">
                                            </div>
                                            <div class="text-white fw-bold fs-5 mb-1" style="position:relative;z-index:1;">{{ $membership->membership_id }}</div>
                                            <div class="text-white-50 small px-3 mb-2" style="position:relative;z-index:1;">{{ __('rewards.idx_scan_counter_desc') }}</div>
                                            <div class="small fw-bold mt-auto" style="color: #6ee7b7; position:relative;z-index:1;">
                                                <i class="fa-solid fa-arrows-rotate me-1"></i> {{ __('rewards.idx_refreshes_in') }}<span id="qrRefreshTimer">60</span>s{{ __('rewards.idx_refreshes_in_suffix') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3 text-white-50 small" style="cursor:pointer;" onclick="document.querySelector('.membership-flip-container').classList.toggle('flipped')">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> {{ __('rewards.idx_tap_flip_card') }}
                            </div>
                        </div>
                        <div class="col-md-6 text-center d-flex flex-column align-items-center justify-content-center py-2">
                            <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                                <h3 class="text-white fw-bold mb-0"><i class="fa-solid fa-trophy text-warning me-2"></i>{{ __('dashboard.rw_missions_rewards') }}</h3>
                                <a href="{{ route('rewards.tiers') }}" class="btn btn-sm btn-outline-light rounded-pill px-3"><i class="fa-solid fa-gem me-1"></i>{{ __('rewards.idx_tiers_benefits') }}</a>
                            </div>
                            <div class="zus-dial-container my-3" style="width:170px;height:170px;">
                                <svg class="zus-dial-ring" viewBox="0 0 100 100">
                                    <defs>
                                        <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#FFD700" />
                                            <stop offset="100%" stop-color="#FF8C00" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="zus-dial-bg" cx="50" cy="50" r="42" />
                                    <circle class="zus-dial-progress" cx="50" cy="50" r="42"
                                        stroke="url(#goldGradient)"
                                        stroke-dasharray="{{ $circumference }}"
                                        stroke-dashoffset="{{ $dashoffset }}" />
                                </svg>
                                <div class="zus-dial-center">
                                    <div class="bg-brand text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg mb-1" style="width:48px;height:48px;font-size:1.3rem;">
                                        <i class="fa-solid fa-car"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="points-display text-white" style="font-size:3.2rem;line-height:1;">{{ number_format($membership->reward_points) }}</div>
                                <div class="text-white-50 small fw-bold text-uppercase mt-1" style="letter-spacing:2px;">{{ __('dashboard.rw_trb_points') }}</div>
                            </div>
                            <div class="zus-stats-hud mt-2 mb-1">
                                <div class="zus-stat-card">
                                    <div class="stat-icon-badge badge-flame"><i class="fa-solid fa-fire-flame-curved"></i></div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ $currentStreak }}</div>
                                        <div class="stat-label">{{ __('rewards.idx_day_streak') }}</div>
                                    </div>
                                </div>
                                <div class="stat-divider"></div>
                                <div class="zus-stat-card">
                                    <div class="stat-icon-badge badge-mission"><i class="fa-solid fa-bullseye"></i></div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ count($completedMissions) }}</div>
                                        <div class="stat-label">{{ __('rewards.idx_missions_done') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ==================== TECH GIANT FLAGSHIP NAVIGATION ==================== --}}
    <div id="zusRewardsTab-wrap" class="d-flex justify-content-center mb-5">
        <ul class="nav nav-pills" id="zusRewardsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="missions-tab" data-bs-toggle="pill" data-bs-target="#tab-missions" type="button" role="tab">
                    <i class="fa-solid fa-compass tab-icon me-1"></i>
                    <span>{{ __('rewards.idx_tab_missions') }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="redeem-tab" data-bs-toggle="pill" data-bs-target="#tab-redeem" type="button" role="tab">
                    <i class="fa-solid fa-gift tab-icon me-1"></i>
                    <span class="d-none d-md-inline">{{ __('dashboard.rw_redeem_rewards') }}</span>
                    <span class="d-md-none">{{ __('rewards.idx_tab_redeem') }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="myrewards-tab" data-bs-toggle="pill" data-bs-target="#tab-myrewards" type="button" role="tab">
                    <i class="fa-solid fa-ticket tab-icon me-1"></i>
                    <span class="d-none d-md-inline">{{ __('dashboard.rw_my_vouchers') }}</span>
                    <span class="d-md-none">{{ __('rewards.idx_tab_vouchers') }}</span>
                    <span class="nav-badge ms-1">{{ count($availableVouchers) }}</span>
                </button>
            </li>
        </ul>
    </div>

    {{-- ==================== TAB CONTENT ==================== --}}
    <div class="tab-content" id="zusRewardsTabContent">

        {{-- ==================== TAB 1: MISSIONS ==================== --}}
        <div class="tab-pane fade show active" id="tab-missions" role="tabpanel" aria-labelledby="missions-tab">
            <div class="row g-4">
                {{-- Left: Daily Check-in & Referral --}}
                <div class="col-lg-4 d-flex flex-column gap-4">
                    <div class="checkin-card p-4 flex-fill d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 34px; height: 34px; background: rgba(255, 42, 67, 0.15); border: 1px solid rgba(255, 42, 67, 0.3);">
                                    <i class="fa-solid fa-fire text-brand" style="color: #FF2A43;"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-0" style="letter-spacing: 0.3px;">{{ __('rewards.idx_checkin_title') }}</h6>
                            </div>
                            <span class="badge rounded-pill px-3 py-1 fw-bold" style="background: rgba(255, 255, 255, 0.08); color: #f8fafc; border: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.72rem;">{{ __('rewards.idx_pts_day', ['points' => \App\Models\Membership::CHECKIN_POINTS]) }}</span>
                        </div>

                        {{-- 7-day streak display --}}
                        <div class="d-flex justify-content-between w-100 mb-4 px-1">
                            @php
                                $claimedDays = $todayCheckin 
                                    ? ($currentStreak == 0 ? 0 : (($currentStreak - 1) % 7) + 1)
                                    : ($currentStreak % 7);
                            @endphp

                            @for($i = 1; $i <= 7; $i++)
                                @php
                                    $isClaimed = $i <= $claimedDays;
                                    $isPendingToday = !$todayCheckin && $i === ($claimedDays + 1);
                                @endphp
                                <div class="d-flex flex-column align-items-center">
                                    <div class="streak-dot {{ $isClaimed ? 'active' : ($isPendingToday ? 'today' : '') }}">
                                        @if($isClaimed)
                                            <i class="fa-solid fa-check" style="font-size: 0.85rem;"></i>
                                        @else
                                            @if($i === 7)
                                                <i class="fa-solid fa-gift" style="font-size: 0.85rem; color: {{ $isPendingToday ? '#FF2A43' : '#94a3b8' }};"></i>
                                            @else
                                                +{{ \App\Models\Membership::CHECKIN_POINTS }}
                                            @endif
                                        @endif
                                    </div>
                                    <span class="mt-2 fw-semibold" style="font-size: 0.68rem; color: {{ $isClaimed || $isPendingToday ? '#ffffff' : '#64748b' }};">{{ __('rewards.idx_day_num', ['num' => $i]) }}</span>
                                </div>
                            @endfor
                        </div>

                        <div class="p-3 rounded-3 mb-4 d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06);">
                            <div class="fs-5 d-flex align-items-center justify-content-center" style="color: {{ $todayCheckin ? '#10b981' : '#FF2A43' }};">
                                <i class="fa-solid {{ $todayCheckin ? 'fa-circle-check' : 'fa-bolt' }}"></i>
                            </div>
                            <div class="small" style="color: #cbd5e1; line-height: 1.4;">
                                @if($todayCheckin)
                                    {{ __('rewards.idx_checked_in_today_streak', ['streak' => $currentStreak]) }}
                                @elseif($currentStreak > 0)
                                    {{ __('rewards.idx_current_streak_checkin', ['streak' => $currentStreak]) }}
                                @else
                                    {{ __('rewards.idx_start_streak_bonus') }}
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('rewards.checkin') }}" method="POST" id="checkinForm" class="mt-auto">
                            @csrf
                            <button type="submit" class="btn-checkin w-100 d-flex align-items-center justify-content-center gap-2" id="checkinBtn" {{ $todayCheckin ? 'disabled' : '' }}>
                                @if($todayCheckin)
                                    <i class="fa-solid fa-circle-check"></i> <span>{{ __('dashboard.rw_checked_in_today') }}</span>
                                @else
                                    <i class="fa-solid fa-bolt"></i> <span>{{ __('rewards.idx_btn_checkin_now', ['points' => \App\Models\Membership::CHECKIN_POINTS]) }}</span>
                                @endif
                            </button>
                        </form>
                    </div>

                    {{-- Referral Code Block --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <div class="text-brand mb-2" style="font-size:2rem;"><i class="fa-solid fa-user-group"></i></div>
                            <h6 class="fw-bold text-body-emphasis">{{ __('dashboard.rw_refer_friend') }}</h6>
                            <p class="text-body-secondary small mb-3">{{ __('rewards.idx_refer_share_desc') }}</p>
                            <div class="rounded-3 p-3 d-flex align-items-center justify-content-between border" style="background: rgba(128,128,128,0.05);">
                                <span class="fw-bold text-body-emphasis fs-5 text-uppercase" id="myReferralCode" style="letter-spacing: 1px;">{{ $membership->referral_code }}</span>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="navigator.clipboard.writeText('{{ $membership->referral_code }}'); this.innerHTML='<i class=\'fa-solid fa-check\'></i> {{ __('rewards.idx_copied') }}'; setTimeout(()=>this.innerHTML='<i class=\'fa-regular fa-copy\'></i> {{ __('rewards.idx_copy') }}', 2000);">
                                    <i class="fa-regular fa-copy"></i> {{ __('rewards.idx_copy') }}
                                </button>
                            </div>
                            @if(is_null(auth()->user()->referred_by))
                                <hr class="my-3">
                                <p class="text-body-secondary small mb-2">{{ __('dashboard.ref_have_code') }}</p>
                                <form action="{{ route('rewards.applyReferral') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="text" name="referral_code" class="form-control text-uppercase" placeholder="{{ __('dashboard.ref_placeholder') }}" required>
                                    <button type="submit" class="btn btn-brand">{{ __('dashboard.ref_apply') }}</button>
                                </form>
                            @else
                                <hr class="my-3">
                                <p class="text-success small mb-0"><i class="fa-solid fa-circle-check me-1"></i>{{ __('dashboard.ref_already_used') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Missions Matrix --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="fa-solid fa-trophy text-brand me-2"></i>{{ __('dashboard.rw_complete_missions') }}</h5>
                            <span class="badge bg-brand rounded-pill px-3">{{ __('rewards.idx_missions_completed_badge', ['completed' => count($completedMissions), 'total' => $missions->count()]) }}</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                @forelse($missions as $mission)
                                @php $completed = in_array($mission->id, $completedMissions); @endphp
                                <div class="col-md-6">
                                    <div class="mission-card {{ $completed ? 'completed' : '' }} h-100 d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="mission-icon {{ $completed ? 'bg-success-subtle text-success' : 'bg-brand-subtle text-brand' }}">
                                                <i class="fa-solid {{ $mission->icon }}"></i>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="fw-bold text-body-emphasis" style="line-height:1.3; font-size:1rem;">{{ $mission->name }}</div>
                                                <div class="text-body-secondary mt-1" style="font-size:0.85rem; line-height:1.4;">{{ $mission->description }}</div>
                                                <div class="mt-2">
                                                    <span class="badge {{ $completed ? 'bg-success' : 'bg-brand-subtle text-brand' }} rounded-pill small">
                                                        <i class="fa-solid fa-star me-1"></i>+{{ $mission->reward_points }} {{ __('rewards.idx_pts') }}
                                                    </span>
                                                    @if($mission->is_repeatable)
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill small ms-1">{{ __('rewards.idx_repeatable') }}</span>
                                                    @endif
                                                </div>

                                                @if(!$completed && $mission->trigger_value > 1)
                                                    @php 
                                                        $progress = min($mission->current_progress ?? 0, $mission->trigger_value); 
                                                        $percentage = ($progress / $mission->trigger_value) * 100;
                                                        if($mission->trigger_type == 'spending_milestone') {
                                                            $progressText = "RM " . number_format($progress, 2) . " / RM " . number_format($mission->trigger_value, 2);
                                                        } else {
                                                            $progressText = "{$progress} / {$mission->trigger_value}";
                                                        }
                                                    @endphp
                                                    <div class="mt-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="text-body-secondary small fw-medium" style="font-size: 0.75rem;">{{ __('rewards.idx_progress') }}</span>
                                                            <span class="text-body-secondary small fw-bold" style="font-size: 0.75rem;">{{ $progressText }}</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px; background-color: rgba(128,128,128,0.15);">
                                                            <div class="progress-bar bg-brand rounded-pill" role="progressbar" style="width: {{ $percentage }}%;"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-4 text-muted">{{ __('rewards.idx_no_missions') }}</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 2: REDEEM REWARDS ==================== --}}
        <div class="tab-pane fade" id="tab-redeem" role="tabpanel" aria-labelledby="redeem-tab">
            
            {{-- ZUS Style User Status Bar --}}
            <div class="user-status-bar d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center mb-4 p-4 bg-white rounded-4 shadow-sm border gap-3">
                {{-- Left/Top Row: Tier Badge + Points Balance --}}
                <div class="d-flex flex-row align-items-center justify-content-between justify-content-md-start gap-1.5 gap-md-4 w-100 w-md-auto flex-wrap">
                    <a href="{{ route('rewards.tiers') }}" class="badge tier-badge-{{ $membership->tier }} text-white px-2.5 py-1.5 px-md-3 py-md-2 rounded-pill fs-6 text-uppercase fw-bold shadow-sm text-decoration-none d-inline-flex align-items-center flex-shrink-0" style="font-size: 0.72rem; letter-spacing: 0.5px; max-width: 100%;">
                        <i class="fa-solid {{ $membership->getTierIcon() }} me-1.5"></i> {{ $membership->getTierLabel() }} {{ __('rewards.idx_member') }} &gt;
                    </a>
                    
                    <div class="d-flex align-items-baseline justify-content-end text-end text-md-start flex-wrap ms-auto">
                        <span class="text-muted small fw-semibold d-inline d-md-none me-1.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('rewards.idx_points_colon') }}</span>
                        <span class="fw-bold fs-5 text-body-emphasis d-none d-md-inline me-2">{{ __('dashboard.rw_trb_points') }}:</span>
                        <span class="fw-bold text-brand status-points-val" style="font-size: 1.35rem; line-height: 1;">{{ number_format($membership->reward_points) }}</span>
                        <span class="text-body-emphasis fw-bold small ms-1" style="font-size: 0.76rem;">{{ __('rewards.idx_pts') }}</span>
                    </div>
                </div>

                {{-- Right/Bottom Row: My Vouchers Button --}}
                <div class="d-flex justify-content-end status-bar-divider pt-md-0 border-md-0 mt-md-0 w-100 w-md-auto">
                    <button type="button" class="btn btn-outline-secondary status-voucher-btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center justify-content-between justify-content-md-center gap-3 w-100 w-md-auto shadow-sm transition-hover border-light border-opacity-15" onclick="document.getElementById('myrewards-tab').click()">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-ticket text-brand fs-6"></i>
                            <span>{{ __('dashboard.rw_my_vouchers') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-brand text-white rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">{{ __('rewards.idx_available_badge', ['count' => count($availableVouchers)]) }}</span>
                            <i class="fa-solid fa-chevron-right text-white-50 fs-7 d-inline d-md-none ms-1"></i>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Spin & Win --}}
            <div class="section-dark p-4 p-md-5 mb-4 rounded-4 position-relative overflow-hidden spin-section">
                <div style="position:absolute;top:-50%;right:-10%;width:300px;height:300px;background:radial-gradient(circle, rgba(236,31,36,0.15) 0%, transparent 70%);pointer-events:none;"></div>
                
                <div class="row align-items-center g-4 position-relative z-1">
                    <div class="col-md-5 text-center order-2 order-md-1">
                        <div class="spin-container" style="position:relative;padding-top:24px;">
                            <div class="spin-arrow">
                                <svg width="30" height="42" viewBox="0 0 30 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 42L3 10C1 5 4 0 10 0H20C26 0 29 5 27 10L15 42Z" fill="url(#arrowGrad)"/>
                                    <circle cx="15" cy="7" r="4" fill="#FFE58F" stroke="#B78103" stroke-width="1.5"/>
                                    <defs>
                                        <linearGradient id="arrowGrad" x1="15" y1="0" x2="15" y2="42" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#FF4D4D"/>
                                            <stop offset="100%" stop-color="#990011"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <canvas id="spinWheel" width="280" height="280"></canvas>
                            <div class="spin-center">{{ __('rewards.idx_spin') }}</div>
                        </div>

                        <form action="{{ route('rewards.spin') }}" method="POST" id="spinForm" class="mt-3 mt-md-4">
                            @csrf
                            <button type="submit" class="btn-spin w-100 fs-5 py-3 shadow-lg" id="spinBtn"
                                {{ ($membership->reward_points < \App\Models\Membership::SPIN_COST || $spinsToday >= \App\Models\Membership::MAX_SPINS_PER_DAY) ? 'disabled' : '' }}>
                                <i class="fa-solid fa-rotate me-2"></i>
                                {{ __('rewards.idx_btn_spin_wheel', ['cost' => \App\Models\Membership::SPIN_COST]) }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-7 order-1 order-md-2">
                        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                            <div>
                                <h3 class="text-white fw-bold mb-1"><i class="fa-solid fa-rotate text-brand me-2"></i>{{ __('rewards.idx_spin_and_win') }}</h3>
                                <div class="text-white-50 small">{{ __('rewards.idx_spin_desc', ['cost' => \App\Models\Membership::SPIN_COST, 'max' => \App\Models\Membership::MAX_SPINS_PER_DAY, 'used' => $spinsToday]) }}</div>
                            </div>
                        </div>

                        <div class="row g-2 g-md-2.5 mb-0">
                            @foreach($spinSegments as $seg)
                            @php
                                $label = $seg['label'];
                                $icon = 'fa-gift';
                                $iconColor = '#FF2A43';
                                $badgeText = 'REWARD';
                                if (str_contains($label, 'Points')) {
                                    $icon = 'fa-coins';
                                    $iconColor = '#fbbf24';
                                    $badgeText = 'POINTS';
                                } elseif (str_contains($label, 'Voucher')) {
                                    $icon = 'fa-ticket';
                                    $iconColor = '#FF2A43';
                                    $badgeText = 'VOUCHER';
                                } elseif (str_contains($label, 'Discount')) {
                                    $icon = 'fa-tag';
                                    $iconColor = '#38bdf8';
                                    $badgeText = 'COUPON';
                                } elseif (str_contains($label, 'Luck')) {
                                    $icon = 'fa-face-smile-wink';
                                    $iconColor = '#94a3b8';
                                    $badgeText = 'CHANCE';
                                }
                            @endphp
                            <div class="col-6">
                                <div class="prize-showcase-card p-2 p-md-2.5 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.035); border: 1px solid rgba(255, 255, 255, 0.08); transition: all 0.25s ease;">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 30px; height: 30px; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1);">
                                            <i class="fa-solid {{ $icon }}" style="color: {{ $iconColor }}; font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="text-white fw-bold small text-nowrap" style="letter-spacing: 0.2px; font-size: 0.82rem;">{{ $label }}</span>
                                    </div>
                                    <span class="badge rounded-pill ms-1 d-none d-sm-inline-block" style="background: rgba(255, 255, 255, 0.08); color: #cbd5e1; font-size: 0.62rem; font-weight: 600; padding: 0.35em 0.6em; flex-shrink: 0;">{{ $badgeText }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- Redeem Points for Vouchers --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="fa-solid fa-ticket text-brand me-2"></i>{{ __('dashboard.rw_catalogue') }}</h5>
                        </div>
                        <div class="card-body p-4 catalogue-card-body">
                            <div class="row g-2.5">
                                @foreach($voucherCatalogue as $item)
                                @php 
                                    $canRedeemPoints = $membership->reward_points >= $item['points']; 
                                    $tierHierarchy = ['bronze' => 1, 'silver' => 2, 'gold' => 3];
                                    $userTierVal = $tierHierarchy[$membership->tier ?? 'bronze'];
                                    $reqTierVal = $tierHierarchy[$item['min_tier'] ?? 'bronze'];
                                    $meetsTier = $userTierVal >= $reqTierVal;
                                    $canRedeem = $canRedeemPoints && $meetsTier;
                                @endphp
                                <div class="col-12 col-md-6">
                                    <div class="catalogue-coupon-item {{ !$canRedeem ? 'disabled-coupon' : '' }} d-flex align-items-center justify-content-between p-3 rounded-4 border position-relative h-100 shadow-sm"
                                         style="background: {{ !$meetsTier ? 'rgba(255, 255, 255, 0.02)' : 'rgba(255, 255, 255, 0.04)' }}; border-color: {{ !$meetsTier ? 'rgba(255, 255, 255, 0.06)' : 'rgba(255, 255, 255, 0.1)' }} !important; transition: all 0.25s ease; {{ !$meetsTier ? 'opacity: 0.75;' : '' }}">
                                        
                                        <div class="d-flex align-items-center min-w-0 flex-grow-1 me-2">
                                            <div class="catalogue-coupon-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" 
                                                 style="width: 48px; height: 48px; background: {{ !$meetsTier ? 'rgba(255,255,255,0.05)' : 'rgba(255, 42, 67, 0.12)' }}; border: 1px solid {{ !$meetsTier ? 'rgba(255,255,255,0.1)' : 'rgba(255, 42, 67, 0.25)' }};">
                                                <i class="fa-solid {{ $item['icon'] }} {{ !$meetsTier ? 'text-secondary' : 'text-brand' }} fs-5"></i>
                                            </div>
                                            
                                            <div class="ms-3 min-w-0 flex-grow-1">
                                                <div class="fw-bold text-white text-truncate mb-0" style="font-size: 0.95rem; letter-spacing: 0.2px;">{{ $item['label'] }}</div>
                                                <div class="text-white-50 small mb-1" style="font-size: 0.72rem;"><i class="fa-regular fa-clock me-1"></i>{{ __('rewards.idx_valid_90_days') }}</div>
                                                @if(!$meetsTier)
                                                    <div class="mt-0.5">
                                                        <span class="badge rounded-pill bg-dark border border-secondary text-warning" style="font-size: 0.65rem; font-weight: 600; padding: 0.3em 0.65em;">
                                                            <i class="fa-solid fa-lock me-1"></i>{{ __('rewards.idx_requires_tier', ['tier' => ucfirst($item['min_tier'])]) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-shrink-0">
                                            <form action="{{ route('rewards.redeemPoints') }}" method="POST" class="mb-0">
                                                @csrf
                                                <input type="hidden" name="points_cost" value="{{ $item['points'] }}">
                                                <button type="submit" class="btn btn-sm {{ $canRedeem ? 'btn-brand shadow-sm' : 'btn-outline-secondary' }} rounded-pill px-3 py-1.5 fw-bold text-nowrap" style="font-size: 0.78rem;" {{ !$canRedeem ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-star me-1"></i>{{ __('rewards.idx_pts_num', ['points' => number_format($item['points'])]) }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Redeem Promo Code --}}
                <div class="col-lg-4">
                    <div class="section-dark p-4 h-100 d-flex flex-column justify-content-center rounded-4">
                        <h5 class="text-white fw-bold mb-3"><i class="fa-solid fa-tag text-brand me-2"></i>{{ __('dashboard.rw_promo_code') }}</h5>
                        <form action="{{ route('rewards.redeemCode') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" name="code" class="form-control promo-input text-uppercase" placeholder="{{ __('rewards.idx_promo_placeholder') }}" maxlength="20" required>
                                <button type="submit" class="btn btn-brand px-3" style="border-radius:0 12px 12px 0;">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                        <div class="text-white-50 small"><i class="fa-solid fa-info-circle me-1"></i>{{ __('rewards.idx_promo_desc') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TAB 3: MY REWARDS (Vouchers Page Layout matching Photo) ==================== --}}
        <div class="tab-pane fade" id="tab-myrewards" role="tabpanel" aria-labelledby="myrewards-tab">
            {{-- Header Banner --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-4 bg-dark text-white rounded-4 h-100 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="fw-bold mb-1"><i class="fa-solid fa-ticket text-brand me-2"></i>{{ __('dashboard.rw_my_vouchers') }}</h2>
                                <p class="text-white-50 mb-0">{{ __('rewards.idx_vouchers_manage_desc') }}</p>
                            </div>
                            <button type="button" class="btn btn-brand rounded-pill px-4" onclick="document.getElementById('missions-tab').click()">
                                <i class="fa-solid fa-gift me-2"></i>{{ __('rewards.idx_earn_more') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add a Voucher (Redeem Promo Code) --}}
            <div class="row mb-4">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 d-flex flex-column justify-content-center border">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-tag text-brand me-2"></i>{{ __('dashboard.rw_add_voucher') }}</h6>
                        <form action="{{ route('rewards.redeemCode') }}" method="POST" class="mb-0">
                            @csrf
                            <div class="input-group shadow-sm" style="border-radius: 8px;">
                                <input type="text" name="code" class="form-control text-uppercase" placeholder="{{ __('dashboard.rw_enter_promo') }}" maxlength="20" required style="border-radius: 8px 0 0 8px; border: 1px solid #dee2e6; border-right: none;">
                                <button type="submit" class="btn btn-brand px-4" style="border-radius: 0 8px 8px 0;">
                                    {{ __('rewards.idx_tab_redeem') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sub Tabs --}}
            <ul class="nav nav-tabs border-0 mb-4 d-inline-flex gap-1" id="voucherSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-0" id="avail-subtab" data-bs-toggle="tab" data-bs-target="#subtab-available" type="button" role="tab">
                        <i class="fa-solid fa-circle-check me-1 opacity-75"></i>{{ __('dashboard.rw_active') }} <span class="badge rounded-pill ms-1">{{ $availableVouchers->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0" id="past-subtab" data-bs-toggle="tab" data-bs-target="#subtab-past" type="button" role="tab">
                        <i class="fa-solid fa-clock-rotate-left me-1 opacity-75"></i>{{ __('dashboard.rw_past') }} <span class="badge rounded-pill ms-1">{{ $usedVouchers->count() + $expiredVouchers->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="voucherSubTabsContent">
                {{-- Active --}}
                <div class="tab-pane fade show active" id="subtab-available" role="tabpanel">
                    @forelse($availableVouchers as $voucher)
                    <div class="card voucher-card border border-0 shadow-sm mb-3 d-flex flex-row bg-white">
                        <div class="voucher-notch-left"></div>
                        <div class="voucher-left">
                            @if($voucher->type === 'fixed')
                                <div class="unit-text">{{ __('rewards.vouchers_rm') }}</div>
                                <div class="value-text">{{ number_format($voucher->value, 0) }}</div>
                            @else
                                <div class="value-text">{{ number_format($voucher->value, 0) }}%</div>
                                <div class="unit-text">{{ __('dashboard.rw_off') }}</div>
                            @endif
                            <div class="unit-text mt-1" style="opacity:0.7;">{{ __('dashboard.rw_voucher_unit') }}</div>
                        </div>
                        <div class="voucher-right d-flex flex-column justify-content-center p-3">
                            <div class="voucher-status-corner">
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">{{ __('dashboard.rw_active') }}</span>
                                <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm voucher-info-btn d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;"
                                        data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                        data-code="{{ $voucher->code }}"
                                        data-discount="{{ $voucher->getDiscountLabel() }}"
                                        data-source="{{ $voucher->getSourceLabel() }}"
                                        data-status="{{ __('dashboard.rw_active') }}"
                                        data-status-class="bg-success"
                                        data-expiry="{{ $voucher->expires_at ? $voucher->expires_at->format('d M Y') : __('dashboard.rw_no_expiry') }}"
                                        data-tnc="{{ $voucher->getTermsAndConditions() }}"
                                        title="{{ __('dashboard.rw_view_details') }}">
                                    <i class="fa-solid fa-circle-info text-brand fs-6"></i>
                                </button>
                            </div>
                            <div class="voucher-top-info mb-2">
                                <div class="fw-bold text-dark fs-5">{{ $voucher->getDiscountLabel() }}</div>
                                <div class="text-muted small">{{ $voucher->getSourceLabel() }}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top border-secondary-subtle border-opacity-10">
                                <div>
                                    <span class="voucher-code">{{ $voucher->code }}</span>
                                </div>
                                <div class="text-end">
                                    @if($voucher->expires_at)
                                        @php $days = $voucher->getDaysUntilExpiry(); @endphp
                                        <div class="small {{ $days <= 7 ? 'expiring-pulse fw-bold text-danger' : 'text-muted' }}">
                                            @if($days <= 0)
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i>{{ __('dashboard.rw_expires_today') }}
                                            @elseif($days <= 7)
                                                <i class="fa-solid fa-clock me-1"></i>{{ __('dashboard.rw_expires_in_days', ['days' => $days]) }}
                                            @else
                                                {{ __('dashboard.rw_expires_date', ['date' => $voucher->expires_at->format('d M Y')]) }}
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-muted small">{{ __('dashboard.rw_no_expiry') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="voucher-notch-right"></div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted my-5">
                        <i class="fa-solid fa-ticket fa-3x mb-3 d-block text-secondary opacity-50"></i>
                        <p class="fs-6">{{ __('dashboard.rw_no_active_vouchers') }} <a href="javascript:void(0)" onclick="document.getElementById('missions-tab').click()" class="text-brand text-decoration-none fw-bold">{{ __('dashboard.rw_go_earn') }}</a></p>
                    </div>
                    @endforelse
                </div>

                {{-- Past --}}
                <div class="tab-pane fade" id="subtab-past" role="tabpanel">
                    @if($usedVouchers->count() > 0 || $expiredVouchers->count() > 0)
                        @foreach($usedVouchers as $voucher)
                        <div class="card voucher-card voucher-used border border-0 shadow-sm mb-3 d-flex flex-row bg-white opacity-75">
                            <div class="voucher-notch-left"></div>
                            <div class="voucher-left">
                                @if($voucher->type === 'fixed')
                                    <div class="unit-text">{{ __('rewards.vouchers_rm') }}</div>
                                    <div class="value-text">{{ number_format($voucher->value, 0) }}</div>
                                @else
                                    <div class="value-text">{{ number_format($voucher->value, 0) }}%</div>
                                    <div class="unit-text">{{ __('dashboard.rw_off') }}</div>
                                @endif
                                <div class="unit-text mt-1" style="opacity:0.7;">{{ __('dashboard.rw_used_unit') }}</div>
                            </div>
                            <div class="voucher-right d-flex flex-column justify-content-center p-3">
                                <div class="voucher-status-corner">
                                    <span class="badge bg-secondary rounded-pill px-3 py-1">{{ __('dashboard.rw_used_badge') }}</span>
                                    <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm voucher-info-btn d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;"
                                            data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                            data-code="{{ $voucher->code }}"
                                            data-discount="{{ $voucher->getDiscountLabel() }}"
                                            data-source="{{ $voucher->getSourceLabel() }}"
                                            data-status="{{ __('dashboard.rw_used_badge') }}"
                                            data-status-class="bg-secondary"
                                            data-expiry="{{ __('dashboard.rw_used_on', ['date' => $voucher->used_at ? $voucher->used_at->format('d M Y') : 'N/A']) }}"
                                            data-tnc="{{ $voucher->getTermsAndConditions() }}"
                                            title="{{ __('dashboard.rw_view_details') }}">
                                        <i class="fa-solid fa-circle-info text-secondary fs-6"></i>
                                    </button>
                                </div>
                                <div class="voucher-top-info mb-2">
                                    <div class="fw-bold text-muted fs-5"><strike>{{ $voucher->getDiscountLabel() }}</strike></div>
                                    <div class="text-muted small">{{ $voucher->getSourceLabel() }}</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top border-secondary-subtle border-opacity-10">
                                    <div>
                                        <span class="voucher-code">{{ $voucher->code }}</span>
                                    </div>
                                    <div class="text-end">
                                        @if($voucher->used_at)
                                            <div class="text-muted small">{{ __('dashboard.rw_used_date', ['date' => $voucher->used_at->format('d M Y')]) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="voucher-notch-right"></div>
                        </div>
                        @endforeach

                        @foreach($expiredVouchers as $voucher)
                        <div class="card voucher-card voucher-expired border border-0 shadow-sm mb-3 d-flex flex-row bg-white" style="opacity:0.65;">
                            <div class="voucher-notch-left"></div>
                            <div class="voucher-left">
                                @if($voucher->type === 'fixed')
                                    <div class="unit-text">{{ __('rewards.vouchers_rm') }}</div>
                                    <div class="value-text">{{ number_format($voucher->value, 0) }}</div>
                                @else
                                    <div class="value-text">{{ number_format($voucher->value, 0) }}%</div>
                                    <div class="unit-text">{{ __('dashboard.rw_off') }}</div>
                                @endif
                            </div>
                            <div class="voucher-right d-flex flex-column justify-content-center p-3">
                                <div class="voucher-status-corner">
                                    <span class="badge bg-danger rounded-pill px-3 py-1">{{ __('dashboard.rw_expired_badge') }}</span>
                                    <button type="button" class="btn btn-sm btn-light border border-secondary-subtle rounded-circle shadow-sm voucher-info-btn d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;"
                                            data-bs-toggle="modal" data-bs-target="#voucherTncModal"
                                            data-code="{{ $voucher->code }}"
                                            data-discount="{{ $voucher->getDiscountLabel() }}"
                                            data-source="{{ $voucher->getSourceLabel() }}"
                                            data-status="{{ __('dashboard.rw_expired_badge') }}"
                                            data-status-class="bg-danger"
                                            data-expiry="{{ __('dashboard.rw_expired_on', ['date' => $voucher->expires_at ? $voucher->expires_at->format('d M Y') : 'N/A']) }}"
                                            data-tnc="{{ $voucher->getTermsAndConditions() }}"
                                            title="{{ __('dashboard.rw_view_details') }}">
                                        <i class="fa-solid fa-circle-info text-danger fs-6"></i>
                                    </button>
                                </div>
                                <div class="voucher-top-info mb-2">
                                    <div class="fw-bold text-danger fs-5">{{ $voucher->getDiscountLabel() }}</div>
                                    <div class="text-muted small">{{ $voucher->getSourceLabel() }}</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2 pt-2 border-top border-secondary-subtle border-opacity-10">
                                    <div>
                                        <span class="voucher-code">{{ $voucher->code }}</span>
                                    </div>
                                    <div class="text-end">
                                        @if($voucher->expires_at)
                                            <div class="text-muted small">{{ __('dashboard.rw_expired_date', ['date' => $voucher->expires_at->format('d M Y')]) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="voucher-notch-right"></div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-5 text-muted my-5">
                        <i class="fa-solid fa-clock fa-3x mb-3 d-block text-secondary opacity-50"></i>
                        <p>{{ __('dashboard.rw_no_past_vouchers') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div> {{-- End Tab Content --}}
</div>

{{-- Spin Result Overlay (hidden by default, shown after spin) --}}
@if(session('spin_result'))
@php $sr = session('spin_result'); @endphp
<div class="spin-result-overlay" id="spinResultOverlay">
    <div class="spin-result-card">
        @if($sr['prize']['type'] === 'nothing')
            <div style="font-size:4rem;margin-bottom:1rem;">😔</div>
            <h4 class="text-white fw-bold">{{ __('rewards.idx_result_better_luck') }}</h4>
            <p class="text-white-50">{{ __('rewards.idx_result_keep_spinning') }}</p>
        @elseif($sr['prize']['type'] === 'points')
            <div style="font-size:4rem;margin-bottom:1rem;">⭐</div>
            <h4 class="text-white fw-bold">+{{ $sr['prize']['points'] }} {{ __('rewards.idx_result_points') }}!</h4>
            <p class="text-white-50">{{ __('rewards.idx_result_points_added') }}</p>
        @else
            <div style="font-size:4rem;margin-bottom:1rem;">🎉</div>
            <h4 class="text-white fw-bold">{{ $sr['prize']['label'] }} {{ __('rewards.idx_result_won') }}!</h4>
            <p class="text-white-50">{{ __('rewards.idx_result_voucher_added', ['tab' => __('dashboard.rw_my_vouchers')]) }}</p>
        @endif
        <button class="btn btn-brand px-4 mt-2 rounded-pill" onclick="document.getElementById('spinResultOverlay').remove()">
            {{ __('rewards.idx_result_awesome') }}
        </button>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
// ========== SPIN WHEEL CANVAS ==========
(function() {
    const segments = @json($spinSegments);
    const canvas = document.getElementById('spinWheel');
    if(!canvas) return;
    const ctx = canvas.getContext('2d');
    const total = segments.length;
    const arc = (2 * Math.PI) / total;

    function drawWheel(rotation) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < total; i++) {
            const startAngle = rotation + (i * arc);
            const endAngle = startAngle + arc;

            // Segment
            ctx.beginPath();
            ctx.moveTo(140, 140);
            ctx.arc(140, 140, 136, startAngle, endAngle);
            ctx.closePath();
            ctx.fillStyle = segments[i].color;
            ctx.fill();
            ctx.strokeStyle = 'rgba(255,255,255,0.2)';
            ctx.lineWidth = 2;
            ctx.stroke();

            // Label
            ctx.save();
            ctx.translate(140, 140);
            ctx.rotate(startAngle + arc / 2);
            ctx.textAlign = 'right';
            ctx.fillStyle = '#ffffff';
            ctx.font = '600 11px Outfit, Inter, sans-serif';
            ctx.shadowColor = 'rgba(0,0,0,0.9)';
            ctx.shadowBlur = 6;
            ctx.fillText(segments[i].label, 126, 4);
            ctx.restore();
        }

        // Draw physical metallic pegs (divider pins) on outer rim
        for (let i = 0; i < total; i++) {
            const pegAngle = rotation + (i * arc);
            const pegX = 140 + 132 * Math.cos(pegAngle);
            const pegY = 140 + 132 * Math.sin(pegAngle);

            ctx.beginPath();
            ctx.arc(pegX, pegY, 4, 0, 2 * Math.PI);
            ctx.fillStyle = '#FFE58F';
            ctx.fill();
            ctx.strokeStyle = '#990011';
            ctx.lineWidth = 1.5;
            ctx.stroke();
        }
    }

    let rotation = -Math.PI / 2;
    drawWheel(rotation);

    // Spin animation on form submit
    const spinForm = document.getElementById('spinForm');
    if (spinForm) {
        spinForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('spinBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-rotate fa-spin me-2"></i>' + @json(__('rewards.idx_spinning'));

            try {
                // Fetch the result first
                const response = await fetch(spinForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    alert(data.message);
                    window.location.reload();
                    return;
                }

                // Find the winning segment index
                const winningLabel = data.prize.label;
                let winningIndex = segments.findIndex(s => s.match === winningLabel);
                if (winningIndex === -1) winningIndex = 0; // fallback

                // Calculate base angle where pointer (-Math.PI/2) points to center of winning segment
                const baseTargetRotation = -Math.PI / 2 - (winningIndex * arc) - (arc / 2);
                
                // Add subtle natural variation inside the winning segment slot (-0.25*arc to +0.25*arc)
                const slotVariation = (Math.random() - 0.5) * (arc * 0.5);
                const targetSlotAngle = baseTargetRotation + slotVariation;
                
                // Ensure wheel spins clockwise at least 6 full revolutions
                let diff = (targetSlotAngle - rotation) % (2 * Math.PI);
                if (diff < 0) diff += 2 * Math.PI;
                const finalTargetAngle = rotation + diff + (2 * Math.PI * 6);
                
                const arrowEl = document.querySelector('.spin-arrow');
                let currentRotation = rotation;
                let angularVelocity = 24.0; // initial high rotational speed (rad/s)
                let lastTime = performance.now();
                let isSettling = false;
                let settledFrames = 0;
                
                // Physics simulation parameters
                const settleThreshold = arc * 0.75; // enter spring-damper settling when entering target slot
                const springStiffness = 55.0;       // Hooke's law spring constant for slot detent
                const dampingCoeff = 7.5;           // damping for realistic pendulum rocking
                
                function animate(currentTime) {
                    let dt = (currentTime - lastTime) / 1000;
                    lastTime = currentTime;
                    if (dt > 0.1 || dt <= 0) dt = 0.016; // handle background tabs or initial step
                    
                    const remainingDist = finalTargetAngle - currentRotation;
                    
                    if (!isSettling && remainingDist <= settleThreshold) {
                        isSettling = true;
                    }
                    
                    if (!isSettling) {
                        // Phase 1: Kinematic deceleration with Peg Collision Energy Loss
                        const targetEntryVel = 3.2;
                        const nominalVel = Math.sqrt(Math.max(0, 2 * 12.0 * (remainingDist - settleThreshold) + targetEntryVel * targetEntryVel));
                        
                        // Smoothly track nominal deceleration curve
                        angularVelocity += (nominalVel - angularVelocity) * Math.min(1, dt * 8);
                        
                        // Check if pointer is currently colliding with a peg
                        const pegPhase = ((currentRotation + Math.PI / 2) % arc + arc) % arc / arc; // 0.0 to 1.0
                        
                        // Micro stutter & energy loss when striking a peg at lower speeds
                        if (pegPhase > 0.82 && angularVelocity < 12.0) {
                            angularVelocity *= 0.985;
                        }
                        
                        currentRotation += angularVelocity * dt;
                        
                        // Realistic mechanical pointer flicking against divider pins
                        if (arrowEl) {
                            if (pegPhase > 0.82) {
                                const p = (pegPhase - 0.82) / 0.18;
                                const tilt = -32 * Math.sin(p * Math.PI / 2);
                                arrowEl.style.transform = `translateX(-50%) rotate(${tilt}deg)`;
                            } else if (pegPhase < 0.25) {
                                const p = pegPhase / 0.25;
                                const tilt = -32 * Math.cos(p * Math.PI / 2) * Math.exp(-p * 3);
                                arrowEl.style.transform = `translateX(-50%) rotate(${tilt}deg)`;
                            } else {
                                arrowEl.style.transform = 'translateX(-50%) rotate(0deg)';
                            }
                        }
                    } else {
                        // Phase 2: Damped Harmonic Oscillator (Spring-Damper Rocking inside winning slot)
                        const subSteps = 4;
                        const subDt = dt / subSteps;
                        for (let s = 0; s < subSteps; s++) {
                            const displacement = currentRotation - finalTargetAngle;
                            const springAcc = -springStiffness * displacement - dampingCoeff * angularVelocity;
                            angularVelocity += springAcc * subDt;
                            currentRotation += angularVelocity * subDt;
                        }
                        
                        // Pointer reacts dynamically to rocking direction and velocity
                        if (arrowEl) {
                            const tilt = Math.max(-25, Math.min(25, -angularVelocity * 4.5));
                            arrowEl.style.transform = `translateX(-50%) rotate(${tilt}deg)`;
                        }
                        
                        // Check for complete rest
                        if (Math.abs(currentRotation - finalTargetAngle) < 0.003 && Math.abs(angularVelocity) < 0.05) {
                            settledFrames++;
                        } else {
                            settledFrames = 0;
                        }
                    }
                    
                    drawWheel(currentRotation);
                    
                    if (settledFrames < 10) {
                        requestAnimationFrame(animate);
                    } else {
                        if (arrowEl) arrowEl.style.transform = 'translateX(-50%) rotate(0deg)';
                        rotation = finalTargetAngle % (2 * Math.PI);
                        localStorage.setItem('activeRewardTab', 'redeem-tab');
                        showWheelPopout(data.prize);
                    }
                }
                
                function showWheelPopout(prize) {
                    const canvas = document.getElementById('spinWheel');
                    const rect = canvas ? canvas.getBoundingClientRect() : { left: window.innerWidth/2, top: window.innerHeight/2, width: 0, height: 0 };
                    const startX = rect.left + rect.width / 2;
                    const startY = rect.top + rect.height / 2;
                    
                    const existing = document.getElementById('wheelPrizePopout');
                    if (existing) existing.remove();
                    const existingBackdrop = document.getElementById('wheelPopoutBackdrop');
                    if (existingBackdrop) existingBackdrop.remove();
                    
                    let iconHtml = '<div style="font-size:4.5rem;margin-bottom:0.75rem;filter:drop-shadow(0 0 25px rgba(255,42,67,0.85));animation:bounceIcon 1s infinite;">🎉</div>';
                    let title = prize.label;
                    let desc = @json(__('rewards.idx_popout_added_wallet'));
                    
                    if (prize.type === 'points') {
                        iconHtml = '<div style="font-size:4.5rem;margin-bottom:0.75rem;filter:drop-shadow(0 0 25px rgba(251,191,36,0.85));animation:bounceIcon 1s infinite;">⭐</div>';
                        title = '+' + prize.points + ' ' + @json(__('rewards.idx_result_points')) + '!';
                        desc = @json(__('rewards.idx_result_points_added'));
                    } else if (prize.type === 'nothing') {
                        iconHtml = '<div style="font-size:4.5rem;margin-bottom:0.75rem;">🍀</div>';
                        title = @json(__('rewards.idx_result_better_luck'));
                        desc = @json(__('rewards.idx_result_keep_spinning'));
                    }
                    
                    const backdrop = document.createElement('div');
                    backdrop.id = 'wheelPopoutBackdrop';
                    backdrop.style.cssText = `
                        position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9998;
                        backdrop-filter: blur(8px); opacity: 0; transition: opacity 0.5s ease;
                    `;
                    document.body.appendChild(backdrop);
                    
                    const popout = document.createElement('div');
                    popout.id = 'wheelPrizePopout';
                    popout.style.cssText = `
                        position: fixed;
                        top: ${startY}px;
                        left: ${startX}px;
                        transform: translate(-50%, -50%) scale(0.1);
                        z-index: 9999;
                        background: linear-gradient(135deg, rgba(22, 24, 32, 0.98) 0%, rgba(45, 31, 61, 0.98) 100%);
                        border: 2px solid #FF2A43;
                        border-radius: 2rem;
                        padding: 2.5rem 2.5rem;
                        min-width: 310px;
                        text-align: center;
                        box-shadow: 0 0 80px rgba(255, 42, 67, 0.65), 0 25px 60px rgba(0, 0, 0, 0.95);
                        transition: left 0.65s cubic-bezier(0.34, 1.56, 0.64, 1),
                                    top 0.65s cubic-bezier(0.34, 1.56, 0.64, 1),
                                    transform 0.65s cubic-bezier(0.34, 1.56, 0.64, 1),
                                    opacity 0.4s ease;
                        opacity: 0;
                        pointer-events: none;
                    `;
                    
                    popout.innerHTML = `
                        <style>
                            @keyframes bounceIcon {
                                0%, 100% { transform: translateY(0); }
                                50% { transform: translateY(-10px); }
                            }
                        </style>
                        <div style="position:absolute;inset:-50px;background:radial-gradient(circle, rgba(255,42,67,0.25) 0%, transparent 70%);z-index:-1;pointer-events:none;border-radius:50%;"></div>
                        ${iconHtml}
                        <h4 class="text-white fw-bold mb-2" style="font-size:1.6rem;">${title}</h4>
                        <p class="text-white-50 mb-0" style="font-size:1rem;">${desc}</p>
                    `;
                    
                    document.body.appendChild(popout);
                    
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            if (backdrop) backdrop.style.opacity = '1';
                            popout.style.left = '50%';
                            popout.style.top = '50%';
                            popout.style.transform = 'translate(-50%, -50%) scale(1)';
                            popout.style.opacity = '1';
                        }, 50);
                    });
                    
                    setTimeout(() => {
                        if (backdrop) backdrop.style.opacity = '0';
                        popout.style.transform = 'translate(-50%, -50%) scale(1.15)';
                        popout.style.opacity = '0';
                        setTimeout(() => window.location.reload(), 400);
                    }, 2600);
                }
                
                requestAnimationFrame(animate);

            } catch (err) {
                console.error(err);
                localStorage.setItem('activeRewardTab', 'redeem-tab');
                window.location.reload();
            }
        });
    }

    // Auto-dismiss result overlay
    const overlay = document.getElementById('spinResultOverlay');
    if (overlay) {
        setTimeout(() => { if (overlay) overlay.style.animation = 'fadeOut 0.3s forwards'; }, 5000);
    }
})();

// ========== CHECK-IN ANIMATION ==========
const checkinForm = document.getElementById('checkinForm');
if (checkinForm) {
    checkinForm.addEventListener('submit', function() {
        const btn = document.getElementById('checkinBtn');
        if (btn && !btn.disabled) {
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i>' + @json(__('rewards.idx_checking_in'));
        }
    });
}

// Ensure Canvas is drawn when changing to Redeem Tab (since canvas might be 0x0 if hidden initially)
const redeemTab = document.getElementById('redeem-tab');
if (redeemTab) {
    redeemTab.addEventListener('shown.bs.tab', function (e) {
        // Redraw or trigger resize logic if needed. The current implementation uses fixed dimensions (280x280) so it's safe.
    });
}

// ========== DYNAMIC QR CODE REFRESH ==========
let qrTimer = 60;
const timerDisplay = document.getElementById('qrRefreshTimer');
const qrImage = document.getElementById('membershipQrImage');

if (timerDisplay && qrImage) {
    setInterval(() => {
        qrTimer--;
        if (qrTimer <= 0) {
            timerDisplay.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            // Fetch new QR
            fetch('{{ route('rewards.qr') }}')
                .then(response => response.json())
                .then(data => {
                    qrImage.src = data.qr_url;
                    qrTimer = 60;
                    timerDisplay.textContent = qrTimer;
                })
                .catch(err => {
                    console.error('Failed to refresh QR:', err);
                    qrTimer = 5; // Retry in 5s
                    timerDisplay.textContent = qrTimer;
                });
        } else {
            timerDisplay.textContent = qrTimer;
        }
    }, 1000);
}

// ========== PERSIST & RESTORE ACTIVE TAB ACROSS RELOADS ==========
document.addEventListener('DOMContentLoaded', function() {
    @if(session('spin_result'))
        localStorage.setItem('activeRewardTab', 'redeem-tab');
    @endif

    const activeTabId = localStorage.getItem('activeRewardTab');
    if (activeTabId) {
        const tabEl = document.getElementById(activeTabId);
        if (tabEl && typeof bootstrap !== 'undefined') {
            const tab = new bootstrap.Tab(tabEl);
            tab.show();
        }
    }

    // Save tab selection on switch
    document.querySelectorAll('#zusRewardsTab .nav-link').forEach(btn => {
        btn.addEventListener('shown.bs.tab', e => {
            if (e.target.id) {
                localStorage.setItem('activeRewardTab', e.target.id);
            }
        });
    });
});
</script>
@include('partials.voucher-tnc-modal')
@endsection
