<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserPicsController;
use App\Http\Controllers\MembersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Laravel Breeze構成に合わせたルート定義
*/

//
// トップページ（みんなのアルバム一覧）
//
Route::get('/', [PageController::class, 'top'])->name('index');

//
// 静的ページ（About、個人情報、問い合わせ）
//
Route::view('/about', 'contents.about')->name('about');
Route::view('/kojin', 'contents.kojin')->name('kojin');
Route::view('/inquiry', 'contents.inquiry')->name('inquiry');

//
// アルバム表示関連（画像一覧・画像取得）
//
Route::post('/show_album', [PageController::class, 'showAlbum']);
Route::post('/show_pics', [PageController::class, 'getpics']);
Route::post('/pic_up', [PageController::class, 'getmaster']);
Route::get('/show_album', fn () => redirect('/'));

//
// Breeze認証ルート読み込み（ログイン／登録／パスワード再発行など）
//
require __DIR__.'/auth.php';

//
// 認証済みユーザー専用ルート（verified も含む）
//
Route::middleware(['auth', 'verified'])->group(
    function () {
        //
        // マイページ（ダッシュボード）
        //
        Route::get('/dashboard', fn () => view('users.dashboard'))->name('dashboard');

        //
        // 写真アップロードページ
        //
        Route::get('/pic-upload', fn () => view('users.pic_upload'))->name('pic_upload');

        //
        // プロフィール編集
        //
        Route::get('/profile', [MembersController::class, 'modify'])->name('profile.edit');
        Route::patch('/profile', [MembersController::class, 'userChange'])->name('profile.update');
        Route::delete('/profile', [MembersController::class, 'destroy'])->name('profile.destroy');

        // POSTで誤って来たときのリダイレクト
        Route::post('/profile', fn () => redirect('/dashboard'));

        //
        // 写真関連（Ajax：登録／一覧／詳細／削除／タイトル変更）
        //
        Route::post('/save_pics', [UserPicsController::class, 'savepics'])->name('pic.save');
        Route::post('/user_pics', [UserPicsController::class, 'getpics'])->name('pic.list');
        Route::post('/get_master', [UserPicsController::class, 'getmaster'])->name('pic.master');
        Route::post('/save_title', [UserPicsController::class, 'savetitle'])->name('pic.title');
        Route::post('/delete_pic', [UserPicsController::class, 'deletepic'])->name('pic.delete');
    }
);
