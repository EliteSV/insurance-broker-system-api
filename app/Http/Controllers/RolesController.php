<?php

namespace App\Http\Controllers;

use App\Models\Rol;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles);
    }
}
