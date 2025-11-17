<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BusinessSettingsController;
use App\Http\Controllers\AccountController;

// Make login the landing page
Route::get('/', [AuthController::class, 'showLogin'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/pending', function () {
        return view('pending');
    })->name('pending');
    
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Sidebar pages
    Route::get('/business-settings', [BusinessSettingsController::class, 'index'])->name('business.settings');
    Route::post('/business-settings', [BusinessSettingsController::class, 'save'])->name('business.settings.save');
    Route::view('/capitalization', 'pages.capitalization')->name('capitalization');
    Route::view('/chart-of-accounts', 'pages.chart-of-accounts')->name('chart.of.accounts');

    // Chart of Accounts JSON/API routes
    Route::get('/accounts/index.json', [AccountController::class, 'indexJson'])->name('accounts.index.json');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::post('/accounts/bulk-destroy', [AccountController::class, 'bulkDestroy'])->name('accounts.bulkDestroy');
    Route::get('/accounts/export', [AccountController::class, 'export'])->name('accounts.export');

    Route::get('/revenue-logsheet', [App\Http\Controllers\RevenueToolkitController::class, 'index'])->name('revenue.logsheet');
    Route::post('/revenue-toolkit', [App\Http\Controllers\RevenueToolkitController::class, 'store'])->name('revenue-toolkit.store');
    Route::put('/revenue-toolkit/{id}', [App\Http\Controllers\RevenueToolkitController::class, 'update'])->name('revenue-toolkit.update');
    Route::get('/expense-logsheet', [App\Http\Controllers\ExpenseToolkitController::class, 'index'])->name('expense.logsheet');
    Route::post('/expense-toolkit', [App\Http\Controllers\ExpenseToolkitController::class, 'store'])->name('expense-toolkit.store');
    Route::put('/expense-toolkit/{id}', [App\Http\Controllers\ExpenseToolkitController::class, 'update'])->name('expense-toolkit.update');
    Route::get('/journal-logsheet', [App\Http\Controllers\JournalToolkitController::class, 'index'])->name('journal.logsheet');
    Route::post('/journal-toolkit', [App\Http\Controllers\JournalToolkitController::class, 'store'])->name('journal-toolkit.store');
    Route::put('/journal-toolkit/{id}', [App\Http\Controllers\JournalToolkitController::class, 'update'])->name('journal-toolkit.update');
    Route::get('/inventory-logsheet', [App\Http\Controllers\InventoryToolkitController::class, 'index'])->name('inventory.logsheet');
    Route::post('/inventory-toolkit', [App\Http\Controllers\InventoryToolkitController::class, 'store'])->name('inventory-toolkit.store');
    Route::put('/inventory-toolkit/{id}', [App\Http\Controllers\InventoryToolkitController::class, 'update'])->name('inventory-toolkit.update');

    Route::get('/books-of-accounts', [App\Http\Controllers\BooksToolkitController::class, 'index'])->name('books.accounts');
    Route::post('/books-toolkit', [App\Http\Controllers\BooksToolkitController::class, 'store'])->name('books-toolkit.store');
    Route::put('/books-toolkit/{id}', [App\Http\Controllers\BooksToolkitController::class, 'update'])->name('books-toolkit.update');

    Route::get('/bank-enrollment', [App\Http\Controllers\BankController::class, 'index'])->name('bank.enrollment');
    Route::post('/banks', [App\Http\Controllers\BankController::class, 'store'])->name('banks.store');
    Route::get('/banks/{id}', [App\Http\Controllers\BankController::class, 'show'])->name('banks.show');
    Route::get('/banks/{id}/edit', [App\Http\Controllers\BankController::class, 'edit'])->name('banks.edit');
    Route::put('/banks/{id}', [App\Http\Controllers\BankController::class, 'update'])->name('banks.update');
    Route::delete('/banks/{id}', [App\Http\Controllers\BankController::class, 'destroy'])->name('banks.destroy');
    
    // Bank Reconciliation routes
    Route::get('/bank-reconciliation', [App\Http\Controllers\BankReconciliationController::class, 'index'])->name('bank.reconciliation');
    Route::post('/bank-reconciliation/deposit', [App\Http\Controllers\BankReconciliationController::class, 'deposit'])->name('bank-reconciliation.deposit');
    Route::post('/bank-reconciliation/withdrawal', [App\Http\Controllers\BankReconciliationController::class, 'withdrawal'])->name('bank-reconciliation.withdrawal');
    Route::post('/bank-reconciliation/adjustment', [App\Http\Controllers\BankReconciliationController::class, 'adjustment'])->name('bank-reconciliation.adjustment');
    Route::post('/bank-reconciliation/delete', [App\Http\Controllers\BankReconciliationController::class, 'delete'])->name('bank-reconciliation.delete');

    Route::get('/billing/e-invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('billing.invoice');
    Route::post('/invoices', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
    Route::put('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::post('/invoices/tax', [App\Http\Controllers\InvoiceController::class, 'storeTaxRate'])->name('invoices.tax.store');
    Route::post('/invoices/discount', [App\Http\Controllers\InvoiceController::class, 'storeDiscountRate'])->name('invoices.discount.store');
    Route::post('/invoices/send-email', [App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
    
    // Acknowledgment Receipt routes
    Route::get('/billing/ack-receipt', [App\Http\Controllers\AcknowledgmentReceiptController::class, 'index'])->name('billing.ack.receipt');
    Route::post('/ack-receipt', [App\Http\Controllers\AcknowledgmentReceiptController::class, 'store'])->name('ack.receipt.store');
    Route::put('/ack-receipt/{id}', [App\Http\Controllers\AcknowledgmentReceiptController::class, 'update'])->name('ack.receipt.update');
    Route::delete('/ack-receipt/{id}', [App\Http\Controllers\AcknowledgmentReceiptController::class, 'destroy'])->name('ack.receipt.destroy');
    Route::post('/ack-receipt/payment-method', [App\Http\Controllers\AcknowledgmentReceiptController::class, 'storePaymentMethod'])->name('ack.receipt.payment-method.store');
    Route::post('/ack-receipt/send-email', [App\Http\Controllers\AcknowledgmentReceiptController::class, 'sendEmail'])->name('ack.receipt.send-email');
    
    // Petty Cash Voucher routes
    Route::get('/billing/petty-cash', [App\Http\Controllers\PettyCashVoucherController::class, 'index'])->name('billing.petty.cash');
    Route::get('/petty-cash-voucher/{id}', [App\Http\Controllers\PettyCashVoucherController::class, 'show'])->name('petty.cash.show');
    Route::post('/petty-cash-voucher', [App\Http\Controllers\PettyCashVoucherController::class, 'store'])->name('petty.cash.store');
    Route::put('/petty-cash-voucher/{id}', [App\Http\Controllers\PettyCashVoucherController::class, 'update'])->name('petty.cash.update');
    Route::delete('/petty-cash-voucher/{id}', [App\Http\Controllers\PettyCashVoucherController::class, 'destroy'])->name('petty.cash.destroy');
    Route::post('/petty-cash-voucher/category', [App\Http\Controllers\PettyCashVoucherController::class, 'storeCategory'])->name('petty.cash.category.store');
    Route::post('/petty-cash-voucher/update-status', [App\Http\Controllers\PettyCashVoucherController::class, 'updateStatus'])->name('petty.cash.update-status');
    Route::post('/petty-cash-voucher/send-email', [App\Http\Controllers\PettyCashVoucherController::class, 'sendEmail'])->name('petty.cash.send-email');

    Route::view('/tax-settings', 'pages.tax-settings')->name('tax.settings');
    Route::view('/ecommerce', 'pages.ecommerce')->name('ecommerce');
    Route::view('/email-notification', 'pages.email-notification')->name('email.notification');
});
