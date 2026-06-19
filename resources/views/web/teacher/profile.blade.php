@extends('layouts.teacher', ['title' => 'প্রোফাইল'])

@push('style')
<style>
    .profile-grid { display: grid; grid-template-columns: 280px 1fr; gap: 24px; }
    .profile-card { background: var(--card); border: 1px solid var(--bdr); border-radius: 20px; padding: 28px 24px; text-align: center; }
    .p-avatar { width: 100px; height: 100px; border-radius: 50%; border: 3px solid rgba(0,180,216,.35); margin: 0 auto 14px; object-fit: cover; background: linear-gradient(135deg, #00b4d8, #0077b6); display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 900; color: #07091e; }
    .p-name  { font-size: 18px; font-weight: 800; }
    .p-sub   { font-size: 13px; color: rgba(255,255,255,.45); margin-top: 4px; }
    .p-badge { display: inline-block; margin-top: 12px; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 100px; }
    .b-active  { background: rgba(34,197,94,.15); color: #22c55e; }
    .b-pending { background: rgba(245,197,24,.15); color: #f5c518; }
    .b-blocked { background: rgba(239,68,68,.15); color: #ef4444; }
    .p-stat { margin-top: 20px; border-top: 1px solid var(--bdr); padding-top: 18px; }
    .p-stat-row { display: flex; justify-content: space-between; font-size: 13px; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.04); color: rgba(255,255,255,.55); }
    .p-stat-row span:last-child { color: #fff; font-weight: 700; }
    .form-card { background: var(--card); border: 1px solid var(--bdr); border-radius: 20px; padding: 28px 28px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.65); margin-bottom: 7px; }
    .form-ctrl { width: 100%; padding: 12px 14px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 11px; color: #fff; font-size: 14px; font-family: inherit; outline: none; transition: border-color .2s; }
    .form-ctrl:focus { border-color: rgba(0,180,216,.5); background: rgba(255,255,255,.09); }
    .form-ctrl::placeholder { color: rgba(255,255,255,.25); }
    textarea.form-ctrl { resize: vertical; min-height: 90px; }
    .fg { margin-bottom: 18px; }
    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .save-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: linear-gradient(135deg, #00b4d8, #0077b6); border: none; border-radius: 11px; color: #07091e; font-family: inherit; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 18px rgba(0,180,216,.3); transition: transform .2s; }
    .save-btn:hover { transform: translateY(-2px); }
    @media (max-width: 768px) { .profile-grid { grid-template-columns: 1fr; } .row-2 { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="pg-head">
    <h2>প্রোফাইল</h2>
    <p>আপনার ব্যক্তিগত তথ্য আপডেট করুন</p>
</div>

<div class="profile-grid">
    {{-- Left: Profile Card --}}
    <div>
        <div class="profile-card">
            @if($teacher->avatar)
                <img src="{{ asset($teacher->avatar) }}" class="p-avatar" alt="{{ $teacher->name }}">
            @else
                <div class="p-avatar">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
            @endif
            <div class="p-name">{{ $teacher->name }}</div>
            <div class="p-sub">{{ $teacher->subject ?? 'বিষয় নির্ধারিত হয়নি' }}</div>
            <span class="p-badge {{ $teacher->status === 'active' ? 'b-active' : ($teacher->status === 'blocked' ? 'b-blocked' : 'b-pending') }}">
                {{ $teacher->status === 'active' ? '✓ সক্রিয়' : ($teacher->status === 'blocked' ? '✗ ব্লক' : '⏳ অনুমোদন অপেক্ষায়') }}
            </span>
            <div class="p-stat">
                <div class="p-stat-row"><span>মোট প্রশ্ন</span><span>{{ $teacher->questions()->count() }}</span></div>
                <div class="p-stat-row"><span>অনুমোদিত</span><span style="color:#22c55e;">{{ $teacher->approvedQuestions()->count() }}</span></div>
                <div class="p-stat-row"><span>পেন্ডিং</span><span style="color:#f5c518;">{{ $teacher->pendingQuestions()->count() }}</span></div>
                <div class="p-stat-row" style="border:0;"><span>যোগদান</span><span>{{ $teacher->created_at->format('d M Y') }}</span></div>
            </div>
        </div>
    </div>

    {{-- Right: Edit Form --}}
    <div class="form-card">
        <h6 style="font-weight:800; font-size:15px; margin-bottom:22px; color:rgba(255,255,255,.8);">তথ্য সম্পাদনা</h6>
        <form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="row-2">
                <div class="fg">
                    <label class="form-label">পূর্ণ নাম *</label>
                    <input type="text" name="name" class="form-ctrl" value="{{ old('name', $teacher->name) }}" required>
                </div>
                <div class="fg">
                    <label class="form-label">বিষয় (Subject)</label>
                    <input type="text" name="subject" class="form-ctrl" placeholder="যেমন: গণিত, বাংলা..." value="{{ old('subject', $teacher->subject) }}">
                </div>
            </div>
            <div class="row-2">
                <div class="fg">
                    <label class="form-label">ফোন নম্বর</label>
                    <input type="text" name="phone" class="form-ctrl" placeholder="01XXXXXXXXX" value="{{ old('phone', $teacher->phone) }}">
                </div>
                <div class="fg">
                    <label class="form-label">প্রোফাইল ছবি</label>
                    <input type="file" name="avatar" class="form-ctrl" accept="image/*" style="padding:8px 14px;">
                </div>
            </div>
            <div class="fg">
                <label class="form-label">বায়ো / পরিচিতি</label>
                <textarea name="bio" class="form-ctrl" placeholder="আপনার সম্পর্কে সংক্ষেপে লিখুন...">{{ old('bio', $teacher->bio) }}</textarea>
            </div>
            <button type="submit" class="save-btn">
                <i class="ri-save-line"></i> পরিবর্তন সংরক্ষণ করুন
            </button>
        </form>
    </div>
</div>
@endsection
