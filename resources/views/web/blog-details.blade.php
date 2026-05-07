@extends('layouts.web', ['title' => $blog->site_title])

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
                            <h3 class="title">{{ $blog->title }}</h3>
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
                                    {{ $blog->title }}
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

    <div class="edu-blog-details-area edu-section-gap bg-color-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="blog-details-1 style-variation2">
                        <div class="content-blog-top">
                            <div class="thumbnail">
                                <img class="radius-small w-100 mb--30" src="{{ asset($blog->banner_image) }}" alt="{{ $blog->title }} Banner Images">
                            </div>
                            <ul class="blog-meta">
                                @if ($blog->author)
                                    <li>
                                        <a href="{{ $blog->author->slug }}">
                                            {{ $blog->author->name }}
                                        </a>
                                    </li>
                                @endif
                                @if ($blog->category)
                                    <li>
                                        <a href="{{ route('slug.handle', $blog->category->slug) }}">
                                            {{ $blog->category->name }}    
                                        </a>    
                                    </li>
                                @endif
                                <li><i class="icon-calendar-2-line"></i>{{ date('d F, Y', strtotime($blog->created_at)) }}</li>
                                <li><i class="icon-discuss-line"></i>0 Comments</li>
                            </ul>
                            <h4 class="title">{{ $blog->title }}</h4>
                        </div>

                        <div class="blog-main-content">
                            {!! $blog->content !!}
                        </div>

                        <div class="blog-tag-and-share mt--50">
                            <div class="blog-tag">
                                <div class="tag-list bg-shade">
                                    <a href="#">Design</a>
                                    <a href="#">Course</a>
                                    <a href="#">Education</a>
                                </div>
                            </div>
                            <div class="blog-share">
                                <div class="blog-share">
                                    <div class="eduvibe-post-share">
                                        @php
                                            $shareUrl   = urlencode(url()->current());
                                            $shareTitle = urlencode($blog->title ?? '');        // blog title
                                            $shareText  = urlencode(Str::limit(strip_tags($blog->short_description ?? ''), 150));
                                        @endphp
                                        <span>Share: </span>
                                        <!-- Facebook -->
                                        <a class="facebook" target="_blank"
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}">
                                            <i class="icon-Fb"></i>
                                        </a>

                                        <!-- Messenger -->
                                        <a class="messenger" target="_blank"
                                        href="https://www.facebook.com/dialog/send?link={{ $shareUrl }}&app_id={{ env('FACEBOOK_APP_ID') }}&redirect_uri={{ $shareUrl }}">
                                            <i class="ri-messenger-line"></i>
                                        </a>

                                        <!-- WhatsApp -->
                                        <a class="whatsapp" target="_blank"
                                        href="https://wa.me/?text={{ $shareTitle }}%20-%20{{ $shareUrl }}">
                                            <i class="ri-whatsapp-line"></i>
                                        </a>

                                        <!-- Twitter/X -->
                                        <a class="twitter" target="_blank"
                                        href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}">
                                            <i class="ri-twitter-line"></i>
                                        </a>

                                        <!-- Email -->
                                        <a class="email" 
                                        href="mailto:?subject={{ $shareTitle }}&body={{ $shareText }}%0A%0ARead more: {{ $shareUrl }}">
                                            <i class="ri-mail-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($blog->author)
                            <div class="blog-author-wrapper">
                                <div class="thumbnail">
                                    <img src="{{ asset($blog->author->profile_picture) }}" alt="{{ $blog->author->name }} Image">
                                </div>
                                <div class="author-content">
                                    <h6 class="title">{{ $blog->author->name }}</h6>
                                    <p>{{ $blog->author->bio }}</p>
                                </div>
                            </div>
                        @endif
                        
                        {{-- <div class="blog-pagination">
                            <div class="row g-5">
                                <div class="col-lg-6">
                                    <div class="blog-pagination-list style-variation-2">
                                        <a href="#">
                                            <i class="ri-arrow-left-s-line"></i>
                                            <span>Social Media & evolution of visual design</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="blog-pagination-list style-variation-2 next-post">
                                        <a href="#">
                                            <span>Many important brands have given us their trust</span>
                                            <i class="ri-arrow-right-s-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Start Comment Area  -->
                        {{-- <div class="edu-comment-area mt--50">
                            <h5 class="blog-page-title">2 Comments</h5>
                            <div class="comment-list-wrapper mt--40">
                                <!-- Start Single Comment  -->
                                <div class="comment">
                                    <div class="thumbnail">
                                        <img src="assets/images/course/student-review/student-1.png" alt="Comment Images">
                                    </div>
                                    <div class="comment-content">
                                        <h6 class="title">Daniel Stevo</h6>
                                        <span class="date">Nov 04, 2023 at 6:07 am</span>
                                        <p>As Thomas pointed out, Cheggâ€™s survey appears more like a scorecard that details obstacles and challenges that the current university undergraduate student population is going through in their universities and countries during and probably after the COVID-19 pandemic.</p>
                                        <div class="reply-btn-wrapper">
                                            <a class="reply-btn" href="#"><i class="icon-reply-all-fill"></i> Reply</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Comment  -->
                                <!-- Start Single Comment  -->
                                <div class="comment">
                                    <div class="thumbnail">
                                        <img src="assets/images/course/student-review/student-2.jpg" alt="Comment Images">
                                    </div>
                                    <div class="comment-content">
                                        <h6 class="title">Elen Saspita</h6>
                                        <span class="date">Nov 04, 2023 at 6:07 am</span>
                                        <p>As Thomas pointed out, Cheggâ€™s survey appears more like a scorecard that details obstacles and challenges that the current university undergraduate student population is going through in their universities and countries during and probably after the COVID-19 pandemic.</p>
                                        <div class="reply-btn-wrapper">
                                            <a class="reply-btn" href="#"><i class="icon-reply-all-fill"></i> Reply</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Comment  -->
                            </div>
                        </div> --}}
                        <!-- End Comment Area  -->

                        <!-- Start Comment Form  -->
                        {{-- <div class="edu-comment-form mt--50">
                            <div class="comment-form-top">
                                <h5 class="blog-page-title">Reply To Elen Saspita</h5>
                            </div>
                            <form class="comment-form-style-1" action="#">
                                <p class="comment-note">Your email address will not be published. Required fields are marked *</p>
                                <div class="row g-5">
                                    <div class="col-lg-6">
                                        <div class="input-box">
                                            <input type="text" placeholder="Name*">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-box">
                                            <input type="text" placeholder="Email*">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-box">
                                            <textarea placeholder="Your comment"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="comment-form-consent">
                                            <input id="checkbox-1" type="checkbox">
                                            <label for="checkbox-1"> Save my name, email, and website in this browser for the next time I comment.</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="comment-btn">
                                            <a class="edu-btn" href="#">Submit Now<i class="icon-arrow-right-line-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> --}}
                        <!-- End Comment Form  -->

                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Start Blog Sidebar Area  -->
                    <aside class="edu-blog-sidebar">

                        <!-- Start Single Widget  -->
                        <div class="edu-blog-widget-2 widget-search">
                            <div class="inner">
                                <h5 class="widget-title">Search Here</h5>
                                <div class="content">
                                    <form class="blog-search" action="#">
                                        <input type="text" placeholder="Search your Keyword...">
                                        <button class="search-button"><i class="icon-search-line"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Widget  -->

                        @if (count($blogCategories) > 0)
                            <!-- Start Single Widget  -->
                            <div class="edu-blog-widget-2 mt--50 widget-categories">
                                <div class="inner">
                                    <h5 class="widget-title">Categories</h5>
                                    <div class="content">
                                        <ul class="category-list">
                                            @foreach ($blogCategories as $blogCategory)
                                                <li>
                                                    <a href="{{ route('slug.handle', $blogCategory->slug) }}">
                                                        {{ $blogCategory->name }} 
                                                        <span>
                                                            ({{ $blogCategory->blogs->count() }})
                                                        </span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Widget  -->
                        @endif

                        @if (count($relatedBlogs) > 0)
                            <!-- Start Single Widget  -->
                            <div class="edu-blog-widget-2 mt--50 widget-latest-post">
                                <div class="inner">
                                    <h5 class="widget-title">Related Post</h5>
                                    <div class="content latest-post-list">
                                        @foreach ($relatedBlogs as $relatedBlog)
                                            <!-- Start Single Post  -->
                                            <div class="latest-post">
                                                <div class="thumbnail">
                                                    <a href="{{ route('slug.handle', $relatedBlog->slug) }}">
                                                        <img src="{{ asset($relatedBlog->thumbnail_image) }}" alt="{{ $relatedBlog->title }} Image">
                                                    </a>
                                                </div>
                                                <div class="post-content">
                                                    <ul class="blog-meta">
                                                        <li>{{ date('d f, Y', strtotime($relatedBlog->created_at)) }}</li>
                                                    </ul>
                                                    <h6 class="title">
                                                        <a href="{{ route('slug.handle', $relatedBlog->slug) }}">
                                                            {{ $relatedBlog->title }}    
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <!-- End Single Post  -->
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Widget  -->
                        @endif

                        @if (count($tags) > 0)
                            <!-- Start Single Widget  -->
                            <div class="edu-blog-widget-2 mt--50 widget-tags">
                                <div class="inner">
                                    <h5 class="widget-title">Popular Tags</h5>
                                    <div class="content">
                                        <div class="tag-list bg-shade">
                                            @foreach ($tags as $tag)
                                                <a href="{{ route('slug.handle', $tag->slug) }}">
                                                    {{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Widget  -->
                        @endif
                    </aside>
                    <!-- End Blog Sidebar Area  -->
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush    
