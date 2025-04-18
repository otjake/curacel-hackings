<?php

use App\Enums\FeatureCode;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Beneficiary\BeneficiaryController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Integration\IntegrationController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Payment\BulkPayoutController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\PaymentLinkController;
use App\Http\Controllers\QuickPayController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\DocumentContextController;
use App\Models\DocumentContext;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $documents = DocumentContext::whereNotNull('file_extract')->get();
        return inertia('DocumentContext/Upload', [
            'documents' => $documents
        ]);
});

// Authentication...
Route::middleware(['guest'])->group(function () {


});

// Route::get('/document-context', function () {
//     $documents = DocumentContext::whereNotNull('file_extract')->get();
//     return inertia('DocumentContext/Upload', [
//         'documents' => $documents
//     ]);
// })->name('document-context');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('api/document-context')->group(function () {
    Route::post('/upload', [DocumentContextController::class, 'upload'])->name('document-context.upload');
    Route::post('/process', [DocumentContextController::class, 'process'])->name('document-context.process');
});
