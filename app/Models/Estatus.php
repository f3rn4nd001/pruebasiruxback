<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;
    
    protected $table = 'catestatus';
    protected $primaryKey = 'EcodEstatus';
    public $timestamps = false;

    protected $fillable = [
        'tNombre',
    ];
}
