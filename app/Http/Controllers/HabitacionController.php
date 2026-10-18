<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\EstadoHabitacion;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    /**
     * Muestra la lista de habitaciones con sus relaciones.
     */
    public function index(Request $request)
    {
        $query = Habitacion::with(['tipoHabitacion', 'estadoHabitacion']);

        // Filtro opcional por piso
        if ($request->filled('piso')) {
            $query->where('piso', $request->piso);
        }

        // Filtro opcional por tipo
        if ($request->filled('tipo_id')) {
            $query->where('id_tipo_habitacion', $request->tipo_id);
        }

        // Filtro opcional por estado
        if ($request->filled('estado_id')) {
            $query->where('id_estado_habitacion', $request->estado_id);
        }

        $habitaciones = $query->orderBy('piso', 'asc')
                              ->orderBy('numero_habitacion', 'asc')
                              ->get();

        $tipos = TipoHabitacion::orderBy('nombre_tipo')->get();
        $estados = EstadoHabitacion::all();

        // Resumen de estadísticas para tarjetas superiores
        $totalHabitaciones = Habitacion::count();
        $totalDisponibles  = Habitacion::whereHas('estadoHabitacion', function($q) {
            $q->where('nombre_estado', 'DISPONIBLE');
        })->count();
        $totalOcupadas     = Habitacion::whereHas('estadoHabitacion', function($q) {
            $q->where('nombre_estado', 'OCUPADA');
        })->count();
        $totalLimpieza     = Habitacion::whereHas('estadoHabitacion', function($q) {
            $q->whereIn('nombre_estado', ['EN LIMPIEZA', 'MANTENIMIENTO']);
        })->count();

        return view('habitaciones.index', compact(
            'habitaciones',
            'tipos',
            'estados',
            'totalHabitaciones',
            'totalDisponibles',
            'totalOcupadas',
            'totalLimpieza'
        ));
    }

    /**
     * Registra una nueva habitación.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_habitacion'    => 'required|string|max:10|unique:HABITACION,numero_habitacion',
            'piso'                 => 'required|integer|min:1|max:50',
            'id_tipo_habitacion'   => 'required|exists:TIPO_HABITACION,id_tipo_habitacion',
            'id_estado_habitacion' => 'required|exists:ESTADO_HABITACION,id_estado_habitacion',
        ], [
            'numero_habitacion.unique' => 'El número de habitación ya se encuentra registrado.',
            'id_tipo_habitacion.exists' => 'El tipo de habitación seleccionado no es válido.',
            'id_estado_habitacion.exists' => 'El estado seleccionado no es válido.',
        ]);

        try {
            Habitacion::create([
                'numero_habitacion'    => trim($request->numero_habitacion),
                'piso'                 => $request->piso,
                'id_tipo_habitacion'   => $request->id_tipo_habitacion,
                'id_estado_habitacion' => $request->id_estado_habitacion,
            ]);

            return redirect()->route('habitaciones.index')
                ->with('success', 'Habitación registrada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('habitaciones.index')
                ->with('error', 'Error al registrar la habitación: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la información de una habitación.
     */
    public function update(Request $request, Habitacion $habitacione)
    {
        // En Laravel resource 'habitaciones', el parámetro por defecto es 'habitacione'
        $request->validate([
            'numero_habitacion'    => 'required|string|max:10|unique:HABITACION,numero_habitacion,' . $habitacione->id_habitacion . ',id_habitacion',
            'piso'                 => 'required|integer|min:1|max:50',
            'id_tipo_habitacion'   => 'required|exists:TIPO_HABITACION,id_tipo_habitacion',
            'id_estado_habitacion' => 'required|exists:ESTADO_HABITACION,id_estado_habitacion',
        ], [
            'numero_habitacion.unique' => 'El número de habitación ya está en uso.',
        ]);

        try {
            $habitacione->update([
                'numero_habitacion'    => trim($request->numero_habitacion),
                'piso'                 => $request->piso,
                'id_tipo_habitacion'   => $request->id_tipo_habitacion,
                'id_estado_habitacion' => $request->id_estado_habitacion,
            ]);

            return redirect()->route('habitaciones.index')
                ->with('success', 'Habitación actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('habitaciones.index')
                ->with('error', 'Error al actualizar la habitación: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una habitación.
     */
    public function destroy($id)
    {
        try {
            $habitacion = Habitacion::findOrFail($id);
            $habitacion->delete();

            return redirect()->route('habitaciones.index')
                ->with('success', 'Habitación eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('habitaciones.index')
                ->with('error', 'No se puede eliminar la habitación: ' . $e->getMessage());
        }
    }

    /**
     * Cambio rápido de estado desde la vista.
     */
    public function cambiarEstado(Request $request, Habitacion $habitacione)
    {
        $request->validate([
            'id_estado_habitacion' => 'required|exists:ESTADO_HABITACION,id_estado_habitacion',
        ]);

        try {
            $habitacione->update([
                'id_estado_habitacion' => $request->id_estado_habitacion,
            ]);

            return redirect()->route('habitaciones.index')
                ->with('success', 'Estado de la habitación ' . $habitacione->numero_habitacion . ' modificado.');
        } catch (\Exception $e) {
            return redirect()->route('habitaciones.index')
                ->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }
}
