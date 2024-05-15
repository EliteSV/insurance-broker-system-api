<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8',
            'rol_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
        ]);

        $usuario->save();

        return response()->json($usuario, 201);
    }

    public function show($id)
    {
        $usuario = Usuario::with('rol')->find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Usuario not found'], 404);
        }
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Usuario not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string|max:255',
            'email' => 'string|email|max:255|unique:usuarios,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'rol_id' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $usuario->update($request->only(['nombre', 'email']));

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        if ($request->filled('rol_id')) {
            $usuario->rol()->associate($request->rol_id);
        }

        $usuario->save();

        return response()->json($usuario);
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Usuario not found'], 404);
        }
        $usuario->delete();
        return response()->json(['message' => 'Usuario deleted successfully']);
    }
}
