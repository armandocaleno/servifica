<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Reporting\ReportingController;
use App\Http\Controllers\SelectCompanyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Transaction\Create;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/welcome', function(){return view('welcome');})->name('welcome');  
    Route::get('/seleccionar-empresa', [SelectCompanyController::class, 'index'])->name('company.select');
    Route::POST('/establecer-empresa', [SelectCompanyController::class, 'set'])->name('company.set');

    // Usuarios       
    Route::resource('admin/users', UserController::class)->except('show')->names('users');
    
    //Transacciones
    Route::get('transacciones/index', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transacciones/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::get('transacciones/edit/{transaction}', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::get('transacciones/create/lotes', [TransactionController::class, 'lotes'])->name('transactions.lotes');
    Route::post('transacciones/lotes/store', [TransactionController::class, 'store'])->name('transactions.lotes.store');
    Route::get('recibo/{id}', [Create::class, 'generatevoucher'])->name('transaction.voucher');
    Route::get('transacciones/pagos/create', [TransactionController::class, 'payment'])->name('transactions.payment.create');

    //Socios
    Route::get('socios/index', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('socios/create', [PartnerController::class, 'create'])->name('partners.create');
    Route::post('socios/store', [PartnerController::class, 'store'])->name('partners.store');
    Route::get('socios/edit/{partner}', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::post('socios/update', [PartnerController::class, 'update'])->name('partners.update');

    //Cuentas
    Route::get('cuentas/index', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('cuentas/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('cuentas/store', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('cuentas/edit/{account}', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::post('cuentas/update', [AccountController::class, 'update'])->name('accounts.update');

    //Reportes
    Route::get('reportes/socios/saldos', [ReportingController::class, 'balances'])->name('reporting.partners.balances');
    Route::post('reportes/socios/saldos/generar', [ReportingController::class, 'balancesReporting'])->name('reporting.partners.balances.generate');
    Route::get('reportes/transacciones', [ReportingController::class, 'transactions'])->name('reporting.transactions');
    Route::match(['get', 'post'],'reportes/transacciones/generar', [ReportingController::class, 'transactionsReporting'])->name('reporting.transactions.generate');
    // Route::post('reportes/transacciones/pdf', [ReportingController::class, 'transactions_pdf'])->name('reporting.transactions.pdf');
    Route::get('reportes/socios', [ReportingController::class, 'partners'])->name('reporting.partners');
    Route::post('reportes/socios/generar', [ReportingController::class, 'partnersReporting'])->name('reporting.partners.generate');
    Route::get('reportes/cuentas', [ReportingController::class, 'accounts'])->name('reporting.accounts');
    Route::match(['get', 'post'],'reportes/cuentas/generar', [ReportingController::class, 'accountsReporting'])->name('reporting.accounts.generate');
    //Empresas
    Route::get('empresas/index', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('empresas/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('empresas/store', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('empresas/{company}/edit/', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::post('empresas/update', [CompanyController::class, 'update'])->name('companies.update');    

    // Plan de cuentas
    Route::get('plan-de-cuentas/index', [AccountingController::class, 'index'])->name('accounting.index');
    Route::get('plan-de-cuentas/create', [AccountingController::class, 'create'])->name('accounting.create');
    Route::post('plan-de-cuentas/store', [AccountingController::class, 'store'])->name('accounting.store');
    Route::get('plan-de-cuentas/edit/{accounting}', [AccountingController::class, 'edit'])->name('accounting.edit');
});