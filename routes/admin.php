<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ExoneracionController;
use App\Http\Controllers\ModalidadController;
use App\Http\Controllers\MotivoExoneracionController;
use App\Http\Controllers\PerfilEmpleadoController;
use App\Http\Controllers\RecorteController;
use App\Http\Controllers\RemuneracionController;
use App\Http\Controllers\TipoContratoController;
use App\Http\Controllers\TipoRecorteController;
use App\Http\Controllers\UserController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');



    /**
     * El código define las rutas para un controlador de categorías en una aplicación Laravel
     */

    Route::get('/areas', [AreaController::class, 'index'])->name('areas');
    Route::post('/areas/data', [AreaController::class, 'getData'])->name('areas.data');
    Route::post('/areas/search', [AreaController::class, 'search'])->name('areas.search');
    Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::put('/areas/{id}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{id}', [AreaController::class, 'destroy'])->name('areas.destroy');
    Route::post('areas/import', [AreaController::class, 'import'])->name('areas.import');

    /**
     * El código define las rutas para un controlador de asistencia en una aplicación Laravel
     */

    Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias');
    Route::post('/asistencias/data', [AsistenciaController::class, 'getData'])->name('asistencias.data');
    Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
    Route::put('/asistencias/{id}', [AsistenciaController::class, 'update'])->name('asistencias.update');
    Route::delete('/asistencias/{id}', [AsistenciaController::class, 'destroy'])->name('asistencias.destroy');
    Route::post('asistencias/import', [AsistenciaController::class, 'import'])->name('asistencias.import');
    /**
     * El código define las rutas para un controlador de cargos en una aplicación Laravel
     */

    Route::get('/cargos', [CargoController::class, 'index'])->name('cargos');
    Route::post('/cargos/data', [CargoController::class, 'getData'])->name('cargos.data');
    Route::post('/cargos', [CargoController::class, 'store'])->name('cargos.store');
    Route::put('/cargos/{id}', [CargoController::class, 'update'])->name('cargos.update');
    Route::delete('/cargos/{id}', [CargoController::class, 'destroy'])->name('cargos.destroy');

    /**Dashboard
     * El código define las rutas para un controlador de contratos en una aplicación Laravel
     */

    Route::get('/contratos', [ContratoController::class, 'index'])->name('contratos');
    Route::post('/contratos/data', [ContratoController::class, 'getData'])->name('contratos.data');
    Route::post('/contratos/search', [ContratoController::class, 'search'])->name('contratos.search');
    Route::post('/contratos', [ContratoController::class, 'store'])->name('contratos.store');
    Route::put('/contratos/{id}', [ContratoController::class, 'update'])->name('contratos.update');
    Route::delete('/contratos/{id}', [ContratoController::class, 'destroy'])->name('contratos.destroy');

    /**
     * El código define las rutas para un controlador de empleados en una aplicación Laravel
     */

    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados');
    Route::post('/empleados/data', [EmpleadoController::class, 'getData'])->name('empleados.data');
    Route::post('/empleados/search', [EmpleadoController::class, 'search'])->name('empleados.search');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

    /**
     * El código define las rutas para un controlador de exoneraciones en una aplicación Laravel
     */

    Route::get('/exoneraciones', [ExoneracionController::class, 'index'])->name('exoneraciones');
    Route::post('/exoneraciones/data', [ExoneracionController::class, 'getData'])->name('exoneraciones.data');
    Route::post('/exoneraciones', [ExoneracionController::class, 'store'])->name('exoneraciones.store');
    Route::put('/exoneraciones/{id}', [ExoneracionController::class, 'update'])->name('exoneraciones.update');
    Route::delete('/exoneraciones/{id}', [ExoneracionController::class, 'destroy'])->name('exoneraciones.destroy');

    /**
     * El código define las rutas para un controlador de modalidades en una aplicación Laravel
     */

    Route::get('/modalidades', [ModalidadController::class, 'index'])->name('modalidades');
    Route::post('/modalidades/data', [ModalidadController::class, 'getData'])->name('modalidades.data');
    Route::post('/modalidades/search', [ModalidadController::class, 'search'])->name('modalidades.search');
    Route::post('/modalidades', [ModalidadController::class, 'store'])->name('modalidades.store');
    Route::put('/modalidades/{id}', [ModalidadController::class, 'update'])->name('modalidades.update');
    Route::delete('/modalidades/{id}', [ModalidadController::class, 'destroy'])->name('modalidades.destroy');
    /**
     * El código define las rutas para un controlador de motivo-exoneraciones en una aplicación Laravel
     */

    Route::get('/motivo-de-exoneraciones', [MotivoExoneracionController::class, 'index'])->name('motivo-exoneraciones');
    Route::post('/motivo-de-exoneraciones/data', [MotivoExoneracionController::class, 'getData'])->name('motivo-exoneraciones.data');
    Route::post('/motivo-de-exoneraciones/search', [MotivoExoneracionController::class, 'search'])->name('motivo-exoneraciones.search');
    Route::post('/motivo-de-exoneraciones', [MotivoExoneracionController::class, 'store'])->name('motivo-exoneraciones.store');
    Route::put('/motivo-de-exoneraciones/{id}', [MotivoExoneracionController::class, 'update'])->name('motivo-exoneraciones.update');
    Route::delete('/motivo-de-exoneraciones/{id}', [MotivoExoneracionController::class, 'destroy'])->name('motivo-exoneraciones.destroy');
    /**
     * El código define las rutas para un controlador de perfil-empleados en una aplicación Laravel
     */

    Route::get('/perfil-de-empleados', [PerfilEmpleadoController::class, 'index'])->name('perfil-empleados');
    Route::post('/perfil-de-empleados/data', [PerfilEmpleadoController::class, 'getData'])->name('perfil-empleados.data');
    Route::post('/perfil-de-empleados', [PerfilEmpleadoController::class, 'store'])->name('perfil-empleados.store');
    Route::put('/perfil-de-empleados/{id}', [PerfilEmpleadoController::class, 'update'])->name('perfil-empleados.update');
    Route::delete('/perfil-de-empleados/{id}', [PerfilEmpleadoController::class, 'destroy'])->name('perfil-empleados.destroy');
    /**
     * El código define las rutas para un controlador de recortes en una aplicación Laravel
     */

    Route::get('/recortes', [RecorteController::class, 'index'])->name('recortes');
    Route::post('/recortes/data', [RecorteController::class, 'getData'])->name('recortes.data');
    Route::post('/recortes', [RecorteController::class, 'store'])->name('recortes.store');
    Route::post('/recortes/search', [RecorteController::class, 'search'])->name('recortes.search');
    Route::put('/recortes/{id}', [RecorteController::class, 'update'])->name('recortes.update');
    Route::delete('/recortes/{id}', [RecorteController::class, 'destroy'])->name('recortes.destroy');
    /**
     * El código define las rutas para un controlador de remuneraciones en una aplicación Laravel
     */

    Route::get('/remuneraciones', [RemuneracionController::class, 'index'])->name('remuneraciones');
    Route::post('/remuneraciones/data', [RemuneracionController::class, 'getData'])->name('remuneraciones.data');
    Route::post('/remuneraciones', [RemuneracionController::class, 'store'])->name('remuneraciones.store');
    Route::put('/remuneraciones/{id}', [RemuneracionController::class, 'update'])->name('remuneraciones.update');
    Route::delete('/remuneraciones/{id}', [RemuneracionController::class, 'destroy'])->name('remuneraciones.destroy');
    Route::get('/remuneraciones/empleado/', [RemuneracionController::class, 'showCalendarDetails'])->name('calendario');
    // {empleado}
    /**
     * El código define las rutas para un controlador de tipo-contratos en una aplicación Laravel
     */

    Route::get('/tipo-de-contratos', [TipoContratoController::class, 'index'])->name('tipo-contratos');
    Route::post('/tipo-de-contratos/data', [TipoContratoController::class, 'getData'])->name('tipo-contratos.data');
    Route::post('/tipo-de-contratos/search', [TipoContratoController::class, 'search'])->name('tipo-contratos.search');
    Route::post('/tipo-de-contratos', [TipoContratoController::class, 'store'])->name('tipo-contratos.store');
    Route::put('/tipo-de-contratos/{id}', [TipoContratoController::class, 'update'])->name('tipo-contratos.update');
    Route::delete('/tipo-de-contratos/{id}', [TipoContratoController::class, 'destroy'])->name('tipo-contratos.destroy');
    /**
     * El código define las rutas para un controlador de tipo-recortes en una aplicación Laravel
     */

    Route::get('/tipo-de-recortes', [TipoRecorteController::class, 'index'])->name('tipo-recortes');
    Route::post('/tipo-de-recortes/data', [TipoRecorteController::class, 'getData'])->name('tipo-recortes.data');
    Route::post('/tipo-recortes/search', [TipoRecorteController::class, 'search'])->name('tipo-recortes.search');
    Route::post('/tipo-de-recortes', [TipoRecorteController::class, 'store'])->name('tipo-recortes.store');
    Route::put('/tipo-de-recortes/{id}', [TipoRecorteController::class, 'update'])->name('tipo-recortes.update');
    Route::delete('/tipo-de-recortes/{id}', [TipoRecorteController::class, 'destroy'])->name('tipo-recortes.destroy');

    /**
     * El código define las rutas para un controlador de USERS en una aplicación Laravel
     */

    //  Route::get('/usuarios', [AreaController::class, 'index'])->name('usuarios');
    Route::post('/usuarios/data', [UserController::class, 'getData'])->name('usuarios.data');
    // Route::post('/usuarios/search', [UserController::class, 'search'])->name('usuarios.search');
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});
