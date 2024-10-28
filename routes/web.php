<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KensaController;
use App\Http\Controllers\KensaTrialController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MasterKensaController;
use App\Http\Controllers\RackingController_T;
use App\Http\Controllers\RencanaProduksiController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UnrackingController_T;
use Illuminate\Support\Facades\Route;

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


Route::redirect('/', 'login');

Route::get('/dashboard', [DashboardController::class, 'home'])->middleware(['auth'])->name('dashboard');

Route::controller(AdminController::class)->middleware(['auth'])->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
    Route::get('/admin/profile', 'profile')->name('admin.profile');
    Route::get('/admin/edit/profile', 'editprofile')->name('edit.profile');
    Route::post('/store/profile', 'storeprofile')->name('store.profile');

    Route::get('/management_user', 'iUser')->name('i.user');
});

// Supplier All Route
Route::controller(MasterDataController::class)->middleware(['auth'])->group(function () {
    Route::get('/supplier/all', 'SupplierAll')->name('supplier.all');
    Route::get('masterdata', 'index')->name('master');
    Route::get('masterdata/tambah', 'tambah')->name('master.tambah');
    Route::post('masterdata', 'simpan')->name('master.simpan');
    Route::post('masterdata/destroy/{id}', 'destroy')->name('master.destroy');
    Route::get('masterdata/{id}/edit', 'edit')->name('master.edit');
    Route::get('masterdata/{id}/show', 'show')->name('master.show');
    Route::patch('masterdata/{id}', 'update')->name('master.update');
    Route::get('masterdata/export_excel', 'exportexcel')->name('master.export');
    Route::post('masterdata/import_excel', 'importexcel')->name('master.import');
    Route::get('masterdata/search', 'search')->name('master.search');
    Route::get('/masterdata/{id}', 'getDataParts');
});

Route::controller(RackingController_T::class)->middleware(['auth'])->group(function () {
    Route::get('racking_t', 'index')->name('racking_t');
    Route::get('racking_t/tambah', 'tambah')->name('racking_t.tambah');

    Route::get('/get-data', 'getData')->name('get-data');
    Route::get('/getCountRack', 'getCountRack')->name('get-count-rack');



    Route::post('racking_t', 'simpan')->name('racking_t.simpan');
    Route::delete('racking_t/delete/{id}', 'delete')->name('racking_t.delete');
    Route::get('racking_t/edit/{id}/', 'edit')->name('racking_t.edit');
    Route::patch('racking_t/{id}', 'update')->name('racking_t.update');
    Route::get('racking_t/ajax', 'ajaxRacking')->name('racking_t.ajax');
    Route::get('racking_t/findMasterdata', 'findMasterdata')->name('findMasterdata');
    Route::get('racking_t/utama', 'utama')->name('racking_t.utama');


    Route::get('racking_t/ngracking', 'ngracking')->name('ngracking');
    Route::get('racking_t/ngracking/tambah', 'tambahngracking')->name('ngracking.tambah');
    Route::post('racking_t/ngracking/simpan', 'simpanngracking')->name('ngracking.simpan');
    Route::get('racking_t/ngracking/edit/{id}', 'editngracking')->name('ngracking.edit');
    Route::patch('racking_t/ngracking/{id}', 'updatengracking')->name('ngracking.update');
    Route::delete('racking_t/ngracking/delete/{id}', 'deletengracking')->name('ngracking.delete');


    Route::get('racking_t/pinbosh_tertinggal', 'pinbosh')->name('pinbosh');
    Route::get('racking_t/pinbosh_tertinggal/tambah', 'pinboshTambah')->name('pinbosh.tambah');
    Route::post('racking_t/pinbosh_tertinggal/simpan', 'pinboshSimpan')->name('pinbosh.simpan');
    Route::get('racking_t/pinbosh_tertinggal/edit/{id}', 'pinboshEdit')->name('pinbosh.edit');
    Route::patch('racking_t/pinbosh_tertinggal/{id}', 'pinboshUpdate')->name('pinbosh.update');
});

Route::controller(UnrackingController_T::class)->middleware(['auth'])->group(function () {
    Route::get('unracking_t', 'index')->name('unracking_t');
    Route::post('unracking_t', 'simpan')->name('unracking_t.simpan');
    // Route::delete('unracking_t/delete/{id}', 'delete')->name('unracking_t.delete');
    Route::get('unracking_t/edit/{id}', 'edit')->name('unracking_t.edit');
    Route::patch('unracking_t/{id}', 'update')->name('unracking_t.update');
    Route::get('unracking_t/print/{id}', 'unrackingPrint')->name('unracking_t.print');
});

Route::controller(KensaController::class)->middleware(['auth'])->group(function () {
    Route::get('kensa', 'index')->name('kensa');
    Route::get('kensa/tambah', 'tambah')->name('kensa.tambah');
    Route::post('kensa', 'simpan')->name('kensa.simpan');
    Route::delete('kensa/delete/{id}', 'delete')->name('kensa.delete');
    Route::get('kensa/{id}/edit', 'edit')->name('kensa.edit');
    Route::patch('kensa/{id}', 'update')->name('kensa.update');
    Route::get('kensa/export_excel', 'exportexcel')->name('kensa.export');
    Route::get('kensa/autocomplete/{id}', 'autocomplete')->name('autocomplete_t');
    Route::get('kensa/search', 'search')->name('kensa.search');
    Route::get('kensa/searchdater', 'searchDater')->name('kensa.searchDate');
    Route::get('kensa/pengiriman', 'pengiriman')->name('kensa.pengiriman');

    Route::delete('kensa/pengiriman/delete/{id}', 'pengirimanDelete')->name('pengiriman.delete');

    Route::get('kensa/print_kanban', 'printKanban')->name('kensa.printKanban');
    Route::post('kensa/print_kanban/simpan', 'kanbansimpan')->name('kensa.kanban-simpan');

    Route::get('kensa/print_kanban/ajax', 'ajaxKanban')->name('kensa.ajaxKanban');

    Route::get('kensa/print_kanban/cetak_kanban/{id}', 'cetak_kanban')->name('kensa.cetak_kanban');
    Route::get('kensa/ajax', 'ajax')->name('kensa.ajax');


    Route::get('kensa/print_kanban/custom_kanban/', 'custom_kanban')->name('kensa.custom_kanban');
    Route::get('kensa/print_kanban/custom_kanban/ajax', 'custom_kanban_ajax')->name('custom.kanban.ajax');
    Route::get('kensa/print_kanban/custom_kanban/print/{id}', 'custom_kanban_print')->name('custom.kanban.print');
    Route::post('kensa/print_kanban/custom_kanban/simpan', 'custom_kanban_simpan')->name('custom.kanban-simpan');


    Route::get('kensa/utama', 'utama')->name('kensa.utama');
    Route::get('kensa/utama2', 'utama2')->name('kensa.utama2');

    Route::get('kensa/data', 'data')->name('kensa.data');
});

Route::controller(LaporanController::class)->middleware(['auth'])->group(function () {
    Route::get('laporan', 'index')->name('laporan');
    Route::get('laporan/getData', 'getData')->name('laporan.getdata');
    Route::get('laporan/kensa', 'kensa')->name('laporan.kensa');
    Route::get('laporan/all', 'all')->name('laporan.all');
    Route::get('laporan/trial', 'trial')->name('laporan.trial');



    Route::get('laporan/allAdmin', 'allAdmin')->name('laporan.allAdmin');
});

Route::controller(KensaTrialController::class)->middleware(['auth'])->group(function () {
    Route::get('kensa_trial', 'index')->name('tr.kensa');
    Route::get('kensa_trial/tambah', 'tambah')->name('tr.kensa.tambah');
    Route::post('kensa_trial', 'simpan')->name('tr.kensa.simpan');
    Route::delete('kensa_trial/delete/{id}', 'delete')->name('tr.kensa.delete');
    Route::get('kensa_trial/{id}/edit', 'edit')->name('tr.kensa.edit');
    Route::patch('kensa_trial/{id}', 'update')->name('tr.kensa.update');
    Route::get('/kensa_trial/approve/{id}', 'trialApprove')->name('tr.kensa.approve');
    Route::get('/kensa_trial/cancell/{id}', 'trialCancell')->name('tr.kensa.cancell');
});

Route::get('stok', [StokController::class, 'index'])->name('stok');
Route::get('stok_bc', [StokController::class, 'index2'])->name('stok_bc');
Route::get('stokAdmin', [StokController::class, 'stokAdmin'])->name('stokAdmin');
Route::get('masterkensa', [MasterKensaController::class, 'index'])->name('msterkensa');
Route::get('masterdata/downloadPDF/{id}', [MasterDataController::class, 'downloadPDF'])->name('masterdata.downloadPDF');

Route::get('lihatPart', [StokController::class, 'lihatPart'])->name('lihatPart');
Route::get('cariPart', [StokController::class, 'cariPart'])->name('cariPart');

Route::get('rencana_produksi', [RencanaProduksiController::class, 'index'])->name('rencana_produksi');
Route::post('rencana_produksi/import_excel', [RencanaProduksiController::class, 'import_excel'])->name('rencana_produksi.import_excel');


require __DIR__ . '/auth.php';
