<!-- Voucher Details & T&C Modal -->
<div class="modal fade" id="voucherTncModal" tabindex="-1" aria-labelledby="voucherTncModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white border-bottom border-secondary-subtle px-4 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0" id="voucherTncModalLabel">
                    <i class="fa-solid fa-ticket text-brand"></i>
                    <span>{{ __('rewards.modal_voucher_details_tnc') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4 p-3 rounded-4 bg-light border border-secondary-subtle shadow-sm" id="vtModalHeaderBox">
                    <span class="badge px-3 py-1 rounded-pill mb-2 fw-semibold" id="vtModalStatus">{{ __('rewards.modal_active') }}</span>
                    <h2 class="fw-bolder text-brand mb-1 display-6" id="vtModalDiscount">--</h2>
                    <div class="font-monospace fw-bold fs-6 text-secondary px-3 py-1 bg-white rounded-3 border d-inline-block mt-1 shadow-sm" id="vtModalCode">--</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light border border-secondary-subtle h-100">
                            <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-gift me-1 text-brand"></i>{{ __('rewards.modal_source') }}
                            </small>
                            <span class="fw-bold text-dark fs-6" id="vtModalSource">--</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light border border-secondary-subtle h-100">
                            <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-calendar-check me-1 text-brand"></i>{{ __('rewards.modal_validity') }}
                            </small>
                            <span class="fw-bold text-dark fs-6" id="vtModalExpiry">--</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-4 border border-secondary-subtle bg-light-subtle">
                    <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-contract text-brand"></i>
                        <span>{{ __('rewards.modal_tnc') }}</span>
                    </h6>
                    <div class="small text-muted mb-0" id="vtModalTnc" style="line-height: 1.6; white-space: pre-line;"></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top border-secondary-subtle px-4 py-3 justify-content-end">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">{{ __('rewards.modal_close') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Dark mode adaptation for Voucher Modal */
.acct-dark #voucherTncModal .modal-content,
[data-bs-theme="dark"] #voucherTncModal .modal-content {
    background: #18181b !important;
    color: #f4f4f5 !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
}
.acct-dark #voucherTncModal .modal-header,
[data-bs-theme="dark"] #voucherTncModal .modal-header {
    background: #09090b !important;
    border-bottom: 1px solid rgba(255,255,255,0.1) !important;
}
.acct-dark #voucherTncModal .bg-light,
.acct-dark #voucherTncModal .bg-light-subtle,
.acct-dark #voucherTncModal #vtModalHeaderBox,
[data-bs-theme="dark"] #voucherTncModal .bg-light,
[data-bs-theme="dark"] #voucherTncModal .bg-light-subtle,
[data-bs-theme="dark"] #voucherTncModal #vtModalHeaderBox {
    background: #27272a !important;
    border-color: rgba(255,255,255,0.08) !important;
}
.acct-dark #voucherTncModal #vtModalCode,
[data-bs-theme="dark"] #voucherTncModal #vtModalCode {
    background: #18181b !important;
    color: #e4e4e7 !important;
    border-color: rgba(255,255,255,0.15) !important;
}
.acct-dark #voucherTncModal .modal-footer,
[data-bs-theme="dark"] #voucherTncModal .modal-footer {
    background: #121215 !important;
    border-top: 1px solid rgba(255,255,255,0.08) !important;
}
.acct-dark #voucherTncModal .text-dark,
[data-bs-theme="dark"] #voucherTncModal .text-dark {
    color: #f4f4f5 !important;
}
.voucher-info-btn {
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.voucher-info-btn:hover {
    transform: scale(1.15);
    color: #EC1F24 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const voucherTncModal = document.getElementById('voucherTncModal');
    if (voucherTncModal) {
        voucherTncModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            if (!btn) return;
            const code = btn.getAttribute('data-code') || '';
            const discount = btn.getAttribute('data-discount') || '';
            const source = btn.getAttribute('data-source') || '';
            const status = btn.getAttribute('data-status') || '';
            const statusClass = btn.getAttribute('data-status-class') || 'bg-brand';
            const expiry = btn.getAttribute('data-expiry') || '';
            const tnc = btn.getAttribute('data-tnc') || '';

            document.getElementById('vtModalCode').textContent = code;
            document.getElementById('vtModalDiscount').textContent = discount;
            document.getElementById('vtModalSource').textContent = source;
            document.getElementById('vtModalExpiry').textContent = expiry;
            document.getElementById('vtModalTnc').textContent = tnc;

            const statusEl = document.getElementById('vtModalStatus');
            statusEl.textContent = status;
            statusEl.className = 'badge px-3 py-1 rounded-pill mb-2 fw-semibold ' + statusClass;
        });
    }
});
</script>
