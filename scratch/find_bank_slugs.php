<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$tags = ['Sonali', 'Janata', 'Rupali', 'Joint Recruitment', 'Polli Sonchoy', 'Modhumoti'];
foreach($tags as $tag) {
    $cat = Category::where('name', 'LIKE', '%' . $tag . '%')->first();
    if ($cat) {
        echo "Tag: " . $tag . " | Slug: " . $cat->slug . " | Name: " . $cat->name . "\n";
    } else {
        echo "Tag: " . $tag . " | Not Found\n";
    }
}
