<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'STOCK';
    protected $primaryKey = 'id_stock';

    protected $fillable = [
        'cantidad_actual',
        'cantidad_minima',
        'id_producto',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
