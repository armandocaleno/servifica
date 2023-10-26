<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountingConfigController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Reporting\ReportingController;
use App\Http\Controllers\SelectCompanyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Transaction\Create;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Livewire\Checks\Create as ChecksCreate;


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
    Route::match(['get', 'post'],'/establecer-empresa', [SelectCompanyController::class, 'set'])->name('company.set');

    // Usuarios       
    Route::resource('admin/users', UserController::class)->except('show')->names('users');
    
    //Transacciones
    Route::get('transacciones/index', [TransactionController::class, 'index'])->middleware('can:transactions.index')->name('transactions.index');
    Route::get('transacciones/create', [TransactionController::class, 'create'])->middleware('can:transactions.collection.create')->name('transactions.create');
    Route::get('transacciones/edit/{transaction}', [TransactionController::class, 'edit'])->middleware('can:transactions.index')->name('transactions.edit');
    Route::get('transacciones/create/lotes', [TransactionController::class, 'lotes'])->middleware('can:transactions.charge.create')->name('transactions.lotes');
    Route::post('transacciones/lotes/store', [TransactionController::class, 'store'])->middleware('can:transactions.charge.create')->name('transactions.lotes.store');
    Route::get('recibo/{id}', [Create::class, 'generatevoucher'])->middleware('can:transactions.collection.voucher')->name('transaction.voucher');
    Route::get('enviar-recibo-email/{id}', [Create::class, 'generateVoucherEmail'])->middleware('can:transactions.collection.voucher')->name('transaction.voucher');
    Route::get('transacciones/pagos/create', [TransactionController::class, 'payment'])->middleware('can:transactions.payment.create')->name('transactions.payment.create');

    //Socios
    Route::get('socios/index', [PartnerController::class, 'index'])->middleware('can:admin.partners.index')->name('partners.index');
    Route::get('socios/create', [PartnerController::class, 'create'])->middleware('can:admin.partners.create')->name('partners.create');
    Route::post('socios/store', [PartnerController::class, 'store'])->middleware('can:admin.partners.create')->name('partners.store');
    Route::get('socios/edit/{partner}', [PartnerController::class, 'edit'])->middleware('can:admin.partners.edit')->name('partners.edit');
    Route::post('socios/update', [PartnerController::class, 'update'])->middleware('can:admin.partners.edit')->name('partners.update');

    //Cuentas
    Route::get('cuentas/index', [AccountController::class, 'index'])->middleware('can:admin.accounts.index')->name('accounts.index');
    Route::get('cuentas/create', [AccountController::class, 'create'])->middleware('can:admin.accounts.create')->name('accounts.create');
    Route::post('cuentas/store', [AccountController::class, 'store'])->middleware('can:admin.accounts.create')->name('accounts.store');
    Route::get('cuentas/edit/{account}', [AccountController::class, 'edit'])->middleware('can:admin.accounts.edit')->name('accounts.edit');
    Route::post('cuentas/update', [AccountController::class, 'update'])->middleware('can:admin.accounts.edit')->name('accounts.update');

    //Reportes
    Route::get('reportes/socios/saldos', [ReportingController::class, 'balances'])->middleware('can:reporting.partners.balance.show')->name('reporting.partners.balances');
    Route::post('reportes/socios/saldos/generar', [ReportingController::class, 'balancesReporting'])->middleware('can:reporting.partners.balance.export')->name('reporting.partners.balances.generate');
    Route::get('reportes/transacciones', [ReportingController::class, 'transactions'])->middleware('can:reporting.transactions.show')->name('reporting.transactions');
    Route::match(['get', 'post'],'reportes/transacciones/generar', [ReportingController::class, 'transactionsReporting'])->middleware('can:reporting.transactions.export')->name('reporting.transactions.generate');
    Route::get('reportes/socios', [ReportingController::class, 'partners'])->middleware('can:reporting.partners.show')->name('reporting.partners');
    Route::post('reportes/socios/generar', [ReportingController::class, 'partnersReporting'])->middleware('can:reporting.partners.export')->name('reporting.partners.generate');
    Route::get('reportes/cuentas', [ReportingController::class, 'accounts'])->middleware('can:reporting.accounts.show')->name('reporting.accounts');
    Route::match(['get', 'post'],'reportes/cuentas/generar', [ReportingController::class, 'accountsReporting'])->middleware('can:reporting.accounts.export')->name('reporting.accounts.generate');
    Route::match(['get', 'post'],'reportes/compras', [ReportingController::class, 'expenses'])->middleware('can:reporting.expenses.show')->name('reporting.expenses');
    Route::match(['get', 'post'],'reportes/compras/generar', [ReportingController::class, 'expensesReporting'])->middleware('can:reporting.expenses.export')->name('reporting.expenses.generate');

    //Empresas
    Route::get('empresas/index', [CompanyController::class, 'index'])->middleware('can:admin.companies.index')->name('companies.index');
    Route::get('empresas/create', [CompanyController::class, 'create'])->middleware('can:admin.companies.create')->name('companies.create');
    Route::post('empresas/store', [CompanyController::class, 'store'])->middleware('can:admin.companies.create')->name('companies.store');
    Route::get('empresas/{company}/edit/', [CompanyController::class, 'edit'])->middleware('can:admin.companies.update')->name('companies.edit');
    Route::post('empresas/update', [CompanyController::class, 'update'])->middleware('can:admin.companies.update')->name('companies.update');    

    //Plan de cuentas
    Route::get('plan-de-cuentas/index', [AccountingController::class, 'index'])->middleware('can:accounting.accounts.index')->name('accounting.index');
    Route::get('plan-de-cuentas/create', [AccountingController::class, 'create'])->middleware('can:accounting.accounts.create')->name('accounting.create');
    Route::post('plan-de-cuentas/store', [AccountingController::class, 'store'])->middleware('can:accounting.accounts.create')->name('accounting.store');
    Route::get('plan-de-cuentas/edit/{accounting}', [AccountingController::class, 'edit'])->middleware('can:accounting.accounts.edit')->name('accounting.edit');

    //Asientos contables
    Route::get('asientos-contables/index', [JournalController::class, 'index'])->middleware('can:accounting.journals.index')->name('journals.index');
    Route::get('asientos-contables/crear', [JournalController::class, 'create'])->middleware('can:accounting.journals.create')->name('journals.create');
    Route::get('asientos-contables/editar/{journal}', [JournalController::class, 'edit'])->middleware('can:accounting.journals.edit')->name('journals.edit');

    //Bancos
    Route::get('bancos/index', [BankController::class, 'index'])->middleware('can:accounting.banks.index')->name('banks.index');

    // Cuentas Bancarias
    Route::get('cuentas-bancarias/index', [BankAccountController::class, 'index'])->middleware('can:accounting.bankaccounts.index')->name('bank-accounts.index');

    //Compras y gastos
    Route::get('compras-gastos/index', [ExpenseController::class, 'index'])->middleware('can:transactions.expenses.index')->name('expenses.index');
    Route::get('compras-gastos/create', [ExpenseController::class, 'create'])->middleware('can:transactions.expenses.create')->name('expenses.create');
    Route::get('compras-gastos/edit/{expense}', [ExpenseController::class, 'edit'])->middleware('can:transactions.expenses.edit')->name('expenses.edit');
    Route::post('compras-gastos/store', [ExpenseController::class, 'store'])->middleware('can:transactions.expenses.create')->name('expenses.store');

    //Config de cuentas contables
    Route::get('config-cuentas-contables/index', [AccountingConfigController::class, 'index'])->middleware('can:admin.config.accounting.index')->name('accounting.config.index');

    //Cheques
    Route::get('cheques', [CheckController::class, 'index'])->name('checks.index');
    Route::get('cheques/nuevo', [CheckController::class, 'create'])->name('checks.create');
    Route::get('cheques/nuevo/ingreso', [CheckController::class, 'income'])->name('checks.income');
    Route::get('cheques/modificar/{check}', [CheckController::class, 'edit'])->name('checks.edit');
    Route::get('comprobante-egreso/{id}', [ChecksCreate::class, 'generatevoucher'])->name('checks.voucher');

    //Proveedores
    Route::get('proveedores/index', [SupplierController::class, 'index'])->middleware('can:admin.suppliers.index')->name('suppliers.index');
    Route::get('proveedores/crear', [SupplierController::class, 'create'])->middleware('can:admin.suppliers.create')->name('suppliers.create');
    Route::post('proveedores/store', [SupplierController::class, 'store'])->middleware('can:admin.suppliers.create')->name('suppliers.store');
    Route::get('proveedores/editar/{supplier}', [SupplierController::class, 'edit'])->middleware('can:admin.suppliers.edit')->name('suppliers.edit');
    Route::post('proveedores/update', [SupplierController::class, 'update'])->middleware('can:admin.suppliers.edit')->name('suppliers.update');

    //Reportes financieros
    Route::match(['get', 'post'],'reportes-financieros/balance', [ReportingController::class, 'general_balance'])->middleware('can:accounting.sfp.show')->name('reporting.balance');
    Route::match(['get', 'post'],'reportes-financieros/balance/pdf', [ReportingController::class, 'general_balance_pdf'])->middleware('can:accounting.sfp.export')->name('reporting.balance.pdf');
    Route::match(['get', 'post'],'reportes-financieros/resultados', [ReportingController::class, 'results'])->middleware('can:accounting.balance.show')->name('reporting.results');
    Route::match(['get', 'post'],'reportes-financieros/resultados/pdf', [ReportingController::class, 'results_pdf'])->middleware('can:accounting.balance.export')->name('reporting.results.pdf');
    Route::match(['get', 'post'],'reportes-financieros/mayor', [ReportingController::class, 'ledger'])->middleware('can:accounting.ledger.show')->name('reporting.ledger');
    // Route::get('reportes/mayor', [ReportingController::class, 'ledger'])->name('reporting.ledger');
    Route::match(['get', 'post'],'reportes-financieros/mayor/pdf', [ReportingController::class, 'ledger_pdf'])->middleware('can:accounting.ledger.export')->name('reporting.ledger.pdf');

     //Roles 
     Route::resource('admin/roles', RoleController::class)->except('show')->names('roles');
});