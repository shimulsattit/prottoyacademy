<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$settings = Setting::all();
$output = [];
foreach ($settings as $setting) {
    $output[$setting->name] = $setting->value;
}

file_put_contents('all_settings.json', json_encode($output, JSON_PRETTY_PRINT));
echo "Settings saved to all_settings.json\n";
