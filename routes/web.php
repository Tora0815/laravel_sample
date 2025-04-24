<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MembersController;

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

// // ===== 記事関連（ArticleController） =====
// Route::get('/articles', [ArticleController::class, 'index']);
// Route::get('/articles/create', [ArticleController::class, 'create']);
// Route::post('/articles', [ArticleController::class, 'store']);
// Route::get('/articles/{article}/edit', [ArticleController::class, 'edit']);
// Route::put('/articles/{article}', [ArticleController::class, 'update']);
// Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);
// Route::get('/articles/{article}', [ArticleController::class, 'show']);

// ===== 認証済みユーザーのみアクセス可能なルート =====
    Route::middleware(['auth', 'verified'])->group(
            function () {
        // ─ ダッシュボード
        Route::get('/dashboard', fn () => view('users.dashboard'))->name('dashboard');

        // ─ 写真アップロードページ（あとで作成予定）
        Route::get('/pic-upload', fn () => view('users.pic_upload'))->name('pic_upload');

        // ─ プロフィール表示（GET）
        Route::get('/profile', [MembersController::class, 'modify'])->name('profile');

        // ─ プロフィール更新（POST）
        Route::post('/profile', [MembersController::class, 'userChange'])->name('profile.update');
    }
);

// ===== Breezeの認証ルート（ログイン／登録など）=====
require __DIR__ . '/auth.php';
