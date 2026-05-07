<!-- Sidebar -->
<aside id="app_sidebar" class="app-sidebar flex-column" data-drawer="true" data-drawer-name="app-sidebar" data-drawer-activate="{default: true, lg: false}" data-drawer-overlay="true" data-drawer-width="225px" data-drawer-direction="start" data-drawer-toggle="#app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="app_sidebar_logo">
        <a href="index.html">
            <img alt="Logo" src="{{ asset(get_settings('system_logo') ?? 'portal-resource/images/mini-logo.svg') }}" class="h-25px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset(get_settings('system_favicon') ?? 'portal-resource/images/mini-logo.svg') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
    
        <div id="app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-toggle="true" data-toggle-state="active" data-toggle-target="body" data-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-scroll="true" data-scroll-activate="true" data-scroll-height="auto" data-scroll-dependencies="#app_sidebar_logo, #app_sidebar_footer" data-scroll-wrappers="#app_sidebar_menu" data-scroll-offset="5px" data-scroll-save-state="true">
               
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#app_sidebar_menu" data-menu="true" data-menu-expand="true">
                    
                    <div class="menu-item">
                        <a class="menu-link {{ Request::is('portal/dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
                            <span class="menu-icon">
                                <i class="fa-solid fa-house"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>
                    
                    @if(Auth::guard('admin')->user()->hasPermissionTo('category.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/category') || Request::is('portal/category/create') ? 'active' : '' }}" href="{{ route('portal.category.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-bars"></i>
                                </span>
                                <span class="menu-title">Category</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('job_category.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/job-category*') ? 'active' : '' }}" href="{{ route('portal.job-category.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-stream"></i>
                                </span>
                                <span class="menu-title">Job Category</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('year.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/year*') ? 'active' : '' }}" href="{{ route('portal.year.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <span class="menu-title">Year</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('exam.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/exam*') ? 'active' : '' }}" href="{{ route('portal.exam.index') }}">
                                <span class="menu-icon">
                                    <i class="fab fa-etsy"></i>
                                </span>
                                <span class="menu-title">Exam</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/question*') ? 'active' : '' }}" href="{{ route('portal.question.index') }}">
                                <span class="menu-icon">
                                    <i class="fab fa-quora"></i>
                                </span>
                                <span class="menu-title">Question</span>
                            </a>
                        </div>
                    @endif 

                    {{-- <div class="menu-item">
                        <a class="menu-link {{ Request::is('portal/quiz*') ? 'active' : '' }}" href="{{ route('portal.quiz.index') }}">
                            <span class="menu-icon">
                                <i class="fab fa-quora"></i>
                            </span>
                            <span class="menu-title">Quiz Question</span>
                        </a>
                    </div> --}}

                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.import'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/import-question*') ? 'active' : '' }}" href="{{ route('portal.import-question') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="menu-title">Import</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('passage.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/passage*') ? 'active' : '' }}" href="{{ route('portal.passage.index') }}">
                                <span class="menu-icon">
                                    <i class="fab fa-pinterest-p"></i>
                                </span>
                                <span class="menu-title">Passage</span>
                            </a>
                        </div>
                    @endif

                    @php
                        $categories = App\Models\Category::select('id', 'name', 'parent_id')->where('parent_id', 9)->get();
                    @endphp

                    @if(Auth::guard('admin')->user()->hasPermissionTo('job_solution.update'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/category-wise-question/*') && in_array(request()->route('id'), $categories->pluck('id')->toArray()) ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fab fa-quora">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Job Solution</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                               
                                @if ($categories)
                                    @foreach ($categories as $category)
                                        <div class="menu-item">
                                            <a class="menu-link {{ Request::is('portal/category-wise-question/'. $category->id) ? 'active' : '' }}" href="{{ route('portal.category-wise-question', $category->id) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $category->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif 

                    @php
                        $categories = App\Models\Category::select('id', 'name', 'parent_id')->where('parent_id', 64)->get();
                    @endphp
                    @if(Auth::guard('admin')->user()->hasPermissionTo('admission.update'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/category-wise-question/*') && in_array(request()->route('id'), $categories->pluck('id')->toArray()) ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fab fa-quora">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Admission</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                
                                @if ($categories)
                                    @foreach ($categories as $category)
                                        <div class="menu-item">
                                            <a class="menu-link {{ Request::is('portal/category-wise-question/'. $category->id) ? 'active' : '' }}" href="{{ route('portal.category-wise-question', $category->id) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $category->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif 

                    @php
                        $categories = App\Models\Category::select('id', 'name', 'parent_id')->where('parent_id', 337)->get();
                    @endphp
                    @if(Auth::guard('admin')->user()->hasPermissionTo('admission.update'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/category-wise-question/*') && in_array(request()->route('id'), $categories->pluck('id')->toArray()) ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fab fa-quora">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Bank</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                
                                @if ($categories)
                                    @foreach ($categories as $category)
                                        <div class="menu-item">
                                            <a class="menu-link {{ Request::is('portal/category-wise-question/'. $category->id) ? 'active' : '' }}" href="{{ route('portal.category-wise-question', $category->id) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $category->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif 

                    @php
                        $categories = App\Models\Category::select('id', 'name', 'parent_id')->where('parent_id', 783)->get();
                    @endphp
                    @if(Auth::guard('admin')->user()->hasPermissionTo('admission.update'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/category-wise-question/*') && in_array(request()->route('id'), $categories->pluck('id')->toArray()) ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fab fa-quora">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Academy</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                
                                @if ($categories)
                                    @foreach ($categories as $category)
                                        <div class="menu-item">
                                            <a class="menu-link {{ Request::is('portal/category-wise-question/'. $category->id) ? 'active' : '' }}" href="{{ route('portal.category-wise-question', $category->id) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $category->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif 

                    @php
                        $categories = App\Models\Category::select('id', 'name', 'parent_id')->where('parent_id', 312)->get();
                    @endphp
                    @if(Auth::guard('admin')->user()->hasPermissionTo('admission.update'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/category-wise-question/*') && in_array(request()->route('id'), $categories->pluck('id')->toArray()) ? 'here show' : '' }} menu-accordion">

                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fab fa-quora">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Current Affairs</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                @if ($categories)
                                    @foreach ($categories as $category)
                                        <div class="menu-item">
                                            <a class="menu-link {{ Request::is('portal/category-wise-question/'. $category->id) ? 'active' : '' }}" href="{{ route('portal.category-wise-question', $category->id) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $category->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('roles.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/role*') ? 'active' : '' }}" href="{{ route('portal.roles.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-tint"></i>
                                </span>
                                <span class="menu-title">Role & Permission</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('stuff.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/stuff*') ? 'active' : '' }}" href="{{ route('portal.stuff.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span class="menu-title">Stuff</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('settings.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/settings') ? 'active' : '' }}" href="{{ route('portal.settings') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-cog"></i>
                                </span>
                                <span class="menu-title">Settings</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::guard('admin')->user()->hasPermissionTo('recycle_bin.view'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/website/*') ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fas fa-globe">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Website</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/website/header') ? 'active' : '' }}" href="{{ route('portal.website.header') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Header</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/website/home_page_seo') ? 'active' : '' }}" href="{{ route('portal.website.home_page_seo') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Home Page SEO & Content</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/home-carousel') ? 'active' : '' }}" href="{{ route('portal.home-carousel.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Home Carousel</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/featured-categories') ? 'active' : '' }}" href="{{ route('portal.featured-categories.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Featured Category</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/featured-banners') ? 'active' : '' }}" href="{{ route('portal.featured-banners.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Featured Banners</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/website/footer') ? 'active' : '' }}" href="{{ route('portal.website.footer') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Footer</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/blog*') ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fas fa-blog"></i>
                                </span>
                                <span class="menu-title">Blog</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/blog-category*') ? 'active' : '' }}" href="{{ route('portal.blog-category.index') }}">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">Category</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/blog-author*') ? 'active' : '' }}" href="{{ route('portal.blog-author.index') }}">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">Author</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/blog-tag*') ? 'active' : '' }}" href="{{ route('portal.blog-tag.index') }}">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">Tag</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/blog*') && !Request::is('portal/blog-category*') && !Request::is('portal/blog-author*') && !Request::is('portal/blog-tag*') ? 'active' : '' }}" href="{{ route('portal.blog.index') }}">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">Blogs</span>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/testimonial') ? 'active' : '' }}" href="{{ route('portal.testimonial.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-cog"></i>
                                </span>
                                <span class="menu-title">Testimonial</span>
                            </a>
                        </div>
                        
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('portal/page') ? 'active' : '' }}" href="{{ route('portal.page.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-cog"></i>
                                </span>
                                <span class="menu-title">Pages</span>
                            </a>
                        </div>
                    @endif 

                    @if(Auth::guard('admin')->user()->hasPermissionTo('recycle_bin.view'))
                        <div data-menu-trigger="click" class="menu-item {{ Request::is('portal/bin/*') ? 'here show' : '' }} menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fas fa-trash">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Recycle Bin</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/bin/categories') ? 'active' : '' }}" href="{{ route('portal.bin.categories') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Categories</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/bin/questions') ? 'active' : '' }}" href="{{ route('portal.bin.questions') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Questions</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/bin/job-categories') ? 'active' : '' }}" href="{{ route('portal.bin.job-categories') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Job Categories</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/bin/years') ? 'active' : '' }}" href="{{ route('portal.bin.years') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Years</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/bin/exams') ? 'active' : '' }}" href="{{ route('portal.bin.exams') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Exams</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('portal/bin/passage') ? 'active' : '' }}" href="{{ route('portal.bin.passage') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Passage</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif 
                </div>
            </div>
        </div>
    </div>
</aside>
