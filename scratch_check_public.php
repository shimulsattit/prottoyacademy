<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$publicPath = public_path();
echo "Public Path: {$publicPath}\n";
$files = scandir($publicPath);
foreach ($files as $file) {
    if (str_contains($file, '.xlsx')) {
        echo "Found Excel file: {$file} | Size: " . filesize($publicPath . '/' . $file) . " bytes\n";
    }
}
