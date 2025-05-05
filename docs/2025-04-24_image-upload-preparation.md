# 📘 作業記録：2025/04/24（木）

## ✅ 今日やったこと（概要）

- プロフィール編集画面のルーティング修正（RESTfulに統一）
- MembersController の修正（Auth::user() を使用）
- profile.blade.php のフォーム動作を確認＆修正
- ナビゲーションメニューのルート修正（profile.edit）
- UI確認（Bootstrap＋Tailwindの競合問題）
- Profileモデルのエラー調査＆修復（App\Models\Profile が見つからない）
- Laravel Sail + Vite 環境でのセッション／マイグレーショントラブル対応
- Gitで修正内容をこまめにコミット＆プッシュ

## 💡 学んだこと・気づき

- Breeze環境下では、ログインユーザー情報は `$request->u_id` より `Auth::user()` を使う方がベスト
- Laravelではルート名が正しく設定されていないと navigation.blade でエラーになる
- BootstrapとTailwindの併用はレイアウト崩れの原因になる可能性あり → スタイル調整が必要
- Laravel Sail 環境では DB 接続名（DB_HOST）ミスがセッション系のエラーに直結する
- Laravelのcache:clear系コマンドは `.sail` 経由で走らせると安全

## 🔜 明日やること

- プロフィール編集画面の**UI修正（レイアウト・余白・ラベル崩れ対応）**
- Bootstrapのフォームクラスを整理（Tailwindとの併用による崩れ防止）
- 画像アップロードページの設計確認（Chapter 5 に向けた準備）
- Gitの操作やエラー再現用コミットの運用を継続して習慣化

## ✏️ 感想・反省（任意）

- 長時間だったけど、トラブルをひとつずつ潰せたので満足感がある
- 学習の密度が濃くなってきたので、引き続き「小さく試して小さくコミット」で進めていく
