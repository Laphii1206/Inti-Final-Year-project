@extends('layouts.admin')

@section('styles')
<style>
/* ─── Admin Agent Workspace Premium SaaS Styling ─── */
.chat-panel {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #eaeeef;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.chat-header {
    background: #f8fafc;
    border-bottom: 1px solid #eaeeef;
    padding: 18px 24px;
}
.chat-messages {
    height: 580px;
    overflow-y: auto;
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    scroll-behavior: smooth;
    background: #fafbfc;
}
.chat-messages::-webkit-scrollbar { width: 6px; }
.chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
.chat-messages::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

/* ─── Message Bubbles ─── */
.msg-row {
    display: flex;
    align-items: flex-end;
    gap: 14px;
    animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    width: 100%;
}
.msg-row.from-admin {
    flex-direction: row-reverse;
    justify-content: flex-start;
}
.msg-row.from-customer {
    flex-direction: row;
    justify-content: flex-start;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

.msg-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.88rem; flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden; padding: 0;
}
.admin-avatar { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: #fff; }
.user-avatar  { background: linear-gradient(135deg, #475569, #1e293b); color: #fff; }

.msg-content {
    display: flex;
    flex-direction: column;
    max-width: calc(100% - 54px);
}
@media (min-width: 768px) {
    .msg-content {
        max-width: 78%;
    }
}
.from-admin .msg-content {
    align-items: flex-end;
}
.from-customer .msg-content {
    align-items: flex-start;
}

.msg-bubble {
    position: relative;
    max-width: 100%;
    padding: 16px 20px;
    border-radius: 20px;
    font-size: 0.95rem;
    line-height: 1.65;
    word-break: break-word;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}
.from-admin .msg-bubble {
    background: linear-gradient(135deg, #0d6efd, #0852c2);
    color: #ffffff;
    border-bottom-right-radius: 6px;
}
.from-customer .msg-bubble {
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 6px;
}

/* Internal Note Style */
.msg-bubble.internal {
    background: repeating-linear-gradient(
        -45deg, #fffbeb, #fffbeb 12px, #fef3c7 12px, #fef3c7 24px
    ) !important;
    border: 1.5px solid #f59e0b !important;
    color: #78350f !important;
}
.internal-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f59e0b; color: #ffffff;
    font-size: 0.68rem; font-weight: 700; padding: 2px 10px;
    border-radius: 20px; text-transform: uppercase;
    letter-spacing: 0.06em; margin-bottom: 8px;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);
}

.msg-meta { font-size: 0.75rem; margin-top: 6px; color: #64748b; padding: 0 4px; }
.from-admin .msg-meta { text-align: right; }
.from-customer .msg-meta { text-align: left; }

@media (max-width: 576px) {
    .chat-messages { padding: 16px !important; gap: 14px !important; }
    .msg-avatar { width: 36px; height: 36px; font-size: 0.82rem; }
    .msg-content { max-width: calc(100% - 48px); }
    .msg-bubble { padding: 12px 16px; font-size: 0.92rem; border-radius: 18px; }
}
.msg-edited { font-size: 0.68rem; font-style: italic; color: #94a3b8; }

.edit-msg-btn {
    opacity: 0; font-size: 0.75rem; padding: 3px 8px;
    transition: opacity 0.2s;
    color: #64748b;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}
.msg-row:hover .edit-msg-btn { opacity: 1; }
.from-admin .edit-inline-form { display: none; }

/* Attachments */
.attachment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 215px), 1fr));
    gap: 12px;
    width: 100%;
    max-width: 100%;
}
.attachment-grid > * {
    min-width: 0 !important;
    max-width: 100% !important;
}
.att-img {
    width: 100%; height: 130px; object-fit: cover;
    border-radius: 14px; cursor: pointer;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s;
}
.att-img:hover { transform: scale(1.03); box-shadow: 0 6px 18px rgba(0,0,0,0.25); }
.from-customer .att-img { border-color: #cbd5e1; }

.att-file {
    display: flex; align-items: center; gap: 12px;
    border-radius: 14px; padding: 12px 14px;
    text-decoration: none; color: inherit;
    font-size: 0.86rem; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    min-width: 0 !important;
    max-width: 100% !important;
    overflow: hidden !important;
}
/* Inside Admin Blue Bubble */
.from-admin .att-file {
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.32);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.from-admin .att-file:hover {
    background: rgba(255, 255, 255, 0.26);
    border-color: rgba(255, 255, 255, 0.6);
    color: #ffffff;
    transform: translateY(-2px);
}
.from-admin .att-file .att-icon-box {
    width: 40px; height: 40px; border-radius: 10px;
    background: #ffffff; color: #0d6efd;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.from-admin .att-file .att-dl-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.2); color: #ffffff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-left: auto; transition: all 0.2s;
}
.from-admin .att-file:hover .att-dl-icon { background: #ffffff; color: #0d6efd; transform: scale(1.08); }

/* Inside Customer White Bubble */
.from-customer .att-file {
    background: #f8fafc; color: #1e293b;
    border: 1px solid #cbd5e1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.from-customer .att-file:hover {
    background: #ffffff; color: #0f172a; border-color: #0d6efd;
    transform: translateY(-2px); box-shadow: 0 6px 16px rgba(13,110,253,0.1);
}
.from-customer .att-file .att-icon-box {
    width: 40px; height: 40px; border-radius: 10px;
    background: #eff6ff; color: #0d6efd;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.from-customer .att-file .att-dl-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: #f1f5f9; color: #64748b;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-left: auto; transition: all 0.2s;
}
.from-customer .att-file:hover .att-dl-icon { background: #0d6efd; color: #ffffff; }

/* ─── Reply Box ─── */
.reply-box { background: #ffffff; border-top: 1px solid #eaeeef; padding: 20px 24px; }
.reply-box textarea {
    resize: none; border-radius: 14px;
    border: 1.5px solid #cbd5e1; padding: 14px 18px;
    font-size: 0.94rem; transition: all 0.25s;
    background: #f8fafc;
}
.reply-box textarea:focus {
    background: #ffffff;
    border-color: #0d6efd; outline: none;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
}
.char-counter { font-size: 0.75rem; color: #94a3b8; text-align: right; margin-top: 4px; }
.char-counter.warn { color: #f59e0b; }
.char-counter.over { color: #ef4444; }

/* File Drop Zone */
.drop-zone {
    border: 2px dashed #cbd5e1; border-radius: 18px;
    min-height: 155px;
    padding: 30px 20px;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    text-align: center; cursor: pointer;
    font-size: 0.88rem; color: #64748b; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    background: #f8fafc; position: relative; overflow: hidden;
}
.drop-zone:hover { border-color: #0d6efd; background: #eff6ff; color: #0d6efd; transform: translateY(-2px); }
.drop-zone.dz-over, .drop-zone.dragover {
    border: 2px solid #0d6efd !important;
    background: linear-gradient(135deg, #e0eafe 0%, #eff6ff 100%) !important;
    box-shadow: 0 0 35px rgba(13, 110, 253, 0.25), inset 0 0 20px rgba(13, 110, 253, 0.1) !important;
    transform: scale(1.02);
}
.drop-zone.dz-over .dz-state-default, .drop-zone.dragover .dz-state-default { display: none !important; }
.drop-zone.dz-over .dz-state-active, .drop-zone.dragover .dz-state-active { display: flex !important; animation: dzPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
.pointer-events-none { pointer-events: none !important; }
.drop-zone * { pointer-events: none !important; }
.file-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.file-chip {
    display: flex; align-items: center; gap: 6px;
    background: #f1f5f9; border: 1px solid #e2e8f0;
    border-radius: 20px; padding: 5px 12px; font-size: 0.78rem;
    color: #334155; font-weight: 500;
}
.remove-chip { cursor: pointer; color: #ef4444; font-weight: bold; margin-left: 4px; }

/* ─── Sidebar Property Cards ─── */
.property-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #eaeeef;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
}
.property-card label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; font-weight: 700; margin-bottom: 8px; display: block; }

/* Lightbox */
.lb-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0, 0, 0, 0.9); z-index: 9999;
    align-items: center; justify-content: center; cursor: zoom-out;
    backdrop-filter: blur(8px);
}
.lb-overlay.on { display: flex; }
.lb-overlay img { max-width: 88vw; max-height: 88vh; border-radius: 12px; object-fit: contain; }
</style>
@endsection

@section('content')
{{-- Navigation & Top Header --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-white border shadow-sm rounded-pill px-3 py-2 text-dark small fw-semibold d-flex align-items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i><span>{{ __('admin.tk_all_t') }}</span>
        </a>
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark px-2.5 py-1 font-monospace">Ticket #{{ $supportTicket->ticket_number }}</span>
                <span class="badge bg-light text-dark border px-2.5 py-1">{{ __('admin.ticket_type_'.$supportTicket->type) }}</span>
            </div>
            <h4 class="fw-bold mb-0 text-dark mt-1">{{ $supportTicket->subject }}</h4>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="small text-muted me-2"><i class="fa-regular fa-clock me-1"></i> Opened {{ $supportTicket->created_at->format('d M Y, h:i A') }}</span>
    </div>
</div>

<div class="row g-4">
    {{-- ─── LEFT: Live Chat Workspace ─── --}}
    <div class="col-lg-8">
        <div class="chat-panel">
            <div class="chat-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-success" style="width: 10px; height: 10px; display: inline-block;"></span>
                    <span class="fw-bold text-dark small">Customer Thread &middot; {{ $supportTicket->user->name }}</span>
                </div>
                <span class="badge bg-light text-muted border">{{ $supportTicket->messages->count() }} messages total</span>
            </div>

            {{-- Messages Area --}}
            <div class="chat-messages" id="chat-messages">
                @forelse($supportTicket->messages as $msg)
                    @php $isAdmin = $msg->sender_role === 'admin'; @endphp
                    <div class="msg-row {{ $isAdmin ? 'from-admin' : 'from-customer' }}" id="msg-{{ $msg->id }}">
                        <div class="msg-avatar {{ $isAdmin ? 'admin-avatar' : 'user-avatar' }}" title="{{ $isAdmin ? 'Admin Agent' : 'Customer' }}">
                            <img src="{{ $msg->sender?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($msg->sender?->name ?? ($isAdmin ? 'Admin' : 'Customer')).'&background='.($isAdmin ? '0d6efd' : '475569').'&color=fff&size=64' }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="msg-content">
                            {{-- Inline Edit Form (Admin only) --}}
                            @if($isAdmin && $msg->sender_id === auth()->id())
                                <form action="{{ route('admin.support-tickets.message.edit', $msg) }}"
                                      method="POST" class="edit-inline-form mb-2" id="edit-form-{{ $msg->id }}">
                                    @csrf @method('PUT')
                                    <textarea name="body" class="form-control mb-2" rows="3"
                                              style="border-radius:12px;font-size:0.9rem;">{{ $msg->body }}</textarea>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                                onclick="cancelEdit({{ $msg->id }})">{{ __('admin.tk_cancel') }}</button>
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">{{ __('admin.tk_save') }}</button>
                                    </div>
                                </form>
                            @endif

                            <div class="msg-bubble {{ $msg->is_internal ? 'internal' : '' }}">
                                @if($msg->is_internal)
                                    <div class="internal-badge"><i class="fa-solid fa-lock"></i> {{ __('admin.tk_int_note') }} (Hidden from customer)</div>
                                @endif
                                <div style="white-space: pre-wrap;">{{ $msg->body }}</div>

                                {{-- Attachments --}}
                                @if($msg->attachments->isNotEmpty())
                                    <div class="attachment-grid {{ !empty(trim($msg->body)) ? 'pt-3 mt-3 border-top border-opacity-25 ' . ($isAdmin ? 'border-white' : 'border-secondary') : '' }}">
                                        @foreach($msg->attachments as $att)
                                            @if($att->isImage())
                                                <img src="{{ $att->getUrl() }}" class="att-img"
                                                     onclick="openLb('{{ $att->getUrl() }}')"
                                                     title="{{ $att->original_filename }}" alt="">
                                            @else
                                                <a href="{{ route('attachments.download', $att) }}" class="att-file">
                                                    <div class="att-icon-box">
                                                        <i class="fa-solid fa-file-pdf"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width: 0; overflow: hidden;">
                                                        <div class="fw-bold text-truncate" style="font-size: 0.86rem; max-width: 100%;" title="{{ $att->original_filename }}">{{ $att->original_filename }}</div>
                                                        <div style="opacity:0.75;font-size:0.73rem;">{{ $att->getHumanFileSize() }}</div>
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

                            <div class="d-flex align-items-center gap-2 {{ $isAdmin ? 'flex-row-reverse' : '' }}">
                                <div class="msg-meta">
                                    <strong>{{ $msg->sender->name ?? ($isAdmin ? 'Admin Support' : __('admin.tic_customer')) }}</strong>
                                    &middot; {{ $msg->created_at->format('d M, h:i A') }}
                                    @if($msg->is_edited)
                                        &middot; <span class="msg-edited">{{ __('admin.tk_edited') }}</span>
                                    @endif
                                </div>
                                @if($isAdmin && $msg->sender_id === auth()->id())
                                    <button class="edit-msg-btn border-0" onclick="startEdit({{ $msg->id }})" title="Edit message">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5 my-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light p-4 mb-3" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-comments fs-3 text-secondary opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark">{{ __('admin.tk_no_msgs') }}</h6>
                        <p class="small mb-0">No replies yet. Use the reply box below to send the first response.</p>
                    </div>
                @endforelse
                <div id="messages-bottom"></div>
            </div>

            {{-- Reply Box --}}
            @if($supportTicket->canReceiveMessages())
                <div class="reply-box">
                    <form action="{{ route('admin.support-tickets.message', $supportTicket) }}"
                          method="POST" enctype="multipart/form-data" id="reply-form">
                        @csrf

                        {{-- Reply Type Switcher --}}
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                            <div class="d-flex gap-3 bg-light p-1.5 rounded-pill border">
                                <label class="form-check-label d-flex align-items-center gap-2 small fw-bold cursor-pointer px-3 py-1 rounded-pill {{ !old('is_internal') ? 'bg-primary text-white shadow-sm' : 'text-dark' }}" id="lbl-public" onclick="setReplyType(0)">
                                    <input type="radio" name="is_internal" value="0" checked class="d-none">
                                    <i class="fa-solid fa-reply"></i>
                                    <span>{{ __('admin.tk_reply_c') }}</span>
                                </label>
                                <label class="form-check-label d-flex align-items-center gap-2 small fw-bold cursor-pointer px-3 py-1 rounded-pill {{ old('is_internal') ? 'bg-warning text-dark shadow-sm' : 'text-muted' }}" id="lbl-internal" onclick="setReplyType(1)">
                                    <input type="radio" name="is_internal" value="1" class="d-none">
                                    <i class="fa-solid fa-lock"></i>
                                    <span>{{ __('admin.tk_int_note') }}</span>
                                </label>
                            </div>

                            @if($cannedResponses->isNotEmpty())
                                <select class="form-select form-select-sm w-auto rounded-pill px-3 py-1 border-secondary border-opacity-25 shadow-sm" id="canned-responses" onchange="insertCannedResponse(this)" style="font-size:0.82rem;">
                                    <option value="">⚡ {{ __('admin.tk_canned') }}</option>
                                    @foreach($cannedResponses as $response)
                                        <option value="{{ $response->body }}">{{ $response->title }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <textarea name="body" id="reply-body" class="form-control mb-1" rows="4"
                                  placeholder="{{ __('admin.tk_reply_ph') }}" maxlength="5000" required></textarea>
                        <div class="char-counter" id="char-count">0 / 5000</div>

                        <div class="drop-zone mt-2" id="drop-zone" onclick="document.getElementById('file-input').click()">
                            <div class="dz-state-default d-flex flex-column align-items-center justify-content-center pointer-events-none w-100">
                                <i class="fa-solid fa-cloud-arrow-up fs-4 text-primary mb-2 d-block"></i>
                                <span class="fw-semibold text-dark d-block mb-1">{{ __('admin.tk_attach_opt') }}</span>
                                <span style="font-size:0.75rem; opacity:0.6;">Supports JPG, PNG, PDF (Up to 5 files, max 10MB each)</span>
                            </div>
                            <div class="dz-state-active d-none flex-column align-items-center justify-content-center pointer-events-none w-100 py-1">
                                <i class="fa-solid fa-file-arrow-down fs-2 text-primary mb-2"></i>
                                <span class="fw-bold text-primary fs-6 mb-1">DROP FILES HERE TO ATTACH</span>
                                <span class="badge bg-primary text-white fw-bold rounded-pill px-3 py-1 mt-1">✨ Ready to attach!</span>
                            </div>
                            <input type="file" id="file-input" name="attachments[]" multiple
                                   accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" style="display:none">
                        </div>
                        <div class="file-chips" id="file-chips"></div>

                        @error('body')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold shadow-sm d-flex align-items-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>{{ __('admin.tk_send') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center py-4 text-muted border-top bg-light" style="font-size:0.9rem;">
                    <i class="fa-solid fa-lock me-2 text-secondary"></i>{{ __('admin.tk_is') }} <strong>{{ __('admin.ticket_status_'.$supportTicket->status) }}</strong>{{ __('admin.tk_reopen') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ─── RIGHT: Properties Sidebar ─── --}}
    <div class="col-lg-4">

        {{-- Status Selector --}}
        <div class="property-card">
            <label><i class="fa-solid fa-list-check me-1.5 text-primary"></i> {{ __('admin.tk_status') }}</label>
            <form action="{{ route('admin.support-tickets.status', $supportTicket) }}" method="POST" class="mt-2">
                @csrf
                <div class="d-flex gap-2">
                    <select name="status" class="form-select fw-semibold">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ $supportTicket->status === $key ? 'selected' : '' }}>{{ __('admin.ticket_status_'.$key) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-dark px-4 fw-bold rounded-3">{{ __('admin.tk_save') }}</button>
                </div>
            </form>
        </div>

        {{-- Priority Selector --}}
        <div class="property-card">
            <label><i class="fa-solid fa-flag me-1.5 text-danger"></i> {{ __('admin.tk_priority') }}</label>
            <form action="{{ route('admin.support-tickets.priority', $supportTicket) }}" method="POST" class="mt-2">
                @csrf
                <div class="d-flex gap-2">
                    <select name="priority" class="form-select fw-semibold">
                        @foreach(\App\Models\SupportTicket::PRIORITIES as $key => $label)
                            <option value="{{ $key }}" {{ ($supportTicket->priority ?? 'medium') === $key ? 'selected' : '' }}>{{ __('admin.ticket_priority_'.$key) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-dark px-4 fw-bold rounded-3">{{ __('admin.tk_save') }}</button>
                </div>
            </form>
        </div>

        {{-- Assignee Selector --}}
        <div class="property-card">
            <label><i class="fa-solid fa-user-shield me-1.5 text-info"></i> {{ __('admin.tk_assign') }}</label>
            <form action="{{ route('admin.support-tickets.assign', $supportTicket) }}" method="POST" class="mt-2">
                @csrf
                <div class="d-flex gap-2">
                    <select name="assigned_to" class="form-select fw-semibold">
                        <option value="">-- {{ __('admin.tk_unassign') }} --</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ $supportTicket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-dark px-4 fw-bold rounded-3">{{ __('admin.tk_save') }}</button>
                </div>
            </form>
        </div>

        {{-- Tags --}}
        <div class="property-card">
            <label><i class="fa-solid fa-tags me-1.5 text-warning"></i> {{ __('admin.tk_tags') }}</label>
            <form action="{{ route('admin.support-tickets.tags', $supportTicket) }}" method="POST" class="mt-2">
                @csrf
                <div class="d-flex gap-2">
                    <input type="text" name="tags" class="form-control" placeholder="{{ __('admin.tk_tags_ph') }}" value="{{ is_array($supportTicket->tags) ? implode(', ', $supportTicket->tags) : '' }}">
                    <button type="submit" class="btn btn-dark px-4 fw-bold rounded-3">{{ __('admin.tk_save') }}</button>
                </div>
                <div class="small text-muted mt-1.5" style="font-size:0.75rem;">{{ __('admin.tk_comma') }}</div>
            </form>
        </div>

        {{-- Lock Ticket --}}
        <div class="property-card">
            <label><i class="fa-solid fa-shield-halved me-1.5 text-secondary"></i> {{ __('admin.tk_lock') }}</label>
            <form action="{{ route('admin.support-tickets.toggle-lock', $supportTicket) }}" method="POST" class="mt-2">
                @csrf
                @if($supportTicket->is_locked)
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold py-2"><i class="fa-solid fa-lock text-danger me-1"></i> {{ __('admin.tk_locked') }}</button>
                @else
                    <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2"><i class="fa-solid fa-unlock me-1"></i> {{ __('admin.tk_unlocked') }}</button>
                @endif
            </form>
        </div>

        {{-- Ticket Metadata Summary --}}
        <div class="property-card">
            <label><i class="fa-solid fa-circle-info me-1.5 text-primary"></i> {{ __('admin.tk_info') }}</label>
            <div class="mt-3 d-flex flex-column gap-2.5 small">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('admin.tk_type') }}</span>
                    <span class="fw-bold text-dark">{{ __('admin.ticket_type_'.$supportTicket->type) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('admin.tk_priority') }}</span>
                    <span class="badge bg-dark px-2.5 py-1">{{ __('admin.ticket_priority_'.($supportTicket->priority ?? 'medium')) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('admin.tk_msgs') }}</span>
                    <span class="fw-bold text-dark">{{ $supportTicket->messages->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('admin.tk_created') }}</span>
                    <span class="fw-bold text-dark">{{ $supportTicket->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">{{ __('admin.tk_updated') }}</span>
                    <span class="fw-bold text-dark">{{ $supportTicket->updated_at->diffForHumans() }}</span>
                </div>
                @if($supportTicket->rating)
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="text-muted fw-bold">CSAT Score</span>
                    <span class="text-warning fs-6">
                        @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= $supportTicket->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                        @endfor
                    </span>
                </div>
                @if($supportTicket->feedback)
                    <div class="p-2 rounded bg-light text-muted fst-italic mt-1" style="font-size: 0.78rem;">"{{ $supportTicket->feedback }}"</div>
                @endif
                @endif
                @if(in_array($supportTicket->status, ['open', 'in_progress']) && $supportTicket->updated_at->diffInHours(now()) > 24)
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="text-muted">{{ __('admin.tk_sla') }}</span>
                    <span class="badge bg-danger px-2 py-1">{{ __('admin.tk_overdue') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Customer Details Card --}}
        <div class="property-card">
            <label><i class="fa-solid fa-user me-1.5 text-success"></i> {{ __('admin.tk_cust') }}</label>
            <div class="d-flex align-items-center gap-3 mt-3">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #0d6efd; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.1rem; flex-shrink: 0; overflow: hidden;">
                    <img src="{{ $supportTicket->user?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($supportTicket->user?->name ?? 'Customer').'&background=0d6efd&color=fff&size=64' }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                </div>
                <div style="font-size: 0.9rem;">
                    <div class="fw-bold text-dark">{{ $supportTicket->user->name ?? '—' }}</div>
                    <div class="text-muted small">{{ $supportTicket->user->email ?? '' }}</div>
                    <div class="text-muted small">{{ $supportTicket->user->phone ?? '' }}</div>
                </div>
            </div>
            @if($supportTicket->user)
                <a href="{{ route('admin.users.show', $supportTicket->user) }}" class="btn btn-outline-dark btn-sm rounded-pill w-100 mt-3 fw-semibold">
                    <i class="fa-solid fa-external-link-alt me-1"></i> {{ __('admin.tic_view_prof') }}
                </a>
            @endif
        </div>

        {{-- Linked Booking Card --}}
        @if($supportTicket->booking)
            <div class="property-card">
                <label><i class="fa-solid fa-car me-1.5 text-brand"></i> {{ __('admin.tk_link_b') }}</label>
                <div class="p-3 rounded-3 bg-light border mt-2">
                    <div class="fw-bold fs-6 text-dark">#{{ $supportTicket->booking->number }}</div>
                    <div class="text-muted small fw-semibold">{{ $supportTicket->booking->service->name ?? '—' }}</div>
                    <div class="text-muted small mt-1">{{ $supportTicket->booking->branch->name ?? '' }} &middot; {{ $supportTicket->booking->booking_date->format('d M Y') }}</div>
                </div>
                <a href="{{ route('admin.bookings.show', $supportTicket->booking) }}" class="btn btn-outline-dark btn-sm rounded-pill w-100 mt-3 fw-semibold">
                    <i class="fa-solid fa-bookmark me-1"></i> {{ __('admin.tic_view_bk') }}
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Lightbox --}}
<div class="lb-overlay" id="lightbox" onclick="closeLb()">
    <img src="" id="lb-img" alt="">
</div>
@endsection

@section('scripts')
<script>
/* ─── Reply Type Toggle Styling ─── */
function setReplyType(val) {
    const lblPub = document.getElementById('lbl-public');
    const lblInt = document.getElementById('lbl-internal');
    if (val === 0) {
        lblPub.className = 'form-check-label d-flex align-items-center gap-2 small fw-bold cursor-pointer px-3 py-1 rounded-pill bg-primary text-white shadow-sm';
        lblInt.className = 'form-check-label d-flex align-items-center gap-2 small fw-bold cursor-pointer px-3 py-1 rounded-pill text-muted';
    } else {
        lblPub.className = 'form-check-label d-flex align-items-center gap-2 small fw-bold cursor-pointer px-3 py-1 rounded-pill text-muted';
        lblInt.className = 'form-check-label d-flex align-items-center gap-2 small fw-bold cursor-pointer px-3 py-1 rounded-pill bg-warning text-dark shadow-sm';
    }
}

/* ─── Scroll to bottom ─── */
(function(){ const el=document.getElementById('chat-messages'); if(el) el.scrollTop=el.scrollHeight; })();

/* ─── Char counter ─── */
const body=document.getElementById('reply-body'), ctr=document.getElementById('char-count');
if(body&&ctr){ body.addEventListener('input',function(){ const l=this.value.length; ctr.textContent=l+' / 5000'; ctr.className='char-counter'+(l>4500?' over':l>4000?' warn':''); }); }

/* ─── Inline edit ─── */
function startEdit(id){
    const form=document.getElementById('edit-form-'+id);
    const bubble=document.querySelector('#msg-'+id+' .msg-bubble');
    if(form&&bubble){ form.style.display='block'; bubble.style.display='none'; }
}
function cancelEdit(id){
    const form=document.getElementById('edit-form-'+id);
    const bubble=document.querySelector('#msg-'+id+' .msg-bubble');
    if(form&&bubble){ form.style.display='none'; bubble.style.display=''; }
}

/* ─── File upload ─── */
const fi=document.getElementById('file-input');
const chips=document.getElementById('file-chips');
const dz=document.getElementById('drop-zone');
let selFiles=[];

if(fi) fi.addEventListener('change',()=>handleFiles(Array.from(fi.files)));
if(dz){
    dz.addEventListener('dragover',e=>{e.preventDefault();dz.classList.add('dz-over');});
    dz.addEventListener('dragleave',e=>{
        if(!dz.contains(e.relatedTarget)){
            dz.classList.remove('dz-over');
        }
    });
    dz.addEventListener('drop',function(e){e.preventDefault();dz.classList.remove('dz-over');handleFiles(Array.from(e.dataTransfer.files));});
}
async function handleFiles(files){
    const allowed=['image/jpeg','image/png','image/gif','image/webp','application/pdf'];
    const max=10*1024*1024; let errs=[];
    for(let f of files){
        if(selFiles.length>=5){errs.push('Max 5 files.');break;}
        if(!allowed.includes(f.type)){errs.push(f.name+': invalid type.');continue;}
        if(f.size>max){errs.push(f.name+': exceeds 10 MB.');continue;}
        if(window.compressImageFile) f = await window.compressImageFile(f);
        if(!selFiles.find(s=>s.name===f.name&&s.size===f.size)) selFiles.push(f);
    }
    if(errs.length) alert(errs.join('\n'));
    renderChips(); syncFi();
}
function renderChips(){
    if(!chips) return; chips.innerHTML='';
    selFiles.forEach((f,i)=>{
        const c=document.createElement('div'); c.className='file-chip';
        const ico=f.type.startsWith('image/')?' fa-image':' fa-file-pdf';
        const sizeBadge = f.originalSize
            ? `<span style="color:#0d6efd;font-weight:700;font-size:0.72rem;">⚡ ${(f.size/1024).toFixed(0)}KB <s style="opacity:0.5;color:#64748b;">(${(f.originalSize/1024/1024).toFixed(1)}MB)</s></span>`
            : '';
        c.innerHTML=`<i class="fa-solid${ico} text-primary"></i><span style="max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</span> ${sizeBadge}<span class="remove-chip" onclick="remFile(${i})">✕</span>`;
        chips.appendChild(c);
    });
}
function remFile(i){ selFiles.splice(i,1); renderChips(); syncFi(); }
function syncFi(){ const dt=new DataTransfer(); selFiles.forEach(f=>dt.items.add(f)); if(fi) fi.files=dt.files; }

/* ─── Lightbox ─── */
function openLb(src){ document.getElementById('lb-img').src=src; document.getElementById('lightbox').classList.add('on'); document.body.style.overflow='hidden'; }
function closeLb(){ document.getElementById('lightbox').classList.remove('on'); document.body.style.overflow=''; }
document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeLb(); });

/* ─── Auto-refresh ─── */
@if($supportTicket->canReceiveMessages())
setTimeout(()=>{ if(!document.querySelector('#reply-body:focus')&&!document.querySelector('#reply-body')?.value) window.location.reload(); }, 30000);
@endif
/* ─── Canned Responses ─── */
function insertCannedResponse(select) {
    if (select.value) {
        const textarea = document.getElementById('reply-body');
        textarea.value += (textarea.value ? '\n\n' : '') + select.value;
        select.value = '';
        textarea.dispatchEvent(new Event('input'));
    }
}
</script>
@endsection
