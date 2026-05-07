<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$targets = [
    'ঢাকা বিশ্ববিদ্যালয়',
    'রাজশাহী বিশ্ববিদ্যালয়',
    'চট্টগ্রাম বিশ্ববিদ্যালয়',
    'জগন্নাথ বিশ্ববিদ্যালয়',
    'জাহাঙ্গীরনগর বিশ্ববিদ্যালয়',
    'খুলনা বিশ্ববিদ্যালয়',
    'বরিশাল বিশ্ববিদ্যালয়'
];

foreach($targets as $name) {
    $cat = Category::where('name', 'LIKE', '%' . $name . '%')->first();
    if ($cat) {
        echo "Name: " . $cat->name . " | ID: " . $cat->id . "\n";
    } else {
        echo "Name: " . $name . " | Not Found\n";
    }
}
