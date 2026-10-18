<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'ASIGNACION';
    protected $primaryKey = 'id_asignacion';

    protected $fillable = [
        'fecha_ingreso',
        'fecha_salida',
        'estado',
        'id_cliente',
        'id_habitacion',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'fecha_salida' => 'datetime',
        'id_cliente' => 'integer',
        'id_habitacion' => 'integer',
        'id_usuario' => 'integer',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // Relación con Habitación
    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion', 'id_habitacion');
    }

    // Relación con Usuario recepcionista
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
