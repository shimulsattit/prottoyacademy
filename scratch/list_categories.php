<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$topCategories = Category::whereNull('parent_id')->get();
foreach($topCategories as $cat) {
    echo "ID: " . $cat->id . " | Name: " . $cat->name . " | Slug: " . $cat->slug . "\n";
}
