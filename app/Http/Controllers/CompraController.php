<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['proveedor', 'usuario', 'detalles.producto'])
            ->orderBy('id_compra', 'desc')
            ->get();
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $productos = Producto::with('categoria', 'stock')->orderBy('nombre_producto')->get();
        return view('compras.index', compact('compras', 'proveedores', 'productos'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $productos = Producto::with('categoria', 'stock')->orderBy('nombre_producto')->get();
        return view('compras.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor'     => 'required|exists:PROVEEDOR,id_proveedor',
            'fecha_compra'     => 'required|date',
            'metodo_pago'      => 'required|string|max:50',
            'productos'        => 'required|array|min:1',
            'productos.*.id'   => 'required|exists:PRODUCTO,id_producto',
            'productos.*.cant' => 'required|integer|min:1',
            'productos.*.costo'=> 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $totalCompra = 0;
            foreach ($request->productos as $item) {
                $totalCompra += ($item['cant'] * $item['costo']);
            }

            // 1. Guardar Compra
            $compra = Compra::create([
                'fecha_compra' => $request->fecha_compra,
                'total_compra' => $totalCompra,
                'id_proveedor' => $request->id_proveedor,
                'id_usuario'   => Auth::id() ?? 1,
            ]);

            // 2. Guardar Detalles e Incrementar Stock
            foreach ($request->productos as $item) {
                $subtotal = $item['cant'] * $item['costo'];
                DetalleCompra::create([
                    'id_compra'       => $compra->id_compra,
                    'id_producto'     => $item['id'],
                    'cantidad'        => $item['cant'],
                    'precio_unitario' => $item['costo'],
                    'subtotal'        => $subtotal,
                ]);

                // Aumentar inventario en tabla STOCK
                $stock = Stock::firstOrCreate(
                    ['id_producto' => $item['id']],
                    ['cantidad_actual' => 0, 'cantidad_minima' => 0]
                );
                $stock->increment('cantidad_actual', $item['cant']);
            }

            // 3. Registrar Pago de Salida (EGRESO)
            Pago::create([
                'fecha_pago'       => $request->fecha_compra,
                'monto'            => $totalCompra,
                'metodo_pago'      => $request->metodo_pago,
                'tipo_transaccion' => 'EGRESO',
                'id_compra'        => $compra->id_compra,
                'id_usuario'       => Auth::id() ?? 1,
            ]);

            DB::commit();
            return redirect()->route('compras.index')->with('success', "Compra #{$compra->id_compra} registrada exitosamente y stock actualizado.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar la compra: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'usuario', 'detalles.producto.categoria', 'pagos'])->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function destroy($id)
    {
        $compra = Compra::with('detalles')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revertir el stock incrementado
            foreach ($compra->detalles as $detalle) {
                $stock = Stock::where('id_producto', $detalle->id_producto)->first();
                if ($stock) {
                    $stock->decrement('cantidad_actual', min($stock->cantidad_actual, $detalle->cantidad));
                }
            }

            // Eliminar pago asociado
            Pago::where('id_compra', $compra->id_compra)->delete();

            // Eliminar compra y sus detalles (por cascada)
            $compra->delete();

            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Compra anulada y stock revertido.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('compras.index')->with('error', 'Error al anular la compra: ' . $e->getMessage());
        }
    }
}
