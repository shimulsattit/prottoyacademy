<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$category = \App\Models\Category::find(786);
$method = new ReflectionMethod(\App\Http\Controllers\Web\WebsiteController::class, 'getAcademyStats');
$method->setAccessible(true);
$stats = $method->invoke(new \App\Http\Controllers\Web\WebsiteController(), $category);

echo "=== Stats for Category 786 ===\n";
print_r($stats);
