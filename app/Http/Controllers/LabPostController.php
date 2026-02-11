<?php

namespace App\Http\Controllers;

use App\Models\LabPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LabPostController extends Controller
{
    // Dentro de la clase ProjectController
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $posts = LabPost::orderBy('created_at', 'desc')->get();
        return view('lab_posts.index', compact('posts'));
    }

    public function create()
    {
        return view('lab_posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required', 'body' => 'required']);

        $post = new LabPost($request->all());
        $post->author_id = Auth::id();
        $post->slug = Str::slug($request->title);
        $post->save();

        return redirect()->route('lab_posts.index');
    }

    public function show($id)
    {
        $post = LabPost::findOrFail($id);
        return view('lab_posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = LabPost::findOrFail($id);
        return view('lab_posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = LabPost::findOrFail($id);
        $post->update($request->all());
        return redirect()->route('lab_posts.index');
    }

    public function destroy($id)
    {
        LabPost::destroy($id);
        return redirect()->route('lab_posts.index');
    }
}