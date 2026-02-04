<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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
    // Kalau user sudah login, lempar ke dashboard
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    // Kalau belum login, lempar ke halaman login
    return redirect()->route('login');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::get('dashboard', 'AdminController@index')->name('admin.dashboard');

    // Route baru untuk fungsi tombol
    Route::post('item/{id}/restock', 'ItemController@restock')->name('admin.item.restock');
    Route::post('transaction/{id}/approve', 'AdminController@approve')->name('admin.trx.approve');
    Route::post('transaction/{id}/reject', 'AdminController@reject')->name('admin.trx.reject');
    // Tambahkan di bawah route approve/reject yang lama
    Route::post('transaction/approve-all/{code}', 'AdminController@approveAll')->name('admin.trx.approveAll');

    // Master Barang (Resource otomatis bikin route index, create, store, edit, update, destroy)
    Route::resource('items', 'ItemController', ['as' => 'admin']);

    // Approval Route (Nanti di langkah 3)
    Route::get('approval', 'TransactionController@approvalPage')->name('admin.transactions.approval');

    Route::resource('users', 'ManageUserController', ['as' => 'admin']);

    // REPORT & EXPORT
    Route::get('reports', 'ReportController@index')->name('admin.reports.index');
    Route::get('reports/pdf', 'ReportController@exportPdf')->name('admin.reports.pdf');
    Route::get('reports/excel', 'ReportController@exportExcel')->name('admin.reports.excel');

    // RIWAYAT BARANG MASUK (STOCK IN)
    Route::get('stock-in', 'IncomingStockController@index')->name('admin.incoming.index');

    Route::get('stock-out', 'StockOutController@index')->name('admin.stockout.index');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('dashboard', 'UserController@index')->name('user.dashboard');
    Route::get('request', 'UserController@createRequest')->name('user.request.create');
    Route::post('request', 'UserController@storeRequest')->name('user.request.store');
});
