<?php

use App\Http\Controllers\EstudioController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/estudios');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('/dashboard', '/estudios')->name('dashboard');

    Route::get('/estudios', [EstudioController::class, 'index'])->name('estudios.index');
    Route::post('/estudios/calcular', [EstudioController::class, 'calcular'])->name('estudios.calcular');
    Route::post('/estudios', [EstudioController::class, 'store'])->name('estudios.store');
    Route::get('/estudios/historial', [EstudioController::class, 'historial'])->name('estudios.historial');
    Route::get('/estudios/exportar', [EstudioController::class, 'exportarCsv'])->name('estudios.exportar');
    Route::delete('/estudios/{estudio}', [EstudioController::class, 'destroy'])->name('estudios.destroy');

    Route::get('/parametros', [ParametroController::class, 'edit'])->name('parametros.edit');
    Route::put('/parametros', [ParametroController::class, 'update'])->name('parametros.update');
    Route::post('/parametros/restaurar', [ParametroController::class, 'restaurar'])->name('parametros.restaurar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
