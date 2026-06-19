@extends('layouts.teacher', ['title' => 'আমার প্রশ্নসমূহ'])

@push('style')
<style>
    .q-table { width: 100%; border-collapse: collapse; }
    .q-table th { font-size: 12px; font-weight: 700; color: rgba(255,255,255,.45); padding: 10px 14px; text-align: left; border-bottom: 1px solid var(--bdr); text-transform: uppercase; letter-spacing: .5px; }
    .q-table td { padding: 14px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,.04); vertical-align: middle; }
    .q-table tr:last-child td { border-bottom: none; }
    .q-table tr:hover td { background: rgba(255,255,255,.03); }
    .badge-t { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 100px; }
    .b-pending  { background: rgba(245,197,24,.15); color: #f5c518; }
    .b-approved { background: rgba(34,197,94,.15);  color: #22c55e; }
    .b-rejected { background: rgba(239,68,68,.15);  color: #ef4444; }
    .b-mcq   { background: rgba(0,180,216,.15); color: #00b4d8; }
    .b-short { background: rgba(167,139,250,.15); color: #a78bfa; }
    .b-cq    { background: rgba(255,107,53,.15);  color: #ff6b35; }
    .filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
    .ftab { padding: 7px 18px; border-radius: 100px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; border: 1px solid var(--bdr); color: rgba(255,255,255,.55); transition: all .2s; }
    .ftab.active, .ftab:hover { background: var(--tp-light); color: var(--tp); border-color: rgba(0,180,216,.35); }
    .empty-state { text-align: center; padding: 60px 20px; color: rgba(255,255,255,.35); }
    .empty-state .ico { font-size: 52px; margin-bottom: 14px; }
    .add-btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 22px; background: linear-gradient(135deg, #00b4d8, #0077b6); border-radius: 11px; color: #07091e; font-weight: 700; font-size: 14px; text-decoration: none; transition: transform .2s; }
    .add-btn:hover { transform: translateY(-2px); color: #07091e; }
</style>
@endpush

@section('content')
<div class="pg-head" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>আমার প্রশ্নসমূহ</h2>
        <p>আপনার যোগ করা সকল প্রশ্ন ও তাদের স্ট্যাটাস</p>
    </div>
    <a href="{{ route('teacher.questions.create') }}" class="add-btn">
        <i class="ri-add-circle-line"></i> নতুন প্রশ্ন যোগ
    </a>
</div>

<div class="t-card" style="padding:24px;">
    @if($questions->count() > 0)
    <table class="q-table">
        <thead>
            <tr>
                <th>#</th>
                <th>প্রশ্ন</th>
                <th>ধরন</th>
                <th>ক্যাটাগরি</th>
                <th>স্ট্যাটাস</th>
                <th>তারিখ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $i => $q)
            <tr>
                <td style="color:rgba(255,255,255,.35); font-size:13px;">{{ $questions->firstItem() + $i }}</td>
                <td style="max-width:320px;">
                    <div style="font-weight:600; line-height:1.5;">{{ Str::limit(strip_tags($q->question), 90) }}</div>
                    @if($q->teacher_status === 'rejected' && $q->teacher_rejection_reason)
                        <div style="font-size:12px; color:#ef4444; margin-top:4px;">❌ কারণ: {{ $q->teacher_rejection_reason }}</div>
                    @endif
                </td>
                <td>
                    <span class="badge-t {{ $q->type === 'mcq' ? 'b-mcq' : ($q->type === 'short' ? 'b-short' : 'b-cq') }}">
                        {{ strtoupper($q->type) }}
                    </span>
                </td>
                <td style="color:rgba(255,255,255,.55); font-size:13px;">{{ $q->category->name ?? '—' }}</td>
                <td>
                    <span class="badge-t {{ $q->teacher_status === 'approved' ? 'b-approved' : ($q->teacher_status === 'rejected' ? 'b-rejected' : 'b-pending') }}">
                        @if($q->teacher_status === 'approved') ✓ অনুমোদিত
                        @elseif($q->teacher_status === 'rejected') ✗ রিজেক্ট
                        @else ⏳ পেন্ডিং
                        @endif
                    </span>
                </td>
                <td style="color:rgba(255,255,255,.4); font-size:12px;">{{ $q->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px;">{{ $questions->links() }}</div>
    @else
    <div class="empty-state">
        <div class="ico">📭</div>
        <p style="font-size:15px; margin-bottom:16px;">এখনো কোনো প্রশ্ন যোগ করেননি।</p>
        <a href="{{ route('teacher.questions.create') }}" class="add-btn">প্রথম প্রশ্ন যোগ করুন</a>
    </div>
    @endif
</div>
@endsection
