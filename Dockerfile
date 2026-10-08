FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install && npm run build

EXPOSE 8080

CMD php -r "file_put_contents('/app/.env', 'APP_NAME='.getenv('APP_NAME').PHP_EOL.'APP_ENV='.getenv('APP_ENV').PHP_EOL.'APP_KEY='.getenv('APP_KEY').PHP_EOL.'APP_DEBUG='.getenv('APP_DEBUG').PHP_EOL.'APP_URL='.getenv('APP_URL').PHP_EOL.'APP_LOCALE='.getenv('APP_LOCALE').PHP_EOL.'APP_FALLBACK_LOCALE='.getenv('APP_FALLBACK_LOCALE').PHP_EOL.'LOG_CHANNEL='.getenv('LOG_CHANNEL').PHP_EOL.'LOG_LEVEL='.getenv('LOG_LEVEL').PHP_EOL.'SESSION_DRIVER='.getenv('SESSION_DRIVER').PHP_EOL.'CACHE_STORE='.getenv('CACHE_STORE').PHP_EOL.'QUEUE_CONNECTION='.getenv('QUEUE_CONNECTION').PHP_EOL.'LASTFM_API_KEY='.getenv('LASTFM_API_KEY').PHP_EOL.'DB_CONNECTION='.getenv('DB_CONNECTION').PHP_EOL.'DATABASE_URL='.getenv('DATABASE_URL').PHP_EOL);" && php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}