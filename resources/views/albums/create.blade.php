<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Добавить альбом
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('albums.store') }}" method="POST">
                    @csrf
                    @include('albums._form', ['album' => null])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>