<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$jcs = App\Models\JobCategory::orderBy('id', 'desc')->take(10)->get();
foreach ($jcs as $jc) {
    echo "ID: {$jc->id} | Name: {$jc->name} | Created: {$jc->created_at} | Status: {$jc->status} | CatID: {$jc->category_id}\n";
}
