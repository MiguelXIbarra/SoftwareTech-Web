<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Dentro de la clase ProjectController
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Muestra el listado de mensajes relacionados al usuario.
     */
    public function index()
    {
        // Obtenemos los mensajes donde el usuario es el cliente del proyecto
        $messages = Message::whereHas('project', function($q) {
            $q->where('client_id', Auth::id());
        })->orderBy('created_at', 'desc')->get();

        return view('messages.index', compact('messages'));
    }

    /**
     * Muestra el formulario para enviar un nuevo mensaje.
     */
    public function create()
    {
        $projects = Project::all();
        return view('messages.create', compact('projects'));
    }

    /**
     * Almacena el mensaje usando input() para evitar conflictos de visibilidad.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'content' => 'required'
        ]);

        $message = new Message();
        $message->project_id = $request->input('project_id');
        $message->sender_id = Auth::id();
        
        // Usamos input() para acceder al dato del formulario sin tocar la propiedad protegida de Laravel
        $message->content = $request->input('content');
        
        if ($request->hasFile('attachment')) {
            $message->attachment = $request->file('attachment')->store('attachments');
        }

        $message->save();

        return redirect()->route('messages.index')->with('message', 'Mensaje enviado correctamente.');
    }

    /**
     * Muestra un mensaje específico.
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('messages.show', compact('message'));
    }

    /**
     * Muestra el formulario para editar un mensaje.
     */
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        
        // Verificamos que solo el autor pueda editar su propio mensaje
        if ($message->sender_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este mensaje');
        }

        return view('messages.edit', compact('message'));
    }

    /**
     * Actualiza el contenido del mensaje.
     */
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        // Validación y actualización segura
        $message->content = $request->input('content');
        $message->save();

        return redirect()->route('messages.index')->with('message', 'Mensaje actualizado correctamente');
    }

    /**
     * Elimina el mensaje físicamente.
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        
        // Seguridad: solo el dueño borra su mensaje
        if ($message->sender_id === Auth::id()) {
            $message->delete();
            return redirect()->route('messages.index')->with('message', 'Mensaje eliminado');
        }

        return redirect()->back()->with('error', 'Acción no autorizada');
    }
}