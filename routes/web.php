<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserPicsController;
use App\Http\Controllers\MembersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Breeze構成に基づいたルート定義（Laravel 12 + Breeze + Vite）
*/

/**
 * トップページ（全体公開）
 */
Route::get('/', [PageController::class, 'top'])->name('index');

/**
 * 静的ページ（公開）
 */
Route::view('/about', 'contents.about')->name('about');
Route::view('/kojin', 'contents.kojin')->name('kojin');
Route::view('/inquiry', 'contents.inquiry')->name('inquiry');

/**
 * 公開アルバム関連（画像表示・取得用Ajax）
 */
Route::post('/show_album', [PageController::class, 'showAlbum']);
Route::post('/show_pics', [PageController::class, 'getpics']);
Route::post('/pic_up', [PageController::class, 'getmaster']);
Route::get('/show_album', fn () => redirect('/')); // POST誤アクセス対策

/**
 * 認証ルート（Breezeが提供する login / register / password 機能）
 */
require __DIR__.'/auth.php';

/**
 * 認証・メール確認済ユーザー専用ルート
 */
Route::middleware(['auth', 'verified'])->group(
    function () {

        /**
         * マイページ（ログイン後のダッシュボード）
         */
        Route::get('/dashboard', fn () => view('users.dashboard'))->name('dashboard');

        /**
         * 写真アップロード画面（フォーム + Ajax表示領域）
         */
        Route::get('/pic-upload', fn () => view('users.pic_upload'))->name('pic_upload');

        /**
         * プロフィール編集
         */
        Route::get('/profile', [MembersController::class, 'modify'])->name('profile.edit');
        Route::patch('/profile', [MembersController::class, 'userChange'])->name('profile.update');
        Route::delete('/profile', [MembersController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile', fn () => redirect('/dashboard')); // POST誤送信時の対応

        /**
         * 写真機能（全てAjaxで使用される）
         */
        Route::post('/save_pics', [UserPicsController::class, 'savepics'])->name('pic.save');

        // Ajaxページネーション対応
        // routes/web.php を以下のように修正
        Route::post('/user_pics', [UserPicsController::class, 'getpics'])->name('user_pics');

        Route::post('/get_master', [UserPicsController::class, 'getmaster'])->name('pic.master');
        Route::post('/save_title', [UserPicsController::class, 'savetitle'])->name('pic.title');
        Route::post('/delete_pic', [UserPicsController::class, 'deletepic'])->name('pic.delete');
    }
);
