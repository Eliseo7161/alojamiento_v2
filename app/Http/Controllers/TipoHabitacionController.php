<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use Illuminate\Http\Request;

class TipoHabitacionController extends Controller
{
    /**
     * Muestra la lista de tipos de habitación.
     */
    public function index()
    {
        $tipos = TipoHabitacion::withCount('habitaciones')
            ->orderBy('id_tipo_habitacion', 'asc')
            ->get();

        return view('tipos-habitacion.index', compact('tipos'));
    }

    /**
     * Registra un nuevo tipo de habitación.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_tipo'  => 'required|string|max:50|unique:TIPO_HABITACION,nombre_tipo',
            'precio_noche' => 'required|numeric|min:0',
            'capacidad'    => 'required|integer|min:1|max:50',
        ], [
            'nombre_tipo.required' => 'El nombre del tipo de habitación es obligatorio.',
            'nombre_tipo.unique'   => 'Este tipo de habitación ya existe.',
            'precio_noche.numeric' => 'El precio debe ser un número válido.',
            'capacidad.min'        => 'La capacidad mínima debe ser 1 persona.',
        ]);

        try {
            TipoHabitacion::create([
                'nombre_tipo'  => trim($request->nombre_tipo),
                'precio_noche' => $request->precio_noche,
                'capacidad'    => $request->capacidad,
            ]);

            return redirect()->route('tipos-habitacion.index')
                ->with('success', 'Tipo de habitación creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('tipos-habitacion.index')
                ->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza un tipo de habitación.
     */
    public function update(Request $request, $id)
    {
        $tipo = TipoHabitacion::findOrFail($id);

        $request->validate([
            'nombre_tipo'  => 'required|string|max:50|unique:TIPO_HABITACION,nombre_tipo,' . $tipo->id_tipo_habitacion . ',id_tipo_habitacion',
            'precio_noche' => 'required|numeric|min:0',
            'capacidad'    => 'required|integer|min:1|max:50',
        ], [
            'nombre_tipo.unique' => 'Ya existe un tipo de habitación con ese nombre.',
        ]);

        try {
            $tipo->update([
                'nombre_tipo'  => trim($request->nombre_tipo),
                'precio_noche' => $request->precio_noche,
                'capacidad'    => $request->capacidad,
            ]);

            return redirect()->route('tipos-habitacion.index')
                ->with('success', 'Tipo de habitación actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('tipos-habitacion.index')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un tipo de habitación si no tiene habitaciones vinculadas.
     */
    public function destroy($id)
    {
        $tipo = TipoHabitacion::findOrFail($id);

        try {
            if ($tipo->habitaciones()->count() > 0) {
                return redirect()->route('tipos-habitacion.index')
                    ->with('error', "No se puede eliminar '{$tipo->nombre_tipo}' porque tiene habitaciones asociadas.");
            }

            $tipo->delete();

            return redirect()->route('tipos-habitacion.index')
                ->with('success', 'Tipo de habitación eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('tipos-habitacion.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
