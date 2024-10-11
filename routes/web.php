<?php

use App\Http\Controllers\PuertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\PrimeroController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ActividadController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/saludar', function (){
    return view('saludo');
});

//rutas de introduccion o practica
Route::get('/entrar',[PuertaController::class,'formulario']);
Route::get('/salir',[AccesoController::class,'salida'])->name('salida');
Route::get('/contraseña',[AccesoController::class,'cambio'])->name('Cambiar_Contraseña');
Route::get('/adentro',[AccesoController::class,'adentro'])->name('adentro');
Route::get('/login',[AccesoController::class,'login'])->name('login');
//Route::get('/registro',[AccesoController::class,'registro'])->name('registro');
//Route::get('/periodo',[AccesoController::class,'periodo'])->name('periodo');
Route::get('/reporte',[AccesoController::class,'reporte'])->name('reporte');
Route::get('/estatus',[AccesoController::class,'estatus'])->name('estatus');

//rutas de los CRUD 
Route::resource('proyectos',ProyectoController::class);
Route::resource('periodos',PeriodoController::class);
Route::resource('estudiantes',EstudianteController::class)->except(['show']);
Route::resource('primeros',PrimeroController::class);
Route::resource('asesores',AsesorController::class)->except(['show']);
Route::resource('carreras',CarreraController::class)->except(['show']);
Route::resource('empresas',EmpresaController::class);
Route::resource('actividades',ActividadController::class);

//rutas de vistas para aviso o Extras al CRUD
Route::get('/asesores/{pagina}', [AsesorController::class, 'mostrar']);
Route::get('/carreras/{pagina}', [CarreraController::class, 'mostrar']);
Route::get('/estudiantes/{pagina}', [EstudianteController::class, 'mostrar']);

//Route::post('/subir', [EstudianteController::class, 'subirPDF'])->name('subir.pdf');
