# Laravel Sample App（学習用ポートフォリオ）

このプロジェクトは、Laravelの書籍（Laravel 8〜9 + Mix構成）をベースに、  
Laravel 12 + Breeze + Vite 環境で再構築したポートフォリオです。  
作成内容は会員制写真投稿サイトです。

主に以下の目的で作成しています：

- Laravelの現行環境（12.x）の基本構造を理解する
- Breezeによる認証処理の流れを実装で学ぶ
- ViteベースのビルドやCSS/JS管理の実践
- Bladeテンプレートの構成力とコメント設計を強化する

---

## 使用技術

- Laravel 12.8.1
- Laravel Breeze v2.3.6（認証機能）
- Vite（ビルドツール）
- Tailwind CSS（フロントデザイン）
- MySQL（Docker + Sail）
- GitHub（バージョン管理）

---

## 環境構成

- Laravel: 12.8.1
- PHP: 8.2.10
- Node.js: 22.15.0
- npm: 10.9.2
- 認証: Laravel Breeze v2.3.6
- フロントビルド: Vite（@vite()構文）
- DB: MySQL 8.0
- 開発環境: Laravel Sail（Docker）

---

## 環境構築手順

```bash
git clone https://github.com/your-name/laravel_sample.git
cd laravel_sample
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
