<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $dbRoles = Role::pluck('name')->toArray();
        $defaultRoles = ['superadmin', 'admin', 'operarios', 'recepcionista'];
        $roles = array_values(array_unique(array_merge($defaultRoles, $dbRoles)));

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    // 1. GUARDAR NUEVO USUARIO
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required',
            'estado'   => 'nullable|string|in:ACTIVO,INACTIVO',
        ]);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'estado'   => $request->estado ?? 'ACTIVO',
            ]);

            if (Role::where('name', $request->role)->exists()) {
                $user->syncRoles([$request->role]);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error en la base de datos: ' . $e->getMessage());
        }
    }

    // 2. EDITAR USUARIO (Corregido)
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'role'     => 'required',
            'estado'   => 'nullable|string|in:ACTIVO,INACTIVO',
        ]);

        try {
            $usuario->name   = $request->name;
            $usuario->email  = $request->email;
            $usuario->role   = $request->role;
            $usuario->estado = $request->estado ?? 'ACTIVO';

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }

            $usuario->save();

            if (Role::where('name', $request->role)->exists()) {
                $usuario->syncRoles([$request->role]);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    // 3. ELIMINAR USUARIO (Corregido)
    public function destroy(User $usuario)
    {
        try {
            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}