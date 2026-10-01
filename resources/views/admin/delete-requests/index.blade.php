@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-user-xmark text-brand me-2"></i>{{ __('admin.del_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.del_desc') }}</p>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr class="small text-uppercase">
                        <th class="ps-4">{{ __('admin.del_col_id') }}</th>
                        <th>{{ __('admin.del_col_customer') }}</th>
                        <th>{{ __('admin.del_col_reason') }}</th>
                        <th>{{ __('admin.del_col_submitted') }}</th>
                        <th class="text-center">{{ __('admin.del_col_status') }}</th>
                        <th>{{ __('admin.del_col_reviewed_by') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.del_col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr class="border-bottom">
                            <td class="ps-4">{{ $request->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $request->user ? $request->user->name : __('admin.bk_del_user') }}</div>
                                <span class="small text-muted">{{ $request->user ? $request->user->email : __('admin.bk_na') }}</span>
                            </td>
                            <td>
                                <div class="small text-dark" style="max-width: 300px; word-wrap: break-word;">
                                    "{{ $request->reason ?? __('admin.del_no_reason') }}"
                                </div>
                            </td>
                            <td>
                                <span class="small">{{ $request->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="text-center">
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-hourglass-half me-1"></i> {{ __('admin.bk_lbl_pending') }}</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success text-white"><i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.del_approved') }}</span>
                                @elseif($request->status === 'rejected')
                                    <span class="badge bg-danger text-white"><i class="fa-solid fa-circle-xmark me-1"></i> {{ __('admin.bk_lbl_rejected') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($request->status !== 'pending' && $request->reviewer)
                                    <div class="small fw-bold text-dark">{{ $request->reviewer->name }}</div>
                                    <span class="small text-muted">{{ __('admin.del_on') }} {{ $request->reviewed_at->format('d M Y') }}</span>
                                    @if($request->admin_notes)
                                        <div class="small text-danger mt-1 italic">{{ __('admin.del_notes') }} "{{ $request->admin_notes }}"</div>
                                    @endif
                                @else
                                    <span class="text-secondary small italic">{{ __('admin.del_awaiting') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($request->status === 'pending')
                                        <form action="{{ route('admin.delete-requests.approve', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success px-3" onclick="return confirm('{{ __('admin.del_approve_confirm') }}')"><i class="fa-solid fa-circle-check me-1"></i> {{ __('admin.del_btn_approve') }}</button>
                                        </form>
                                        
                                        <button class="btn btn-sm btn-outline-danger px-3" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $request->id }}">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Reject
                                        </button>

                                        <!-- Modal: Reject Deletion Request -->
                                        <div class="modal fade text-start" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <div class="modal-header bg-danger text-white border-0 py-3">
                                                        <h5 class="modal-title fw-bold" id="rejectModalLabel{{ $request->id }}"><i class="fa-solid fa-circle-xmark me-2"></i>{{ __('admin.del_modal_title') }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.delete-requests.reject', $request->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body p-4 bg-white">
                                                            <p class="small text-secondary mb-3">{!! __('admin.del_modal_desc') !!}</p>
                                                            
                                                            <div class="mb-3">
                                                                <label for="admin_notes_{{ $request->id }}" class="form-label small fw-bold text-secondary">{{ __('admin.del_admin_notes') }}</label>
                                                                <textarea name="admin_notes" id="admin_notes_{{ $request->id }}" rows="3" class="form-control bg-light border-0" placeholder="{{ __('admin.del_ph_notes') }}" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 p-3">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.del_cancel') }}</button>
                                                            <button type="submit" class="btn btn-danger px-4">{{ __('admin.del_btn_submit') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-secondary small italic">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-user-shield fs-2 mb-3"></i>
                                <p class="mb-0">{{ __('admin.del_empty') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($requests->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
