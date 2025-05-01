# Laravel + Sail 用 Renderデプロイ対応 Dockerfile（artisan自動実行付き）

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

EXPOSE 8000

# 🟢 本番起動時にartisan key生成＆マイグレーションも自動実行
CMD bash -c "php artisan key:generate && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"
