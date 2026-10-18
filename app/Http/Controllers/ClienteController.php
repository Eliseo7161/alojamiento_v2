<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Muestra el listado de clientes.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('documento', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        $clientes = $query->orderBy('id_cliente', 'desc')->get();

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Registra un nuevo cliente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:20|unique:CLIENTE,documento',
            'nombre'    => 'required|string|max:50',
            'apellido'  => 'required|string|max:50',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
        ], [
            'documento.required' => 'El documento de identidad es obligatorio.',
            'documento.unique'   => 'Este documento ya se encuentra registrado.',
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'email.email'        => 'Ingrese un correo electrónico válido.',
        ]);

        try {
            Cliente::create([
                'documento' => trim($request->documento),
                'nombre'    => trim($request->nombre),
                'apellido'  => trim($request->apellido),
                'telefono'  => trim($request->telefono),
                'email'     => trim($request->email),
            ]);

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al registrar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza un cliente existente.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'documento' => 'required|string|max:20|unique:CLIENTE,documento,' . $cliente->id_cliente . ',id_cliente',
            'nombre'    => 'required|string|max:50',
            'apellido'  => 'required|string|max:50',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
        ], [
            'documento.unique'  => 'Ya existe otro cliente con este número de documento.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
        ]);

        try {
            $cliente->update([
                'documento' => trim($request->documento),
                'nombre'    => trim($request->nombre),
                'apellido'  => trim($request->apellido),
                'telefono'  => trim($request->telefono),
                'email'     => trim($request->email),
            ]);

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un cliente.
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            // Verificar si tiene asignaciones registradas
            if ($cliente->asignaciones()->count() > 0) {
                return redirect()->route('clientes.index')
                    ->with('error', "No se puede eliminar a '{$cliente->nombre_completo}' porque tiene asignaciones de hospedaje registradas.");
            }

            $cliente->delete();

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se pudo eliminar el cliente: ' . $e->getMessage());
        }
    }
}
