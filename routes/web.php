<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Laravel Breeze構成に合わせたルート定義
| ゲストページ・記事CRUD・プロフィール編集・認証周り
*/

// ===== ゲスト向け固定ページ（page-base レイアウト） =====
Route::get('/', fn () => view('contents.index'))->name('index');
Route::get('/about', fn () => view('contents.about'))->name('about');
Route::get('/kojin', fn () => view('contents.kojin'))->name('kojin');
Route::get('/inquiry', fn () => view('contents.inquiry'))->name('inquiry');

// ===== 記事関連（ArticleController） =====
Route::get('/articles',               [ArticleController::class, 'index']);
Route::get('/articles/create',        [ArticleController::class, 'create']);
Route::post('/articles',              [ArticleController::class, 'store']);
Route::get('/articles/{article}/edit', [ArticleController::class, 'edit']);
Route::put('/articles/{article}',     [ArticleController::class, 'update']);
Route::delete('/articles/{article}',  [ArticleController::class, 'destroy']);
Route::get('/articles/{article}',     [ArticleController::class, 'show']);

// ===== 認証済みユーザーのみアクセス可能なルート（Breeze） =====
Route::middleware(['auth', 'verified'])->group(
    function () {
        // ──────────────────────────────────────
        // ダッシュボード
        // ──────────────────────────────────────
        Route::get('/dashboard', fn () => view('users.dashboard'))
        ->name('dashboard');

        // ──────────────────────────────────────
        // 写真アップロードページ
        // ──────────────────────────────────────
        // ※ resources/views/users/pic_upload.blade.php を用意して下さい
        Route::get('/pic-upload', fn () => view('users.pic_upload'))
        ->name('pic_upload');

        // ──────────────────────────────────────
        // プロフィール（POST）
        // ──────────────────────────────────────
        // ※ ナビドロップダウンからのPOSTリクエスト受け皿
        //    resources/views/users/profile.blade.php を用意するか、
        //    コントローラにルーティングしてください
        Route::post('/profile', fn () => view('users.profile'))
        ->name('profile');

        // ──────────────────────────────────────
        // プロフィール編集（PATCH/DELETE）
        // ──────────────────────────────────────
        Route::get('/profile',    [ProfileController::class, 'edit'])
        ->name('profile.edit');
        Route::patch('/profile',  [ProfileController::class, 'update'])
        ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    }
);

// ===== Breezeの認証ルート自動読み込み =====
require __DIR__ . '/auth.php';
