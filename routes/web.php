<?php

use App\Http\Controllers\FormController;
use App\Http\Livewire\Form;
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

Route::get('form', Form::class);

Route::post('form/send', [FormController::class, 'send']);
