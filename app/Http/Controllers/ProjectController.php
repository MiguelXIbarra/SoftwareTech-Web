<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Dentro de la clase ProjectController
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Muestra el listado de proyectos.
     */
    public function index()
    {
        $projects = Project::orderBy('id', 'desc')->get();

        return view('projects.index', [
            'projects' => $projects
        ]);
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Guarda el nuevo proyecto vinculándolo al usuario autenticado.
     */
    public function store(Request $request)
    {
        // Validación de los campos según la nueva vista corregida
        $this->validate($request, [
            'title' => 'required|min:5',
            'description' => 'required',
            'category' => 'required',
        ]);

        $project = new Project();
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->category = $request->input('category');
        
        // Asignación automática del cliente autenticado para evitar errores manuales
        $project->client_id = Auth::id();
        
        // El estado se define como 'Lead' por defecto según la estructura de la base de datos
        $project->status = 'Lead'; 
        
        $project->save();

        return redirect()->route('projects.index')->with([
            'message' => 'El proyecto se ha creado exitosamente'
        ]);
    }

    /**
     * Muestra los detalles de un proyecto específico.
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Muestra el formulario para editar el proyecto.
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    /**
     * Actualiza los datos del proyecto.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'description' => 'required',
        ]);

        $project = Project::findOrFail($id);
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->category = $request->input('category');
        $project->status = $request->input('status');
        $project->save();

        return redirect()->route('projects.index')->with([
            'message' => 'El proyecto se ha actualizado correctamente'
        ]);
    }

    /**
     * Elimina el proyecto de la base de datos.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with([
            'message' => 'El proyecto se ha eliminado correctamente'
        ]);
    }
}