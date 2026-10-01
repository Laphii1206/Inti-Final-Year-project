@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 840px;">

    <div class="text-center mb-5">
        <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 76px; height: 76px;">
            <i class="fa-solid fa-file-contract text-brand fs-3"></i>
        </div>
        <h1 class="fw-bold display-6 text-dark mb-2">{{ __('legal.terms_title') }}</h1>
        <p class="text-muted mb-0">{{ __('legal.last_updated') }}</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 p-md-5">
            <p class="text-secondary mb-0" style="line-height: 1.9;">{{ __('legal.terms_intro') }}</p>
        </div>
    </div>

    @php
        $sections = [
            ['legal.terms_s1_title', 'legal.terms_s1_body'],
            ['legal.terms_s2_title', 'legal.terms_s2_body'],
            ['legal.terms_s3_title', 'legal.terms_s3_body'],
            ['legal.terms_s4_title', 'legal.terms_s4_body'],
        ];
    @endphp

    @foreach ($sections as $sec)
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-4 p-md-5">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                    <span class="bg-brand rounded-pill me-3" style="display:inline-block;width:5px;height:24px;"></span>
                    {{ __($sec[0]) }}
                </h5>
                <p class="text-secondary mb-0" style="line-height: 1.9;">{{ __($sec[1]) }}</p>
            </div>
        </div>
    @endforeach

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4 p-md-5 text-center">
            <i class="fa-solid fa-envelope text-brand fs-4 mb-3"></i>
            <h5 class="fw-bold text-dark mb-2">{{ __('legal.contact_title') }}</h5>
            <p class="text-secondary mb-0">{{ __('legal.terms_contact_body') }}</p>
        </div>
    </div>

</div>
@endsection
