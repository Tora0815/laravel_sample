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

## 📚 使用技術

- Laravel 12.x
- Laravel Breeze（認証機能）
- Vite（ビルドツール）
- Tailwind CSS（フロントデザイン）
- MySQL（Docker + Sail）
- GitHub（バージョン管理）

---

## ⚙️ 環境構築手順

```bash
git clone https://github.com/your-name/laravel_sample.git
cd laravel_sample
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
