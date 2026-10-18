<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['categoria', 'stock'])->orderBy('id_producto', 'desc')->get();
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_producto' => 'required|string|max:100',
            'precio_venta'    => 'required|numeric|min:0',
            'id_categoria'    => 'required|exists:CATEGORIA,id_categoria',
            'cantidad_minima' => 'nullable|integer|min:0',
            'cantidad_inicial'=> 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $producto = Producto::create([
                'nombre_producto' => $request->nombre_producto,
                'precio_venta'    => $request->precio_venta,
                'id_categoria'    => $request->id_categoria,
            ]);

            Stock::create([
                'id_producto'     => $producto->id_producto,
                'cantidad_actual' => $request->cantidad_inicial ?? 0,
                'cantidad_minima' => $request->cantidad_minima ?? 0,
            ]);

            DB::commit();
            return redirect()->route('productos.index')->with('success', 'Producto registrado exitosamente con su registro de inventario.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('productos.index')->with('error', 'Error al registrar el producto: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre_producto' => 'required|string|max:100',
            'precio_venta'    => 'required|numeric|min:0',
            'id_categoria'    => 'required|exists:CATEGORIA,id_categoria',
            'cantidad_minima' => 'nullable|integer|min:0',
        ]);

        $producto->update([
            'nombre_producto' => $request->nombre_producto,
            'precio_venta'    => $request->precio_venta,
            'id_categoria'    => $request->id_categoria,
        ]);

        if ($producto->stock) {
            $producto->stock->update([
                'cantidad_minima' => $request->cantidad_minima ?? 0,
            ]);
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->detallesCompra()->count() > 0 || $producto->detallesVenta()->count() > 0) {
            return redirect()->route('productos.index')->with('error', 'No se puede eliminar el producto porque tiene movimientos de compras o ventas.');
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    // Guardar categoría rápida vía Ajax o formulario
    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:50|unique:CATEGORIA,nombre_categoria',
        ]);

        Categoria::create(['nombre_categoria' => $request->nombre_categoria]);

        return redirect()->back()->with('success', 'Categoría agregada exitosamente.');
    }
}
