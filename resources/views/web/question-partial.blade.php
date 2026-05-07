@if($list->count() == 0)
    <p class="text-center fs-4 mt-5">কোনো প্রশ্ন পাওয়া যায়নি</p>
@endif

@foreach($list as $q)
<div class="question-card mt-4">

    <!-- Header -->
    <div class="question-header">
        <p class="question-text">
            <span>{{ $loop->iteration }}.</span>
            {!! $q->question !!}
        </p>
    </div>

    <!-- Options -->
    <div class="question-content">

        @php $opts = $q->options; @endphp

        <div class="options-row">
            <div class="option {{ $q->correct_answer == 'a' ? 'text-success' : '' }}">
                <span class="option-label">a.</span> {!! $opts->option_a !!}
            </div>

            <div class="option {{ $q->correct_answer == 'b' ? 'text-success' : '' }}">
                <span class="option-label">b.</span> {!! $opts->option_b !!}
            </div>
        </div>

        <div class="options-row">
            <div class="option mt-3 {{ $q->correct_answer == 'c' ? 'text-success' : '' }}">
                <span class="option-label">c.</span> {!! $opts->option_c !!}
            </div>

            <div class="option mt-3 {{ $q->correct_answer == 'd' ? 'text-success' : '' }}">
                <span class="option-label">d.</span> {!! $opts->option_d !!}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="card-footer-custom">
        <button
            class="footer-toggle bg-danger white collapsed"
            data-bs-toggle="collapse"
            data-bs-target="#desc-{{ $q->id }}">
            ▼ Description
        </button>

        <span>
            <i class="fas fa-video fs-7"></i>
            <a href="#">video</a>
        </span>

        <div class="collapse" id="desc-{{ $q->id }}">
            <div class="footer-content">
                {!! $q->content !!}
            </div>
        </div>
    </div>
</div>
@endforeach
