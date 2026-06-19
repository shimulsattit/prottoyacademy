<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$classId = 786;
$classSubIds = [$classId];
$subs = \App\Models\Category::where('parent_id', $classId)->pluck('id')->toArray();
$classSubIds = array_merge($classSubIds, $subs);

echo "=== Child Category IDs of 786 ===\n";
print_r($subs);

$count = \App\Models\Question::whereIn('category_id', $classSubIds)->where('status', 1)->count();
echo "Total questions count for 786: $count\n";

$jcs = \App\Models\JobCategory::whereIn('category_id', [$classId])->where('status', 1)->get();
echo "\n=== Subjects (Job Categories) under 786 ===\n";
foreach ($jcs as $jc) {
    $jcCount = \App\Models\Question::where('job_category_id', $jc->id)->where('status', 1)->count();
    echo "ID: {$jc->id}, Name: {$jc->name}, Count: {$jcCount}\n";
    
    $chapterIds = \App\Models\Question::where('job_category_id', $jc->id)
        ->where('status', 1)
        ->select('category_id')
        ->distinct()
        ->pluck('category_id')
        ->toArray();
        
    $chapters = \App\Models\Category::whereIn('id', $chapterIds)->where('status', 1)->get();
    foreach ($chapters as $ch) {
        $chCount = \App\Models\Question::where('category_id', $ch->id)
            ->where('job_category_id', $jc->id)
            ->where('status', 1)
            ->count();
        echo "   - Chapter ID: {$ch->id}, Name: {$ch->name}, Count: {$chCount}\n";
    }
}
