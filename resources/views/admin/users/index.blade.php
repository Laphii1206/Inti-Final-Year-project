@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-users-gear text-brand me-2"></i>{{ __('admin.usr_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.usr_desc') }}</p>
        </div>
        <div>
            <button class="btn btn-brand fw-bold px-4" data-bs-toggle="modal" data-bs-target="#createSubAdminModal">
                <i class="fa-solid fa-user-shield me-1"></i> {{ __('admin.us_reg') }}
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="search" class="form-label small fw-bold text-secondary">{{ __('admin.usr_search') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" id="search" class="form-control bg-light border-0" placeholder="{{ __('admin.usr_search_ph') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label for="role" class="form-label small fw-bold text-secondary">{{ __('admin.usr_role_filter') }}</label>
                <select name="role" id="role" class="form-select bg-light border-0">
                    <option value="">{{ __('admin.usr_all_roles') }}</option>
                    <option value="0" {{ request('role') === '0' ? 'selected' : '' }}>{{ __('admin.usr_customers') }}</option>
                    <option value="1" {{ request('role') === '1' ? 'selected' : '' }}>{{ __('admin.usr_mechanics') }}</option>
                    <option value="2" {{ request('role') === '2' ? 'selected' : '' }}>{{ __('admin.usr_admins') }}</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-dark w-100 py-2.5 fw-bold"><i class="fa-solid fa-filter me-1"></i> {{ __('admin.usr_btn_filter') }}</button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr class="small text-uppercase">
                        <th class="ps-4">{{ __('admin.usr_col_id') }}</th>
                        <th>{{ __('admin.usr_col_profile') }}</th>
                        <th>{{ __('admin.usr_col_phone') }}</th>
                        <th>{{ __('admin.usr_col_role') }}</th>
                        <th>{{ __('admin.usr_col_joined') }}</th>
                        <th class="text-center">{{ __('admin.usr_col_status') }}</th>
                        <th class="pe-4 text-end">{{ __('admin.usr_col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-bottom {{ $user->trashed() ? 'table-secondary text-muted' : '' }}">
                            <td class="ps-4">{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light p-2 text-secondary"><i class="fa-solid fa-user"></i></div>
                                    <div>
                                        <div class="fw-bold {{ $user->trashed() ? 'text-decoration-line-through' : 'text-dark' }}">{{ $user->name }}</div>
                                        <span class="small text-muted">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if($user->isAdmin())
                                    @if($user->is_main_admin)
                                        <span class="badge bg-danger"><i class="fa-solid fa-user-shield me-1"></i> {{ __('admin.usr_main_admin') }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fa-solid fa-user-shield me-1"></i> {{ __('admin.usr_sub_admin') }}</span>
                                    @endif
                                @elseif($user->isMechanic())
                                    <span class="badge bg-dark"><i class="fa-solid fa-wrench me-1 text-brand"></i> {{ __('admin.usr_mechanic') }}</span>
                                @else
                                    <span class="badge bg-light text-secondary border"><i class="fa-solid fa-user me-1 text-muted"></i> {{ __('admin.usr_customer') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="small">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="text-center">
                                @if($user->trashed())
                                    <span class="badge bg-secondary">{{ __('admin.usr_archived') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('admin.usr_active') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-dark" title="{{ __('admin.usr_view_profile') ?? 'View Profile' }}"><i class="fa-solid fa-eye"></i></a>
                                    @if(!$user->trashed())
                                        @if($user->isCustomer())
                                            <form action="{{ route('admin.users.upgrade', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-dark px-3"><i class="fa-solid fa-user-gear me-1"></i> {{ __('admin.usr_make_mechanic') }}</button>
                                            </form>
                                        @elseif($user->isMechanic() || ($user->isAdmin() && !$user->is_main_admin))
                                            <form action="{{ route('admin.users.downgrade', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary px-3"><i class="fa-solid fa-user-minus me-1"></i> {{ __('admin.usr_make_customer') }}</button>
                                            </form>
                                        @endif
                                        
                                        @if(!$user->is_main_admin)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('admin.usr_archive_confirm') }}')" title="{{ __('admin.usr_archive_btn') }}"><i class="fa-solid fa-box-archive"></i></button>
                                            </form>
                                        @endif
                                        
                                        @if($user->webAuthnCredentials()->count() > 0 && !$user->is_main_admin)
                                            <form action="{{ route('admin.users.removePasskey', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('admin.usr_remove_passkey_confirm') }}')" title="{{ __('admin.usr_remove_passkey') }}">
                                                    <i class="fa-solid fa-fingerprint"></i> <i class="fa-solid fa-xmark" style="font-size: 0.6em; position: absolute; margin-left: -5px;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success px-3" onclick="return confirm('{{ __('admin.usr_restore_confirm') }}')"><i class="fa-solid fa-trash-arrow-up me-1"></i> {{ __('admin.usr_restore_btn') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-users-slash fs-2 mb-3"></i>
                                <p class="mb-0">{{ __('admin.usr_empty') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($users->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Create Sub-Admin -->
<div class="modal fade" id="createSubAdminModal" tabindex="-1" aria-labelledby="createSubAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="createSubAdminModalLabel"><i class="fa-solid fa-user-shield me-2 text-brand"></i>{{ __('admin.usr_modal_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.createSubAdmin') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="name" class="form-label small fw-bold text-secondary">{{ __('admin.usr_full_name') }}</label>
                            <input type="text" name="name" id="name" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('admin.usr_ph_name') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="phone" class="form-label small fw-bold text-secondary">{{ __('admin.usr_col_phone') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('admin.usr_ph_phone') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-secondary">{{ __('admin.usr_email') }}</label>
                        <input type="email" name="email" id="email" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('admin.usr_ph_email') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="password" class="form-label small fw-bold text-secondary">{{ __('admin.usr_password') }}</label>
                            <input type="password" name="password" id="password" class="form-control bg-light border-0 py-2.5" placeholder="••••••••" required>
                        </div>
                        <div class="col-6">
                            <label for="password_confirmation" class="form-label small fw-bold text-secondary">{{ __('admin.usr_confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-0 py-2.5" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.usr_cancel') }}</button>
                    <button type="submit" class="btn btn-brand px-4">{{ __('admin.usr_btn_register') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
