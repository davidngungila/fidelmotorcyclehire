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
    Route::get('/members/{encryptedMemberNumber}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::get('/members/{encryptedMemberNumber}/loans', [AdminMemberController::class, 'loans'])->name('members.loans');
    Route::get('/members/{encryptedMemberNumber}/savings', [AdminMemberController::class, 'savings'])->name('members.savings');
    Route::post('/members/import', [AdminMemberController::class, 'import'])->name('members.import');

    Route::get('/loans', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{encryptedLoanNumber}', [AdminLoanController::class, 'show'])->name('loans.show');

    Route::get('/savings', [AdminSavingController::class, 'index'])->name('savings.index');
    Route::get('/savings/{encryptedMemberNumber}', [AdminSavingController::class, 'show'])->name('savings.show');

    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/{certificateNumber}', [AdminDepositController::class, 'show'])->name('deposits.show');

    Route::get('/swf', [AdminSwfController::class, 'index'])->name('swf.index');
    Route::get('/swf/{memberNumber}', [AdminSwfController::class, 'show'])->name('swf.show');

    Route::get('/investments', [AdminInvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/{memberNumber}', [AdminInvestmentController::class, 'show'])->name('investments.show');

    Route::get('/shares', [AdminShareController::class, 'index'])->name('shares.index');
    Route::get('/shares/{memberNumber}', [AdminShareController::class, 'show'])->name('shares.show');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/google-sheets', [AdminGoogleSheetsController::class, 'index'])->name('google-sheets.index');
    Route::post('/google-sheets/sync', [AdminGoogleSheetsController::class, 'sync'])->name('google-sheets.sync');

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
    Route::get('/loans/{loanNumber}', [MemberLoanController::class, 'show'])->name('loans.show');

    Route::get('/savings', [MemberSavingController::class, 'index'])->name('savings.index');

    Route::get('/deposits', [MemberDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/{certificateNumber}', [MemberDepositController::class, 'show'])->name('deposits.show');

    Route::get('/swf', [MemberSwfController::class, 'index'])->name('swf.index');

    Route::get('/investments', [MemberInvestmentController::class, 'index'])->name('investments.index');

    Route::get('/statements', [MemberStatementController::class, 'index'])->name('statements.index');
    Route::get('/statements/download/{type}', [MemberStatementController::class, 'download'])->name('statements.download');

    Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [MemberNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/read', [MemberNotificationController::class, 'markRead'])->name('notifications.read');
});
