<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'nullable|string|max:255|unique:roles,name,' . $role->id,
        ]);

        try {
            if ($request->filled('name')) {
                $role->update(['name' => $request->name]);
            }

            $role->syncPermissions($request->permissions ?? []);

            return redirect()->route('roles.index')
                ->with('success', 'Permisos actualizados correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'Error al actualizar permisos: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
