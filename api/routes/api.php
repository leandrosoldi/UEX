<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('contact',ContactController::class);
Route::post('validate-cpf',[ContactController::class,'validateCPF']);