<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $emails = Email::where('receiver_id', Auth::id())
                      ->orWhere('sender_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('emails.index', compact('emails'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('emails.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'content' => 'required',
            'subject' => 'required'
        ]);

        $emails = new Email();
        $emails->subject = $request->input('subject');
        $emails->project_id = $request->input('project_id');
        $emails->sender_id = Auth::id();
        $emails->content = $request->input('content');
        
        if ($request->hasFile('attachment')) {
            $emails->attachment = $request->file('attachment')->store('attachments');
        }

        $emails->save();

        return redirect()->route('emails.index')->with('emails', 'Mensaje enviado correctamente.');
    }

    public function show($id)
    {
        $emails = Email::findOrFail($id);
        return view('emails.show', compact('emails'));
    }

    public function edit($id)
    {
        $emails = Email::findOrFail($id);
        

        if ($emails->sender_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este mensaje');
        }

        return view('emails.edit', compact('emails'));
    }

    public function update(Request $request, $id)
    {
        $emails = Email::findOrFail($id);
        $emails->subject = $request->input('subject');
        $emails->content = $request->input('content');
        $emails->save();

        return redirect()->route('emails.index')->with('emails', 'Mensaje actualizado correctamente');
    }

    public function destroy($id)
    {
        $emails = Email::findOrFail($id);
    
        if ($emails->sender_id === Auth::id()) {
            $emails->delete();
            return redirect()->route('emails.index')->with('emails', 'Mensaje eliminado');
        }

        return redirect()->back()->with('error', 'Acción no autorizada');
    }
}