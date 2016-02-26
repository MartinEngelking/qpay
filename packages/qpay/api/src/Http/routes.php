<?php
// This simple API supports two routes:
// 1: list all transactions
// 2: store new transaction
Route::group(['prefix' => 'v1'], function () {
    Route::get('transactions', 'TransactionController@index')->name('list-transactions');
    Route::post('transaction', 'TransactionController@store')->name('store-transaction');
});