<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\Cliente;
use App\Models\Asignacion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'asignacion.habitacion', 'usuario', 'detalles.producto'])
            ->orderBy('id_venta', 'desc')
            ->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $asignacionesActivas = Asignacion::with(['cliente', 'habitacion'])
            ->where('estado', 'Activa')
            ->get();
        $productos = Producto::with('stock')
            ->whereHas('stock', function ($query) {
                $query->where('cantidad_actual', '>', 0);
            })
            ->orderBy('nombre_producto')
            ->get();
        return view('ventas.index', compact('ventas', 'clientes', 'asignacionesActivas', 'productos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $asignacionesActivas = Asignacion::with(['cliente', 'habitacion'])
            ->where('estado', 'Activa')
            ->get();

        // Solo productos con stock disponible
        $productos = Producto::with('stock')
            ->whereHas('stock', function ($query) {
                $query->where('cantidad_actual', '>', 0);
            })
            ->orderBy('nombre_producto')
            ->get();

        return view('ventas.create', compact('clientes', 'asignacionesActivas', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_venta'        => 'required|in:publico,huesped',
            'id_cliente'        => 'nullable|exists:CLIENTE,id_cliente',
            'id_asignacion'     => 'nullable|exists:ASIGNACION,id_asignacion',
            'fecha_venta'       => 'required|date',
            'metodo_pago'       => 'required|string|max:50',
            'productos'         => 'required|array|min:1',
            'productos.*.id'    => 'required|exists:PRODUCTO,id_producto',
            'productos.*.cant'  => 'required|integer|min:1',
            'productos.*.precio'=> 'required|numeric|min:0.01',
        ]);

        // Si es a huésped, asociamos el cliente de la asignación
        $idCliente = $request->id_cliente;
        $idAsignacion = null;

        if ($request->tipo_venta === 'huesped' && $request->id_asignacion) {
            $asignacion = Asignacion::find($request->id_asignacion);
            if ($asignacion) {
                $idAsignacion = $asignacion->id_asignacion;
                $idCliente = $asignacion->id_cliente;
            }
        }

        DB::beginTransaction();
        try {
            $totalVenta = 0;
            // Validar stock antes de vender
            foreach ($request->productos as $item) {
                $stock = Stock::where('id_producto', $item['id'])->first();
                if (!$stock || $stock->cantidad_actual < $item['cant']) {
                    $prod = Producto::find($item['id']);
                    $nombreProd = $prod ? $prod->nombre_producto : 'Producto';
                    throw new \Exception("Stock insuficiente para: {$nombreProd}. Disponible: " . ($stock ? $stock->cantidad_actual : 0));
                }
                $totalVenta += ($item['cant'] * $item['precio']);
            }

            // 1. Guardar Venta
            $venta = Venta::create([
                'fecha_venta'   => $request->fecha_venta,
                'total'         => $totalVenta,
                'id_usuario'    => Auth::id() ?? 1,
                'id_cliente'    => $idCliente,
                'id_asignacion' => $idAsignacion,
            ]);

            // 2. Guardar Detalles y Descontar Stock
            foreach ($request->productos as $item) {
                $subtotal = $item['cant'] * $item['precio'];
                DetalleVenta::create([
                    'id_venta'        => $venta->id_venta,
                    'id_producto'     => $item['id'],
                    'cantidad'        => $item['cant'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $subtotal,
                ]);

                // Descontar inventario
                $stock = Stock::where('id_producto', $item['id'])->first();
                $stock->decrement('cantidad_actual', $item['cant']);
            }

            // 3. Registrar Pago (INGRESO)
            Pago::create([
                'fecha_pago'       => $request->fecha_venta,
                'monto'            => $totalVenta,
                'metodo_pago'      => $request->metodo_pago,
                'tipo_transaccion' => 'INGRESO',
                'id_venta'         => $venta->id_venta,
                'id_asignacion'    => $idAsignacion,
                'id_usuario'       => Auth::id() ?? 1,
            ]);

            DB::commit();
            return redirect()->route('ventas.index')->with('success', "Venta #{$venta->id_venta} registrada exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['cliente', 'asignacion.habitacion', 'usuario', 'detalles.producto.categoria', 'pagos'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function destroy($id)
    {
        $venta = Venta::with('detalles')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revertir y reponer stock de productos
            foreach ($venta->detalles as $detalle) {
                $stock = Stock::where('id_producto', $detalle->id_producto)->first();
                if ($stock) {
                    $stock->increment('cantidad_actual', $detalle->cantidad);
                }
            }

            // Eliminar pago asociado
            Pago::where('id_venta', $venta->id_venta)->delete();

            // Eliminar venta
            $venta->delete();

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta anulada y stock retornado a inventario.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ventas.index')->with('error', 'Error al anular la venta: ' . $e->getMessage());
        }
    }
}
