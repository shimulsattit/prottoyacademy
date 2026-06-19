<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/পিএসসি-ও-অন্যান্য-পরীক্ষা', 'GET');
$response = $kernel->handle($request);

$content = $response->getContent();
echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Contains 'Oops... Page Not Found!': " . (str_contains($content, 'Oops... Page Not Found!') ? "YES" : "NO") . "\n";
echo "Contains '404': " . (str_contains($content, '404') ? "YES" : "NO") . "\n";
echo "Contains 'errors.404': " . (str_contains($content, 'errors.404') ? "YES" : "NO") . "\n";
echo "Contains 'পিএসসি ও অন্যান্য পরীক্ষা': " . (str_contains($content, 'পিএসসি ও অন্যান্য পরীক্ষা') ? "YES" : "NO") . "\n";

// Write the full response to a scratch file so we can view it
file_put_contents('scratch_response.html', $content);
echo "Written full response to scratch_response.html\n";
