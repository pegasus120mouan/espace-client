<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/annee', [AuthController::class, 'dashboardAnnee'])->name('dashboard.annee');

Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
Route::get('/commandes/print', [CommandeController::class, 'print'])->name('commandes.print');
Route::get('/commandes/valider', [CommandeController::class, 'valider'])->name('commandes.valider');
Route::get('/commandes/points-valides', [CommandeController::class, 'pointsValides'])->name('commandes.points-valides');
Route::get('/commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
