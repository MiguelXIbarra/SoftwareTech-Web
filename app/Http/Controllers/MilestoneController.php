<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    // Dentro de la clase ProjectController
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $milestones = Milestone::orderBy('due_date', 'asc')->get();
        return view('milestones.index', compact('milestones'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('milestones.create', compact('projects'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name'       => 'required|min:3',
            'due_date'   => 'required|date',
        ]);

        $milestone = new Milestone();
        $milestone->project_id = $request->project_id;
        $milestone->name = $request->name;
        $milestone->due_date = $request->due_date;
        $milestone->status = 'Pendiente';
        $milestone->save();

    return redirect()->route('milestones.index')->with('message', 'Hito registrado con éxito');
    }

    public function show($id)
    {
        $milestone = Milestone::findOrFail($id);
        return view('milestones.show', compact('milestone'));
    }

    public function edit($id)
    {
        $milestone = Milestone::findOrFail($id);
        $projects = Project::all();
        return view('milestones.edit', compact('milestone', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);
        $milestone->update($request->all());
        return redirect()->route('milestones.index')->with('message', 'Hito actualizado');
    }

    public function destroy($id)
    {
        Milestone::destroy($id);
        return redirect()->route('milestones.index')->with('message', 'Hito eliminado');
    }
}