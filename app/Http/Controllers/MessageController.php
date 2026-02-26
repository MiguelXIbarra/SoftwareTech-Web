<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $messages = Message::whereHas('project', function($q) {
            $q->where('client_id', Auth::id());
        })->orderBy('created_at', 'desc')->get();

        $newMessagesCount = Message::where('created_at', '>=', now()->subDay())
                                    ->whereHas('project', function($q) {
                                        $q->where('client_id', Auth::id());
                                    })->count();

        return view('messages.index', compact('messages', 'newMessagesCount'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('messages.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'content' => 'required'
        ]);

        $message = new Message();
        $message->project_id = $request->input('project_id');
        $message->sender_id = Auth::id();
        

        $message->content = $request->input('content');
        
        if ($request->hasFile('attachment')) {
            $message->attachment = $request->file('attachment')->store('attachments');
        }

        $message->save();

        return redirect()->route('messages.index')->with('message', 'Mensaje enviado correctamente.');
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('messages.show', compact('message'));
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);
        

        if ($message->sender_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este mensaje');
        }

        return view('messages.edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $message->content = $request->input('content');
        $message->save();

        return redirect()->route('messages.index')->with('message', 'Mensaje actualizado correctamente');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
    
        if ($message->sender_id === Auth::id()) {
            $message->delete();
            return redirect()->route('messages.index')->with('message', 'Mensaje eliminado');
        }

        return redirect()->back()->with('error', 'Acción no autorizada');
    }
}