@extends('layouts.admin', ['title' => 'শিক্ষক ব্যবস্থাপনা'])

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">👨‍🏫 শিক্ষক ব্যবস্থাপনা</h4>
            <p class="text-muted mb-0">সকল নিবন্ধিত শিক্ষকের তালিকা ও ব্যবস্থাপনা</p>
        </div>
        <a href="{{ route('portal.teachers.pending') }}" class="btn btn-warning btn-sm">
            ⏳ অনুমোদন অপেক্ষায় ({{ \App\Models\Teacher::where('status','pending')->count() }})
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; background: linear-gradient(135deg,#1B4F72,#2E86C1);">
                <div class="card-body text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-3 fw-bold">{{ $totalTeachers }}</div><div class="small opacity-75">মোট শিক্ষক</div></div>
                        <div style="font-size:36px; opacity:.3;">👨‍🏫</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; background: linear-gradient(135deg,#1E8449,#27AE60);">
                <div class="card-body text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-3 fw-bold">{{ $activeTeachers }}</div><div class="small opacity-75">সক্রিয়</div></div>
                        <div style="font-size:36px; opacity:.3;">✅</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; background: linear-gradient(135deg,#E67E22,#F39C12);">
                <div class="card-body text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-3 fw-bold">{{ $pendingTeachers }}</div><div class="small opacity-75">অনুমোদন অপেক্ষায়</div></div>
                        <div style="font-size:36px; opacity:.3;">⏳</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; background: linear-gradient(135deg,#E74C3C,#C0392B);">
                <div class="card-body text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><div class="fs-3 fw-bold">{{ $blockedTeachers }}</div><div class="small opacity-75">ব্লকড</div></div>
                        <div style="font-size:36px; opacity:.3;">🚫</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold">#</th>
                            <th class="px-4 py-3 text-muted small fw-bold">নাম</th>
                            <th class="px-4 py-3 text-muted small fw-bold">ইমেইল</th>
                            <th class="px-4 py-3 text-muted small fw-bold">বিষয়</th>
                            <th class="px-4 py-3 text-muted small fw-bold">স্ট্যাটাস</th>
                            <th class="px-4 py-3 text-muted small fw-bold">নিবন্ধন</th>
                            <th class="px-4 py-3 text-muted small fw-bold">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $i => $t)
                        <tr>
                            <td class="px-4 py-3 text-muted small">{{ $teachers->firstItem() + $i }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1B4F72,#2E86C1);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:14px;flex-shrink:0;">
                                        {{ strtoupper(substr($t->name,0,1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $t->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $t->email }}</td>
                            <td class="px-4 py-3">{{ $t->subject ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($t->status === 'active')
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">✓ সক্রিয়</span>
                                @elseif($t->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">⏳ পেন্ডিং</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">🚫 ব্লক</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-muted small">{{ $t->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex gap-1 flex-wrap">
                                    @if($t->status === 'pending')
                                    <form action="{{ route('portal.teachers.approve', $t->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm rounded-pill px-3">✓ অনুমোদন</button>
                                    </form>
                                    @endif
                                    @if($t->status === 'active')
                                    <form action="{{ route('portal.teachers.block', $t->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-warning btn-sm rounded-pill px-3">🚫 ব্লক</button>
                                    </form>
                                    @endif
                                    @if($t->status === 'blocked')
                                    <form action="{{ route('portal.teachers.unblock', $t->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-primary btn-sm rounded-pill px-3">🔓 আনব্লক</button>
                                    </form>
                                    @endif
                                    <form action="{{ route('portal.teachers.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('শিক্ষক মুছে দিতে চান?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm rounded-pill px-3">🗑</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">কোনো শিক্ষক পাওয়া যায়নি।</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($teachers->hasPages())
        <div class="card-footer bg-transparent border-0 px-4 py-3">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>toastr.success('{{ session('success') }}'));</script>
@endif
@endsection
