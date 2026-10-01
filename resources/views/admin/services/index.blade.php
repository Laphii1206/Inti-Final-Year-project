@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-screwdriver-wrench text-brand me-2"></i>{{ __('admin.svc_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.svc_desc') }}</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-brand px-4 py-2 fw-bold rounded-3 shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> {{ __('admin.svc_add_new') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.services.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">{{ __('admin.svc_search_label') }}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="{{ __('admin.svc_search_ph') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">{{ __('admin.svc_filter_label') }}</label>
                    <select name="category" class="form-select">
                        <option value="">{{ __('admin.svc_filter_all') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold"><i class="fa-solid fa-filter me-2"></i>{{ __('admin.svc_apply_filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-secondary small fw-bold text-uppercase py-3">{{ __('admin.svc_col_name') }}</th>
                            <th class="text-secondary small fw-bold text-uppercase py-3">{{ __('admin.svc_col_category') }}</th>
                            <th class="text-secondary small fw-bold text-uppercase py-3 text-end">{{ __('admin.svc_col_price') }}</th>
                            <th class="text-secondary small fw-bold text-uppercase py-3 text-center">{{ __('admin.svc_col_duration') }}</th>
                            <th class="text-secondary small fw-bold text-uppercase py-3 text-center">{{ __('admin.svc_col_status') }}</th>
                            <th class="pe-4 text-secondary small fw-bold text-uppercase py-3 text-end">{{ __('admin.svc_col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3 text-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-gear text-secondary mt-1"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $service->name }}</h6>
                                        <span class="small text-muted">{{ $service->branch ? $service->branch->name : __('admin.svc_all_branches') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ ucfirst($service->category) }}</span>
                            </td>
                            <td class="py-3 text-end fw-bold text-dark">
                                RM {{ number_format($service->price, 2) }}
                            </td>
                            <td class="py-3 text-center text-muted">
                                {{ $service->estimated_duration }} min
                            </td>
                            <td class="py-3 text-center">
                                @if($service->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i>{{ __('admin.svc_active') }}</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i>{{ __('admin.svc_inactive') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-sm btn-light text-dark border" title="{{ __('admin.svc_view') ?? 'View Details' }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-light text-primary border" title="{{ __('admin.br_btn_edit') }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" title="{{ __('admin.br_soft_delete') }}" onclick="return confirm('{{ __('admin.svc_delete_confirm') }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-1 mb-3 text-light"></i>
                                <h5>{{ __('admin.svc_empty_title') }}</h5>
                                <p class="mb-0">{{ __('admin.svc_empty_desc') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($services->hasPages())
        <div class="card-footer bg-white border-top p-3">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</div>
@endsection