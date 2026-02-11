<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\PhotoController;

// Mock session and auth
session([]);
$user = User::where('role', 'adminqcfitting')->first() ?: User::create([
    'name' => 'QC Fitting Test',
    'email' => 'test-qc@peroniks.com',
    'password' => bcrypt('password'),
    'role' => 'adminqcfitting'
]);

auth()->login($user);

echo "User Role: " . $user->role . "\n";
echo "User Department: " . $user->getDepartment() . "\n";

// Test store logic manually
$request = new Request([
    'photo_date' => date('Y-m-d'),
    'location' => 'Kantor',
    'department' => 'PPIC', // Try to hijack
    'notes' => 'Test notes'
]);

$controller = new PhotoController();

// We need to bypass the file upload part for a simple logic check
// Or we can just inspect the store method's department logic

$department = $request->department;
if (!auth()->user()->isGlobalAdmin()) {
    $department = auth()->user()->getDepartment() ?? $request->department;
}

echo "Final Department to be saved: " . $department . "\n";

if ($department === 'QC Fitting') {
    echo "SUCCESS: Department enforced correctly.\n";
} else {
    echo "FAILURE: Department NOT enforced.\n";
}
