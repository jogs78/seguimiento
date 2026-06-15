<?php

use App\Http\Controllers\PuertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ParcialController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ExternoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\DocumentoEstudianteController;
use App\Http\Controllers\DocumentoCoordinadorController;
use App\Http\Controllers\ForgotPasswordController;

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

use Inertia\Inertia;

Route::get('/login',function(){
    return Inertia::render('acceso/formulario');
})->name('Inicio_Sesion');


Route::get('/estudiante/evidencias', [DocumentoEstudianteController::class, 'index'])->name('estudiante.evidencias')->middleware('auth');
Route::get('/estudiante/subir-evidencia', [DocumentoEstudianteController::class, 'index'])->name('estudiante.subir-evidencia')->middleware('auth');

Route::get('/coordinador/evidencias', [DocumentoCoordinadorController::class, 'index'])
        ->name('coordinador.evidencias');

Route::get('/documentos', [DocumentoEstudianteController::class, 'index'])->name('documentos.index');
Route::get('/documentos/{id}/download', [DocumentoEstudianteController::class, 'download'])->name('documentos.download');
Route::delete('/documentos/{id}', [DocumentoEstudianteController::class, 'destroy'])->name('documentos.destroy');
// Ruta para almacenar documentos (subir evidencia)
Route::post('/documentos', [DocumentoEstudianteController::class, 'store'])->name('documentos.store');
Route::post('/seleccionar-carrera',[CoordinadorController::class,'seleccionarCarrera'])->name('coordinadores.seleccionarCarrera');
Route::post('/seleccionar-carrera',[AccesoController::class,'seleccionarCarrera'])->name('seleccionar.carrera');
// Ruta para la página de bienvenida (adentro)
Route::get('/adentro', function () {
    return Inertia::render('acceso/adentro');
})->name('adentro')->middleware('auth');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('welcome');

Route::get('/saludar', function (){
    return view('saludo');
});



// Recuperación de contraseña 
Route::get('/olvide-contrasena', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/olvide-contrasena', [ForgotPasswordController::class, 'sendResetLink'])->name('password.send');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

//rutas de introduccion o practica
Route::get('/entrar',[PuertaController::class,'formulario']);
Route::get('/salir',[AccesoController::class,'salida'])->name('salida');
Route::get('/contraseña',[AccesoController::class,'cambio'])->name('Cambiar_Contraseña');
Route::post('/cambiar-password', [UsuarioController::class, 'cambiarPassword'])->name('usuario.cambiar-password');
Route::post('/adentro',[AccesoController::class,'adentro'])->name('adentro');

Route::get('home',[AccesoController::class,'home'])->middleware('auth')->name('home');
//Route::get('/registro',[AccesoController::class,'registro'])->name('registro');
//Route::get('/periodo',[AccesoController::class,'periodo'])->name('periodo');
Route::get('/reporte',[AccesoController::class,'reporte'])->name('reporte');
Route::get('/estatus',[AccesoController::class,'estatus'])->name('estatus');
Route::get('/plantilla',[AccesoController::class,'plantilla'])->name('plantilla');

//rutas especificas del coordinador
Route::get('tabla',[CoordinadorController::class,'tabla'])->middleware('auth')->name('coordinadores.tabla');
//con inertia lo de tabla

Route::get('asignar-asesores',[CoordinadorController::class,'asignarAsesor1'])->middleware('auth')->name('coordinadores.asignarAsesor1');
Route::post('asignar-asesores',[CoordinadorController::class,'asignarAsesor2'])->middleware('auth')->name('coordinadores.asignarAsesor2');
Route::put('asignar-asesores/{proyecto_id}',[CoordinadorController::class,'asignarAsesor3'])->middleware('auth')->name('coordinadores.asignarAsesor3');

//rutas de busqueda de nombres
Route::get('/estudiantes/buscar-estudiante', [EstudianteController::class, 'buscarEstudiante'])->name('estudiantes.buscar-estudiante');
Route::get('/asesores/buscar-asesor', [AsesorController::class, 'buscarAsesor'])->name('asesores.buscar-asesor');
Route::get('/externos/buscar-externo', [ExternoController::class, 'buscarExterno'])->name('externos.buscar-externo');
Route::get('/proyectos/buscar', [ProyectoController::class, 'buscar'])->name('proyectos.buscar');


//rutas especificas del estudiante
Route::get('edit',[EstudianteController::class,'edit'])->middleware('auth')->name('estudiante.editar');
Route::get('index',[EstudianteController::class,'index'])->middleware('auth')->name('estudiante.index');
//Route::put('update',[EstudianteController::class,'update'])->middleware('auth')->name('estudiante.update');
//Route::put('update/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update');
Route::get('solicitud', [EstudianteController::class, 'solicitud'])->middleware('auth')->name('estudiante.impresiones.solicitud');
Route::get('promedio',[EstudianteController::class,'promedio'])->middleware('auth')->name('estudiante.promedio');
Route::get('anteproyecto',[EstudianteController::class,'anteproyecto'])->middleware('auth')->name('estudiante.impresiones.anteproyecto');
Route::get('primer/{estudiante?}',[EstudianteController::class,'primer'])->middleware('auth')->name('estudiante.impresiones.seguimientos.primer');
Route::get('segundo/{estudiante?}',[EstudianteController::class,'segundo'])->middleware('auth')->name('estudiante.impresiones.seguimientos.segundo');
Route::get('ultimo/{estudiante?}',[EstudianteController::class,'ultimo'])->middleware('auth')->name('estudiante.impresiones.seguimientos.ultimo');
Route::post('/proyectos/unirse', [ProyectoController::class, 'unirse'])->name('proyectos.unirse');
Route::get('/proyecto/mostrar', [ProyectoController::class, 'create'])->name('proyectos.mostrar'); 
Route::get('/buscar-proyectos', [ProyectoController::class, 'buscar'])->name('proyectos.buscar');
Route::post('/configuracion/fuera-tiempo', [ProyectoController::class, 'actualizarFueraTiempo'])
    ->name('configuracion.fuera-tiempo');


//rutas especificas del asesor interno
//para que el interno vea sus proyectos
Route::get('listar-proyectos',[AsesorController::class,'proyecto'])->middleware('auth')->name('asesor.listar-proyectos');

Route::get('historico', [AsesorController::class, 'historico'])->name('asesor.historico');
Route::get('historico-externo', [ExternoController::class, 'historico'])->name('externo.historico');
Route::get('/coordinador/historico', [CoordinadorController::class, 'historico'])->name('coordinadores.historico');

//rutas especificas del asesor Externo
Route::get('lista-de-proyectos',[ExternoController::class,'proyecto'])->middleware('auth')->name('externo.lista-de-proyectos');

//rutas especificas del coordinador
Route::resource('periodos',PeriodoController::class)->middleware('auth');
Route::resource('configuraciones',ConfiguracionController::class)->middleware('auth');
Route::post('/configuracion/interno', [ConfiguracionController::class, 'cambiarInterno'])
    ->name('configuracion.interno');

Route::resource('estudiantes',EstudianteController::class)->only(['create','store']);
Route::resource('estudiantes',EstudianteController::class)->except(['create','store'])->middleware('auth');
 Route::get('/estudiante/subir-evidencia', function () {
        return Inertia::render('estudiante/subir-evidencia');
    })->name('estudiante.subir-evidencia');
 

Route::get('/coordinador/sugerencias', [ProyectoController::class, 'sugerencias'])->name('coordinadores.sugerencias');
Route::get('/coordinador/sugerencias-proyecto', [ProyectoController::class, 'sugerenciasProyecto'])->name('coordinadores.sugerenciasProyecto');
Route::get('/coordinador/sugerencias-asesor', [ProyectoController::class, 'sugerenciasAsesor'])->name('coordinadores.sugerenciasAsesor');
Route::get('/coordinador/sugerencias-empresa', [ProyectoController::class, 'sugerenciasEmpresa'])->name('coordinadores.sugerenciasEmpresa');

Route::get('listar-estudiantes',[CoordinadorController::class,'estudiante'])->middleware('auth')->name('coordinador.listar-estudiantes');
Route::get('listar-asesores',[CoordinadorController::class,'asesores'])->middleware('auth')->name('coordinador.listar-asesores');


//rutas especificas de los segumientos (pueden entrar asesor, estudiante, coordinador)
//faltan dos tipos de Read en el crud
Route::get('realizar-seguimientos/{estudiante}/{consecutivo}',[SeguimientoController::class,'create'])->middleware('auth')->name('realizar-seguimientos'); //Crud
Route::post('guardar-seguimientos/{estudiante}/{consecutivo}',[SeguimientoController::class,'calificar'])->middleware('auth')->name('guardar-seguimientos'); //Crud

Route::get('modificar-seguimientos/{estudiante}/{consecutivo}',[SeguimientoController::class,'edit'])->name('modificar-seguimientos'); //crUd
Route::put('actualizar-seguimientos/{estudiante}/{consecutivo}',[SeguimientoController::class,'update'])->name('actualizar-seguimientos'); //crUd
//no implmentaremos el D puesto que es para borrar y no veo la necesidad de borrar un seguimiento

//rutas de los CRUD 
//Route::resource('actividades',ActividadController::class);
Route::resource('configuraciones',ConfiguracionController::class);
Route::resource('proyectos',ProyectoController::class);
Route::resource('periodos',PeriodoController::class);
//Route::resource('estudiantes',EstudianteController::class)->except(['show']);
Route::resource('parciales',ParcialController::class);

Route::resource('asesores',AsesorController::class)->except(['show'])->middleware('auth'); //esto es para asesores internos
Route::resource('externos',ExternoController::class)->except(['show','create'])->middleware('auth'); //esto es para asesores externos
Route::put('externos/crearcuenta/{externo}',[ExternoController::class,'crearCuenta'])->middleware('auth')->name('externos.crearcuenta');
Route::resource('carreras',CarreraController::class)->except(['show']);
Route::resource('empresas',EmpresaController::class);
Route::resource('usuarios',UsuarioController::class);
Route::resource('proyectos.actividades',ActividadController::class);

Route::get('proyectos/{proyecto}/actividades/{actividad}/reutilizar', [ActividadController::class, 'reutilizar'])->name('proyectos.actividades.reutilizar');
Route::post('proyectos/{proyecto}/actividades/{actividad}/reutilizar', [ActividadController::class, 'storeReutilizar'])->name('proyectos.actividades.storeReutilizar');


//rutas de vistas para aviso o Extras al CRUD
Route::get('/asesores/{pagina}', [AsesorController::class, 'mostrar'])->middleware('auth');
Route::get('/carreras/{pagina}', [CarreraController::class, 'mostrar']);
Route::get('/estudiantes/{pagina}', [EstudianteController::class, 'mostrar']);
Route::post('/generar-pdf', [EmpresaController::class,'generarPdf'])->name('generar.pdf');
Route::get('/generar-excel', [CoordinadorController::class,'exportarLista'])->name('generar-estudiantes.excel');
Route::get('/generando-excel', [CoordinadorController::class,'exportandoLista'])->name('generar-asesores.excel');
Route::get('/imprimir-excel', [CoordinadorController::class,'exportLista'])->name('imprimir-externos.excel');

//Para enviar correo
Route::get('/correo/{type}/{id}', [CorreoController::class, 'create'])->name('correo.create');
Route::post('/correo/enviar', [CorreoController::class, 'send'])->name('correo.send');





