<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$tags = ['শিক্ষা অফিসার'];
foreach($tags as $tag) {
    $cat = Category::where('name', 'LIKE', '%' . $tag . '%')->first();
    if ($cat) {
        echo "Tag: " . $tag . " | Slug: " . $cat->slug . "\n";
    } else {
        echo "Tag: " . $tag . " | Not Found\n";
    }
}
