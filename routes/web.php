<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Laravel Breeze 構成に合わせたルート定義
| ゲストページ・プロフィール編集・認証周り
*/

// ===== ゲスト向け固定ページ（page-base レイアウト） =====
Route::get('/', fn () => view('contents.index'))->name('index');
Route::get('/about', fn () => view('contents.about'))->name('about');
Route::get('/kojin', fn () => view('contents.kojin'))->name('kojin');
Route::get('/inquiry', fn () => view('contents.inquiry'))->name('inquiry');

// ===== 認証済みユーザーのみアクセス可能なルート =====
Route::middleware(['auth', 'verified'])->group(
    function () {
        // ─── ダッシュボード ─────────────────────────────
        Route::get('/dashboard', fn () => view('users.dashboard'))
        ->name('dashboard');

        // ─── 写真アップロードページ（後で作成予定） ───────────────
        Route::get('/pic-upload', fn () => view('users.pic_upload'))
        ->name('pic_upload');

        // ─── プロフィール編集画面表示 ────────────────────────
        // MembersController@modify でフォームに既存データを渡して表示
        Route::get('/profile', [MembersController::class, 'modify'])
        ->name('profile.edit');

        // ─── プロフィール更新 ────────────────────────────────
        // フォームは PATCH メソッドで profile.update に送信
        Route::patch('/profile', [MembersController::class, 'userChange'])
        ->name('profile.update');

        // ─── （必要であれば）プロフィール削除 ───────────────────
        // Route::delete('/profile', [MembersController::class,'destroy'])
        //      ->name('profile.destroy');
    }
);

// ===== Breeze の認証ルート自動読み込み（ログイン／登録など） =====
require __DIR__ . '/auth.php';
