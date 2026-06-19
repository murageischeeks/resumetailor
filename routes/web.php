<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download/pdf/{id}', [PdfController::class, 'download'])->name('download.pdf');
