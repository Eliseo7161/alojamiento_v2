<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $table = 'HABITACION';
    protected $primaryKey = 'id_habitacion';

    protected $fillable = [
        'numero_habitacion',
        'piso',
        'id_tipo_habitacion',
        'id_estado_habitacion',
    ];

    protected $casts = [
        'piso' => 'integer',
        'id_tipo_habitacion' => 'integer',
        'id_estado_habitacion' => 'integer',
    ];

    // Relación con Tipo de Habitación
    public function tipoHabitacion()
    {
        return $this->belongsTo(TipoHabitacion::class, 'id_tipo_habitacion', 'id_tipo_habitacion');
    }

    // Relación con Estado de Habitación
    public function estadoHabitacion()
    {
        return $this->belongsTo(EstadoHabitacion::class, 'id_estado_habitacion', 'id_estado_habitacion');
    }
}
