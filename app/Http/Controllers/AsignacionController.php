<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\EstadoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    /**
     * Muestra la lista de asignaciones de habitaciones.
     */
    public function index(Request $request)
    {
        $query = Asignacion::with(['cliente', 'habitacion.tipoHabitacion', 'usuario']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->whereHas('cliente', function($qc) use ($buscar) {
                    $qc->where('documento', 'like', "%{$buscar}%")
                       ->orWhere('nombre', 'like', "%{$buscar}%")
                       ->orWhere('apellido', 'like', "%{$buscar}%");
                })->orWhereHas('habitacion', function($qh) use ($buscar) {
                    $qh->where('numero_habitacion', 'like', "%{$buscar}%");
                });
            });
        }

        $asignaciones = $query->orderBy('id_asignacion', 'desc')->get();

        $clientes = Cliente::orderBy('nombre')->get();

        // Habitaciones disponibles (o todas para editar)
        $habitacionesDisponibles = Habitacion::with('tipoHabitacion')
            ->whereHas('estadoHabitacion', function($q) {
                $q->where('nombre_estado', 'DISPONIBLE');
            })->get();

        $todasHabitaciones = Habitacion::with('tipoHabitacion')->get();

        return view('asignaciones.index', compact('asignaciones', 'clientes', 'habitacionesDisponibles', 'todasHabitaciones'));
    }

    /**
     * Registra una nueva asignación de habitación a un cliente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'    => 'required|exists:CLIENTE,id_cliente',
            'id_habitacion' => 'required|exists:HABITACION,id_habitacion',
            'fecha_ingreso' => 'required|date',
            'fecha_salida'  => 'nullable|date|after_or_equal:fecha_ingreso',
        ], [
            'id_cliente.required'    => 'Debe seleccionar un cliente.',
            'id_habitacion.required' => 'Debe seleccionar una habitación.',
            'fecha_ingreso.required' => 'La fecha y hora de ingreso es obligatoria.',
            'fecha_salida.after_or_equal' => 'La fecha de salida debe ser posterior o igual a la de ingreso.',
        ]);

        DB::beginTransaction();
        try {
            // Crear asignación con estado Activa
            Asignacion::create([
                'id_cliente'    => $request->id_cliente,
                'id_habitacion' => $request->id_habitacion,
                'id_usuario'    => Auth::id() ?? 1,
                'fecha_ingreso' => $request->fecha_ingreso,
                'fecha_salida'  => $request->fecha_salida,
                'estado'        => 'Activa',
            ]);

            // Cambiar el estado de la habitación a 'OCUPADA'
            $estadoOcupada = EstadoHabitacion::where('nombre_estado', 'OCUPADA')->first();
            if ($estadoOcupada) {
                $habitacion = Habitacion::find($request->id_habitacion);
                if ($habitacion) {
                    $habitacion->update(['id_estado_habitacion' => $estadoOcupada->id_estado_habitacion]);
                }
            }

            DB::commit();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación de habitación registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al registrar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza una asignación.
     */
    public function update(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $request->validate([
            'id_cliente'    => 'required|exists:CLIENTE,id_cliente',
            'id_habitacion' => 'required|exists:HABITACION,id_habitacion',
            'fecha_ingreso' => 'required|date',
            'fecha_salida'  => 'nullable|date|after_or_equal:fecha_ingreso',
            'estado'        => 'required|in:Activa,Finalizada,Cancelada',
        ]);

        DB::beginTransaction();
        try {
            $habitacionAnteriorId = $asignacion->id_habitacion;
            $nuevoEstado = $request->estado;

            $asignacion->update([
                'id_cliente'    => $request->id_cliente,
                'id_habitacion' => $request->id_habitacion,
                'fecha_ingreso' => $request->fecha_ingreso,
                'fecha_salida'  => $request->fecha_salida,
                'estado'        => $nuevoEstado,
            ]);

            // Ajustar estado de la habitación si cambió de habitación o de estado
            $estadoDisponible = EstadoHabitacion::where('nombre_estado', 'DISPONIBLE')->first();
            $estadoOcupada    = EstadoHabitacion::where('nombre_estado', 'OCUPADA')->first();
            $estadoLimpieza   = EstadoHabitacion::where('nombre_estado', 'EN LIMPIEZA')->first();

            if ($nuevoEstado === 'Finalizada') {
                // Si finaliza, pasa a EN LIMPIEZA o DISPONIBLE
                $hab = Habitacion::find($request->id_habitacion);
                if ($hab && ($estadoLimpieza || $estadoDisponible)) {
                    $hab->update(['id_estado_habitacion' => $estadoLimpieza ? $estadoLimpieza->id_estado_habitacion : $estadoDisponible->id_estado_habitacion]);
                }
            } elseif ($nuevoEstado === 'Cancelada') {
                // Si se cancela, la habitación vuelve a estar DISPONIBLE
                $hab = Habitacion::find($request->id_habitacion);
                if ($hab && $estadoDisponible) {
                    $hab->update(['id_estado_habitacion' => $estadoDisponible->id_estado_habitacion]);
                }
            } elseif ($nuevoEstado === 'Activa') {
                // Si sigue activa pero cambió de habitación
                if ($habitacionAnteriorId != $request->id_habitacion && $estadoDisponible) {
                    $habAnterior = Habitacion::find($habitacionAnteriorId);
                    if ($habAnterior) {
                        $habAnterior->update(['id_estado_habitacion' => $estadoDisponible->id_estado_habitacion]);
                    }
                }
                $habNueva = Habitacion::find($request->id_habitacion);
                if ($habNueva && $estadoOcupada) {
                    $habNueva->update(['id_estado_habitacion' => $estadoOcupada->id_estado_habitacion]);
                }
            }

            DB::commit();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al actualizar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Finaliza rápidamente una asignación (Check-out).
     */
    public function finalizar($id)
    {
        $asignacion = Asignacion::findOrFail($id);

        DB::beginTransaction();
        try {
            $asignacion->update([
                'estado'       => 'Finalizada',
                'fecha_salida' => now(),
            ]);

            // Poner la habitación en limpieza
            $estadoLimpieza   = EstadoHabitacion::where('nombre_estado', 'EN LIMPIEZA')->first();
            $estadoDisponible = EstadoHabitacion::where('nombre_estado', 'DISPONIBLE')->first();
            $hab = Habitacion::find($asignacion->id_habitacion);
            if ($hab) {
                $hab->update([
                    'id_estado_habitacion' => $estadoLimpieza ? $estadoLimpieza->id_estado_habitacion : ($estadoDisponible ? $estadoDisponible->id_estado_habitacion : $hab->id_estado_habitacion)
                ]);
            }

            DB::commit();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación finalizada con éxito. Habitación lista para limpieza.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al finalizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una asignación.
     */
    public function destroy($id)
    {
        try {
            $asignacion = Asignacion::findOrFail($id);
            
            // Si estaba activa, liberar la habitación
            if ($asignacion->estado === 'Activa') {
                $estadoDisponible = EstadoHabitacion::where('nombre_estado', 'DISPONIBLE')->first();
                $hab = Habitacion::find($asignacion->id_habitacion);
                if ($hab && $estadoDisponible) {
                    $hab->update(['id_estado_habitacion' => $estadoDisponible->id_estado_habitacion]);
                }
            }

            $asignacion->delete();

            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
        }
    }
}
