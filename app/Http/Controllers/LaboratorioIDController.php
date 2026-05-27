<?php

namespace App\Http\Controllers;

use App\Models\LabPost; 
use Illuminate\Http\Request;

class LaboratorioIdController extends Controller
{
    public function index()
    {
        $proyectos = LabPost::where('type', 'id')->orderBy('created_at', 'desc')->get();
        return view('laboratorioID.index', compact('proyectos'));
    }

    public function show($id)
    {
        $proyecto = LabPost::findOrFail($id);
        return view('laboratorioID.show', compact('proyecto'));
    }
}