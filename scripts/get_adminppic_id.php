<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User;
$user = User::where('email', 'adminppic@peroniks.com')->first();
if ($user) {
    echo "ID: " . $user->id . PHP_EOL;
} else {
    echo "NOT FOUND" . PHP_EOL;
}
