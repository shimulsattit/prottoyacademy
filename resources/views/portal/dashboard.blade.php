@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Welcome {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name }}</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="index.html" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Dashboards</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            {{-- <a href="#" class="btn btn-sm fw-bold btn-secondary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Rollover</a>
            <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">Add Target</a> --}}
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content  flex-column-fluid ">
    <div id="kt_app_content_container" class="app-container  container-xxl ">
        <div class="row gy-5 gx-xl-10">
            <!-- Number of Active Question --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-success">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfActiveQuestions }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold text-white fs-6">
                                    Active Questions
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Number of Inactive Question --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-warning">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">
                                {{ $numberOfInactiveQuestions }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6">
                                    Inactive Questions
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Number of Passages --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-primary">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfPassage }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-white">
                                    Total Passage
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Number of Years --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-info">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfYears }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-white">
                                    Total Years
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Number of Categories --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-primary">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfCategories }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-white">
                                    Categories
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>
            
            <!-- Number of Job Categories --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-info">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfJobCategories }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-white">
                                    Job Categories
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Number of Exams --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-success">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfExams }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-white">
                                    Exams
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Number of Admins --> 
            <div class="col-md-3 mb-xl-10">
                <div class="card h-lg-100 bg-danger">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">         
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">
                                {{ $numberOfAdmins }}    
                            </span> 
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-white">
                                    Admin Users
                                </span>  
                            </div>       
                        </div>  
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-lg-100">
                    <div class="card-body">         
                        <div class="row">
                            <div class="col-md-8">
                                <h4>My Question Activity</h4>
                            </div>
                            <div class="col-md-4 text-end">
                                <select id="monthSelector" class="form-control mb-3">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $m == now()->format('m') ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <table class="table table-bordered" id="activityTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Number of Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-lg-100">
                    <div class="card-body">         
                        <div class="row">
                            <div class="col-md-8">
                                <h4>My Description Logs</h4>
                            </div>
                            <div class="col-md-4 text-end">
                                <select id="descriptionMonthSelector" class="form-control mb-3">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $m == now()->format('m') ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <table class="table table-bordered" id="descriptionActivityTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Number of Descriptions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Chart --> 
            <div class="col-md-12">
                <div class="card h-lg-100">
                    <div class="card-body">         
                        <div class="row">
                            <div class="col-md-8">
                                <h4>User Description Activity</h4>
                            </div>

                            <div class="col-md-4">
                                <select id="filterType" class="form-control mb-3">
                                    <option value="daily" selected>Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>

                        <canvas id="descriptionChart" height="100"></canvas>
                    </div>
                </div>
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
                        borderColor: `hsl(${index * 60}, 70%, 50%)`,
                        fill: false,
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
                        plugins: {
                            legend: { position: 'bottom' },
                            title: { display: true, text: 'Question Descriptions by User' }
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

        $(document).ready(function () {
            function fetchActivityData(month) {
                $.ajax({
                    url: "{{ route('portal.questions.activity') }}",
                    method: "GET",
                    data: { month: month },
                    success: function (data) {
                        let tbody = $('#activityTable tbody');
                        tbody.empty();

                        if (data.length === 0) {
                            tbody.append(`<tr><td colspan="2" class="text-center">No data available</td></tr>`);
                            return;
                        }

                        data.forEach(item => {
                            tbody.append(`
                                <tr>
                                    <td>${item.date}</td>
                                    <td>${item.total}</td>
                                </tr>
                            `);
                        });
                    },
                    error: function () {
                        $('#activityTable tbody').html(`<tr><td colspan="2" class="text-danger text-center">Error loading data</td></tr>`);
                    }
                });
            }

            // Initial load
            const currentMonth = $('#monthSelector').val();
            fetchActivityData(currentMonth);

            // On change
            $('#monthSelector').on('change', function () {
                let selectedMonth = $(this).val();
                fetchActivityData(selectedMonth);
            });
        });

        $(document).ready(function () {
            function fetchDescriptionLog(month) {
                $.ajax({
                    url: "{{ route('portal.question.description.logs.activity') }}",
                    method: "GET",
                    data: { month: month },
                    success: function (data) {
                        let tbody = $('#descriptionActivityTable tbody');
                        tbody.empty();

                        if (data.length === 0) {
                            tbody.append(`<tr><td colspan="2" class="text-center">No data available</td></tr>`);
                            return;
                        }

                        data.forEach(item => {
                            tbody.append(`
                                <tr>
                                    <td>${item.date}</td>
                                    <td>${item.total}</td>
                                </tr>
                            `);
                        });
                    },
                    error: function () {
                        $('#descriptionActivityTable tbody').html(`<tr><td colspan="2" class="text-danger text-center">Error loading data</td></tr>`);
                    }
                });
            }

            const currentMonth = $('#descriptionMonthSelector').val();
            fetchDescriptionLog(currentMonth);

            $('#descriptionMonthSelector').on('change', function () {
                let selectedMonth = $(this).val();
                fetchDescriptionLog(selectedMonth);
            });
        });
    </script>
@endpush
