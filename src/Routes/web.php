<?php

use Illuminate\Support\Facades\Route;
use SmartCms\Reviews\Routes\ReviewController;

Route::post('/api/product/reviews/add', [ReviewController::class, 'add'])->middleware('web');
