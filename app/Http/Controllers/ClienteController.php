<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cliente::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'dui' => 'required|max:255|unique:clientes,dui',
            'nit' => 'required|max:255|unique:clientes,nit',
            'email' => 'required|email|max:255|unique:clientes,email',
            'telefono' => 'required|max:255'
        ]);

        return Cliente::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Cliente::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'dui' => 'required|max:255',
            'nit' => 'required|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|max:255'
        ]);

        $cliente = Cliente::findOrFail($id); 
        $cliente->update($request->all()); 
        return $cliente;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Cliente::destroy($id); 
    }
}
