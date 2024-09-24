<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'catusuarios';

    // Llave primaria de la tabla
    protected $primaryKey = 'ecodUsuario';

    // Si no utilizas timestamps en esta tabla, puedes desactivarlo
    public $timestamps = false;

    // Campos que pueden ser rellenados masivamente (fillable)
    protected $fillable = [
        'tNombre',
        'tApellido',
        'tCRUP',
        'tRFC',
        'ecodEstatus',
        'ecodCreacion',
        'ecodEdicion',
        'nEdad',
        'nTelefono',
        'tSexo',
        'fhCreacion',
        'fhEdicion',
        'fhNacimiento'
    ];

    // Relaciones con otros modelos, si es que existen
    // Relación con el modelo 'Estatus'
    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'ecodEstatus', 'EcodEstatus');
    }

    // Relación con el usuario creador
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'ecodCreacion', 'ecodUsuario');
    }

    // Relación con el usuario que editó
    public function editor()
    {
        return $this->belongsTo(Usuario::class, 'ecodEdicion', 'ecodUsuario');
    }
}
