@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-plus-circle text-brand me-2"></i>{{ __('admin.svc_create_title') }}</h2>
            <p class="text-secondary mb-0">{{ __('admin.svc_create_desc') }}</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3 shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i> {{ __('admin.svc_btn_back') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_name') }}</label>
                                <input type="text" name="name" id="name" class="form-control bg-light border-0 py-2.5" value="{{ old('name') }}" placeholder="{{ __('admin.svc_ph_name') }}" required autofocus>
                                @error('name')
                                    <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-4 mt-md-0">
                                <label for="branch_id" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_branch') }}</label>
                                <select name="branch_id" id="branch_id" class="form-select bg-light border-0 py-2.5" required>
                                    <option value="">{{ __('admin.svc_select_branch') }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="category" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_category') }}</label>
                                <select name="category" id="category" class="form-select bg-light border-0 py-2.5" required>
                                    <option value="">{{ __('admin.svc_select_category') }}</option>
                                    <option value="Tyres" {{ old('category') == 'Tyres' ? 'selected' : '' }}>{{ __('admin.svc_cat_tyres') }}</option>
                                    <option value="Maintenance" {{ old('category') == 'Maintenance' ? 'selected' : '' }}>{{ __('admin.svc_cat_maintenance') }}</option>
                                    <option value="Wipers" {{ old('category') == 'Wipers' ? 'selected' : '' }}>{{ __('admin.svc_cat_wipers') }}</option>
                                    <option value="Tinting Films" {{ old('category') == 'Tinting Films' ? 'selected' : '' }}>{{ __('admin.svc_cat_tinting') }}</option>
                                    <option value="Dashcams" {{ old('category') == 'Dashcams' ? 'selected' : '' }}>{{ __('admin.svc_cat_dashcams') }}</option>
                                    <option value="Car Mats" {{ old('category') == 'Car Mats' ? 'selected' : '' }}>{{ __('admin.svc_cat_carmats') }}</option>
                                </select>
                                @error('category')
                                    <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-4 mt-md-0">
                                <label for="estimated_duration" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_duration') }}</label>
                                <input type="number" name="estimated_duration" id="estimated_duration" class="form-control bg-light border-0 py-2.5" value="{{ old('estimated_duration') }}" placeholder="{{ __('admin.svc_ph_duration') }}" min="0" required>
                                @error('estimated_duration')
                                    <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="price" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_price') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">RM</span>
                                    <input type="number" name="price" id="price" class="form-control bg-light border-0 py-2.5" value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-4 mt-md-0">
                                <label for="image_path" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_image') }}</label>
                                <input type="file" name="image_path" id="image_path" class="form-control bg-light border-0" accept="image/*">
                                @error('image_path')
                                    <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label small fw-bold text-secondary">{{ __('admin.svc_label_desc') }}</label>
                            <textarea name="description" id="description" class="form-control bg-light border-0 py-2.5" rows="4" placeholder="{{ __('admin.svc_ph_desc') }}">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4 bg-light p-3 rounded-3 border border-light">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark ms-2" for="is_active">{{ __('admin.svc_label_active') }}</label>
                                <div class="form-text mt-0">{{ __('admin.svc_active_help') }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-brand px-4 py-2.5 fw-bold rounded-3">{{ __('admin.svc_create_title') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 shadow-sm rounded-4 bg-brand text-white overflow-hidden h-100">
                <div class="card-body p-5 d-flex flex-column justify-content-center align-items-center text-center">
                    <i class="fa-solid fa-screwdriver-wrench fa-4x mb-4 text-white-50"></i>
                    <h4 class="fw-bold">{{ __('admin.svc_details_title') }}</h4>
                    <p class="text-white-50 mb-0">{{ __('admin.svc_details_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection