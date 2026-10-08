<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактировать: {{ $album->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('albums.update', $album) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('albums._form', ['album' => $album])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>