<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RolePermission;

$p = RolePermission::where('role', 'admin')->where('permission', 'like', 'asset.bu%')->first();
if ($p) {
    echo "Permission found: |" . $p->permission . "|\n";
    echo "Hex values: " . bin2hex($p->permission) . "\n";
} else {
    echo "Permission not found starting with asset.bu\n";
}

$all = RolePermission::where('role', 'admin')->get();
foreach ($all as $item) {
    echo "Admin Permission: |" . $item->permission . "|\n";
}
