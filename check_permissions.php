<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RolePermission;
use App\Models\User;

$user = User::where('email', 'admin@aset.local')->first();
echo "User Role: " . ($user ? $user->role : "Not found") . "\n";

$permissions = RolePermission::all();
echo "Current Permissions:\n";
foreach ($permissions as $p) {
    echo "- Role: {$p->role}, Permission: {$p->permission}\n";
}

if ($user && $user->hasPermission('asset.bulk-delete')) {
    echo "Admin HAS asset.bulk-delete permission.\n";
} else {
    echo "Admin DOES NOT have asset.bulk-delete permission.\n";
}
