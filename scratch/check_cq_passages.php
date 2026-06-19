<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cqQuestions = \App\Models\Question::where('question_type', 'cq')
    ->orderBy('id', 'desc')
    ->take(15)
    ->get();

echo "=== Recent 15 CQ Questions ===\n";
foreach ($cqQuestions as $q) {
    echo "ID: {$q->id}, Question: " . strip_tags($q->question) . ", Passage ID: {$q->passage_id}, Job Category: " . ($q->job_category?->name ?? 'N/A') . ", Category: " . ($q->category?->name ?? 'N/A') . ", Created At: {$q->created_at}\n";
}

$uniquePassages = \App\Models\Question::where('question_type', 'cq')
    ->where('created_at', '>=', '2026-06-11 00:00:00')
    ->distinct()
    ->pluck('passage_id')
    ->toArray();

echo "\n=== Unique CQ Passages created today: " . count($uniquePassages) . " ===\n";
foreach ($uniquePassages as $pid) {
    $p = \App\Models\Passage::find($pid);
    if ($p) {
        $count = \App\Models\Question::where('passage_id', $pid)->count();
        echo "Passage ID: {$pid}, Name: " . strip_tags($p->name) . ", Sub-questions count: {$count}, Created At: {$p->created_at}\n";
    }
}
