@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
<style>
/* ─── Enterprise SaaS / Big Tech Dark Chat UI ─── */
.ticket-wrapper {
    max-width: 960px;
    margin: 0 auto;
}

/* ─── Header Card ─── */
.ticket-header-card {
    background: linear-gradient(145deg, #181920 0%, #22242e 100%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    color: #fff;
}
@media (max-width: 576px) {
    .ticket-header-card { padding: 20px 18px; border-radius: 18px; }
}

/* ─── Status Badges ─── */
.badge-status-open        { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
.badge-status-in_progress { background: rgba(13, 110, 253, 0.15); color: #6ea8fe; border: 1px solid rgba(13, 110, 253, 0.3); }
.badge-status-resolved    { background: rgba(25, 135, 84, 0.15); color: #75b798; border: 1px solid rgba(25, 135, 84, 0.3); }
.badge-status-closed      { background: rgba(255, 255, 255, 0.08); color: #a8aabc; border: 1px solid rgba(255, 255, 255, 0.15); }

/* ─── Chat Window Container ─── */
.chat-window {
    background: #121318;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.chat-messages {
    /* Desktop: fixed comfortable height */
    height: 560px;
    overflow-y: auto;
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    scroll-behavior: smooth;
}
@media (max-width: 767px) {
    /* Mobile: let messages expand naturally up to viewport, no wasted whitespace */
    .chat-messages {
        height: auto;
        min-height: 260px;
        max-height: calc(60vh - 60px);
    }
}
.chat-messages::-webkit-scrollbar { width: 6px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 6px; }
.chat-messages::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }

/* ─── Message Rows & Bubbles ─── */
.msg-row {
    display: flex;
    align-items: flex-end;
    gap: 14px;
    animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    width: 100%;
}
.msg-row.from-me {
    flex-direction: row-reverse;
    justify-content: flex-start;
}
.msg-row.from-other {
    flex-direction: row;
    justify-content: flex-start;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

.msg-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.9rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
    padding: 0;
}
.msg-avatar.admin-avatar {
    background: linear-gradient(135deg, #EC1F24, #9a0f13);
    color: #fff;
    border: 2px solid rgba(236, 31, 36, 0.4);
}
.msg-avatar.user-avatar {
    background: linear-gradient(135deg, #3a3f48, #212529);
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.15);
}

.msg-content {
    display: flex;
    flex-direction: column;
    max-width: calc(100% - 56px);
}
@media (min-width: 768px) {
    .msg-content {
        max-width: 78%;
    }
}
.from-me .msg-content {
    align-items: flex-end;
}
.from-other .msg-content {
    align-items: flex-start;
}

.msg-bubble {
    position: relative;
    max-width: 100%;
    padding: 16px 20px;
    border-radius: 22px;
    font-size: 0.96rem;
    line-height: 1.65;
    word-break: break-word;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}
.from-me .msg-bubble {
    background: linear-gradient(135deg, #EC1F24, #c91419);
    color: #ffffff;
    border-bottom-right-radius: 6px;
}
.from-other .msg-bubble {
    background: #1e2029;
    color: #e4e4e7;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-bottom-left-radius: 6px;
}

.msg-meta {
    font-size: 0.75rem;
    margin-top: 6px;
    color: rgba(255, 255, 255, 0.45);
    padding: 0 6px;
}
.from-me .msg-meta { text-align: right; }
.from-other .msg-meta { text-align: left; }

@media (max-width: 576px) {
    .chat-messages { padding: 14px !important; gap: 12px !important; }
    .msg-avatar { width: 34px; height: 34px; font-size: 0.8rem; flex-shrink: 0; }
    .msg-content { max-width: calc(100% - 44px); }
    .msg-bubble { padding: 10px 14px; font-size: 0.9rem; border-radius: 16px; }
}

/* ─── Attachments inside Bubbles ─── */
.attachment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 215px), 1fr));
    gap: 12px;
    width: 100%;
    max-width: 100%;
}
@media (max-width: 576px) {
    /* Single-column image grid so images don't overflow narrow bubbles */
    .attachment-grid { grid-template-columns: 1fr; gap: 8px; }
}
.attachment-grid > * {
    min-width: 0 !important;
    max-width: 100% !important;
}
.attachment-img-thumb {
    width: 100%; height: 130px;
    object-fit: cover;
    border-radius: 14px;
    cursor: pointer;
    border: 2px solid rgba(255, 255, 255, 0.25);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s;
}
.attachment-img-thumb:hover { transform: scale(1.03); box-shadow: 0 6px 18px rgba(0,0,0,0.3); border-color: #fff; }
@media (max-width: 576px) {
    .attachment-img-thumb { height: 110px; border-radius: 12px; }
}

.attachment-file-card {
    display: flex; align-items: center; gap: 12px;
    border-radius: 14px;
    padding: 12px 14px;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    font-size: 0.86rem;
    min-width: 0 !important;
    max-width: 100% !important;
    overflow: hidden !important;
}
/* Inside My Red Bubble */
.from-me .attachment-file-card {
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.32);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}
.from-me .attachment-file-card:hover {
    background: rgba(255, 255, 255, 0.26);
    border-color: rgba(255, 255, 255, 0.6);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
}
.from-me .attachment-file-card .att-icon-box {
    width: 40px; height: 40px; border-radius: 10px;
    background: #ffffff; color: #EC1F24;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.from-me .attachment-file-card .att-dl-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.2); color: #ffffff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-left: auto; transition: all 0.2s;
}
.from-me .attachment-file-card:hover .att-dl-icon {
    background: #ffffff; color: #EC1F24; transform: scale(1.08);
}

/* Inside Other/Admin Dark Bubble */
.from-other .attachment-file-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #ffffff;
}
.from-other .attachment-file-card:hover {
    background: rgba(255, 255, 255, 0.09);
    border-color: #EC1F24; color: #ffffff;
    transform: translateY(-2px);
}
.from-other .attachment-file-card .att-icon-box {
    width: 40px; height: 40px; border-radius: 10px;
    background: rgba(236, 31, 36, 0.15); color: #EC1F24;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.from-other .attachment-file-card .att-dl-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.08); color: rgba(255,255,255,0.7);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-left: auto; transition: all 0.2s;
}
.from-other .attachment-file-card:hover .att-dl-icon {
    background: #EC1F24; color: #ffffff;
}

/* ─── Reply Input Area ─── */
.chat-reply-box {
    background: #161820;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding: 22px 28px;
}
@media (max-width: 576px) {
    .chat-reply-box { padding: 14px 14px 18px; }
}
.chat-reply-box textarea {
    background: #1c1e28;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    resize: none;
    border-radius: 16px;
    padding: 16px 20px;
    font-size: 0.95rem;
    transition: all 0.25s ease;
}
@media (max-width: 576px) {
    .chat-reply-box textarea { padding: 12px 16px; font-size: 0.92rem; border-radius: 14px; }
}
.chat-reply-box textarea::placeholder { color: rgba(255, 255, 255, 0.35); }
.chat-reply-box textarea:focus {
    background: #212432;
    border-color: #EC1F24;
    box-shadow: 0 0 0 4px rgba(236, 31, 36, 0.25);
    color: #ffffff;
    outline: none;
}
.char-counter { font-size: 0.75rem; color: rgba(255, 255, 255, 0.4); text-align: right; margin-top: 6px; }
.char-counter.warning { color: #ffc107; }
.char-counter.danger  { color: #EC1F24; }

/* ─── Mobile compact file attach button ─── */
.mobile-attach-btn {
    display: none;
}
@media (max-width: 767px) {
    /* Hide the full drag-and-drop zone on mobile – replace with compact button row */
    .file-drop-zone { display: none !important; }
    .mobile-attach-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 12px;
        color: rgba(255,255,255,0.75);
        font-size: 0.85rem;
        padding: 8px 16px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .mobile-attach-btn:hover, .mobile-attach-btn:active {
        background: rgba(236,31,36,0.12);
        border-color: rgba(236,31,36,0.5);
        color: #fff;
    }
    .mobile-attach-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #EC1F24;
        color: #fff;
        border-radius: 50%;
        width: 18px; height: 18px;
        font-size: 0.65rem;
        font-weight: 700;
        line-height: 1;
    }
    /* Full-width send button on mobile */
    .send-btn-wrapper {
        margin-top: 14px;
    }
    .send-btn-wrapper .btn-send-msg {
        width: 100%;
        justify-content: center;
        padding-top: 13px;
        padding-bottom: 13px;
        font-size: 1rem;
        border-radius: 16px;
    }
}

/* ─── Modern Drag & Drop File Upload ─── */
.file-drop-zone {
    border: 2px dashed rgba(255, 255, 255, 0.22);
    border-radius: 18px;
    min-height: 155px;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.025);
    color: rgba(255, 255, 255, 0.65);
    font-size: 0.88rem;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
.file-drop-zone:hover {
    border-color: rgba(236, 31, 36, 0.6);
    background: rgba(236, 31, 36, 0.04);
    color: #ffffff;
    transform: translateY(-2px);
}
.file-drop-zone.dragover {
    border: 2px solid #EC1F24 !important;
    background: linear-gradient(135deg, rgba(236, 31, 36, 0.25) 0%, rgba(236, 31, 36, 0.1) 100%) !important;
    box-shadow: 0 0 35px rgba(236, 31, 36, 0.45), inset 0 0 20px rgba(236, 31, 36, 0.2) !important;
    transform: scale(1.02);
}
.file-drop-zone.dragover .dz-state-default { display: none !important; }
.file-drop-zone.dragover .dz-state-active { display: flex !important; animation: dzPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
.pointer-events-none { pointer-events: none !important; }
.file-drop-zone * { pointer-events: none !important; }
.file-preview-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
.file-preview-item {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 10px;
    padding: 6px 14px;
    font-size: 0.82rem;
    color: #fff;
}
.file-preview-item .remove-file { cursor: pointer; color: #ff6b6b; font-weight: bold; margin-left: 4px; }

/* ─── Banners & Glass Cards ─── */
.ticket-closed-banner {
    background: #181920;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding: 20px;
    text-align: center;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}
.custom-glass-card {
    background: #181920;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    color: #fff;
}

/* ─── Lightbox ─── */
.lightbox-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0, 0, 0, 0.92);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    cursor: zoom-out;
    backdrop-filter: blur(10px);
}
.lightbox-overlay.active { display: flex; }
.lightbox-overlay img {
    max-width: 88vw;
    max-height: 88vh;
    border-radius: 12px;
    object-fit: contain;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8);
}
</style>
@endsection

@section('content')
<div class="container my-5 my-sm-3 acct-dark">
    <div class="ticket-wrapper">

        {{-- Navigation Bar --}}
        <div class="d-flex align-items-center justify-content-between mb-4 mb-sm-3">
            <a href="{{ route('help-centre.index') }}" class="btn btn-outline-light rounded-pill px-3 px-sm-4 py-2 small fw-semibold d-inline-flex align-items-center gap-2" style="border-color: rgba(255,255,255,0.2);">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="d-none d-sm-inline">@if(app()->getLocale() == 'zh') 返回帮助中心 @else Back to Help Centre @endif</span>
                <span class="d-sm-none">@if(app()->getLocale() == 'zh') 返回 @else Back @endif</span>
            </a>
            <span class="small text-muted d-none d-sm-inline"><i class="fa-solid fa-shield-halved text-brand me-1"></i> End-to-End Secure Support</span>
            <span class="small text-muted d-sm-none"><i class="fa-solid fa-shield-halved text-brand"></i></span>
        </div>

        {{-- ─── Header Card ─── --}}
        <div class="ticket-header-card mb-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-4">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-danger bg-opacity-25 text-danger px-3 py-1 rounded-pill fw-bold" style="font-size:0.75rem; letter-spacing:0.06em;">{{ __('account.st_support_ticket') }}</span>
                        <code class="text-light bg-black bg-opacity-50 px-3 py-1 rounded-pill small border border-secondary border-opacity-25">Ticket #{{ $ticket->ticket_number }}</code>
                    </div>
                    <h3 class="fw-bold text-white mb-3">{{ $ticket->subject }}</h3>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge badge-status-{{ $ticket->status }} px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-circle me-1" style="font-size:0.5rem;"></i> {{ __('admin.ticket_status_'.$ticket->status) }}
                        </span>
                        <span class="badge bg-secondary bg-opacity-25 text-light px-3 py-2 rounded-pill border border-secondary border-opacity-25">{{ __('admin.ticket_type_'.$ticket->type) }}</span>
                        @if($ticket->booking)
                            <a href="{{ route('bookings.show', $ticket->booking->uuid) }}" class="badge bg-brand bg-opacity-25 text-white px-3 py-2 rounded-pill border border-danger border-opacity-25 text-decoration-none">
                                <i class="fa-solid fa-link me-1"></i> Booking #{{ $ticket->booking->number }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="text-md-end pt-1">
                    <div class="small text-muted mb-1 text-uppercase tracking-wider">Submitted On</div>
                    <div class="fw-bold text-white fs-6">{{ $ticket->created_at->format('d M Y, h:i A') }}</div>
                    <div class="small text-muted mb-3">{{ $ticket->created_at->diffForHumans() }}</div>

                    @if(!in_array($ticket->status, ['resolved', 'closed']))
                        <form action="{{ route('support-tickets.resolve', $ticket->ticket_number) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-4 py-2 fw-bold"
                                    onclick="return confirm('Is your issue resolved? Click to confirm.')">
                                <i class="fa-solid fa-check-double me-1"></i> Mark as Resolved
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ─── Chat Window ─── --}}
        <div class="chat-window mb-4">

            {{-- Messages Area --}}
            <div class="chat-messages" id="chat-messages">
                @forelse($messages as $msg)
                    @php $isMe = $msg->sender_id === auth()->id(); @endphp
                    <div class="msg-row {{ $isMe ? 'from-me' : 'from-other' }}" id="msg-{{ $msg->id }}">
                        <div class="msg-avatar {{ $isMe ? 'user-avatar' : 'admin-avatar' }}" title="{{ $isMe ? 'You' : ($msg->sender->name ?? 'Support') }}">
                            <img src="{{ $msg->sender?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($msg->sender?->name ?? ($isMe ? 'User' : 'Support')).'&background='.($isMe ? '3a3f48' : 'EC1F24').'&color=fff&size=64' }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="msg-content">
                            <div class="msg-bubble">
                                <div style="white-space:pre-wrap;">{{ $msg->body }}</div>

                                {{-- Attachments --}}
                                @if($msg->attachments->isNotEmpty())
                                    <div class="attachment-grid {{ !empty(trim($msg->body)) ? 'pt-3 mt-3 border-top border-white border-opacity-25' : '' }}">
                                        @foreach($msg->attachments as $att)
                                            @if($att->isImage())
                                                <img src="{{ $att->getUrl() }}"
                                                     class="attachment-img-thumb"
                                                     alt="{{ $att->original_filename }}"
                                                     onclick="openLightbox('{{ $att->getUrl() }}')"
                                                     title="{{ $att->original_filename }}">
                                            @else
                                                <a href="{{ route('attachments.download', $att) }}"
                                                   class="attachment-file-card">
                                                    <div class="att-icon-box">
                                                        <i class="fa-solid fa-file-pdf"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width: 0; overflow: hidden;">
                                                        <div class="fw-bold text-truncate text-white" style="font-size: 0.86rem; max-width: 100%;" title="{{ $att->original_filename }}">
                                                            {{ $att->original_filename }}
                                                        </div>
                                                        <div style="opacity:.75; font-size:0.73rem;">{{ $att->getHumanFileSize() }}</div>
                                                    </div>
                                                    <div class="att-dl-icon">
                                                        <i class="fa-solid fa-download fs-6"></i>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="msg-meta">
                                <strong>{{ $isMe ? 'You' : ($msg->sender->name ?? 'Support Agent') }}</strong>
                                &middot; {{ $msg->created_at->format('d M, h:i A') }}
                                @if($msg->is_edited)
                                    &middot; <span class="fst-italic">edited</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5 my-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10 p-4 mb-3" style="width:80px;height:80px;">
                            <i class="fa-solid fa-comments fs-2 text-brand"></i>
                        </div>
                        <h6 class="fw-bold text-white mb-1">Live Chat Created</h6>
                        <p class="small mb-0">Your request has been routed to our support specialists. Our team will reply shortly.</p>
                    </div>
                @endforelse
                <div id="messages-bottom"></div>
            </div>

            {{-- Reply Box or Locked Banner --}}
            @if($ticket->is_locked)
                <div class="ticket-closed-banner text-danger">
                    <i class="fa-solid fa-lock me-2"></i>
                    This ticket has been <strong>locked</strong> by an administrator and no longer accepts new replies.
                </div>
            @elseif($ticket->canReceiveMessages())
                @if($ticket->status === 'resolved')
                    <div class="p-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-top" style="background: rgba(25, 135, 84, 0.15); border-color: rgba(25, 135, 84, 0.3) !important;">
                        <div class="d-flex align-items-center gap-2 text-light small">
                            <i class="fa-solid fa-circle-check text-success fs-5"></i>
                            <span>This ticket is marked as <strong>Resolved</strong>. If your issue is solved, you can close it below or send a reply to reopen.</span>
                        </div>
                        <form action="{{ route('support-tickets.close', $ticket->ticket_number) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Permanently close this ticket?')">
                                <i class="fa-solid fa-lock me-1"></i> {{ __('account.st_close_ticket') }}
                            </button>
                        </form>
                    </div>
                @endif
                <div class="chat-reply-box">
                    <form action="{{ route('support-tickets.message', $ticket->ticket_number) }}"
                          method="POST" enctype="multipart/form-data" id="reply-form">
                        @csrf

                        <textarea name="body" id="reply-body" class="form-control mb-1" rows="3"
                                  placeholder="Type your reply here to message our support team..." maxlength="5000" required>{{ old('body') }}</textarea>
                        <div class="char-counter" id="char-count">0 / 5000</div>

                        {{-- Desktop: full drag-and-drop upload zone --}}
                        <div class="file-drop-zone mt-3" id="drop-zone" onclick="document.getElementById('file-input').click()">
                            <div class="dz-state-default d-flex flex-column align-items-center justify-content-center pointer-events-none w-100">
                                <i class="fa-solid fa-cloud-arrow-up fs-3 text-brand mb-2 d-block"></i>
                                <span class="fw-semibold text-light d-block mb-1">Click or drag files here to attach to your reply</span>
                                <span style="font-size:0.75rem; opacity:0.5;">Supported formats: JPG, PNG, PDF (Up to 5 files, max 10MB each)</span>
                            </div>
                            <div class="dz-state-active d-none flex-column align-items-center justify-content-center pointer-events-none w-100 py-1">
                                <i class="fa-solid fa-file-arrow-down fs-2 text-white mb-2"></i>
                                <span class="fw-bold text-white fs-6 mb-1">DROP FILES HERE TO ATTACH</span>
                                <span class="badge bg-white text-brand fw-bold rounded-pill px-3 py-1 mt-1">✨ Ready to attach!</span>
                            </div>
                            <input type="file" id="file-input" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" style="display:none">
                        </div>

                        {{-- Mobile: compact attach button (file-input shared with desktop zone) --}}
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <button type="button" class="mobile-attach-btn" id="mobile-attach-btn"
                                    onclick="document.getElementById('file-input').click()">
                                <i class="fa-solid fa-paperclip"></i>
                                <span>Attach Files</span>
                                <span class="mobile-attach-badge" id="mobile-attach-count" style="display:none;">0</span>
                            </button>
                            <span class="text-muted" style="font-size:0.72rem; opacity:0.55;">JPG, PNG, PDF · max 10 MB</span>
                        </div>

                        <div class="file-preview-list" id="file-preview-list"></div>

                        @error('body')<div class="text-danger small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror
                        @error('attachments.*')<div class="text-danger small mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</div>@enderror

                        <div class="send-btn-wrapper d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-brand rounded-pill px-5 fw-bold shadow-lg d-flex align-items-center gap-2 btn-send-msg" id="send-btn" style="padding-top:11px;padding-bottom:11px;">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Send Message</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="ticket-closed-banner">
                    <i class="fa-solid fa-lock me-2 text-warning"></i>
                    This ticket is <strong>Closed</strong> and archived. Need further assistance? <a href="{{ route('help-centre.index') }}#chat-agent" class="text-brand fw-bold text-decoration-none">Open a New Support Ticket</a>.
                </div>
            @endif
        </div>

        {{-- CSAT Rating Card --}}
        @if(in_array($ticket->status, ['resolved', 'closed']))
            @if(!$ticket->rating)
                <div class="custom-glass-card p-5 text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 p-3 mb-3 text-warning fs-3">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">{{ __('account.st_how_did_we_do') }}</h4>
                    <p class="text-muted small mb-4 max-w-md mx-auto">Please rate your overall experience with our support team on this request. Your feedback helps us continuously improve.</p>
                    <form action="{{ route('support-tickets.rate', $ticket->ticket_number) }}" method="POST" style="max-width: 480px; margin: 0 auto;">
                        @csrf
                        <div class="d-flex justify-content-center gap-3 mb-4" id="rating-stars">
                            @for($i=1; $i<=5; $i++)
                                <button type="button" class="btn btn-outline-warning rating-btn rounded-circle d-flex align-items-center justify-content-center p-0" data-val="{{ $i }}" style="width: 50px; height: 50px; font-size:1.4rem;">
                                    <i class="fa-regular fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" required>
                        <textarea name="feedback" class="form-control mb-3 p-3" rows="3" placeholder="Tell us what went well or what could be improved... (Optional)" style="border-radius: 14px; background: rgba(255,255,255,0.05); color:#fff; border: 1px solid rgba(255,255,255,0.15);"></textarea>
                        <button type="submit" class="btn btn-brand rounded-pill px-5 py-2.5 fw-bold shadow-sm" id="submit-rating" disabled>{{ __('account.st_submit_feedback') }}</button>
                    </form>
                </div>
            @else
                <div class="custom-glass-card p-5 text-center mb-4">
                    <h5 class="fw-bold text-white mb-2"><i class="fa-solid fa-heart text-danger me-2"></i>Thank you for rating our support!</h5>
                    <div class="text-warning fs-3 my-3">
                        @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= $ticket->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                        @endfor
                    </div>
                    @if($ticket->feedback)
                        <div class="text-light small fst-italic max-w-md mx-auto p-3 rounded-3" style="background: rgba(255,255,255,0.04);">"{{ $ticket->feedback }}"</div>
                    @endif
                </div>
            @endif
        @endif

        {{-- Linked Booking Banner --}}
        @if($ticket->booking)
            <div class="custom-glass-card p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-brand bg-opacity-25 text-brand" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="fa-solid fa-car"></i>
                        </div>
                        <div>
                            <div class="small text-muted text-uppercase tracking-wider">Linked Service Booking</div>
                            <div class="fw-bold text-white fs-6">#{{ $ticket->booking->number }} &middot; {{ $ticket->booking->service->name ?? 'Service' }}</div>
                            <div class="small text-muted">
                                @if($ticket->booking->branch) {{ $ticket->booking->branch->name }} &middot; @endif
                                {{ $ticket->booking->booking_date ? $ticket->booking->booking_date->format('d M Y') : '' }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('bookings.show', $ticket->booking->uuid) }}" class="btn btn-outline-light rounded-pill px-4 py-2 small fw-semibold" style="border-color: rgba(255,255,255,0.2);">
                        <span>View Booking Details</span>
                        <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Lightbox Modal --}}
<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox()">
    <img src="" id="lightbox-img" alt="Preview">
</div>
@endsection

@section('scripts')
<script>
/* ─── Auto scroll to bottom ─── */
(function() {
    const el = document.getElementById('chat-messages');
    if (el) el.scrollTop = el.scrollHeight;
})();

/* ─── Character counter ─── */
const body = document.getElementById('reply-body');
const counter = document.getElementById('char-count');
if (body && counter) {
    body.addEventListener('input', function() {
        const len = this.value.length;
        counter.textContent = len + ' / 5000';
        counter.className = 'char-counter' + (len > 4500 ? ' danger' : len > 4000 ? ' warning' : '');
    });
}

/* ─── File upload with preview ─── */
const fileInput = document.getElementById('file-input');
const previewList = document.getElementById('file-preview-list');
const dropZone = document.getElementById('drop-zone');
let selectedFiles = [];

if (fileInput) {
    fileInput.addEventListener('change', function() { handleFiles(Array.from(this.files)); });
}

if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', e => {
        if (!dropZone.contains(e.relatedTarget)) {
            dropZone.classList.remove('dragover');
        }
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        handleFiles(Array.from(e.dataTransfer.files));
    });
}

async function handleFiles(files) {
    const allowed = ['image/jpeg','image/png','image/gif','image/webp','application/pdf'];
    const maxSize = 10 * 1024 * 1024;
    let errors = [];

    for (let f of files) {
        if (selectedFiles.length >= 5) { errors.push('{{ __("messages.err_max_5_files") }}'); break; }
        if (!allowed.includes(f.type)) { errors.push(`${f.name}: {{ __("messages.err_invalid_file_type") }}`); continue; }
        if (f.size > maxSize) { errors.push(`${f.name}: {{ __("messages.err_exceeds_10mb") }}`); continue; }
        if (window.compressImageFile) f = await window.compressImageFile(f);
        if (!selectedFiles.find(sf => sf.name === f.name && sf.size === f.size)) {
            selectedFiles.push(f);
        }
    }

    if (errors.length) alert(errors.join('\n'));
    renderPreviews();
    syncFileInput();
}

function renderPreviews() {
    if (!previewList) return;
    previewList.innerHTML = '';
    selectedFiles.forEach((f, i) => {
        const isImg = f.type.startsWith('image/');
        const item = document.createElement('div');
        item.className = 'file-preview-item';
        const sizeStr = f.originalSize
            ? `<span style="color:#20c997;font-weight:700;">⚡ ${(f.size/1024).toFixed(0)} KB <s style="opacity:.6;font-weight:400;color:#fff;">(${(f.originalSize/1024/1024).toFixed(1)} MB)</s></span>`
            : `<span style="opacity:.6;font-size:.7rem;">(${(f.size/1024).toFixed(0)} KB)</span>`;
        item.innerHTML = `<i class="fa-solid ${isImg ? 'fa-image' : 'fa-file-pdf'} text-brand"></i>
            <span style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</span>
            ${sizeStr}
            <span class="remove-file" onclick="removeFile(${i})">✕</span>`;
        previewList.appendChild(item);
    });
    /* Update mobile attach badge count */
    const badge = document.getElementById('mobile-attach-count');
    if (badge) {
        if (selectedFiles.length > 0) {
            badge.textContent = selectedFiles.length;
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderPreviews();
    syncFileInput();
}

function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    if (fileInput) fileInput.files = dt.files;
}

/* ─── Lightbox ─── */
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

/* ─── Auto-refresh for new messages (every 30s) ─── */
@if($ticket->canReceiveMessages() && !$ticket->is_locked)
setTimeout(function() {
    const draft = document.getElementById('reply-body');
    if (!draft || !draft.value.trim()) {
        window.location.reload();
    }
}, 30000);
@endif

/* ─── Rating System ─── */
const ratingStars = document.querySelectorAll('.rating-btn');
const ratingInput = document.getElementById('rating-input');
const submitRating = document.getElementById('submit-rating');

if (ratingStars.length > 0) {
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const val = parseInt(this.getAttribute('data-val'));
            ratingInput.value = val;
            submitRating.disabled = false;
            
            ratingStars.forEach(s => {
                const sVal = parseInt(s.getAttribute('data-val'));
                const icon = s.querySelector('i');
                if (sVal <= val) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    s.classList.add('btn-warning', 'text-dark');
                    s.classList.remove('btn-outline-warning');
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    s.classList.remove('btn-warning', 'text-dark');
                    s.classList.add('btn-outline-warning');
                }
            });
        });
    });
}
</script>
@endsection
