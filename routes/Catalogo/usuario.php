<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Catalogo\Usuario;

Route::post('catalogo/usuario', [Usuario::class,'getRegistro'])->middleware(['sesionactiva','Validadpermisos']);
Route::post('Catalogo/usuario/detalles', [Usuario::class,'getDetalles'])->middleware(['sesionactiva']);
Route::post('catalogo/usuario/registrar', [Usuario::class,'postRegistro'])->middleware('sesionactiva','Validadpermisos'); 
Route::post('catalogo/usuario/eliminar', [Usuario::class,'postEliminar'])->middleware('sesionactiva','Validadpermisos'); 


Auth::routes();