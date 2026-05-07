<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$header_links = Setting::where('name', 'header_menu_links')->first();
if ($header_links) {
    echo "Header Menu Links:\n";
    print_r(json_decode($header_links->value, true));
} else {
    echo "No header_menu_links found.\n";
}

$footer_links = Setting::where('name', 'footer_menu_links')->first();
if ($footer_links) {
    echo "\nFooter Menu Links:\n";
    print_r(json_decode($footer_links->value, true));
}
