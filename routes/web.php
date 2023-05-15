<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

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
    return view('welcome');
});
//ambil semua data
Route::get('/students', [StudentController::class,'index']);
Route::get('/students/show/trash', [StudentController::class,'trash']);
//tambah data
Route::post('/students/tambah-data', [StudentController::class,'store']);
//generate toke csrf
Route::get('/generate-token', [StudentController::class,'createToken']);
//ambil satu data spesifik
Route::get('/students/{id}', [StudentController::class,'show']);
//mengubah data
Route::patch('/students/update/{id}', [StudentController::class,'update']);
Route::delete('/students/delete/{id}', [StudentController::class,'destroy']);

//mengembalikan data spesifik yang sudah di hapus
Route::get('students/trash/restore/{id}', [StudentController::class, 'restore']);

//menghapus permanen data tertentu 
Route::get('/students/trash/delete/permanent/{id}', [StudentController::class, 'permanentDelete']);