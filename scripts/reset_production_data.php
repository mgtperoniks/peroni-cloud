<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Photo;
use App\Models\User;

$targetUserId = 3; // adminppic
$targetDept = 'PPIC';

echo "Starting data reset..." . PHP_EOL;

$count = Photo::count();
echo "Found $count photos to reset." . PHP_EOL;

Photo::query()->update([
    'user_id' => $targetUserId,
    'department' => $targetDept
]);

echo "SUCCESS: All photos reassigned to adminppic (ID: 3) and department: $targetDept." . PHP_EOL;
