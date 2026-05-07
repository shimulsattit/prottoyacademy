<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$settingsToUpdate = [
    'footer_menu_one_links',
    'footer_menu_two_links',
    'footer_menu_three_links',
    'header_menu_links'
];

foreach ($settingsToUpdate as $name) {
    $setting = Setting::where('name', $name)->first();
    if ($setting) {
        $links = json_decode($setting->value, true);
        $updated = false;
        foreach ($links as $key => $link) {
            if (strpos($link, 'https://prottoyacademy.com') !== false) {
                $links[$key] = str_replace('https://prottoyacademy.com', '', $link);
                $updated = true;
            }
        }
        if ($updated) {
            $setting->value = json_encode($links);
            $setting->save();
            echo "Updated links for $name\n";
        } else {
            echo "No updates needed for $name\n";
        }
    }
}

echo "Done.\n";
