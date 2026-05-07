@extends('layouts.web', ['title' => 'Content not found | Prottoy Academy'])

@push('meta')

@endpush

@push('style')

@endpush

@section('content')
    <div class="edu-error-area eduvibe-404-page edu-error-style">
        <div class="container eduvibe-animated-shape">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="content text-center">
                        <div class="thumbnail mb--60">
                            <img src="{{ asset('assets/images/others/404.png') }}" alt="404 Images">
                        </div>
                        <h3 class="title">Oops... Page Not Found!</h3>
                        <p class="description">Please return to the site's homepage, It looks like nothing was found at this location</p>
                        <div class="backto-home-btn">
                            <a class="edu-btn" href="{{ route('home') }}">Back To Home<i class="icon-arrow-right-line-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                <div class="shape-image shape-image-1">
                    <img src="{{ asset('assets/images/shapes/shape-11-06.png') }}" alt="Shape Thumb">
                </div>
                <div class="shape-image shape-image-2">
                    <img src="{{ asset('assets/images/shapes/shape-09-02.png') }}" alt="Shape Thumb">
                </div>
                <div class="shape-image shape-image-3">
                    <img src="{{ asset('assets/images/shapes/shape-05-06.png') }}" alt="Shape Thumb">
                </div>
                <div class="shape-image shape-image-4">
                    <img src="{{ asset('assets/images/shapes/shape-14-03.png') }}" alt="Shape Thumb">
                </div>
                <div class="shape-image shape-image-5">
                    <img src="{{ asset('assets/images/shapes/shape-03-10.png') }}" alt="Shape Thumb">
                </div>
                <div class="shape-image shape-image-6">
                    <img src="{{ asset('assets/images/shapes/shape-15.png') }}" alt="Shape Thumb">
                </div>
            </div>
        </div>
    </div>
@endsection
