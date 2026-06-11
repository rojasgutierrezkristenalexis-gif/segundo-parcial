<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::with('rol.privilegios')->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Registrar intento fallido
            Bitacora::create([
                'usuario' => $request->email,
                'accion' => 'Intento de sesión fallido',
                'detalle' => 'IP: ' . $request->ip()
            ]);
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        $rol = $user->rol;
        $privilegios = $rol ? $rol->privilegios->pluck('nombre')->toArray() : [];

        // Registrar inicio de sesión exitoso
        Bitacora::create([
            'usuario' => $user->email,
            'accion' => 'Inicio de sesión exitoso',
            'detalle' => 'IP: ' . $request->ip() . ' - Rol: ' . ($rol ? $rol->nombre : 'Ninguno')
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $rol ? $rol->nombre : null,
                'privilegios' => $privilegios,
            ]
        ]);
    }
}