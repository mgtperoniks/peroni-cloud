<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Photo;
$stats = Photo::with('user')->get()->groupBy('department');
foreach ($stats as $dept => $photos) {
    echo "Department: $dept\n";
    foreach ($photos->groupBy('user.email') as $email => $p) {
        echo "  - $email: " . $p->count() . " photos\n";
    }
}
