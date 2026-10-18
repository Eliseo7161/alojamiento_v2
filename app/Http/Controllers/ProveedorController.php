<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::withCount('compras')->orderBy('id_proveedor', 'desc')->get();
        return view('proveedores.index', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'razon_social' => 'required|string|max:100',
            'nit_ruc'      => 'required|string|max:20|unique:PROVEEDOR,nit_ruc',
            'telefono'     => 'nullable|string|max:20',
            'direccion'    => 'nullable|string|max:200',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'razon_social' => 'required|string|max:100',
            'nit_ruc'      => 'required|string|max:20|unique:PROVEEDOR,nit_ruc,' . $id . ',id_proveedor',
            'telefono'     => 'nullable|string|max:20',
            'direccion'    => 'nullable|string|max:200',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        if ($proveedor->compras()->count() > 0) {
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene compras asociadas.');
        }

        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
