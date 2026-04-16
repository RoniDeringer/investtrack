<?php

use Illuminate\Support\Facades\Route;

// SPA entry (Vue). Any non-API route should return the same app shell.
Route::view('/{any?}', 'app')->where('any', '.*');
