<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Producto;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['producto.categoria'])->get();
        $proveedores = \App\Models\Proveedor::orderBy('razon_social')->get();
        $productos = \App\Models\Producto::with('categoria', 'stock')->orderBy('nombre_producto')->get();
        return view('stock.index', compact('stocks', 'proveedores', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $request->validate([
            'cantidad_actual' => 'required|integer|min:0',
            'cantidad_minima' => 'required|integer|min:0',
        ]);

        $stock->update([
            'cantidad_actual' => $request->cantidad_actual,
            'cantidad_minima' => $request->cantidad_minima,
        ]);

        return redirect()->route('stock.index')->with('success', 'Nivel de stock actualizado correctamente.');
    }
}
