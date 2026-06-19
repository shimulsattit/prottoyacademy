<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$jc = App\Models\JobCategory::where('name', 'like', '%৬ষ্ঠ%বেসরকারি%প্রভাষক%')->first();
if (!$jc) {
    echo "Job Category Not Found\n";
    exit;
}

$sidebarExams = App\Models\JobCategory::where('category_id', $jc->category_id)
    ->where('status', 1)
    ->get();

$sortedExams = $sidebarExams->sortByDesc(function ($sExam) {
    if (preg_match('/\((\d{2}-\d{2}-\d{4})\)/', $sExam->name, $matches)) {
        $parts = explode('-', $matches[1]);
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
    }
    return '0000-00-00';
});

foreach ($sortedExams as $sExam) {
    echo "ID: " . $sExam->id . " | Name: " . $sExam->name . "\n";
}
