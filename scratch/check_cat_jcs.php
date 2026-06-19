<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slug = "পিএসসি-ও-অন্যান্য-পরীক্ষা";
$category = App\Models\Category::where('slug', $slug)->first();
if (!$category) {
    echo "Category not found!\n";
    exit;
}

$allJobCategories = App\Models\JobCategory::where('category_id', $category->id)->where('status', 1)->get();
echo "Total Job Categories: " . $allJobCategories->count() . "\n\n";

$sortedCategories = $allJobCategories->sortByDesc(function ($sExam) {
    if (preg_match('/\((\d{2}-\d{2}-\d{4})\)/', $sExam->name, $matches)) {
        $parts = explode('-', $matches[1]);
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
    }
    return '0000-00-00';
});

echo "Sorted list (Top 10):\n";
$i = 0;
foreach ($sortedCategories as $sExam) {
    if ($i++ >= 10) break;
    echo "ID: {$sExam->id} | Name: {$sExam->name} | Created: {$sExam->created_at}\n";
}
