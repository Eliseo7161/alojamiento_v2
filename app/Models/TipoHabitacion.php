<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    use HasFactory;

    protected $table = 'TIPO_HABITACION';
    protected $primaryKey = 'id_tipo_habitacion';

    protected $fillable = [
        'nombre_tipo',
        'precio_noche',
        'capacidad',
    ];

    protected $casts = [
        'precio_noche' => 'decimal:2',
        'capacidad'    => 'integer',
    ];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'id_tipo_habitacion', 'id_tipo_habitacion');
    }
}
