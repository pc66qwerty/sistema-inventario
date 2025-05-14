<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Mostrar listado de usuarios
     */
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Almacenar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:jefe,inventario,vendedor',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'active' => true,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $usuario)
    {
        // Validar datos básicos
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'role' => 'required|in:jefe,inventario,vendedor',
        ];

        // Solo validar la contraseña si se proporcionó
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        // Actualizar usuario
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'active' => $request->has('active') ? true : false,
        ];

        // Solo actualizar la contraseña si se proporcionó
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $usuario)
    {
        // No permitir eliminar al propio usuario
        if (Auth::id() === $usuario->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Forzar cierre de sesión en todos los dispositivos
     */
    public function logoutEverywhere(User $usuario)
    {
        // Utiliza Laravel Sanctum/Jetstream para revocar tokens
        if (method_exists($usuario, 'tokens')) {
            $usuario->tokens()->delete();
        }
        
        // Cambiar el remember_token forzará el cierre de sesión de sesiones recordadas
        $usuario->forceFill([
            'remember_token' => null,
        ])->save();
        
        return back()->with('success', 'Se ha forzado el cierre de sesión para este usuario en todos los dispositivos.');
    }

    /**
     * Enviar enlace de restablecimiento de contraseña
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('success', 'Se ha enviado un enlace de restablecimiento de contraseña.')
                    : back()->withErrors(['email' => __($status)]);
    }
}