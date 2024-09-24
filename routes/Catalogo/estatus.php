<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Catalogo\Estatus;

Route::post('Catalogo/estatus', [Estatus::class,'getRegistro'])->middleware(['sesionactiva','Validadpermisos']);
Route::post('Catalogo/estatus/comprementos', [Estatus::class,'getcompremento'])->middleware(['sesionactiva']);

Auth::routes();