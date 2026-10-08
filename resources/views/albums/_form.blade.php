<div class="space-y-4">
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label class="block font-medium text-gray-700">Название альбома *</label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $album->title ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm mt-1" required>
    </div>

    <div>
        <button type="button" id="fetchBtn"
                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            Заполнить из Last.fm
        </button>
        <span id="fetchStatus" class="text-sm text-gray-500 ml-2"></span>
    </div>

    <div>
        <label class="block font-medium text-gray-700">Исполнитель</label>
        <input type="text" name="artist" id="artist"
               value="{{ old('artist', $album->artist ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm mt-1">
    </div>

    <div>
        <label class="block font-medium text-gray-700">Описание</label>
        <textarea name="description" id="description" rows="4"
                  class="w-full border-gray-300 rounded-md shadow-sm mt-1">{{ old('description', $album->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block font-medium text-gray-700">Ссылка на обложку</label>
        <input type="url" name="cover_url" id="cover_url"
               value="{{ old('cover_url', $album->cover_url ?? '') }}"
               class="w-full border-gray-300 rounded-md shadow-sm mt-1">
        <img id="coverPreview"
             src="{{ old('cover_url', $album->cover_url ?? '') }}"
             alt=""
             class="mt-2 max-h-40 rounded {{ old('cover_url', $album->cover_url ?? '') ? '' : 'hidden' }}">
    </div>

    <div class="flex gap-3 pt-4">
        <button type="submit"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
            Сохранить
        </button>
        <a href="{{ route('albums.index') }}"
           class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400">
            Отмена
        </a>
    </div>
</div>

<script>
document.getElementById('fetchBtn').addEventListener('click', async function () {
    const titleInput = document.getElementById('title');
    const status = document.getElementById('fetchStatus');
    const title = titleInput.value.trim();

    if (!title) {
        status.textContent = 'Введите название альбома';
        status.className = 'text-sm text-red-500 ml-2';
        return;
    }

    this.disabled = true;
    this.textContent = 'Загрузка...';
    status.textContent = '';
    status.className = 'text-sm text-gray-500 ml-2';

    try {
        const response = await fetch('{{ route('lastfm.album') }}?album=' + encodeURIComponent(title));
        const data = await response.json();

        if (!response.ok || data.error) {
            status.textContent = data.error || 'Не найдено';
            status.className = 'text-sm text-red-500 ml-2';
            return;
        }

        if (data.artist) document.getElementById('artist').value = data.artist;
        if (data.description) document.getElementById('description').value = data.description;

        if (data.cover_url) {
            document.getElementById('cover_url').value = data.cover_url;
            const preview = document.getElementById('coverPreview');
            preview.src = data.cover_url;
            preview.classList.remove('hidden');
        }

        status.textContent = 'Данные загружены!';
        status.className = 'text-sm text-green-600 ml-2';
    } catch (e) {
        status.textContent = 'Ошибка при запросе';
        status.className = 'text-sm text-red-500 ml-2';
    } finally {
        this.disabled = false;
        this.textContent = 'Заполнить из Last.fm';
    }
});
</script>