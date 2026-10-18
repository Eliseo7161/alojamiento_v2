<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'COMPRA';
    protected $primaryKey = 'id_compra';

    protected $fillable = [
        'fecha_compra',
        'total_compra',
        'id_proveedor',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_compra' => 'datetime',
        'total_compra' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id_compra');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_compra', 'id_compra');
    }
}
