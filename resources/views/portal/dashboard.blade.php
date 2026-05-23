@extends('layouts.portal-premium', ['title' => 'ড্যাশবোর্ড'])

@push('style')
<style>
    /* STAT CARDS */
    .stats-grid { display:grid; grid-template-columns: repeat(4,1fr); gap:18px; margin-bottom:24px; }
    .stat-card {
        background:var(--card-bg); border-radius:var(--radius); padding:22px;
        box-shadow:var(--shadow); position:relative; overflow:hidden;
        transition: transform 0.2s, box-shadow 0.2s; cursor:pointer;
    }
    .stat-card:hover { transform:translateY(-3px); box-shadow:var(--shadow-hover); }
    .stat-card::before {
        content:''; position:absolute; top:0; left:0; right:0; height:4px;
    }
    .stat-card.blue::before { background: linear-gradient(90deg,#2E86C1,#AED6F1); }
    .stat-card.green::before { background: linear-gradient(90deg,#27AE60,#82E0AA); }
    .stat-card.orange::before { background: linear-gradient(90deg,#E67E22,#FAD7A0); }
    .stat-card.purple::before { background: linear-gradient(90deg,#8E44AD,#D2B4DE); }

    .stat-icon {
        width:52px; height:52px; border-radius:14px;
        display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px;
    }
    .stat-card.blue .stat-icon { background:#EBF5FB; color:#2E86C1; }
    .stat-card.green .stat-icon { background:#EAFAF1; color:#27AE60; }
    .stat-card.orange .stat-icon { background:#FEF9E7; color:#E67E22; }
    .stat-card.purple .stat-icon { background:#F5EEF8; color:#8E44AD; }

    .stat-value { font-size:32px; font-weight:700; line-height:1; }
    .stat-card.blue .stat-value { color:#1B4F72; }
    .stat-card.green .stat-value { color:#1E8449; }
    .stat-card.orange .stat-value { color:#A04000; }
    .stat-card.purple .stat-value { color:#6C3483; }

    .stat-label { font-size:13px; color:var(--text-muted); margin-top:6px; font-weight:500; }
    .stat-bg-icon {
        position:absolute; bottom:-10px; right:-10px; font-size:80px; opacity:0.06;
    }

    /* CARD GENERIC */
    .card {
        background:var(--card-bg); border-radius:var(--radius);
        box-shadow:var(--shadow); overflow:hidden; margin-bottom: 24px;
    }
    .card-header {
        padding:18px 22px 14px; display:flex; align-items:center; justify-content:space-between;
        border-bottom:1px solid var(--border);
    }
    .card-title { font-size:15px; font-weight:700; color:var(--text-main); }
    .card-body { padding:18px 22px; }
    
    .chart-container { position:relative; height:300px; }

    @media(max-width:1200px) {
        .stats-grid { grid-template-columns:repeat(2,1fr); }
    }
    @media(max-width:768px) {
        .stats-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h1 class="h3 fw-bold">📊 ড্যাশবোর্ড</h1>
    <p class="text-muted">প্রত্যয় একাডেমি অ্যাডমিন প্যানেলে আপনাকে স্বাগতম।</p>
</div>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
        <div class="stat-value">{{ number_format($numberOfActiveQuestions) }}</div>
        <div class="stat-label">সক্রিয় প্রশ্ন</div>
        <i class="fas fa-question-circle stat-bg-icon"></i>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-value">{{ number_format($numberOfExams) }}</div>
        <div class="stat-label">মোট পরীক্ষা</div>
        <i class="fas fa-file-alt stat-bg-icon"></i>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon"><i class="fas fa-list-ul"></i></div>
        <div class="stat-value">{{ number_format($numberOfCategories) }}</div>
        <div class="stat-label">ক্যাটাগরি</div>
        <i class="fas fa-list-ul stat-bg-icon"></i>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
        <div class="stat-value">{{ number_format($numberOfAdmins) }}</div>
        <div class="stat-label">অ্যাডমিন ইউজার</div>
        <i class="fas fa-user-shield stat-bg-icon"></i>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <a href="{{ route('portal.question.create') }}" class="btn btn-primary w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700;">
            <i class="fas fa-plus-circle me-2"></i> নতুন প্রশ্ন
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('portal.exam.create') }}" class="btn btn-success w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700;">
            <i class="fas fa-file-signature me-2"></i> পরীক্ষা তৈরি
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('portal.category.create') }}" class="btn btn-warning w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700; color: #fff;">
            <i class="fas fa-folder-plus me-2"></i> ক্যাটাগরি যোগ
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('portal.import-question') }}" class="btn btn-info w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700; color: #fff;">
            <i class="fas fa-upload me-2"></i> ইমপোর্ট করুন
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title">📈 প্রশ্ন সংযোজন অ্যাক্টিভিটি</div>
                <select id="filterType" class="form-select form-select-sm" style="width: 120px;">
                    <option value="daily" selected>Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="descriptionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">📋 অন্যান্য তথ্য</div>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light">
                        জব ক্যাটাগরি
                        <span class="badge bg-primary rounded-pill">{{ $numberOfJobCategories }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light">
                        মোট বছর
                        <span class="badge bg-info rounded-pill">{{ $numberOfYears }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light">
                        প্যাসেজ সংখ্যা
                        <span class="badge bg-success rounded-pill">{{ $numberOfPassage }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light">
                        নিষ্ক্রিয় প্রশ্ন
                        <span class="badge bg-danger rounded-pill">{{ $numberOfInactiveQuestions }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chart;
    let ctx = document.getElementById('descriptionChart').getContext('2d');

    function loadChart(type = 'daily') {
        fetch("{{ route('portal.dashboard.logs.data') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type })
        })
        .then(res => res.json())
        .then(data => {
            const labelsSet = new Set();
            const datasets = [];

            Object.entries(data).forEach(([user, entries], index) => {
                const dates = Object.keys(entries);
                dates.forEach(date => labelsSet.add(date));

                datasets.push({
                    label: user,
                    data: Object.values(entries),
                    borderColor: `hsl(${index * 137.5}, 70%, 50%)`,
                    backgroundColor: `hsl(${index * 137.5}, 70%, 50%, 0.1)`,
                    fill: true,
                    tension: 0.4
                });
            });

            const labels = Array.from(labelsSet).sort();

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    }

    document.getElementById('filterType').addEventListener('change', function () {
        loadChart(this.value);
    });

    // Initial Load
    loadChart();
</script>
@endpush
