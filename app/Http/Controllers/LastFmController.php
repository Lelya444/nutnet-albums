<?php

namespace App\Http\Controllers;

use App\Services\LastFmService;
use Illuminate\Http\Request;

class LastFmController extends Controller
{
    public function fetch(Request $request, LastFmService $lastFm)
    {
        $request->validate([
            'album' => 'required|string|max:255',
        ]);

        $info = $lastFm->getAlbumInfo($request->album);

        if (!$info) {
            return response()->json(['error' => 'Альбом не найден'], 404);
        }

        return response()->json($info);
    }
}