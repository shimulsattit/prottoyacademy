@extends('layouts.web', ['title' => $model->meta_title])

@push('meta')
    
@endpush

@push('style')
    <style>
        h3 {
            font-size: 25px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="edu-breadcrumb-area breadcrumb-style-1 ptb--60 ptb_md--40 ptb_sm--40 bg-image">
        <div class="container eduvibe-animated-shape">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-start">
                        <div class="page-title">
                            <h3 class="title">{{ $model->title }}</h3>
                        </div>
                        <nav class="edu-breadcrumb-nav">
                            <ol class="edu-breadcrumb d-flex justify-content-start liststyle">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        Home
                                    </a>
                                </li>
                                <li class="separator">
                                    <i class="ri-arrow-drop-right-line"></i>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $model->title }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                    <div class="shape-image shape-image-1">
                        <img src="{{ asset('assets/images/shapes/shape-11-07.png') }}" alt="Breadcrumb Shape Thumb One" />
                    </div>
                    <div class="shape-image shape-image-2">
                        <img src="{{ asset('assets/images/shapes/shape-01-02.png') }}" alt="Breadcrumb Shape Thumb Two" />
                    </div>
                    <div class="shape-image shape-image-3">
                        <img src="{{ asset('assets/images/shapes/shape-03.png') }}" alt="Breadcrumb Shape Thumb Three" />
                    </div>
                    <div class="shape-image shape-image-4">
                        <img src="{{ asset('assets/images/shapes/shape-13-12.png') }}" alt="Breadcrumb Shape Thumb Four" />
                    </div>
                    <div class="shape-image shape-image-5">
                        <img src="{{ asset('assets/images/shapes/shape-36.png') }}" alt="Breadcrumb Shape Thumb Five" />
                    </div>
                    <div class="shape-image shape-image-6">
                        <img src="{{ asset('assets/images/shapes/shape-05-07.png') }}" alt="Breadcrumb Shape Thumb Six" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="login-register-page-wrapper edu-section-gap bg-color-white">
        <div class="container checkout-page-style">
            <div class="row g-5 justify-content-center">
                <div class="col-lg-12">
                    {!! $model->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush    
