<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aseguradora;

class AseguradorasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Aseguradora::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);
        return Aseguradora::create($request->all());

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Aseguradora::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);
        return Aseguradora::findOrFail($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Aseguradora::destroy($id);
    }
}
