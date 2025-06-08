<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuarios;

class UsuarioController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::guard('usuarios')->user();
        $query = Usuarios::query();

        if ($request->filled('nombre')) {
            $nombreBusqueda = $request->nombre;
            $query->where(function($q) use ($nombreBusqueda) {
                $q->where('user_nombre', 'like', '%' . $nombreBusqueda . '%')
                ->orWhere('user_apellido', 'like', '%' . $nombreBusqueda . '%');
            });
        }

        if ($request->filled('rol')) {
            $rolValue = $this->getRolValue($request->rol);
            if ($rolValue !== null) {
                $query->where('user_tipo', $rolValue);
            }
        }

        $usuarios = $query->paginate(2);

        $usuarios->getCollection()->transform(function ($usuario) {
            $usuario->nombre = $usuario->user_nombre . ' ' . $usuario->user_apellido;
            $usuario->email = $usuario->user_gmail;
            $usuario->rol = $this->getRolText($usuario->user_tipo);
            return $usuario;
        });

        return view('pages.usuarios', compact('usuarios', 'user'));
    }

    /**
     * Crear nuevo usuario
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_nombre' => 'required|string|max:255',
            'user_apellido' => 'required|string|max:255',
            'user_gmail' => 'required|email|unique:usuarios,user_gmail',
            'user_password' => 'required|confirmed|min:8',
            'user_tipo' => 'required|in:0,1',
        ], [
            'user_nombre.required' => 'El nombre es obligatorio.',
            'user_apellido.required' => 'El apellido es obligatorio.',
            'user_gmail.required' => 'El correo electrónico es obligatorio.',
            'user_gmail.email' => 'El correo electrónico debe ser válido.',
            'user_gmail.unique' => 'Este correo electrónico ya está registrado.',
            'user_password.required' => 'La contraseña es obligatoria.',
            'user_password.confirmed' => 'Las contraseñas no coinciden.',
            'user_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'user_tipo.required' => 'El tipo de usuario es obligatorio.',
            'user_tipo.in' => 'El tipo de usuario no es válido.',
        ]);

        try {
            Usuarios::create([
                'user_nombre' => $validatedData['user_nombre'],
                'user_apellido' => $validatedData['user_apellido'],
                'user_gmail' => $validatedData['user_gmail'],
                'user_password' => Hash::make($validatedData['user_password']),
                'user_tipo' => $validatedData['user_tipo'],
            ]);

            return redirect()->route('usuarios.index')
                        ->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                        ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $usuario = Usuarios::findOrFail($id);
            return response()->json([
                'id_usuario' => $usuario->id_usuario,
                'user_nombre' => $usuario->user_nombre,
                'user_apellido' => $usuario->user_apellido,
                'user_gmail' => $usuario->user_gmail,
                'user_tipo' => $usuario->user_tipo,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuarios::findOrFail($id);

            $validatedData = $request->validate([
                'user_nombre' => 'required|string|max:255',
                'user_apellido' => 'required|string|max:255',
                'user_gmail' => 'required|email|unique:usuarios,user_gmail,' . $id . ',id_usuario',
                'user_password' => 'nullable|confirmed|min:8',
                'user_tipo' => 'required|in:0,1',
            ], [
                'user_nombre.required' => 'El nombre es obligatorio.',
                'user_apellido.required' => 'El apellido es obligatorio.',
                'user_gmail.required' => 'El correo electrónico es obligatorio.',
                'user_gmail.email' => 'El correo electrónico debe ser válido.',
                'user_gmail.unique' => 'Este correo electrónico ya está registrado.',
                'user_password.confirmed' => 'Las contraseñas no coinciden.',
                'user_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'user_tipo.required' => 'El tipo de usuario es obligatorio.',
                'user_tipo.in' => 'El tipo de usuario no es válido.',
            ]);

            $dataToUpdate = [
                'user_nombre' => $validatedData['user_nombre'],
                'user_apellido' => $validatedData['user_apellido'],
                'user_gmail' => $validatedData['user_gmail'],
                'user_tipo' => $validatedData['user_tipo'],
            ];

            // Solo actualizar contraseña si se proporcionó una nueva
            if (!empty($validatedData['user_password'])) {
                $dataToUpdate['user_password'] = Hash::make($validatedData['user_password']);
            }

            $usuario->update($dataToUpdate);

            return redirect()->route('usuarios.index')
                        ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                        ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        try {
            $usuario = Usuarios::findOrFail($id);
            
            // Verificar que no sea el usuario actual
            if (Auth::guard('usuarios')->id() == $id) {
                return redirect()->route('usuarios.index')
                            ->with('error', 'No puedes eliminar tu propio usuario');
            }

            $usuario->delete();

            return redirect()->route('usuarios.index')
                        ->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                        ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Convertir texto de rol a valor numérico
     */
    private function getRolValue($rolText)
    {
        switch (strtolower($rolText)) {
            case 'administrador':
                return 0;
            case 'docente':
                return 1;
            case 'estudiante':
                return 2; // Por si agregas estudiantes más adelante
            default:
                return null;
        }
    }

    /**
     * Convertir valor numérico a texto de rol
     */
    private function getRolText($rolValue)
    {
        switch ($rolValue) {
            case 0:
                return 'Administrador';
            case 1:
                return 'Docente';
            case 2:
                return 'Estudiante';
            default:
                return 'Desconocido';
        }
    }
}