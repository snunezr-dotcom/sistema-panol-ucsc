<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\SetLocale;
use App\Imports\InventarioImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

//Al entrar a localhost
Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(SetLocale::class)->group(function () {
    Route::post('/language', function (Request $request) {
        $validated = $request->validate([
            'locale' => ['required', 'in:es,en'],
        ]);

        $request->session()->put('locale', $validated['locale']);

        return back();
    })->name('language.update');

    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect('/carrito');
        }

        return view('login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'rut' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/carrito');
        }

        return back()
            ->with('error', __('RUT o contraseña incorrectos. Intente nuevamente.'))
            ->withInput($request->only('rut'));
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    })->middleware('auth');

    Route::get('/carrito', [\App\Http\Controllers\PanolController::class, 'index'])
        ->middleware('auth');
});

// Ruta para importar los datos de Excel a la base de datos
Route::get('/importar-excel', function () {
    Excel::import(new InventarioImport, base_path('Copia de InventarioModernizadoComparativo.xlsx'));
    return 'Inventario importado con éxito!';
});