FROM php:8.4-cli

RUN apt-get update && apt-get install -y git unzip libpq-dev libzip-dev libpng-dev libonig-dev && docker-php-ext-install pdo pdo_pgsql zip bcmath && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && curl -sL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

EXPOSE 8080

CMD ["sh", "-c", "php -r \"file_put_contents('/app/.env', implode(PHP_EOL, array_map(fn(\\$k) => \\$k.'='.getenv(\\$k), ['APP_NAME','APP_ENV','APP_KEY','APP_DEBUG','APP_URL','APP_LOCALE','APP_FALLBACK_LOCALE','LOG_CHANNEL','LOG_LEVEL','SESSION_DRIVER','CACHE_STORE','QUEUE_CONNECTION','LASTFM_API_KEY','DB_CONNECTION','DB_HOST','DB_PORT','DB_DATABASE','DB_USERNAME','DB_PASSWORD'])));\" && php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]