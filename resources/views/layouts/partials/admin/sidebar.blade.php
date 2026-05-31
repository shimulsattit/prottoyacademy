@php
    $user = Auth::guard('admin')->user();
@endphp

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">প্র</div>
        <div class="logo-text">
            <h2>প্রত্যয় একাডেমি</h2>
            <span>অ্যাডমিন পোর্টাল</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <!-- Main Menu Section -->
        <div class="section-title">প্রধান মেনু</div>
        
        <a href="{{ route('portal.dashboard') }}" class="nav-item {{ Request::is('portal/dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-label">ড্যাশবোর্ড</span>
        </a>
        
        @if($user->hasPermissionTo('category.view'))
            <a href="{{ route('portal.category.index') }}" class="nav-item {{ Request::is('portal/category*') ? 'active' : '' }}">
                <i class="fas fa-bars"></i>
                <span class="nav-label">ক্যাটাগরি</span>
            </a>
        @endif

        @if($user->hasPermissionTo('job_category.view'))
            <a href="{{ route('portal.job-category.index') }}" class="nav-item {{ Request::is('portal/job-category*') ? 'active' : '' }}">
                <i class="fas fa-stream"></i>
                <span class="nav-label">জব ক্যাটাগরি</span>
            </a>
        @endif

        @if($user->hasPermissionTo('year.view'))
            <a href="{{ route('portal.year.index') }}" class="nav-item {{ Request::is('portal/year*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                <span class="nav-label">বছর</span>
            </a>
        @endif

        @if($user->hasPermissionTo('exam.view'))
            <a href="{{ route('portal.exam.index') }}" class="nav-item {{ Request::is('portal/exam*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="nav-label">পরীক্ষা</span>
            </a>
        @endif

        @if($user->hasPermissionTo('question.view'))
            <a href="{{ route('portal.question.index') }}" class="nav-item {{ Request::is('portal/question*') && !Request::is('portal/import-question*') && !Request::is('portal/pdf-questions*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i>
                <span class="nav-label">প্রশ্নব্যাংক</span>
            </a>
        @endif

        @if($user->hasPermissionTo('question.import'))
            <a href="{{ route('portal.import-question') }}" class="nav-item {{ Request::is('portal/import-question*') ? 'active' : '' }}">
                <i class="fas fa-upload"></i>
                <span class="nav-label">ইম্পোর্ট</span>
            </a>
        @endif

        <!-- PDF to AI Question Generator (Always visible or customizable) -->
        <a href="{{ route('portal.pdf.index') }}" class="nav-item {{ Request::is('portal/pdf-questions*') ? 'active' : '' }}">
            <i class="fas fa-magic" style="color: #F39C12;"></i>
            <span class="nav-label" style="font-weight: 600;">PDF → AI প্রশ্ন</span>
        </a>

        @if($user->hasPermissionTo('passage.view'))
            <a href="{{ route('portal.passage.index') }}" class="nav-item {{ Request::is('portal/passage*') ? 'active' : '' }}">
                <i class="fas fa-align-left"></i>
                <span class="nav-label">প্যাসেজ</span>
            </a>
        @endif

        <!-- Category Wise Section -->
        @php
            $jobSolutionCats = App\Models\Category::select('id', 'name')->where('parent_id', 9)->get();
            $admissionCats = App\Models\Category::select('id', 'name')->where('parent_id', 64)->get();
            $bankCats = App\Models\Category::select('id', 'name')->where('parent_id', 337)->get();
            $academyCats = App\Models\Category::select('id', 'name')->where('parent_id', 783)->get();
            $currentAffairsCats = App\Models\Category::select('id', 'name')->where('parent_id', 312)->get();
            
            $hasAnyCategoryWise = 
                ($user->hasPermissionTo('job_solution.update') && $jobSolutionCats->count() > 0) ||
                ($user->hasPermissionTo('admission.update') && (
                    $admissionCats->count() > 0 || 
                    $bankCats->count() > 0 || 
                    $academyCats->count() > 0 || 
                    $currentAffairsCats->count() > 0
                ));
        @endphp

        @if($hasAnyCategoryWise)
            <div class="section-title">ক্যাটাগরি ভিত্তিক</div>
            
            @if($user->hasPermissionTo('job_solution.update') && $jobSolutionCats->count() > 0)
                <div class="nav-dropdown">
                    <div class="nav-item has-dropdown">
                        <i class="fas fa-briefcase"></i>
                        <span class="nav-label">Job Solution</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="dropdown-content">
                        @foreach($jobSolutionCats as $cat)
                            <a href="{{ route('portal.category-wise-question', $cat->id) }}" class="nav-item {{ Request::is('portal/category-wise-question/'.$cat->id) ? 'active' : '' }}">
                                <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                                <span class="nav-label">{{ $cat->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($user->hasPermissionTo('admission.update') && $admissionCats->count() > 0)
                <div class="nav-dropdown">
                    <div class="nav-item has-dropdown">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="nav-label">Admission</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="dropdown-content">
                        @foreach($admissionCats as $cat)
                            <a href="{{ route('portal.category-wise-question', $cat->id) }}" class="nav-item {{ Request::is('portal/category-wise-question/'.$cat->id) ? 'active' : '' }}">
                                <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                                <span class="nav-label">{{ $cat->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($user->hasPermissionTo('admission.update') && $bankCats->count() > 0)
                <div class="nav-dropdown">
                    <div class="nav-item has-dropdown">
                        <i class="fas fa-university"></i>
                        <span class="nav-label">Bank</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="dropdown-content">
                        @foreach($bankCats as $cat)
                            <a href="{{ route('portal.category-wise-question', $cat->id) }}" class="nav-item {{ Request::is('portal/category-wise-question/'.$cat->id) ? 'active' : '' }}">
                                <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                                <span class="nav-label">{{ $cat->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($user->hasPermissionTo('admission.update') && $academyCats->count() > 0)
                <div class="nav-dropdown">
                    <div class="nav-item has-dropdown">
                        <i class="fas fa-book-reader"></i>
                        <span class="nav-label">Academy</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="dropdown-content">
                        @foreach($academyCats as $cat)
                            <a href="{{ route('portal.category-wise-question', $cat->id) }}" class="nav-item {{ Request::is('portal/category-wise-question/'.$cat->id) ? 'active' : '' }}">
                                <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                                <span class="nav-label">{{ $cat->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($user->hasPermissionTo('admission.update') && $currentAffairsCats->count() > 0)
                <div class="nav-dropdown">
                    <div class="nav-item has-dropdown">
                        <i class="fas fa-globe-asia"></i>
                        <span class="nav-label">Current Affairs</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="dropdown-content">
                        @foreach($currentAffairsCats as $cat)
                            <a href="{{ route('portal.category-wise-question', $cat->id) }}" class="nav-item {{ Request::is('portal/category-wise-question/'.$cat->id) ? 'active' : '' }}">
                                <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                                <span class="nav-label">{{ $cat->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Administration Section -->
        <div class="section-title">প্রশাসন</div>

        @if($user->hasPermissionTo('stuff.view'))
            <a href="{{ route('portal.stuff.index') }}" class="nav-item {{ Request::is('portal/stuff*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span class="nav-label">স্টাফগণ</span>
            </a>
        @endif

        @if($user->hasPermissionTo('roles.view'))
            <a href="{{ route('portal.roles.index') }}" class="nav-item {{ Request::is('portal/roles*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span class="nav-label">রোল ও পারমিশন</span>
            </a>
        @endif

        @if($user->hasPermissionTo('settings.view'))
            <a href="{{ route('portal.settings') }}" class="nav-item {{ Request::is('portal/settings*') && !Request::is('portal/website*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span class="nav-label">সেটিংস</span>
            </a>
        @endif

        <!-- Website & Blog & Extra Section (recycle_bin.view gate) -->
        @if($user->hasPermissionTo('recycle_bin.view'))
            <div class="section-title">অন্যান্য</div>

            <!-- Website Settings Dropdown -->
            <div class="nav-dropdown">
                <div class="nav-item has-dropdown">
                    <i class="fas fa-globe"></i>
                    <span class="nav-label">ওয়েবসাইট</span>
                    <i class="fas fa-chevron-down chevron-icon"></i>
                </div>
                <div class="dropdown-content">
                    <a href="{{ route('portal.website.header') }}" class="nav-item {{ Request::is('portal/website/header') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Header</span>
                    </a>
                    <a href="{{ route('portal.website.home_page_seo') }}" class="nav-item {{ Request::is('portal/website/home_page_seo') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">SEO & Content</span>
                    </a>
                    <a href="{{ route('portal.home-carousel.index') }}" class="nav-item {{ Request::is('portal/home-carousel*') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Carousel</span>
                    </a>
                    <a href="{{ route('portal.featured-categories.index') }}" class="nav-item {{ Request::is('portal/featured-categories*') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Featured Category</span>
                    </a>
                    <a href="{{ route('portal.featured-banners.index') }}" class="nav-item {{ Request::is('portal/featured-banners*') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Featured Banners</span>
                    </a>
                    <a href="{{ route('portal.website.footer') }}" class="nav-item {{ Request::is('portal/website/footer') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Footer</span>
                    </a>
                </div>
            </div>

            <!-- Blog Dropdown -->
            <div class="nav-dropdown">
                <div class="nav-item has-dropdown">
                    <i class="fas fa-blog"></i>
                    <span class="nav-label">ব্লগ</span>
                    <i class="fas fa-chevron-down chevron-icon"></i>
                </div>
                <div class="dropdown-content">
                    <a href="{{ route('portal.blog-category.index') }}" class="nav-item {{ Request::is('portal/blog-category*') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">ক্যাটাগরি</span>
                    </a>
                    <a href="{{ route('portal.blog-author.index') }}" class="nav-item {{ Request::is('portal/blog-author*') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">লেখক</span>
                    </a>
                    <a href="{{ route('portal.blog-tag.index') }}" class="nav-item {{ Request::is('portal/blog-tag*') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">ট্যাগ</span>
                    </a>
                    <a href="{{ route('portal.blog.index') }}" class="nav-item {{ Request::is('portal/blog') || (Request::is('portal/blog*') && !Request::is('portal/blog-category*') && !Request::is('portal/blog-author*') && !Request::is('portal/blog-tag*')) ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">ব্লগসমূহ</span>
                    </a>
                </div>
            </div>

            <!-- Testimonial link -->
            <a href="{{ route('portal.testimonial.index') }}" class="nav-item {{ Request::is('portal/testimonial*') ? 'active' : '' }}">
                <i class="fas fa-quote-left"></i>
                <span class="nav-label">টেস্টিমোনিয়াল</span>
            </a>

            <!-- Pages link -->
            <a href="{{ route('portal.page.index') }}" class="nav-item {{ Request::is('portal/page*') ? 'active' : '' }}">
                <i class="fas fa-file"></i>
                <span class="nav-label">পেজসমূহ</span>
            </a>

            <!-- Recycle Bin Dropdown -->
            <div class="nav-dropdown">
                <div class="nav-item has-dropdown">
                    <i class="fas fa-trash-alt"></i>
                    <span class="nav-label">রিসাইকেল বিন</span>
                    <i class="fas fa-chevron-down chevron-icon"></i>
                </div>
                <div class="dropdown-content">
                    <a href="{{ route('portal.bin.categories') }}" class="nav-item {{ Request::is('portal/bin/categories') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Categories</span>
                    </a>
                    <a href="{{ route('portal.bin.questions') }}" class="nav-item {{ Request::is('portal/bin/questions') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Questions</span>
                    </a>
                    <a href="{{ route('portal.bin.job-categories') }}" class="nav-item {{ Request::is('portal/bin/job-categories') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Job Categories</span>
                    </a>
                    <a href="{{ route('portal.bin.years') }}" class="nav-item {{ Request::is('portal/bin/years') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Years</span>
                    </a>
                    <a href="{{ route('portal.bin.exams') }}" class="nav-item {{ Request::is('portal/bin/exams') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Exams</span>
                    </a>
                    <a href="{{ route('portal.bin.passage') }}" class="nav-item {{ Request::is('portal/bin/passage') ? 'active' : '' }}">
                        <i class="fas fa-circle-notch" style="font-size: 8px;"></i>
                        <span class="nav-label">Passage</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>
    <div class="sidebar-footer">
        <div class="user-card" id="logout" data-url="{{ route('portal.logout') }}">
            <div class="user-avatar">{{ substr(Auth::guard('admin')->user()->first_name, 0, 1) }}</div>
            <div class="user-info">
                <strong>{{ Auth::guard('admin')->user()->first_name }}</strong>
                <span>লগআউট করুন</span>
            </div>
        </div>
    </div>
</aside>
