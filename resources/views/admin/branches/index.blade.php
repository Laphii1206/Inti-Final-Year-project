@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-warehouse text-brand me-2"></i>{{ __('admin.br_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.br_desc') }}</p>
        </div>
        <div>
            <button class="btn btn-brand fw-bold px-4" data-bs-toggle="modal" data-bs-target="#createBranchModal">
                <i class="fa-solid fa-plus me-1"></i> {{ __('admin.br_btn_create') }}
            </button>
        </div>
    </div>

    <!-- Outlets Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr class="small text-uppercase">
                        <th class="ps-4">{{ __('admin.br_col_id') }}</th>
                        <th>{{ __('admin.br_col_name') }}</th>
                        <th>{{ __('admin.br_col_contact') }}</th>
                        <th>{{ __('admin.br_col_hours') }}</th>
                        <th>{{ __('admin.br_col_address') }}</th>
                        <th class="text-center">{{ __('admin.br_col_capacity') }}</th>
                        <th class="text-center">{{ __('admin.br_col_status') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.br_col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr class="border-bottom {{ $branch->trashed() ? 'table-secondary text-muted' : '' }}">
                            <td class="ps-4">{{ $branch->id }}</td>
                            <td>
                                <div class="fw-bold {{ $branch->trashed() ? 'text-decoration-line-through' : 'text-dark' }}">{{ $branch->name }}</div>
                                @if($branch->trashed())
                                    <span class="badge bg-secondary">{{ __('admin.br_soft_deleted') }}</span>
                                @endif
                            </td>
                            <td>{{ $branch->contact_number }}</td>
                            <td>
                                <div class="small fw-bold">
                                    {{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <div class="small" style="max-width: 250px;">{{ $branch->address }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $branch->service_capacity }} {{ __('admin.br_bays') }}</span>
                            </td>
                            <td class="text-center">
                                @if($branch->is_active && !$branch->trashed())
                                    <span class="badge bg-success">{{ __('admin.br_active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.br_inactive') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if(!$branch->trashed())
                                        <a href="{{ route('admin.branches.show', $branch->id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <button class="btn btn-sm btn-dark btn-edit-branch" 
                                                data-id="{{ $branch->id }}" 
                                                data-name="{{ $branch->name }}" 
                                                data-phone="{{ $branch->contact_number }}" 
                                                data-address="{{ $branch->address }}" 
                                                data-map="{{ $branch->google_map_link }}" 
                                                data-open="{{ \Carbon\Carbon::parse($branch->opening_time)->format('H:i') }}" 
                                                data-close="{{ \Carbon\Carbon::parse($branch->closing_time)->format('H:i') }}" 
                                                data-capacity="{{ $branch->service_capacity }}"
                                                data-active="{{ $branch->is_active }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        
                                        <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('admin.br_delete_confirm') }}')" title="{{ __('admin.br_soft_delete') }}"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.branches.index') }}/{{ $branch->id }}/restore" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success px-3" onclick="return confirm('{{ __('admin.br_restore_confirm') }}')"><i class="fa-solid fa-trash-arrow-up me-1"></i> {{ __('admin.br_btn_restore') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-hotel fs-2 mb-3"></i>
                                <p class="mb-0">{{ __('admin.br_empty') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($branches->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Create Branch -->
<div class="modal fade" id="createBranchModal" tabindex="-1" aria-labelledby="createBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="createBranchModalLabel"><i class="fa-solid fa-warehouse me-2 text-brand"></i>{{ __('admin.br_modal_create_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.branches.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_name') }}</label>
                        <input type="text" name="name" id="name" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('admin.br_ph_name') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="contact_number" class="form-label small fw-bold text-secondary">{{ __('admin.br_col_contact') }}</label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('admin.br_ph_contact') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="service_capacity" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_capacity') }}</label>
                            <input type="number" name="service_capacity" id="service_capacity" class="form-control bg-light border-0 py-2.5" placeholder="4" min="1" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="opening_time" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_open') }}</label>
                            <input type="time" name="opening_time" id="opening_time" class="form-control bg-light border-0" value="09:00" required>
                        </div>
                        <div class="col-6">
                            <label for="closing_time" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_close') }}</label>
                            <input type="time" name="closing_time" id="closing_time" class="form-control bg-light border-0" value="18:00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="google_map_link" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_map') }}</label>
                        <input type="url" name="google_map_link" id="google_map_link" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('admin.br_ph_map') }}">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_address') }}</label>
                        <textarea name="address" id="address" rows="3" class="form-control bg-light border-0" placeholder="{{ __('admin.br_ph_address') }}" required></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label small text-secondary" for="is_active">
                            {{ __('admin.br_active_help') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.br_btn_cancel') }}</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('admin.br_btn_save_create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Branch -->
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="editBranchModalLabel"><i class="fa-solid fa-pen-to-square me-2 text-brand"></i>{{ __('admin.br_modal_edit_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editBranchForm">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_name') }}</label>
                        <input type="text" name="name" id="edit-name" class="form-control bg-light border-0 py-2.5" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="edit-phone" class="form-label small fw-bold text-secondary">{{ __('admin.br_col_contact') }}</label>
                            <input type="text" name="contact_number" id="edit-phone" class="form-control bg-light border-0 py-2.5" required>
                        </div>
                        <div class="col-6">
                            <label for="edit-capacity" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_capacity') }}</label>
                            <input type="number" name="service_capacity" id="edit-capacity" class="form-control bg-light border-0 py-2.5" min="1" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="edit-open" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_open') }}</label>
                            <input type="time" name="opening_time" id="edit-open" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-6">
                            <label for="edit-close" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_close') }}</label>
                            <input type="time" name="closing_time" id="edit-close" class="form-control bg-light border-0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit-map" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_map') }}</label>
                        <input type="url" name="google_map_link" id="edit-map" class="form-control bg-light border-0 py-2.5">
                    </div>

                    <div class="mb-3">
                        <label for="edit-address" class="form-label small fw-bold text-secondary">{{ __('admin.br_label_address') }}</label>
                        <textarea name="address" id="edit-address" rows="3" class="form-control bg-light border-0" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.br_btn_cancel') }}</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('admin.br_btn_save_edit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-branch');
        const editModal = new bootstrap.Modal(document.getElementById('editBranchModal'));
        const editForm = document.getElementById('editBranchForm');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const branchId = this.dataset.id;
                
                // Form action URL
                editForm.action = `/admin/branches/${branchId}`;

                // Populate modal fields
                document.getElementById('edit-name').value = this.dataset.name;
                document.getElementById('edit-phone').value = this.dataset.phone;
                document.getElementById('edit-capacity').value = this.dataset.capacity;
                document.getElementById('edit-open').value = this.dataset.open;
                document.getElementById('edit-close').value = this.dataset.close;
                document.getElementById('edit-map').value = this.dataset.map || '';
                document.getElementById('edit-address').value = this.dataset.address;

                editModal.show();
            });
        });
    });
</script>
@endsection
