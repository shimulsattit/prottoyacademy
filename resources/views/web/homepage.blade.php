@extends('layouts.web', ['title' => get_settings('home_site_title')])

@push('style')
<style>
  /* ANIMATIONS */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes pulse {
    0%,100% { opacity: 1; } 50% { opacity: 0.4; }
  }

  /* HERO */
  .hero-premium {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    text-align: center;
    padding: 160px 48px 100px;
    overflow: hidden;
  }

  .badge-premium {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(245,197,24,0.1);
    border: 1px solid rgba(245,197,24,0.28);
    border-radius: 100px; padding: 8px 24px;
    font-size: 13px; color: var(--accent-gold); font-weight: 600;
    margin-bottom: 26px;
    animation: fadeUp .8s ease both;
  }
  .badge-dot-premium { width: 7px; height: 7px; border-radius: 50%; background: var(--accent-gold); animation: pulse 1.6s infinite; }

  /* STATS ROW */
  .stats-row-exact {
    display: flex; justify-content: center;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px; overflow: hidden;
    max-width: 680px; width: 100%;
    animation: fadeUp .8s .4s ease both;
    margin: 0 auto;
  }
  .stat-box-exact {
    flex: 1; padding: 20px 14px; text-align: center;
    border-right: 1px solid rgba(255,255,255,0.07);
    box-sizing: border-box;
  }
  .stat-box-exact:last-child { border-right: none; }
  .stat-num-exact {
    font-family: 'Noto Serif Bengali', serif; font-size: 26px; font-weight: 900;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  }
  .stat-lbl-exact { font-size: 12px; color: var(--text-light); margin-top: 3px; }

  /* SECTION */
  .section-premium { position: relative; z-index: 1; padding: 72px 52px; }
  .sec-top-premium { margin-bottom: 42px; }
  .sec-label-premium {
    font-size: 12px; font-weight: 700; letter-spacing: 3px;
    text-transform: uppercase; color: var(--accent-gold); margin-bottom: 10px;
  }
  .sec-title-premium {
    font-family: 'Noto Serif Bengali', serif;
    font-size: clamp(26px, 3.5vw, 40px); font-weight: 800;
  }
  .sec-sub-premium { font-size: 15px; color: var(--text-light); margin-top: 8px; opacity: 0.9; }

  /* CATEGORY CARDS */
  .pz-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
  }

  .pz-card {
    position: relative; overflow: hidden;
    border-radius: 22px;
    padding: 36px 36px 32px;
    border: 1px solid rgba(255,255,255,0.08) !important;
    cursor: pointer;
    transition: transform .28s cubic-bezier(.22,.68,0,1.2), box-shadow .28s;
    background: rgba(255,255,255,0.055) !important;
    backdrop-filter: blur(12px);
    text-decoration: none !important;
    color: #fff !important;
    display: flex !important;
    flex-direction: column !important;
  }
  .pz-card:hover { transform: translateY(-8px) scale(1.01); }

  .pz-glow {
    position: absolute; top: -40px; right: -40px;
    width: 180px; height: 180px; border-radius: 50%;
    opacity: 0.12; transition: opacity .3s, transform .3s;
    pointer-events: none;
  }
  .pz-card:hover .pz-glow { opacity: 0.22; transform: scale(1.2); }

  .pz-header { display: flex !important; align-items: flex-start !important; justify-content: space-between !important; margin-bottom: 20px !important; }

  .pz-icon-box {
    width: 64px; height: 64px; border-radius: 18px;
    display: flex !important; align-items: center !important; justify-content: center !important;
    font-size: 30px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    flex-shrink: 0;
  }

  .pz-badge {
    font-size: 11px; font-weight: 700; letter-spacing: 1px;
    padding: 4px 12px; border-radius: 100px;
  }

  .pz-title {
    font-family: 'Noto Serif Bengali', serif !important;
    font-size: 24px !important; font-weight: 800 !important; margin-bottom: 8px !important;
    line-height: 1.2 !important; display: block !important; color: #fff !important;
  }

  .pz-desc { font-size: 14px !important; color: var(--text-light) !important; line-height: 1.65 !important; margin-bottom: 22px !important; }

  .pz-tags { display: flex !important; flex-wrap: wrap !important; gap: 8px !important; margin-bottom: 22px !important; }
  .pz-tag {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    font-size: 13px !important; 
    font-weight: 600 !important;
    padding: 6px 14px !important; 
    border-radius: 100px !important;
    background: rgba(255,255,255,0.08) !important;
    color: var(--text-light) !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
    text-decoration: none !important;
    transition: all .2s ease;
    white-space: nowrap !important;
    margin-bottom: 4px;
  }
  .pz-tag:hover {
    background: rgba(255,255,255,0.15) !important;
    border-color: rgba(245,197,24,0.3) !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  }

  .pz-footer {
    display: flex !important; align-items: center !important; justify-content: space-between !important;
    margin-top: auto !important;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.08);
  }
  .pz-stat-box { display: flex; flex-direction: column; gap: 2px; flex: 1; }
  
  .pz-num-box { 
    display: inline-flex; align-items: center; justify-content: flex-start;
    color: #fff; font-weight: 800; font-size: 22px;
    width: fit-content;
    background-clip: text;
    -webkit-background-clip: text;
  }
  .pz-stat-label { font-size: 13px; color: var(--text-light); white-space: nowrap; margin-top: -2px; }

  .pz-btn {
    display: flex !important; align-items: center !important; gap: 8px !important;
    color: #fff !important; font-family: 'Hind Siliguri', sans-serif !important;
    font-size: 14px !important; font-weight: 700 !important;
    padding: 10px 20px !important; border: none !important; border-radius: 10px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    transition: transform .2s;
  }
  .pz-btn:hover { transform: translateX(4px); }

  /* FEATURES (HUBUHU) */
  .features-row-exact { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
  .feat-card-exact {
    background: rgba(255,255,255,0.055);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 18px; padding: 30px 24px;
    transition: transform .25s;
  }
  .feat-card-exact:hover { transform: translateY(-4px); }
  .feat-icon-exact {
    width: 48px; height: 48px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin-bottom: 16px;
  }
  .feat-card-exact h3 { font-family: 'Noto Serif Bengali', serif; font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #fff; }
  .feat-card-exact p { font-size: 13.5px; color: var(--text-light); line-height: 1.7; }

  /* RECENT Q (HUBUHU) */
  .q-list-premium { display: flex; flex-direction: column; gap: 12px; }
  .q-row-premium {
    display: flex; align-items: center; gap: 18px;
    background: rgba(255,255,255,0.055);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 13px; padding: 18px 22px; cursor: pointer;
    transition: background .2s, border-color .2s, transform .2s;
    text-decoration: none; color: #fff;
  }
  .q-row-premium:hover { background: rgba(255,255,255,0.09); border-color: rgba(245,197,24,0.25); transform: translateX(4px); color: #fff; }
  .q-badge-premium {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; flex-shrink: 0;
  }
  .q-body-premium { flex: 1; }
  .q-body-premium p { font-size: 15.5px; font-weight: 600; margin-bottom: 10px; line-height: 1.6; color: #ffffff !important; }
  .q-chips-premium { display: flex; flex-wrap: wrap; gap: 8px; }
  .chip-premium {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 100px;
    background: rgba(255, 255, 255, 0.07);
    color: var(--text-light);
  }
  .q-arrow-premium { color: rgba(255,255,255,0.4); font-size: 20px; flex-shrink: 0; transition: transform .2s, color .2s; }
  .q-row-premium:hover .q-arrow-premium { transform: translateX(4px); color: var(--accent-gold); }

  @media (max-width: 1024px) {
    .features-row-exact { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 768px) {
    .hero-premium { padding: 100px 20px 60px; }
    .badge-premium { padding: 6px 16px; font-size: 11px; margin-bottom: 20px; }
    .stats-row-exact { flex-wrap: wrap; border-radius: 12px; }
    .stat-box-exact { flex: 0 0 50%; padding: 15px 10px; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.07); }
    .stat-box-exact:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.07); }
    .stat-box-exact:nth-last-child(-n+2) { border-bottom: none; }
    .stat-num-exact { font-size: 20px; }
    .stat-lbl-exact { font-size: 11px; }

    .section-premium { padding: 48px 20px; }
    .sec-top-premium { margin-bottom: 30px; }
    .sec-title-premium { font-size: 24px; }
    
    .pz-grid, .features-row-exact { grid-template-columns: 1fr; gap: 16px; }
    .pz-card { padding: 26px 22px 22px; border-radius: 18px; }
    .pz-title { font-size: 20px !important; }
    .pz-icon-box { width: 54px; height: 54px; font-size: 24px !important; }
    .pz-footer { flex-direction: row; align-items: center !important; justify-content: space-between !important; gap: 10px; }
    .pz-btn { width: auto; padding: 8px 16px !important; font-size: 13px !important; }
    .pz-num-box { font-size: 18px; }
    .pz-stat-label { font-size: 11px; }

    .q-row-premium { padding: 14px 16px; gap: 12px; }
    .q-badge-premium { width: 32px; height: 32px; font-size: 13px; }
    .q-body-premium p { font-size: 14px; margin-bottom: 8px; }

    .cta-container-exact { padding: 0 16px 60px !important; }
    .cta-inner-exact { padding: 40px 20px !important; text-align: center; }
    .cta-inner-exact .col-lg-4 { margin-top: 24px; }
  }
  
  *, *::before, *::after { box-sizing: border-box; }
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="hero-premium">
  <div class="badge-premium"><span class="badge-dot-premium"></span> বাংলাদেশের সবচেয়ে বড় ডিজিটাল প্রশ্ন ব্যাংক</div>
  <h1 style="font-family: 'Noto Serif Bengali', serif; font-size: clamp(28px, 6vw, 62px); font-weight: 900; line-height: 1.2; margin-bottom: 18px; color:#fff">সকল পরীক্ষার <span class="hl" style="background: linear-gradient(90deg, var(--accent-gold), var(--accent-orange)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">সেরা প্রস্তুতি</span><br>এক জায়গায়</h1>
  <p style="max-width: 580px; font-size: clamp(14px, 4vw, 17px); color: var(--text-light); line-height: 1.75; margin: 0 auto 36px;">চাকরি, ব্যাংক, ভর্তি ও বোর্ড পরীক্ষার জন্য লক্ষাধিক প্রশ্ন, মডেল টেস্ট ও বিস্তারিত সমাধান — সম্পূর্ণ বাংলায়।<br><strong style="color:#f5c518">প্রত্যয় একাডেমি</strong> — আপনার সাফল্যের সঙ্গী।</p>
  
  <div class="hero-btns-premium" style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 56px;">
    <a href="{{ route('register') }}" class="btn-primary" style="background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange)); color: #07091e; font-family: 'Hind Siliguri', sans-serif; font-size: 16px; font-weight: 700; padding: 13px 30px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 6px 22px rgba(245,197,24,0.35); text-decoration: none;">🎯 বিনামূল্যে শুরু করুন</a>
    <a href="#" class="btn-outline" style="background: transparent; color: #fff; font-family: 'Hind Siliguri', sans-serif; font-size: 16px; font-weight: 600; padding: 13px 30px; border: 1.5px solid rgba(255,255,255,0.22); border-radius: 10px; cursor: pointer; text-decoration: none;">📖 ডেমো দেখুন</a>
  </div>
  
  <div class="stats-row-exact">
    <div class="stat-box-exact">
      <div class="stat-num-exact">১.৫ লাখ+</div>
      <div class="stat-lbl-exact">মোট প্রশ্ন</div>
    </div>
    <div class="stat-box-exact">
      <div class="stat-num-exact">৭৫ হাজার+</div>
      <div class="stat-lbl-exact">সক্রিয় শিক্ষার্থী</div>
    </div>
    <div class="stat-box-exact">
      <div class="stat-num-exact">৪টি</div>
      <div class="stat-lbl-exact">মূল ক্যাটাগরি</div>
    </div>
    <div class="stat-box-exact">
      <div class="stat-num-exact">৯৮%</div>
      <div class="stat-lbl-exact">সন্তুষ্টি হার</div>
    </div>
  </div>
</section>

<!-- CATEGORIES -->
<section class="section-premium" id="categories">
  <div class="sec-top-premium text-center">
    <div class="sec-label-premium">ক্যাটাগরিসমূহ</div>
    <div class="sec-title-premium">আপনি কোন পরীক্ষার জন্য প্রস্তুতি নিচ্ছেন?</div>
    <div class="sec-sub-premium">আপনার লক্ষ্য অনুযায়ী ক্যাটাগরি বেছে নিন এবং স্মার্ট প্র্যাকটিস শুরু করুন।</div>
  </div>

  <div class="pz-grid">
    @php
        $categoryDataMap = [
            'Job Solution' => [
                'icon' => '💼', 
                'badge' => '★ সবচেয়ে জনপ্রিয়', 
                'desc' => 'সরকারি ও বেসরকারি চাকরির বিগত বছরের প্রশ্ন ও সমাধান। BCS, PSC, NTRCA, সরকারি প্রাথমিক শিক্ষক নিয়োগ সহ সকল চাকরির পূর্ণ প্রস্তুতি।',
                'tags' => [
                    ['label' => 'বিসিএস', 'slug' => '--1741629608471'],
                    ['label' => 'পিএসসি ও অন্যান্য পরীক্ষা', 'slug' => 'পিএসসি-ও-অন্যান্য-পরীক্ষা'],
                    ['label' => 'শিক্ষক নিবন্ধন', 'slug' => 'শিক্ষক-নিবন্ধন-ও-প্রত্যয়ন-পরীক্ষা'],
                    ['label' => 'সহকারী জজ', 'slug' => 'সহকারী-জজ-নিয়োগ-পরীক্ষা'],
                    ['label' => 'উপজেলা/থানা শিক্ষা অফিসার', 'slug' => 'উপজেলা-থানা-শিক্ষা-অফিসার']
                ],
                'color' => ['#f5c518', '#ff6b35'],
                'border' => 'rgba(245,197,24,0.1)',
                'watermark' => '💼'
            ],
            'Bank Recruitment' => [
                'icon' => '🏦', 
                'badge' => '🔥 হট সেকশন', 
                'desc' => 'বাংলাদেশ ব্যাংক, সোনালী, জনতা, অগ্রণী ব্যাংকসহ সকল সরকারি-বেসরকারি ব্যাংক নিয়োগ পরীক্ষার বিগত প্রশ্ন ও বিস্তারিত সমাধান।',
                'tags' => [
                    ['label' => 'Sonali & Janata Bank', 'slug' => 'sonali-bank-ltd'],
                    ['label' => 'Janata & Rupali Bank Ltd', 'slug' => 'janata-bank-ltd'],
                    ['label' => 'Joint Recruitment Test', 'slug' => 'joint-recruitment-test'],
                    ['label' => 'Polli Sonchoy Bank', 'slug' => 'polli-sonchoy-bank'],
                    ['label' => 'Modhumoti Bank Ltd', 'slug' => 'modhumoti-bank-ltd']
                ],
                'color' => ['#00b4d8', '#0077b6'],
                'border' => 'rgba(0,180,216,0.1)',
                'watermark' => '🏦'
            ],
            'Bank' => [
                'icon' => '🏦', 
                'badge' => '🔥 হট সেকশন', 
                'desc' => 'বাংলাদেশ ব্যাংক, সোনালী, জনতা, অগ্রণী ব্যাংকসহ সকল সরকারি-বেসরকারি ব্যাংক নিয়োগ পরীক্ষার বিগত প্রশ্ন ও বিস্তারিত সমাধান।',
                'tags' => [
                    ['label' => 'Sonali & Janata Bank', 'slug' => 'sonali-bank-ltd'],
                    ['label' => 'Janata & Rupali Bank Ltd', 'slug' => 'janata-bank-ltd'],
                    ['label' => 'Joint Recruitment Test', 'slug' => 'joint-recruitment-test'],
                    ['label' => 'Polli Sonchoy Bank', 'slug' => 'polli-sonchoy-bank'],
                    ['label' => 'Modhumoti Bank Ltd', 'slug' => 'modhumoti-bank-ltd']
                ],
                'color' => ['#00b4d8', '#0077b6'],
                'border' => 'rgba(0,180,216,0.1)',
                'watermark' => '🏦'
            ],
            'Admission' => [
                'icon' => '🎓', 
                'badge' => 'নতুন প্রশ্ন যুক্ত', 
                'desc' => 'ঢাকা বিশ্ববিদ্যালয়, বুয়েট, মেডিকেল কলেজ ও জাতীয় বিশ্ববিদ্যালয়ের ভর্তি পরীক্ষার প্রশ্ন ও পূর্ণ সমাধান।',
                'tags' => ['ঢাকা বিশ্ববিদ্যালয়', 'বুয়েট', 'মেডিকেল', 'জাতীয় বিশ্ববিদ্যালয়', 'GST গুচ্ছ', 'রুয়েট / কুয়েট'],
                'color' => ['#a78bfa', '#7c3aed'],
                'border' => 'rgba(167,139,250,0.1)',
                'watermark' => '🎓'
            ],
            'School & College' => [
                'icon' => '📚', 
                'badge' => 'সকল বোর্ড', 
                'desc' => 'SSC, HSC, JSC ও ৬ষ্ঠ থেকে ১০ম শ্রেণির সকল বিষয়ের বোর্ড প্রশ্ন, সৃজনশীল, MCQ ও সাজেশন — সকল শিক্ষা বোর্ড অনুযায়ী বিভক্ত।',
                'tags' => ['HSC', 'SSC', 'JSC', '৬ষ্ঠ–৮ম শ্রেণি', 'সৃজনশীল', 'MCQ'],
                'color' => ['#22c55e', '#16a34a'],
                'border' => 'rgba(34,197,94,0.1)',
                'watermark' => '📚'
            ],
            'Academy' => [
                'icon' => '📚', 
                'badge' => 'স্কুল ও কলেজ', 
                'desc' => 'SSC, HSC, JSC ও ৬ষ্ঠ থেকে ১০ম শ্রেণির সকল বিষয়ের বোর্ড প্রশ্ন, সৃজনশীল, MCQ ও সাজেশন — সকল শিক্ষা বোর্ড অনুযায়ী বিভক্ত।',
                'tags' => ['HSC', 'SSC', 'JSC', '৬ষ্ঠ–৮ম শ্রেণি', 'সৃজনশীল', 'MCQ'],
                'color' => ['#22c55e', '#16a34a'],
                'border' => 'rgba(34,197,94,0.1)',
                'watermark' => '📚'
            ],
            'Current Affairs' => [
                'icon' => '🌍', 
                'badge' => 'সাম্প্রতিক তথ্য', 
                'desc' => 'দেশ-বিদেশের সাম্প্রতিক খবরাখবর, সাধারণ জ্ঞান এবং সকল প্রকার কুইজ ও তথ্যভাণ্ডার এখানে পাবেন।',
                'tags' => ['জাতীয়', 'আন্তর্জাতিক', 'খেলাধুলা', 'বিজ্ঞান ও প্রযুক্তি', 'অর্থনীতি'],
                'color' => ['#ff6b35', '#ef4444'],
                'border' => 'rgba(255,107,53,0.1)',
                'watermark' => '🌍'
            ]
        ];

        $categories = \App\Models\Category::whereNull('parent_id')->where('status', 1)->orderBy('id', 'asc')->get();
        
        $toBn = function($num) {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, (string)$num);
        };
    @endphp
    
    @foreach ($categories as $cat)
        @php
            $data = $categoryDataMap[$cat->name] ?? $categoryDataMap['Job Solution'];
            $count = method_exists($cat, 'totalQuestionsCount') ? $cat->totalQuestionsCount() : 0;
            $bnCount = str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], number_format((int)$count));
        @endphp
        <div class="pz-card" style="border-color:{{ $data['border'] }}">
          <div class="pz-glow" style="background:radial-gradient(circle, {{ $data['color'][0] }}, transparent 70%)"></div>
          <div style="position: absolute; bottom: -16px; right: 16px; font-size: 110px; line-height: 1; opacity: 0.05; pointer-events: none;">{{ $data['watermark'] }}</div>
          
          <div class="pz-header">
            <div class="pz-icon-box" style="background:linear-gradient(135deg, {{ $data['color'][0] }}, {{ $data['color'][1] }}); color: #07091e">
                {{ $data['icon'] }}
            </div>
            <span class="pz-badge" style="background:{{ $data['color'][0] }}26; color:{{ $data['color'][0] }}; border:1px solid {{ $data['color'][0] }}4d">{{ $data['badge'] }}</span>
          </div>
          
          <span class="pz-title">{{ $cat->name }}</span>
          <p class="pz-desc">{{ $data['desc'] }}</p>
          
          <div class="pz-tags">
            @foreach($data['tags'] as $tag)
                @if(is_array($tag))
                    <a href="{{ route('slug.handle', $tag['slug']) }}" class="pz-tag">{{ $tag['label'] }}</a>
                @else
                    <span class="pz-tag">{{ $tag }}</span>
                @endif
            @endforeach
          </div>

          <div class="pz-footer">
            <div class="pz-stat-box">
              <div class="pz-num-box" style="background:linear-gradient(135deg, {{ $data['color'][0] }}, {{ $data['color'][1] }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ $bnCount }}+
              </div>
              <span class="pz-stat-label">প্রশ্ন ও সমাধান</span>
            </div>
            <a href="{{ route('slug.handle', $cat->slug ?? '#') }}" class="pz-btn" style="background:linear-gradient(135deg, {{ $data['color'][0] }}, {{ $data['color'][1] }}); color: #07091e; text-decoration:none;">
                সকল ক্যাটাগরি <span style="font-size: 18px; margin-left:4px">→</span>
            </a>
          </div>
        </div>
    @endforeach
  </div>
</section>

<!-- FEATURES (HUBUHU) -->
<section class="section-premium" style="padding-top:0">
  <div class="sec-top-premium">
    <div class="sec-label-premium">কেন আমরা?</div>
    <div class="sec-title-premium">স্মার্ট প্রস্তুতির সুবিধাসমূহ</div>
  </div>
  <div class="features-row-exact">
    <div class="feat-card-exact">
      <div class="feat-icon-exact" style="background:rgba(245,197,24,0.13)">🎯</div>
      <h3>AI-ভিত্তিক প্র্যাকটিস</h3>
      <p>কৃত্রিম বুদ্ধিমত্তা আপনার দুর্বল বিষয় বিশ্লেষণ করে কাস্টমাইজড প্রশ্নসেট তৈরি করে দেয়।</p>
    </div>
    <div class="feat-card-exact">
      <div class="feat-icon-exact" style="background:rgba(0,180,216,0.13)">⏱️</div>
      <h3>লাইভ মডেল টেস্ট</h3>
      <p>টাইমার সহ প্রকৃত পরীক্ষার পরিবেশে মডেল টেস্ট দিন এবং তাৎক্ষণিক ফলাফল ও ব্যাখ্যা পান।</p>
    </div>
    <div class="feat-card-exact">
      <div class="feat-icon-exact" style="background:rgba(167,139,250,0.13)">📊</div>
      <h3>পারফরম্যান্স ট্র্যাকার</h3>
      <p>প্রতিটি সেশনের পর বিষয়ভিত্তিক গ্রাফ ও রিপোর্ট দেখুন। কোথায় উন্নতি দরকার তা বুঝুন।</p>
    </div>
  </div>
</section>

<!-- RECENT QUESTIONS (HUBUHU) - FULLY DYNAMIC & WHITE FONT -->
<section class="section-premium" style="padding-top:0">
  <div class="sec-top-premium">
    <div class="sec-label-premium">সাম্প্রতিক</div>
    <div class="sec-title-premium">নতুন যোগ হওয়া প্রশ্নসমূহ</div>
  </div>
  <div class="q-list-premium">
    @php
        $recentQuestions = \App\Models\Question::with(['category.parent', 'job_category', 'exam', 'year'])->latest()->take(5)->get();
        $qColors = ['#f5c518', '#00b4d8', '#a78bfa', '#22c55e', '#ff6b35'];
        $catMapping = [
            'BCS' => 'Job Solution', 'বিসিএস' => 'Job Solution',
            'Bank' => 'Bank Recruitment', 'ব্যাংক' => 'Bank Recruitment',
            'Admission' => 'Admission', 'ভর্তি' => 'Admission',
            'School' => 'School & College', 'স্কুল' => 'School & College'
        ];
    @endphp
    @foreach ($recentQuestions as $index => $q)
        @php
            $color = $qColors[$index % 5];
            $origCat = $q->category->name ?? 'General';
            $displayCat = $origCat;
            foreach($catMapping as $key => $val) {
                if (str_contains(strtolower($origCat), strtolower($key))) {
                    $displayCat = $val;
                    break;
                }
            }
            
            // Build dynamic title with fallbacks
            $examName = $q->exam->name ?? $q->job_category->name ?? $q->category->name ?? '';
            $yearName = $q->year->name ?? '';
            $prefix = trim($examName . ($yearName ? ' (' . $toBn($yearName) . ')' : ''));
            $fullQuestion = $prefix ? $prefix . ' — ' . $q->question : $q->question;
            
            // Subject/Topic logic
            $subject = $q->category->name ?? 'সাধারণ জ্ঞান';
            $parentCat = $q->category->parent->name ?? $displayCat;
        @endphp
        <a href="{{ $q->job_category ? route('slug.handle', $q->job_category->slug) : '#' }}" class="q-row-premium">
          <div class="q-badge-premium" style="background:{{ $color }}26; color:{{ $color }}">
            {{ $toBn($index + 1) }}
          </div>
          <div class="q-body-premium">
            <p style="color: #ffffff !important; font-weight: 700; opacity: 1;">{{ Str::limit($fullQuestion, 160) }}</p>
            <div class="q-chips-premium">
                <span class="chip-premium">{{ $parentCat }}</span>
                @if($examName)
                    <span class="chip-premium">{{ $examName }}</span>
                @endif
                <span class="chip-premium">{{ $subject }}</span>
            </div>
          </div>
          <span class="q-arrow-premium">›</span>
        </a>
    @endforeach
  </div>
</section>

<!-- CTA -->
<div class="container mb-5 cta-container-exact" style="padding: 0 52px 80px;">
  <div class="p-5 rounded-4 position-relative overflow-hidden cta-inner-exact" style="background: linear-gradient(135deg, #111c5e 0%, #0d1b5c 50%, #0a3660 100%); border: 1px solid rgba(0,180,216,0.18);">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-2 text-white" style="font-family: 'Noto Serif Bengali', serif; font-size: 34px; font-weight: 900;">আজই শুরু করুন — সম্পূর্ণ বিনামূল্যে!</h2>
            <p class="text-light opacity-75" style="font-size: 15px;">প্রথম ৩০ দিন সব ক্যাটাগরি ফ্রি। কোনো ক্রেডিট কার্ড লাগবে না।</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('register') }}" class="btn-primary" style="background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange)); color: #07091e; font-family: 'Hind Siliguri', sans-serif; font-size: 17px; font-weight: 700; padding: 15px 38px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 6px 22px rgba(245,197,24,0.35); text-decoration: none; display: inline-block;">🎓 এখনই নিবন্ধন করুন</a>
        </div>
    </div>
    <div class="position-absolute bottom-0 end-0 opacity-05" style="font-size: 220px; line-height: 1; transform: translate(20%, 20%); color: rgba(255,255,255,0.05); pointer-events: none;">✦</div>
  </div>
</div>
@endsection
