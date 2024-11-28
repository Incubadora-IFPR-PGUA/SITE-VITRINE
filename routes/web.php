<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnilhaPendenteController;
use App\Http\Controllers\SmartHarpiaController;
use App\Http\Controllers\PhmetroController;

Route::get('/', function () {
    return view('main');
});

Route::get('/home', function () {
    return view('home');
})/*->middleware(['auth'])*/->name('home');
//Comentei para nao exigir autenticacao

Route::middleware('auth')->group(function () {
    //Colocar aqui depois as rotas que devem para ser acessadas estarem autenticado
});

// MacAddress
Route::resource('macaddress', 'App\Http\Controllers\SmartHarpiaController');
Route::get('/macaddress', 'App\Http\Controllers\SmartHarpiaController@index')->name('macaddress');
Route::get('/macaddressReload', 'App\Http\Controllers\SmartHarpiaController@macaddressReload');

// Horta
Route::resource('horta', 'App\Http\Controllers\SmartHortaController');
Route::get('/smarthorta', 'App\Http\Controllers\SmartHortaController@index')->name('smarthorta');
Route::get('/recarregarDadosHorta', 'App\Http\Controllers\SmartHortaController@recarregar');

// PHmetro
Route::resource('phmetro', 'App\Http\Controllers\PhmetroController');
Route::get('/phmetro', 'App\Http\Controllers\PhmetroController@index')->name('phmetro');
Route::get('/recarregarDadosPhmetro', 'App\Http\Controllers\PhmetroController@recarregar');

// Anilhas
Route::get('/cadastroReload', 'App\Http\Controllers\AnilhaCadastroController@reload');
Route::get('/registroReload', 'App\Http\Controllers\AnilhaRegistroController@reload');
Route::get('/pendenteReload', 'App\Http\Controllers\AnilhaPendenteController@reload');
Route::resource('cadastro', 'App\Http\Controllers\AnilhaCadastroController');
Route::resource('pendente', 'App\Http\Controllers\AnilhaPendenteController');
Route::resource('registro', 'App\Http\Controllers\AnilhaRegistroController');
Route::post('/aceitarPendente/{id}', 'App\Http\Controllers\AnilhaPendenteController@aceitarPendente');
Route::delete('/pendenteDelete/{id}', 'App\Http\Controllers\AnilhaPendenteController@destroy');
Route::delete('/cadastroDelete/{id}', 'App\Http\Controllers\AnilhaCadastroController@destroy');
Route::put('/cadastroUpdate/{id}', 'App\Http\Controllers\AnilhaCadastroController@update');

require __DIR__.'/auth.php';