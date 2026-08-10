<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "--- ROLES ---" . PHP_EOL;
$roles = Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id} | Role Name: {$role->role_name}" . PHP_EOL;
}

echo PHP_EOL . "--- USERS ---" . PHP_EOL;
$users = User::with('role')->get();
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: " . ($user->role->role_name ?? 'None') . PHP_EOL;
}

// Ensure Creator user exists
$creatorRole = Role::where('role_name', 'creator')->first();
if ($creatorRole) {
    $creatorUser = User::where('email', 'creator@example.com')->first();
    if (!$creatorUser) {
        $creatorUser = User::create([
            'name' => 'Admin Creator',
            'email' => 'creator@example.com',
            'password' => Hash::make('password'),
            'role_id' => $creatorRole->id,
        ]);
        echo PHP_EOL . "Created creator user: creator@example.com with password 'password'" . PHP_EOL;
    }
}
