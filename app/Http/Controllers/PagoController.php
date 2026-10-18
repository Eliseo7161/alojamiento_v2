<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['usuario', 'venta.cliente', 'compra.proveedor', 'asignacion.habitacion', 'asignacion.cliente'])
            ->orderBy('id_pago', 'desc')
            ->get();

        $ingresosTotal = Pago::where('tipo_transaccion', 'INGRESO')->sum('monto');
        $egresosTotal  = Pago::where('tipo_transaccion', 'EGRESO')->sum('monto');
        $balance       = $ingresosTotal - $egresosTotal;

        $asignacionesActivas = Asignacion::with(['cliente', 'habitacion'])->where('estado', 'Activa')->get();

        return view('pagos.index', compact('pagos', 'ingresosTotal', 'egresosTotal', 'balance', 'asignacionesActivas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_pago'       => 'required|date',
            'monto'            => 'required|numeric|min:0.01',
            'metodo_pago'      => 'required|string|max:50',
            'tipo_transaccion' => 'required|in:INGRESO,EGRESO',
            'id_asignacion'    => 'nullable|exists:ASIGNACION,id_asignacion',
            'observacion'      => 'nullable|string',
        ]);

        Pago::create([
            'fecha_pago'       => $request->fecha_pago,
            'monto'            => $request->monto,
            'metodo_pago'      => $request->metodo_pago,
            'tipo_transaccion' => $request->tipo_transaccion,
            'id_asignacion'    => $request->id_asignacion,
            'id_usuario'       => Auth::id() ?? 1,
        ]);

        return redirect()->route('pagos.index')->with('success', 'Movimiento de caja registrado exitosamente.');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Registro de pago/movimiento eliminado.');
    }
}
