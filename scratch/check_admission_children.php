<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$admission = Category::where('name', 'LIKE', '%Admission%')->whereNull('parent_id')->first();
if ($admission) {
    $children = Category::where('parent_id', $admission->id)->get();
    echo "Admission ID: " . $admission->id . " | Total Children: " . $children->count() . "\n";
    foreach($children as $child) {
        echo "ID: " . $child->id . " | Name: " . $child->name . " | Status: " . $child->status . "\n";
    }
} else {
    echo "Admission category not found.\n";
}
