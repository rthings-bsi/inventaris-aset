<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetExportController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
	Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
	// Dashboard
	Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

	// Assets
	Route::middleware('permission:asset.create')->group(function () {
		Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create');
		Route::post('assets', [AssetController::class, 'store'])->name('assets.store');
		Route::get('assets/import/template', [\App\Http\Controllers\AssetExportController::class, 'templateExcel'])->name('assets.import.template');
		Route::post('assets/import/excel', [\App\Http\Controllers\AssetExportController::class, 'importExcel'])->name('assets.import.excel');
	});

	Route::delete('assets/bulk-delete', [AssetController::class, 'bulkDestroy'])
		->name('assets.bulkDestroy')
		->middleware('permission:asset.bulk-delete');

	Route::middleware('permission:asset.view')->group(function () {
		Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
		Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
	});

	Route::middleware('permission:asset.edit')->group(function () {
		Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
		Route::put('assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
	});

	Route::delete('assets/{asset}', [AssetController::class, 'destroy'])
		->name('assets.destroy')
		->middleware('permission:asset.delete');

	// Loans
	Route::middleware('permission:loan.view')->get('loans', [\App\Http\Controllers\AssetLoanController::class, 'index'])->name('loans.index');
	
	Route::middleware('permission:loan.create')->group(function () {
		Route::get('loans/create', [\App\Http\Controllers\AssetLoanController::class, 'create'])->name('loans.create');
		Route::post('loans', [\App\Http\Controllers\AssetLoanController::class, 'store'])->name('loans.store');
	});

	Route::post('loans/{loan}/return', [\App\Http\Controllers\AssetLoanController::class, 'return'])->name('loans.return');
	Route::post('loans/{loan}/cancel', [\App\Http\Controllers\AssetLoanController::class, 'cancel'])->name('loans.cancel');

	Route::middleware('permission:loan.manage')->group(function () {
		Route::post('loans/{loan}/approve', [\App\Http\Controllers\AssetLoanController::class, 'approve'])->name('loans.approve');
		Route::post('loans/{loan}/reject', [\App\Http\Controllers\AssetLoanController::class, 'reject'])->name('loans.reject');
		Route::post('assets/{asset}/pinjam', [\App\Http\Controllers\AssetLoanController::class, 'pinjam'])->name('assets.pinjam');
		Route::post('assets/{asset}/kembali', [\App\Http\Controllers\AssetLoanController::class, 'kembalikan'])->name('assets.kembali');
	});

	// Exports
	Route::middleware('permission:report.export')->group(function () {
		Route::get('assets/export/pdf', [AssetExportController::class, 'exportPdf'])->name('assets.export.pdf');
		Route::get('assets/export/excel', [AssetExportController::class, 'exportExcel'])->name('assets.export.excel');
	});

	// Resource routes untuk Master Data
	Route::resource('categories', App\Http\Controllers\CategoryController::class);
	Route::resource('locations', App\Http\Controllers\LocationController::class);
	
	// Admin specific routes
	Route::resource('users', App\Http\Controllers\UserController::class);
	Route::resource('roles', App\Http\Controllers\RoleController::class);
	Route::get('settings/roles', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('settings.roles.index');
	Route::post('settings/roles', [App\Http\Controllers\RolePermissionController::class, 'update'])->name('settings.roles.update');

	// Notifications
	Route::post('notifications/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');

	// Audits
	Route::get('audits', [App\Http\Controllers\AssetAuditController::class, 'index'])->name('audits.index');
	Route::post('audits', [App\Http\Controllers\AssetAuditController::class, 'store'])->name('audits.store');
	Route::get('audits/{audit}', [App\Http\Controllers\AssetAuditController::class, 'show'])->name('audits.show');
	Route::post('audits/{audit}/scan', [App\Http\Controllers\AssetAuditController::class, 'scan'])->name('audits.scan');
	Route::post('audits/{audit}/complete', [App\Http\Controllers\AssetAuditController::class, 'complete'])->name('audits.complete');
	Route::get('audits/{audit}/report', [App\Http\Controllers\AssetAuditController::class, 'report'])->name('audits.report');
	Route::get('audits/{audit}/export/excel', [App\Http\Controllers\AssetAuditController::class, 'exportExcel'])->name('audits.export.excel');
	Route::get('audits/{audit}/export/pdf', [App\Http\Controllers\AssetAuditController::class, 'exportPdf'])->name('audits.export.pdf');
	Route::delete('audits/{audit}', [App\Http\Controllers\AssetAuditController::class, 'destroy'])->name('audits.destroy');
});

// Route tambahan jika diperlukan
// Route::get('/reports', [AssetController::class, 'reports'])->name('assets.reports');
