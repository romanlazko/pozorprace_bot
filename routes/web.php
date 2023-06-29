<?php

use App\Bots\pozorprace_bot\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'telegram:pozorprace_bot'])->prefix('/pozorprace_bot')->name('pozorprace_bot.')->group(function () {
    Route::resource('/announcement', AnnouncementController::class);
});
