<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FolderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('photos.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('photos.index');
    })->name('dashboard');

    Route::get('/timeline', [PhotoController::class, 'timeline'])->name('photos.timeline');
    Route::resource('photos', PhotoController::class);
    Route::resource('folders', FolderController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Temporary route for data migration
Route::get('/dev/fix-locations', function () {
    \App\Models\Photo::query()->update(['location' => 'Kantor']);
    return 'All locations migrated to "Kantor". <a href="' . route('photos.index') . '">Back to Home</a>';
});
