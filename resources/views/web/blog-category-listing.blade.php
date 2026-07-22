@extends('layouts.web', ['title' => $category->site_title])

@push('meta')
    
@endpush

@push('style')

@endpush

@section('content')
    <div class="edu-breadcrumb-area breadcrumb-style-1 ptb--60 ptb_md--40 ptb_sm--40 bg-image">
        <div class="container eduvibe-animated-shape">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-start">
                        <div class="page-title">
                            <h3 class="title">{{ $category->name }}</h3>
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('blogs') }}">
                                        Blogs
                                    </a>
                                </li>
                                <li class="separator">
                                    <i class="ri-arrow-drop-right-line"></i>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $category->name }}
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

    <div class="edu-elements-area edu-section-gap bg-color-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="row g-5">
                        @if (count($blogs) > 0)
                            @foreach ($blogs as $blog)
                                <!-- Start Blog Grid  -->
                                <div class="col-lg-4 col-md-6 col-12" data-sal-delay="150" data-sal="slide-up" data-sal-duration="800">
                                    <div class="blog-card-exact">
                                        <div class="blog-thumb-exact">
                                            <a href="{{ route('slug.handle', $blog->slug) }}">
                                                <img src="{{ $blog->thumbnail_image ? asset($blog->thumbnail_image) : ($blog->thumbnail ? asset('storage/' . $blog->thumbnail) : asset('assets/images/logo/logo.png')) }}" alt="{{ $blog->title }} Image">
                                            </a>
                                        </div>
                                        <div class="blog-content-exact">
                                            @if ($blog->category)
                                                <a href="{{ route('slug.handle', $blog->category->slug) }}" class="blog-cat-badge" style="text-decoration: none;">
                                                    {{ $blog->category->name }}
                                                </a>
                                            @endif
                                            
                                            <h4>
                                                <a href="{{ route('slug.handle', $blog->slug) }}">
                                                    {{ $blog->title }}
                                                </a>
                                            </h4>
                                            <p>
                                                {{ Str::limit(strip_tags($blog->short_description ?? $blog->content ?? ''), 120) }}
                                            </p>
                                            <div class="blog-meta-exact">
                                                <span><i class="ri-calendar-line" style="margin-right: 5px; color: var(--accent-gold);"></i>{{ date('d M, Y', strtotime($blog->created_at)) }}</span>
                                                <a href="{{ route('slug.handle', $blog->slug) }}">
                                                    পড়ুন <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Blog Grid  -->
                            @endforeach
                        @else   
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <p class="text-center mb-0">Nothing to show.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($blogs->hasPages())
                        <div class="row">
                            <div class="col-lg-12 mt--60">
                                <nav>
                                    <ul class="edu-pagination">

                                        {{-- Previous Page Link --}}
                                        @if ($blogs->onFirstPage())
                                            <li class="disabled"><span><i class="ri-arrow-drop-left-line"></i></span></li>
                                        @else
                                            <li>
                                                <a href="{{ $blogs->previousPageUrl() }}">
                                                    <i class="ri-arrow-drop-left-line"></i>
                                                </a>
                                            </li>
                                        @endif


                                        {{-- Pagination Elements --}}
                                        @foreach ($blogs->links()->elements[0] ?? [] as $page => $url)
                                            @if ($page == $blogs->currentPage())
                                                <li class="active"><a href="#">{{ $page }}</a></li>
                                            @else
                                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach


                                        {{-- Next Page Link --}}
                                        @if ($blogs->hasMorePages())
                                            <li>
                                                <a href="{{ $blogs->nextPageUrl() }}">
                                                    <i class="ri-arrow-drop-right-line"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="disabled"><span><i class="ri-arrow-drop-right-line"></i></span></li>
                                        @endif

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush    
