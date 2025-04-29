<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserPicsController;
use App\Http\Controllers\MembersController;

/**
 * --------------------------------------------
 * Webルート設定ファイル (p.216–217準拠)
 * --------------------------------------------
 *
 * ・このファイルは、画面表示やAjax通信など、Webリクエスト全般を管理します。
 * ・認証済みユーザー専用ルートには auth ミドルウェアを適用。
 * ・Breeze の認証関連ルートは最後に require で読み込み。
 *
 * 注意：
 * - GET/POST/PATCH の HTTP メソッドを適切に使い分け
 * - 名前付きルート（->name()）は Blade や JS 側で参照されるため必須
 * - 静的ページはクロージャ関数で簡潔に定義
 */


/*
|--------------------------------------------------------------------------
| ゲストページ（認証不要）
|--------------------------------------------------------------------------
| トップページ／静的なコンテンツ／ゲスト用フォームの Ajax
*/

// ─── トップページ （みんなのアルバム一覧） ─────────────────────────────
Route::get('/', [PageController::class, 'top'])
    ->name('index');

// ─── 静的ページ（About、個人情報、問い合わせ） ─────────────────────────
Route::get('/about',   fn() => view('contents.about'))
    ->name('about');
Route::get('/kojin',   fn() => view('contents.kojin'))
    ->name('kojin');
Route::get('/inquiry', fn() => view('contents.inquiry'))
    ->name('inquiry');

// ─── アルバム表示関連（Ajax：サムネイル一覧／マスター画像取得） ──────────────
Route::post('/show_album', [PageController::class, 'showAlbum'])
    ->name('show_album');
Route::post('/show_pics',  [PageController::class, 'getpics'])
    ->name('show_pics');
Route::post('/pic_up',     [PageController::class, 'getmaster'])
    ->name('pic_up');
// GET で /show_album に来た場合の安全なリダイレクト
Route::get('/show_album', fn() => redirect('/'));



/*
|--------------------------------------------------------------------------
| 会員ページ（認証必須：auth ミドルウェア適用）
|--------------------------------------------------------------------------
| ダッシュボード／プロフィール編集／写真投稿機能の Ajax
*/
Route::middleware(['auth'])->group(
    function () {
        // ─── マイページ（ダッシュボード） ─────────────────────────────────
        Route::get('/dashboard', fn() => view('users.dashboard'))
        ->name('dashboard');

        // ─── 写真アップロード画面 ────────────────────────────────────────────
        Route::get('/pic_upload', fn() => view('users.pic_upload'))
        ->name('pic_upload');

        // ─── プロフィール編集 ───────────────────────────────────────────────
        // GET：/profile への直接アクセスはダッシュボードにリダイレクト
        Route::get('/profile', fn() => redirect('/dashboard'))
        ->name('profile.edit');
        // POST：MembersController@modify へフォームデータを送信
        Route::post('/profile', [MembersController::class, 'modify'])
        ->name('profile.edit');
        // PATCH：プロフィール情報更新（MembersController@userChange）
        Route::patch('/profile', [MembersController::class, 'userChange'])
        ->name('profile.update');

        // ─── 写真管理（Ajax：一覧取得／保存／拡大表示／削除／タイトル更新） ────────
        Route::post('/user_pics',  [UserPicsController::class, 'getpics'])
        ->name('user_pics');
        Route::post('/save_pics',  [UserPicsController::class, 'savepics'])
        ->name('save_pics');
        Route::post('/get_master', [UserPicsController::class, 'getmaster'])
        ->name('get_master');
        Route::post('/delete_pic', [UserPicsController::class, 'deletepic'])
        ->name('delete_pic');
        Route::post('/save_title', [UserPicsController::class, 'savetitle'])
        ->name('save_title');
    }
);


/*
|--------------------------------------------------------------------------
| Breeze 認証ルート（ログイン／登録／パスワードリセットなど）
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
