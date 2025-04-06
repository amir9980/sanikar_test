<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Layouts.app');
});

Route::get('/{vue_capture?}', function () {
    return view('Layouts.app');
})->where('vue_capture', '[\/\w\.-]*');
