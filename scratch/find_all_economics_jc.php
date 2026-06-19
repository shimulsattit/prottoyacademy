<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$jcs = \App\Models\JobCategory::all();
echo "=== All Job Categories in DB: " . count($jcs) . " ===\n";
foreach ($jcs as $jc) {
    if (str_contains($jc->name, 'অর্থনীতি') || $jc->name === 'অর্থনীতি') {
        $count = \App\Models\Question::where('job_category_id', $jc->id)->count();
        $cqCount = \App\Models\Question::where('job_category_id', $jc->id)->where('question_type', 'cq')->count();
        echo "ID: {$jc->id}, Name: '{$jc->name}', Category ID: {$jc->category_id}, Count: {$count}, CQ Count: {$cqCount}\n";
    }
}
