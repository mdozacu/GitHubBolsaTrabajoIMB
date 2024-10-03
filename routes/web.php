<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DNI\DNIConsultaController;
use App\Http\Controllers\Representante\RepresentanteController;
use App\Http\Controllers\Categoria\CategoriaController;
use App\Http\Controllers\Ofertas\OfertasController;


// !!!!!!! POR DEFECTO !!!!!!!
// Grupo de rutas protegidas por autenticación
Route::group(['middleware' => 'auth'], function () {

    // Rutas del RoutingController
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('/home', fn() => view('index'))->name('home');
Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
Route::get('{any}', [RoutingController::class, 'root'])->name('any');



//!!!!!!! CATEGORÍAS  !!!!!!!
    //Mostrar Formulario de registro de Categorías    
    Route::get('/categoria/agregar', fn () => view('Categoria.agregar'))->name('categoria.agregar');
    //Ruta Registrar Categorías
    Route::post('registrar-categoria', [CategoriaController::class, 'registrarCategoria'])->name('categoria.registrar');



//!!!!!!! EMPRESAS  !!!!!!!
    // Mostrar formulario de registro de empresas
    Route::get('/empresa/register', fn () => view('empresa.register'))->name('empresa.register');
    // Ruta para autocompletar datos mediante API
    //Route::post('/empresa/autoComplete', [DNIConsultaController::class, 'autoComplete'])->name('empresa.autoComplete');
    // Ruta para registrar la empresa
    Route::post('/registrar-empresa', [DNIConsultaController::class, 'registrarEmpresa'])->name('empresa.registrar');
    // Ruta para mostrar mensaje de éxito después del registro
    Route::get('/empresa/success', fn () => 'Registro exitoso!')->name('empresa.success');



//!!!!!!! REPRESENTANTE  !!!!!!!
    // /representante/agregar.blade.php
    //Mostrar formulario de representante
    Route::get('/representante/agregar',fn()=> view('representante.agregar'))->name('representante.agregar');


    //--- RUTAS REPRESENTANTE
    //Route::get('/representante/agregar', function () {
    //    return view('Representante.agregar_representante');
    //})->name('representante.agregar');
    Route::post('registrar-representante', [RepresentanteController::class, 'registrarRepresentante'])->name('representante.registrar');





    //Prueba Profile
    //Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::get('/profile', [ProfileController::class, 'profile.edit'])->name('profile.edit');
    //Route::get('/profile', [ProfileController::class, 'profile.edit'])->name('profile.edit');

    // Rutas para perfil
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //--- RUTAS EMPRESA
    //Route::get('/empresa/register', function () {
    //    return view('empresa.register');
    //})->name('empresa.register');
    //-Route::post('/empresa/autoComplete', [DNIConsultaController::class, 'autoComplete'])->name('empresa.autoComplete');
    //-Route::post('/registrar-empresa', [DNIConsultaController::class, 'registrarEmpresa'])->name('empresa.registrar');
    //-Route::get('/empresa/success', function () {
    //-    return 'Registro exitoso!';
    //-})->name('empresa.success');

    //--- RUTAS REPRESENTANTE
    //Route::get('/representante/agregar', function () {
    //    return view('Representante.agregar_representante');
    //})->name('representante.agregar');
    Route::post('registrar-representante', [RepresentanteController::class, 'registrarRepresentante'])->name('representante.registrar');

    //--- RUTAS CATEGORIAS
    //Route::get('/categoria/agregar', function () {
    //    return view('Categoria.agregar_categoria');
    //})->name('ategoria.agregar');
    //Route::post('registrar-categoria', [CategoriaController::class, 'registrarCategoria'])->name('categoria.registrar');

    //--- RUTAS OFERTAS
    Route::get('/ofertas/agregar', [OfertasController::class, 'create'])->name('oferta.agregar');
    Route::post('registrar-oferta', [OfertasController::class, 'store'])->name('ofertas.registrar');
});


// Página de bienvenida (no requiere autenticación)
Route::get('/', function () {
    return view('auth.login');
});
// Archivo de autenticación
require __DIR__ . '/auth.php';
