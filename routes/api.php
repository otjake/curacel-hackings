<?php

use App\Http\Controllers\DocumentContextController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public document context routes
Route::prefix('document-context')->as('document-context.')->group(function () {
    Route::post('/upload', [DocumentContextController::class, 'upload'])->name('upload');
    Route::post('/process', [DocumentContextController::class, 'process'])->name('process');
    Route::post('/extract-content', [DocumentContextController::class, 'extractContent'])->name('extract-content');
});

Route::get('/document-context', [DocumentContextController::class, 'index'])->name('document-context.index');
