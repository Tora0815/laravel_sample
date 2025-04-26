<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserPicsController;
use App\Http\Controllers\MembersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group. Now create something great!
*/

// トップページ（みんなのアルバム）
Route::get('/', [PageController::class, 'top'])->name('index');

// 静的ページ（個人情報・お問い合わせ）
Route::get(
    '/kojin', function () {
        return view('contents.kojin');
    }
);
Route::get(
    '/inquiry', function () {
        return view('contents.inquiry');
    }
);

// アルバム表示関連
Route::post('/show_album', [PageController::class, 'showAlbum']);
Route::post('/show_pics', [PageController::class, 'getpics']);
Route::post('/pic_up', [PageController::class, 'getmaster']);

// /show_album にGETでアクセスされたときのエラー防止リダイレクト
Route::get(
    '/show_album', function () {
        return redirect('/');
    }
);

// ログイン後のマイページ関連（ミドルウェアauth適用）
Route::get(
    '/dashboard', function () {
        return view('users.dashboard');
    }
)->middleware(['auth'])->name('dashboard');

// 写真アップロードページ（ログイン必須）
Route::get(
    '/pic_upload', function () {
        return view('users.pic_upload');
    }
)->middleware(['auth']);

// プロフィール編集ページ（ログイン必須）
Route::get('/profile', [MembersController::class, 'modify'])->middleware(['auth']);

// プロフィール更新処理（ログイン必須）
Route::patch('/profile', [MembersController::class, 'userChange'])->middleware(['auth']);

// 写真関連（Ajax用）
Route::post('/user_pics', [UserPicsController::class, 'getpics'])->middleware(['auth']);
Route::post('/save_pics', [UserPicsController::class, 'savepics'])->middleware(['auth']);
Route::post('/get_master', [UserPicsController::class, 'getmaster'])->middleware(['auth']);
Route::post('/delete_pic', [UserPicsController::class, 'deletepic'])->middleware(['auth']);
Route::post('/save_title', [UserPicsController::class, 'savetitle'])->middleware(['auth']);

// Breeze認証ルート（ログイン・ログアウトなど）
require __DIR__.'/auth.php';
