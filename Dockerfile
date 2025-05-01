# Laravel + Sail 用 Renderデプロイ対応 Dockerfile（PostgreSQL対応）

FROM laravelsail/php82-composer

WORKDIR /var/www/html

COPY . .

RUN apt-get update && apt-get install -y \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  git \
  curl \
  libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD bash -c "php artisan key:generate && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"
