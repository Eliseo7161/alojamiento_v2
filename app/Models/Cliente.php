<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'CLIENTE';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'documento',
        'nombre',
        'apellido',
        'telefono',
        'email',
    ];

    // Nombre completo accesorio
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    // Relación con Asignaciones
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_cliente', 'id_cliente');
    }
}
