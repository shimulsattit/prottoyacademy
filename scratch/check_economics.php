<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$subjectQuestions = \App\Models\Question::where('job_category_id', 789) // Wait, let's look up Job Category ID for Economics
    ->get();

echo "=== Questions for Subject ID 789 ===\n";
echo "Count: " . count($subjectQuestions) . "\n";
foreach ($subjectQuestions as $q) {
    echo "ID: {$q->id}, Type: {$q->question_type}, Question: " . strip_tags($q->question) . ", Passage ID: {$q->passage_id}, Created At: {$q->created_at}\n";
}

// Let's also check if there are other job categories with name like 'অর্থনীতি'
$jcs = \App\Models\JobCategory::where('name', 'like', '%অর্থনীতি%')->get();
echo "\n=== Job Categories like 'অর্থনীতি' ===\n";
foreach ($jcs as $jc) {
    echo "ID: {$jc->id}, Name: {$jc->name}, Category ID: {$jc->category_id}\n";
    $count = \App\Models\Question::where('job_category_id', $jc->id)->count();
    echo " - Question Count: {$count}\n";
    $cqCount = \App\Models\Question::where('job_category_id', $jc->id)->where('question_type', 'cq')->count();
    echo " - CQ Question Count: {$cqCount}\n";
}
