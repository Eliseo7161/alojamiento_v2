<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'VENTA';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'fecha_venta',
        'total',
        'id_usuario',
        'id_cliente',
        'id_asignacion',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'total'       => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion', 'id_asignacion');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_venta', 'id_venta');
    }
}
