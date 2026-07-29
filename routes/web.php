<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\SavingController as AdminSavingController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\SwfController as AdminSwfController;
use App\Http\Controllers\Admin\InvestmentController as AdminInvestmentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\GoogleSheetsController as AdminGoogleSheetsController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ShareController as AdminShareController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\LoanController as MemberLoanController;
use App\Http\Controllers\Member\SavingController as MemberSavingController;
use App\Http\Controllers\Member\DepositController as MemberDepositController;
use App\Http\Controllers\Member\SwfController as MemberSwfController;
use App\Http\Controllers\Member\InvestmentController as MemberInvestmentController;
use App\Http\Controllers\Member\StatementController as MemberStatementController;
use App\Http\Controllers\Member\NotificationController as MemberNotificationController;
use App\Http\Controllers\Member\SavingPlanController as MemberSavingPlanController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('member.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/members/template', [AdminMemberController::class, 'downloadTemplate'])->name('members.template');
    Route::get('/members/import/{jobId}/progress', [AdminMemberController::class, 'importProgress'])->name('members.import-progress');
    Route::get('/members/{memberNumber}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::get('/members/{encryptedMemberNumber}/loans', [AdminMemberController::class, 'loans'])->name('members.loans');
    Route::get('/members/{encryptedMemberNumber}/savings', [AdminMemberController::class, 'savings'])->name('members.savings');
    Route::post('/members/import', [AdminMemberController::class, 'import'])->name('members.import');

    Route::get('/loans/applications', [AdminLoanController::class, 'applications'])->name('loans.applications');
    Route::get('/loans', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/repayments', [AdminLoanController::class, 'repayments'])->name('loans.repayments');
    Route::get('/loans/create', [AdminLoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [AdminLoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{encryptedLoanNumber}', [AdminLoanController::class, 'show'])->name('loans.show');
    Route::get('/loans/{id}/edit', [AdminLoanController::class, 'edit'])->name('loans.edit');
    Route::put('/loans/{id}', [AdminLoanController::class, 'update'])->name('loans.update');
    Route::delete('/loans/{id}', [AdminLoanController::class, 'destroy'])->name('loans.destroy');
    Route::post('/loans/{id}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{id}/disburse', [AdminLoanController::class, 'disburse'])->name('loans.disburse');
    Route::post('/loans/import-loan-payments', [AdminLoanController::class, 'importLoanPayments'])->name('loans.import-loan-payments');
    Route::post('/loans/import-loans-information', [AdminLoanController::class, 'importLoansInformation'])->name('loans.import-loans-information');

    Route::get('/savings', [AdminSavingController::class, 'index'])->name('savings.index');
    Route::get('/savings/{encryptedMemberNumber}', [AdminSavingController::class, 'show'])->name('savings.show');

    Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/saving-plans', [App\Http\Controllers\Admin\SavingPlanController::class, 'index'])->name('saving-plans.index');
    Route::get('/saving-plans/create', [App\Http\Controllers\Admin\SavingPlanController::class, 'create'])->name('saving-plans.create');
    Route::post('/saving-plans', [App\Http\Controllers\Admin\SavingPlanController::class, 'store'])->name('saving-plans.store');
    Route::get('/saving-plans/{id}/edit', [App\Http\Controllers\Admin\SavingPlanController::class, 'edit'])->name('saving-plans.edit');
    Route::put('/saving-plans/{id}', [App\Http\Controllers\Admin\SavingPlanController::class, 'update'])->name('saving-plans.update');
    Route::delete('/saving-plans/{id}', [App\Http\Controllers\Admin\SavingPlanController::class, 'destroy'])->name('saving-plans.destroy');

    Route::get('/statements', [App\Http\Controllers\Admin\StatementController::class, 'index'])->name('statements.index');
    Route::get('/statements/{id}', [App\Http\Controllers\Admin\StatementController::class, 'show'])->name('statements.show');
    Route::get('/statements/{id}/download', [App\Http\Controllers\Admin\StatementController::class, 'download'])->name('statements.download');

    Route::get('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [App\Http\Controllers\Admin\TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}/edit', [App\Http\Controllers\Admin\TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/export', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('transactions.export');
    Route::post('/transactions/import', [App\Http\Controllers\Admin\TransactionController::class, 'import'])->name('transactions.import');

    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/{encryptedCertificateNumber}', [AdminDepositController::class, 'show'])->name('deposits.show');

    Route::get('/swf', [AdminSwfController::class, 'index'])->name('swf.index');
    Route::get('/swf/{encryptedMemberNumber}', [AdminSwfController::class, 'show'])->name('swf.show');

    Route::get('/investments', [AdminInvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/{encryptedMemberNumber}', [AdminInvestmentController::class, 'show'])->name('investments.show');

    Route::get('/investment-products', [App\Http\Controllers\Admin\InvestmentProductController::class, 'index'])->name('investment-products.index');
    Route::get('/investment-products/create', [App\Http\Controllers\Admin\InvestmentProductController::class, 'create'])->name('investment-products.create');
    Route::post('/investment-products', [App\Http\Controllers\Admin\InvestmentProductController::class, 'store'])->name('investment-products.store');
    Route::get('/investment-products/{id}/edit', [App\Http\Controllers\Admin\InvestmentProductController::class, 'edit'])->name('investment-products.edit');
    Route::put('/investment-products/{id}', [App\Http\Controllers\Admin\InvestmentProductController::class, 'update'])->name('investment-products.update');
    Route::delete('/investment-products/{id}', [App\Http\Controllers\Admin\InvestmentProductController::class, 'destroy'])->name('investment-products.destroy');

    Route::get('/shares', [AdminShareController::class, 'index'])->name('shares.index');
    Route::get('/shares/{encryptedMemberNumber}', [AdminShareController::class, 'show'])->name('shares.show');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{encryptedId}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{encryptedId}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{encryptedId}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{encryptedId}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/google-sheets', [AdminGoogleSheetsController::class, 'index'])->name('google-sheets.index');
    Route::post('/google-sheets/sync', [AdminGoogleSheetsController::class, 'sync'])->name('google-sheets.sync');
    Route::get('/google-sheets/status', [AdminGoogleSheetsController::class, 'status'])->name('google-sheets.status');
    Route::get('/google-sheets/logs', [AdminGoogleSheetsController::class, 'logs'])->name('google-sheets.logs');
    Route::get('/google-sheets/customers', [AdminGoogleSheetsController::class, 'customers'])->name('google-sheets.customers');
    Route::get('/google-sheets/customers/{customerId}', [AdminGoogleSheetsController::class, 'customer'])->name('google-sheets.customer');
    Route::get('/google-sheets/summary', [AdminGoogleSheetsController::class, 'summary'])->name('google-sheets.summary');
    Route::post('/google-sheets/manual-sync', [AdminGoogleSheetsController::class, 'manualSync'])->name('google-sheets.manual-sync');

    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [AdminRoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [AdminRoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [AdminRoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [AdminRoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [AdminPermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [AdminPermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [AdminPermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [AdminPermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [AdminPermissionController::class, 'destroy'])->name('permissions.destroy');

    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('member')->middleware(['auth', 'role:member', 'member.isolation'])->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/show', [MemberProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [MemberProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [MemberProfileController::class, 'update'])->name('profile.update');

    Route::get('/loans', [MemberLoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{encryptedLoanNumber}', [MemberLoanController::class, 'show'])->name('loans.show');

    Route::get('/savings', [MemberSavingController::class, 'index'])->name('savings.index');

    Route::get('/saving-plan', [MemberSavingPlanController::class, 'index'])->name('saving-plan.index');

    Route::get('/deposits', [MemberDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/{encryptedCertificateNumber}', [MemberDepositController::class, 'show'])->name('deposits.show');

    Route::get('/swf', [MemberSwfController::class, 'index'])->name('swf.index');

    Route::get('/investments', [MemberInvestmentController::class, 'index'])->name('investments.index');

    Route::get('/statements', [MemberStatementController::class, 'index'])->name('statements.index');
    Route::get('/statements/download/{type}', [MemberStatementController::class, 'download'])->name('statements.download');

    Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [MemberNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/read', [MemberNotificationController::class, 'markRead'])->name('notifications.read');
});
