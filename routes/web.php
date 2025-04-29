<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserPicsController;
use App\Http\Controllers\MembersController;

/**
 * --------------------------------------------
 * Webルート設定ファイル
 * --------------------------------------------
 *
 * ・このファイルは、主に画面表示やデータ通信に関するルートを管理します。
 * ・認証（auth）ミドルウェア適用済みルートもここに記載します。
 * ・Breeze認証関連ルートは、最後に auth.php を読み込んでいます。
 *
 * 注意：
 * - GET/POST/PATCHの使い分けを正しく行う
 * - 名前付きルート（->name()）はBladeテンプレートから参照されるため必須
 * - 静的ページルート（about/kojin/inquiry）はfunction()で対応
 */


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Laravel Breeze 構成に合わせたルート定義
| ゲストページ・プロフィール編集・認証周り
*/


// --------------------------------------------
// トップページ（みんなのアルバム一覧）
// --------------------------------------------
Route::get('/', [PageController::class, 'top'])->name('index');

// --------------------------------------------
// 静的ページ（About、個人情報、問い合わせ）
// --------------------------------------------
Route::get(
    '/about', function () {
        return view('contents.about');
    }
)->name('about');

Route::get(
    '/kojin', function () {
        return view('contents.kojin');
    }
)->name('kojin');

Route::get(
    '/inquiry', function () {
        return view('contents.inquiry');
    }
)->name('inquiry');

// --------------------------------------------
// アルバム表示関連（画像一覧・画像取得）
// --------------------------------------------
Route::post('/show_album', [PageController::class, 'showAlbum']);
Route::post('/show_pics', [PageController::class, 'getpics']);
Route::post('/pic_up', [PageController::class, 'getmaster']);

// /show_album にGETアクセスされた場合のエラー防止リダイレクト
Route::get(
    '/show_album', function () {
        return redirect('/');
    }
);

// --------------------------------------------
// ログイン後の会員用ページ
// --------------------------------------------

// マイページ（ダッシュボード）
Route::get(
    '/dashboard', function () {
        return view('users.dashboard');
    }
)->middleware(['auth'])->name('dashboard');

// 写真アップロードページ
Route::get(
    '/pic_upload', function () {
        return view('users.pic_upload');
    }
)->middleware(['auth'])->name('pic_upload');

// プロフィール編集ページ（GET）
Route::get('/profile', [MembersController::class, 'modify'])->middleware(['auth'])->name('profile.edit');

// プロフィール情報更新処理（PATCH）
Route::patch('/profile', [MembersController::class, 'userChange'])->middleware(['auth'])->name('profile.update');

// 【追加】誤ってPOSTで/profileに来たときエラー防止リダイレクト
Route::post(
    '/profile', function () {
        return redirect('/dashboard');
    }
);

// --------------------------------------------
// 写真関連（Ajax用：一覧・登録・取得・削除・更新）
// --------------------------------------------
Route::post('/user_pics', [UserPicsController::class, 'getpics'])->middleware(['auth']);
Route::post('/save_pics', [UserPicsController::class, 'savepics'])->middleware(['auth']);
Route::post('/get_master', [UserPicsController::class, 'getmaster'])->middleware(['auth']);
Route::post('/delete_pic', [UserPicsController::class, 'deletepic'])->middleware(['auth']);
Route::post('/save_title', [UserPicsController::class, 'savetitle'])->middleware(['auth']);

// --------------------------------------------
// Breeze認証ルート（ログイン・登録・パスワードリセットなど）
// --------------------------------------------
require __DIR__.'/auth.php';
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
        Route::delete('/profile', [MembersController::class,'destroy'])
        ->name('profile.destroy');
    }
);

// ===== Breeze の認証ルート自動読み込み（ログイン／登録など） =====
require __DIR__ . '/auth.php';
