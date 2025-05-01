# Laravel + Sail 用 Renderデプロイ対応 Dockerfile（修正版）

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
  && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN composer install --no-dev --optimize-autoloader

# ⚠️ ここでは key:generate / migrate は実行しない（実行時にやる）

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
