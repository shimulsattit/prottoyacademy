@extends('layouts.admin', ['title' => 'PDF → AI প্রশ্ন জেনারেটর'])

@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Toolbar --}}
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        🤖 PDF → AI প্রশ্ন জেনারেটর
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">PDF প্রশ্ন জেনারেটর</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.pdf.create') }}" class="btn btn-sm fw-bold btn-primary">
                        <i class="bi bi-plus-lg"></i> নতুন PDF আপলোড
                    </a>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Stats Cards --}}
                <div class="row g-4 mb-6">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <div class="fs-2 fw-bold text-primary">{{ $pdfs->total() }}</div>
                                <div class="text-muted small">মোট PDF</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <div class="fs-2 fw-bold text-success">{{ \App\Models\PdfUpload::where('status','done')->count() }}</div>
                                <div class="text-muted small">সম্পন্ন</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <div class="fs-2 fw-bold text-info">{{ \App\Models\PdfUpload::sum('questions_generated') }}</div>
                                <div class="text-muted small">মোট প্রশ্ন তৈরি</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <div class="fs-2 fw-bold text-warning">{{ \App\Models\PdfUpload::sum('questions_saved') }}</div>
                                <div class="text-muted small">প্রশ্ন সেভ হয়েছে</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card shadow-sm">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-5">আপলোড করা PDF সমূহ</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>শিরোনাম</th>
                                        <th>ক্যাটাগরি</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>প্রশ্ন তৈরি</th>
                                        <th>সেভ হয়েছে</th>
                                        <th>তারিখ</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pdfs as $pdf)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $pdf->title }}</div>
                                                <div class="text-muted small">{{ $pdf->original_name }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $pdf->category->name ?? '—' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $pdf->status_color }}">{{ $pdf->status_label }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-info">{{ $pdf->questions_generated }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ $pdf->questions_saved }}</span>
                                            </td>
                                            <td class="text-muted small">{{ $pdf->created_at->format('d M, Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.pdf.show', $pdf->id) }}" class="btn btn-sm btn-light-primary me-1" title="বিস্তারিত">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($pdf->generated_questions)
                                                    <a href="{{ route('admin.pdf.preview', $pdf->id) }}" class="btn btn-sm btn-light-success me-1" title="Preview">
                                                        <i class="bi bi-list-check"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('admin.pdf.destroy', $pdf->id) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('মুছে ফেলবেন?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-light-danger" title="মুছুন">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="bi bi-file-earmark-pdf fs-1 d-block mb-2"></i>
                                                কোনো PDF আপলোড করা হয়নি।
                                                <a href="{{ route('admin.pdf.create') }}">এখনই করুন</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $pdfs->links() }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
