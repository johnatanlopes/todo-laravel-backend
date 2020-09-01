<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'AuthController@login');
Route::post('/me', 'AuthController@me');
Route::post('/logout', 'AuthController@logout');
Route::post('/refresh', 'AuthController@refresh');

Route::get('/tarefas', 'TarefaController@index');
Route::post('/tarefa', 'TarefaController@store');
Route::put('/tarefa/{id}', 'TarefaController@closeTask');
Route::delete('/tarefa/{id}', 'TarefaController@destroy');

Route::get('/marcadores', 'MarcadorController@index');
