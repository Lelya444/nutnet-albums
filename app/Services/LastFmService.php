<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LastFmService
{
    protected string $apiKey;
    protected string $proxy;

    public function __construct()
    {
        $this->apiKey = config('services.lastfm.key');
        $this->proxy = 'http://127.0.0.1:10809';
    }

    public function getAlbumInfo(string $albumName, ?string $artistName = null): ?array
    {
        if ($artistName) {
            $result = $this->getByArtistAndAlbum($albumName, $artistName);
            if ($result) {
                return $result;
            }
        }

        return $this->searchByAlbum($albumName);
    }

    protected function getByArtistAndAlbum(string $albumName, string $artistName): ?array
    {
        $params = [
            'method' => 'album.getinfo',
            'album' => $albumName,
            'artist' => $artistName,
            'api_key' => $this->apiKey,
            'format' => 'json',
        ];

        $response = Http::withoutVerifying()
            ->timeout(20)
            ->withOptions(['proxy' => $this->proxy])
            ->get('https://ws.audioscrobbler.com/2.0/', $params);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        if (isset($data['error']) || !isset($data['album'])) {
            return null;
        }

        return $this->formatAlbum($data['album']);
    }

    protected function searchByAlbum(string $albumName): ?array
    {
        $params = [
            'method' => 'album.search',
            'album' => $albumName,
            'api_key' => $this->apiKey,
            'format' => 'json',
            'limit' => 1,
        ];

        $response = Http::withoutVerifying()
            ->timeout(20)
            ->withOptions(['proxy' => $this->proxy])
            ->get('https://ws.audioscrobbler.com/2.0/', $params);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        if (isset($data['error'])) {
            return null;
        }

        $matches = $data['results']['albummatches']['album'] ?? [];

        if (empty($matches)) {
            return null;
        }

        $first = $matches[0];

        if (!empty($first['artist']) && !empty($first['name'])) {
            $detailed = $this->getByArtistAndAlbum($first['name'], $first['artist']);
            if ($detailed) {
                return $detailed;
            }
        }

        return [
            'artist' => $first['artist'] ?? null,
            'description' => null,
            'cover_url' => $this->extractCoverUrl($first['image'] ?? []),
        ];
    }

    protected function formatAlbum(array $album): array
    {
        return [
            'artist' => $album['artist'] ?? null,
            'description' => $album['wiki']['summary'] ?? null,
            'cover_url' => $this->extractCoverUrl($album['image'] ?? []),
        ];
    }

    protected function extractCoverUrl(array $images): ?string
    {
        foreach (['extralarge', 'large', 'medium', 'small'] as $size) {
            foreach ($images as $image) {
                if (($image['size'] ?? '') === $size && !empty($image['#text'])) {
                    return $image['#text'];
                }
            }
        }

        return null;
    }
}