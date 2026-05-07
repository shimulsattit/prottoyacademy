<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$targetList = ['Job Solution', 'Bank Recruitment', 'Admission', 'School & College'];
foreach($targetList as $name) {
    $cat = Category::where('name', 'LIKE', '%' . $name . '%')->first();
    if (!$cat && $name == 'Bank Recruitment') $cat = Category::where('name', 'LIKE', '%Bank%')->first();
    if ($cat) {
        echo "Category: " . $cat->name . " | Count: " . $cat->totalQuestionsCount() . "\n";
    } else {
        echo "Category: " . $name . " | Not Found\n";
    }
}
