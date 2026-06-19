@extends('layouts.teacher', ['title' => 'ড্যাশবোর্ড'])

@push('style')
<style>
    .stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
    .stat-c { padding: 24px 20px; border-radius: 18px; border: 1px solid var(--bdr); background: var(--card); position: relative; overflow: hidden; transition: transform .25s; }
    .stat-c:hover { transform: translateY(-4px); }
    .s-icon { width: 46px; height: 46px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 14px; }
    .s-num { font-size: 28px; font-weight: 900; }
    .s-lbl { font-size: 13px; color: rgba(255,255,255,.5); margin-top: 4px; }
    .s-watermark { position: absolute; bottom: -10px; right: -4px; font-size: 64px; opacity: .05; }

    .quick-btn { display: inline-flex; align-items: center; gap: 8px; padding: 13px 26px; border-radius: 12px; font-family: inherit; font-size: 15px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: transform .2s, box-shadow .2s; }
    .quick-btn:hover { transform: translateY(-2px); }
    .btn-primary-t { background: linear-gradient(135deg, #00b4d8, #0077b6); color: #07091e; box-shadow: 0 6px 18px rgba(0,180,216,.35); }
    .btn-outline-t { background: transparent; color: #00b4d8; border: 1.5px solid rgba(0,180,216,.35); }

    .q-table { width: 100%; border-collapse: collapse; }
    .q-table th { font-size: 12px; font-weight: 700; color: rgba(255,255,255,.45); padding: 10px 14px; text-align: left; border-bottom: 1px solid var(--bdr); letter-spacing: .5px; text-transform: uppercase; }
    .q-table td { padding: 13px 14px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
    .q-table tr:last-child td { border-bottom: none; }
    .q-table tr:hover td { background: rgba(255,255,255,.03); }

    .badge-t { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; letter-spacing: .3px; }
    .b-pending  { background: rgba(245,197,24,.15); color: #f5c518; }
    .b-approved { background: rgba(34,197,94,.15);  color: #22c55e; }
    .b-rejected { background: rgba(239,68,68,.15);  color: #ef4444; }
    .b-mcq   { background: rgba(0,180,216,.15); color: #00b4d8; }
    .b-short { background: rgba(167,139,250,.15); color: #a78bfa; }
    .b-cq    { background: rgba(255,107,53,.15); color: #ff6b35; }

    @media (max-width: 768px) { .stat-row { grid-template-columns: repeat(2,1fr); } }
</style>
@endpush

@section('content')
<div class="pg-head">
    <h2>স্বাগতম, {{ $teacher->name }}! 👋</h2>
    <p>আপনার শিক্ষক প্যানেলের সারসংক্ষেপ</p>
</div>

{{-- STAT CARDS --}}
<div class="stat-row">
    <div class="stat-c">
        <div class="s-icon" style="background:rgba(0,180,216,.15); color:#00b4d8;">📝</div>
        <div class="s-num" style="color:#00b4d8;">{{ $totalQuestions }}</div>
        <div class="s-lbl">মোট প্রশ্ন</div>
        <div class="s-watermark">📝</div>
    </div>
    <div class="stat-c">
        <div class="s-icon" style="background:rgba(34,197,94,.15); color:#22c55e;">✅</div>
        <div class="s-num" style="color:#22c55e;">{{ $approvedQuestions }}</div>
        <div class="s-lbl">অনুমোদিত</div>
        <div class="s-watermark">✅</div>
    </div>
    <div class="stat-c">
        <div class="s-icon" style="background:rgba(245,197,24,.15); color:#f5c518;">⏳</div>
        <div class="s-num" style="color:#f5c518;">{{ $pendingQuestions }}</div>
        <div class="s-lbl">অনুমোদন অপেক্ষায়</div>
        <div class="s-watermark">⏳</div>
    </div>
    <div class="stat-c">
        <div class="s-icon" style="background:rgba(239,68,68,.15); color:#ef4444;">❌</div>
        <div class="s-num" style="color:#ef4444;">{{ $rejectedQuestions }}</div>
        <div class="s-lbl">রিজেক্টেড</div>
        <div class="s-watermark">❌</div>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:28px;">
    <a href="{{ route('teacher.questions.create') }}" class="quick-btn btn-primary-t">
        <i class="ri-add-circle-line"></i> নতুন প্রশ্ন যোগ করুন
    </a>
    <a href="{{ route('teacher.questions') }}" class="quick-btn btn-outline-t">
        <i class="ri-list-check-2"></i> সব প্রশ্ন দেখুন
    </a>
</div>

{{-- RECENT QUESTIONS --}}
<div class="t-card" style="padding: 24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h5 style="font-weight:800; font-size:16px;">সাম্প্রতিক প্রশ্নসমূহ</h5>
        <a href="{{ route('teacher.questions') }}" style="font-size:13px; color:#00b4d8; text-decoration:none;">সব দেখুন →</a>
    </div>
    @if($recentQuestions->count() > 0)
    <table class="q-table">
        <thead>
            <tr>
                <th>প্রশ্ন</th>
                <th>ধরন</th>
                <th>ক্যাটাগরি</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentQuestions as $q)
            <tr>
                <td style="max-width:280px;">{{ Str::limit(strip_tags($q->question), 70) }}</td>
                <td>
                    <span class="badge-t {{ $q->type === 'mcq' ? 'b-mcq' : ($q->type === 'short' ? 'b-short' : 'b-cq') }}">
                        {{ strtoupper($q->type) }}
                    </span>
                </td>
                <td style="color:rgba(255,255,255,.6); font-size:13px;">{{ $q->category->name ?? '—' }}</td>
                <td>
                    <span class="badge-t {{ $q->teacher_status === 'approved' ? 'b-approved' : ($q->teacher_status === 'rejected' ? 'b-rejected' : 'b-pending') }}">
                        {{ $q->teacher_status === 'approved' ? '✓ অনুমোদিত' : ($q->teacher_status === 'rejected' ? '✗ রিজেক্ট' : '⏳ পেন্ডিং') }}
                    </span>
                </td>
                <td style="color:rgba(255,255,255,.4); font-size:12px;">{{ $q->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align:center; padding:40px; color:rgba(255,255,255,.35);">
        <div style="font-size:48px; margin-bottom:12px;">📭</div>
        <p>এখনো কোনো প্রশ্ন যোগ করেননি।</p>
        <a href="{{ route('teacher.questions.create') }}" class="quick-btn btn-primary-t" style="display:inline-flex; margin-top:16px;">প্রথম প্রশ্ন যোগ করুন</a>
    </div>
    @endif
</div>
@endsection
