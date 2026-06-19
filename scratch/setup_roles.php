<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use App\Models\Admin;

try {
    $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);

    $admin = Admin::first();
    if ($admin) {
        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
            echo "Assigned super-admin to {$admin->email}\n";
        } else {
            echo "Admin {$admin->email} already has super-admin role\n";
        }
    } else {
        echo "No admin found in DB.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
