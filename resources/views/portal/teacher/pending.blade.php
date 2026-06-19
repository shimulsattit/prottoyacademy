@extends('layouts.admin', ['title' => 'অনুমোদন অপেক্ষায় শিক্ষক'])
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">⏳ অনুমোদন অপেক্ষায়</h4>
            <p class="text-muted mb-0">নতুন শিক্ষক নিবন্ধন অনুমোদন করুন</p>
        </div>
        <a href="{{ route('portal.teachers.index') }}" class="btn btn-outline-secondary btn-sm">← সব শিক্ষক</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-body p-0">
            @forelse($teachers as $t)
            <div class="d-flex align-items-start gap-3 p-4 border-bottom">
                <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#E67E22,#F39C12);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:20px;flex-shrink:0;">
                    {{ strtoupper(substr($t->name,0,1)) }}
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-6">{{ $t->name }}</div>
                    <div class="text-muted small">{{ $t->email }} &bull; {{ $t->subject ?? 'বিষয় উল্লেখ নেই' }}</div>
                    <div class="text-muted small mt-1">নিবন্ধন: {{ $t->created_at->format('d M Y, h:i A') }}</div>
                    @if($t->bio)
                    <div class="mt-2 text-muted small fst-italic">"{{ Str::limit($t->bio, 100) }}"</div>
                    @endif
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <form action="{{ route('portal.teachers.approve', $t->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success rounded-pill px-4">✓ অনুমোদন দিন</button>
                    </form>
                    <form action="{{ route('portal.teachers.destroy', $t->id) }}" method="POST" onsubmit="return confirm('প্রত্যাখ্যান করবেন?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger rounded-pill px-3">✗ বাতিল</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div style="font-size:52px; margin-bottom:12px;">🎉</div>
                <h5 class="text-muted">কোনো অনুমোদন বাকি নেই!</h5>
                <p class="text-muted small">সব শিক্ষকের অ্যাকাউন্ট প্রক্রিয়া করা হয়েছে।</p>
            </div>
            @endforelse
        </div>
        @if($teachers->hasPages())
        <div class="card-footer bg-transparent">{{ $teachers->links() }}</div>
        @endif
    </div>
</div>
@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>toastr.success('{{ session('success') }}'));</script>
@endif
@endsection
