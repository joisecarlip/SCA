<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth:usuarios'])->group(function () {
    Route::get('/inicio', [LoginController::class, 'inicio'])->name('inicio');
    
    Route::get('/usuarios', function() {
        $user = Auth::guard('usuarios')->user();
        if ($user->user_tipo != 0) {
            return redirect()->route('inicio')->with('error', 'No tienes permisos para acceder a esta sección');
        }
        return app(UsuarioController::class)->index(request());
    })->name('usuarios.index');
    
    Route::post('/usuarios', function() {
        $user = Auth::guard('usuarios')->user();
        if ($user->user_tipo != 0) {
            return redirect()->route('inicio')->with('error', 'No tienes permisos para realizar esta acción');
        }
        return app(UsuarioController::class)->store(request());
    })->name('usuarios.store');
    
    Route::get('/usuarios/{id}/edit', function($id) {
        $user = Auth::guard('usuarios')->user();
        if ($user->user_tipo != 0) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        return app(UsuarioController::class)->edit($id);
    })->name('usuarios.edit');
    
    Route::put('/usuarios/{id}', function($id) {
        $user = Auth::guard('usuarios')->user();
        if ($user->user_tipo != 0) {
            return redirect()->route('inicio')->with('error', 'No tienes permisos para realizar esta acción');
        }
        return app(UsuarioController::class)->update(request(), $id);
    })->name('usuarios.update');
    
    Route::delete('/usuarios/{id}', function($id) {
        $user = Auth::guard('usuarios')->user();
        if ($user->user_tipo != 0) {
            return redirect()->route('inicio')->with('error', 'No tienes permisos para realizar esta acción');
        }
        return app(UsuarioController::class)->destroy($id);
    })->name('usuarios.destroy');
});