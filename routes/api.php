<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleSheetsReceiverController;
use App\Http\Controllers\Admin\GoogleSheetsController;

Route::prefix('api/v1')->group(function () {
    // Google Sheets Receiver Endpoints (from Google Apps Script)
    Route::post('/sync/full-data', [GoogleSheetsReceiverController::class, 'syncFullData']);
    Route::post('/sync/customers', [GoogleSheetsReceiverController::class, 'syncCustomers']);
    Route::post('/sync/balances', [GoogleSheetsReceiverController::class, 'syncBalances']);
    Route::post('/sync/transactions', [GoogleSheetsReceiverController::class, 'syncTransactions']);
    Route::post('/sync/saving-plans', [GoogleSheetsReceiverController::class, 'syncSavingPlans']);
    
    // Admin API Endpoints
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/admin/sync/trigger', [GoogleSheetsController::class, 'sync']);
        Route::get('/admin/sync/status', [GoogleSheetsController::class, 'status']);
        Route::get('/admin/sync/logs', [GoogleSheetsController::class, 'logs']);
        Route::get('/admin/customers', [GoogleSheetsController::class, 'customers']);
        Route::get('/admin/customers/{customerId}', [GoogleSheetsController::class, 'customer']);
        Route::get('/admin/summary', [GoogleSheetsController::class, 'summary']);
    });
});
