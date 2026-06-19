<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$slug = "পিএসসি-ও-অন্যান্য-পরীক্ষা";
$slug = "প্রাথমিক-সহকারী-শিক্ষক-নিয়োগ-পরীক্ষা";
$slug_decoded = urldecode($slug);

$slug = "প্রাথমিক-সহকারী-শিক্ষক-নিয়োগ-পরীক্ষা";
$slug_decoded = urldecode($slug);

$regex = '^(?!portal).*$';
$slugs = [
    'portal/dashboard',
    'portal/login',
    'portal',
    'পিএসসি-ও-অন্যান্য-পরীক্ষা',
    'job-solution',
    'portal-settings' // should match this!
];

foreach ($slugs as $s) {
    $matches = preg_match('#' . $regex . '#', $s);
    echo "Slug: '$s' | Matches: " . ($matches ? "YES" : "NO") . "\n";
}













