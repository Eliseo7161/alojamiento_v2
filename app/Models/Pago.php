<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'PAGO';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'fecha_pago',
        'monto',
        'metodo_pago',
        'tipo_transaccion',
        'id_asignacion',
        'id_venta',
        'id_compra',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto'      => 'decimal:2',
    ];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion', 'id_asignacion');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
