<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Email::with('sender')
            ->where(function ($subQuery) {
                $subQuery->where('receiver_id', Auth::id())
                         ->orWhere('sender_id', Auth::id());
            });

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('subject', 'like', "%{$search}%")
                            ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sender_id')) {
            $query->where('sender_id', $request->input('sender_id'));
        }

        if ($request->boolean('has_attachment')) {
            $query->whereNotNull('attachment');
        }

        if ($request->boolean('important')) {
            $query->where('is_important', true);
        }

        $emails = $query->orderBy('created_at', 'desc')->get();

        $senderIds = $emails->pluck('sender_id')->filter()->unique();
        $senders = User::whereIn('id', $senderIds)->get();

        if ($request->ajax()) {
            return view('emails.partials.list', compact('emails'))->render();
        }

        return view('emails.index', compact('emails', 'senders'));
    }

    public function toggleImportant(Request $request, $id)
    {
        $email = Email::findOrFail($id);

        if ($email->receiver_id !== Auth::id() && $email->sender_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Acción no autorizada'], 403);
            }
            return redirect()->back()->with('error', 'Acción no autorizada');
        }

        $email->is_important = !$email->is_important;
        $email->save();

        if ($request->ajax()) {
            return response()->json(['important' => $email->is_important]);
        }

        return redirect()->back();
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
            $emails->attachment = $request->file('attachment')->store('attachments', 'public');
            $emails->attachment_name = $request->file('attachment')->getClientOriginalName();
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

    public function attachment($id)
    {
        $emails = Email::findOrFail($id);

        if ($emails->receiver_id !== Auth::id() && $emails->sender_id !== Auth::id()) {
            abort(403);
        }

        if (! $emails->attachment) {
            abort(404);
        }

        if (Storage::disk('public')->exists($emails->attachment)) {
            return Storage::disk('public')->response($emails->attachment);
        }

        if (Storage::exists($emails->attachment)) {
            return Storage::response($emails->attachment);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $emails = Email::findOrFail($id);

        if ($emails->sender_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para actualizar este mensaje');
        }

        $this->validate($request, [
            'subject' => 'required|string|max:255',
            'content' => 'required',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $emails->subject = $request->input('subject');
        $emails->content = $request->input('content');

        if ($request->hasFile('attachment')) {
            if ($emails->attachment && Storage::disk('public')->exists($emails->attachment)) {
                Storage::disk('public')->delete($emails->attachment);
            }
            $emails->attachment = $request->file('attachment')->store('attachments', 'public');
            $emails->attachment_name = $request->file('attachment')->getClientOriginalName();
        } elseif ($request->boolean('remove_attachment')) {
            if ($emails->attachment && Storage::disk('public')->exists($emails->attachment)) {
                Storage::disk('public')->delete($emails->attachment);
            }
            $emails->attachment = null;
            $emails->attachment_name = null;
        }

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