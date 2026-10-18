<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoHabitacion extends Model
{
    use HasFactory;

    protected $table = 'ESTADO_HABITACION';
    protected $primaryKey = 'id_estado_habitacion';

    protected $fillable = [
        'nombre_estado',
        'descripcion',
        'color_hex',
    ];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'id_estado_habitacion', 'id_estado_habitacion');
    }
}
