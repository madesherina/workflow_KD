<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = Role::where('role_name', 'Verifier')->first();

if (!$role) {
    die("Role Verifier not found\n");
}

$user = User::where('email', 'verifier@example.com')->first();
if ($user) {
    $user->delete();
}

User::create([
    'name' => 'Verifier Test',
    'email' => 'verifier@example.com',
    'password' => Hash::make('password123'),
    'role_id' => $role->id,
]);

echo "Verifier account created successfully.\n";
