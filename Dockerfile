# Laravel + Sail 用 Renderデプロイ対応 Dockerfile（PostgreSQL対応＋Seeder）

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

# ✅ Viteビルド（必要なら）を追加してもOK
# RUN npm ci && npm run build

EXPOSE 10000

# ✅ 本番起動時にマイグレーション＆Seederを含めて起動
CMD bash -c "php artisan config:clear && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000"
