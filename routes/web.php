<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // or whatever view serves your SPA
})->where('any', '^(?!api).*$');
