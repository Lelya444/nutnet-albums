<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::orderBy('created_at', 'desc')->paginate(6);
        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return view('albums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'artist' => 'nullable|max:255',
            'description' => 'nullable',
            'cover_url' => 'nullable|url',
        ]);

        Album::create($validated);

        return redirect()->route('albums.index')
            ->with('success', 'Альбом добавлен');
    }

    public function edit(Album $album)
    {
        return view('albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'artist' => 'nullable|max:255',
            'description' => 'nullable',
            'cover_url' => 'nullable|url',
        ]);

        $album->update($validated);

        return redirect()->route('albums.index')
            ->with('success', 'Альбом обновлён');
    }

    public function destroy(Album $album)
    {
        $album->delete();

        return redirect()->route('albums.index')
            ->with('success', 'Альбом удалён');
    }
}