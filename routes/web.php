<?php

use App\Http\Controllers\CsvImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
