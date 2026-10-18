<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permission::with('roles')->get();
        $roles = Role::all();
        return view('permisos.index', compact('permisos', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        try {
            $permission = Permission::create(['name' => $request->name, 'guard_name' => 'web']);

            if ($request->has('roles')) {
                $permission->syncRoles($request->roles);
            }

            return redirect()->route('permisos.index')
                ->with('success', 'Permiso creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al crear el permiso: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Permission $permiso)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permiso->id,
        ]);

        try {
            $permiso->update(['name' => $request->name]);

            $permiso->syncRoles($request->roles ?? []);

            return redirect()->route('permisos.index')
                ->with('success', 'Permiso actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy(Permission $permiso)
    {
        try {
            $permiso->delete();

            return redirect()->route('permisos.index')
                ->with('success', 'Permiso eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('permisos.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
