<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/midtest', function () {
    return file_get_contents('https://app.sandbox.midtrans.com');
});

Route::get('/pay/{token}', function ($token) {
    return view('pay', compact('token'));
});



