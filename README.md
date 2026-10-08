# Nutnet Albums — справочник музыкальных альбомов

Тестовое задание для компании Nutnet. Laravel-приложение — каталог музыкальных альбомов с интеграцией Last.fm API.

## Что реализовано

- **Список альбомов** — название, исполнитель, описание, обложка (пагинация, 6 на страницу).
- **Создание/редактирование/удаление альбомов** — доступно только авторизованным пользователям.
- **Автозаполнение из Last.fm** — по названию альбома подтягиваются исполнитель, описание и обложка.
- **Логирование изменений** — все действия с альбомами записываются в таблицу activity_log (spatie/laravel-activitylog).
- **Авторизация** — Laravel Breeze.
- **Адаптивная вёрстка** — Tailwind CSS.

## Стек

- PHP 8.4
- Laravel 12
- SQLite
- Blade + Tailwind CSS
- Last.fm API

## Установка и запуск

    git clone https://github.com/Lelya444/nutnet-albums.git
    cd nutnet-albums
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite
    php artisan migrate
    npm run build
    php artisan serve

Приложение откроется на http://127.0.0.1:8000

## Настройка Last.fm

В .env добавьте:

    LASTFM_API_KEY=ваш_ключ

Ключ: https://www.last.fm/api/account/create

В app/Services/LastFmService.php указан прокси http://127.0.0.1:10809 (Last.fm блокирует РФ). Если вы не в РФ — удалите строку withOptions([proxy => ...]).

## Автор

Lelya444 — тестовое задание для Nutnet (2026)