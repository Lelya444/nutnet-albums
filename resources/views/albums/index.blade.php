<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Музыкальные альбомы
            </h2>
            @auth
                <a href="{{ route('albums.create') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    + Добавить альбом
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($albums->isEmpty())
                <p class="text-center text-gray-500 py-8">
                    Пока альбомов нет.
                </p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($albums as $album)
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                            @if($album->cover_url)
                                <img src="{{ $album->cover_url }}"
                                     alt="{{ $album->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                                    Нет обложки
                                </div>
                            @endif

                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="text-lg font-semibold">{{ $album->title }}</h3>
                                <p class="text-gray-600">{{ $album->artist ?? 'Исполнитель не указан' }}</p>
                                @if($album->description)
                                    <p class="text-sm text-gray-500 mt-2 flex-1">
                                        {{ \Illuminate\Support\Str::limit($album->description, 120) }}
                                    </p>
                                @endif

                                @auth
                                    <div class="mt-4 flex gap-3">
                                        <a href="{{ route('albums.edit', $album) }}"
                                           class="text-sm text-blue-600 hover:underline">
                                            Редактировать
                                        </a>
                                        <form action="{{ route('albums.destroy', $album) }}"
                                              method="POST"
                                              onsubmit="return confirm('Удалить альбом?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-sm text-red-600 hover:underline">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $albums->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>