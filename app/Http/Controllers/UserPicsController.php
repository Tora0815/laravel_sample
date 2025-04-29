<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Picture;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * UserPicsController
 *
 * 会員の写真アップロード機能を担うコントローラー。
 * 画像の保存、サムネイル生成、一覧取得、拡大表示、タイトル更新、削除を
 * Ajax 経由で実装します。
 */
class UserPicsController extends Controller
{
    /**
     * 画像をストレージに保存 + サムネイル生成
     *
     * @param  Request $request
     * @return void
     */
    public function savepics(Request $request)
    {
        // バリデーション：必須・画像ファイル・サイズ制限
        $request->validate(
            [
            'upfile' => 'required|file|image|max:500000',
            ]
        );

        // アップロードされたファイルを取得
        $file = $request->file('upfile');

        // メイン画像・サムネイル保存先ディレクトリを準備
        $mainPath  = storage_path('app/main_images/');
        $thumbPath = storage_path('app/thumb_images/');
        if (!is_dir($mainPath)) { mkdir($mainPath, 0755, true);
        }
        if (!is_dir($thumbPath)) { mkdir($thumbPath, 0755, true);
        }

        // メイン画像を保存（ストレージ/app/main_images）
        $savedMain = $file->store('main_images');

        // 一時ファイル名をハッシュ化して拡張子を付与
        $info = pathinfo($file->getRealPath());
        $hash = md5(file_get_contents($file->getRealPath()));
        $filename = $hash . '.' . $info['extension'];

        // Intervention Image でメイン画像をリサイズし保存
        Image::make($file)
            ->resize(
                1600, 1600, function ($constraint) {
                    $constraint->aspectRatio();
                }
            )
            ->save($mainPath . $filename);

        // サムネイルをリサイズして保存
        Image::make($file)
            ->resize(
                300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                }
            )
            ->save($thumbPath . $filename);

        // DB にレコード登録
        $pic = new Picture();
        $pic->u_id       = auth()->id();       // ログイン中ユーザーID
        $pic->thumb_name = $filename;           // サムネイルファイル名
        $pic->save();                           // 保存

        // 明示的なレスポンス不要（Ajax の success を利用）
        return;
    }

    /**
     * Ajax 用：サムネイル一覧表示
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function getpics(Request $request)
    {
        // ユーザーごとの画像を取得し、ページング配列に分割
        $all = Picture::where('u_id', auth()->id())
                    ->orderBy('id')
                    ->get();
        $perPage = 24;
        $pages = [];
        foreach ($all as $index => $pic) {
            $page = floor($index / $perPage);
            $pages[$page][] = $pic;
        }

        // 現在ページ番号
        $pageNum = (int) $request->page_num;
        $dataList = [];
        // 表示用：Base64 エンコードしつつ配列に格納
        foreach ($pages[$pageNum] ?? [] as $pic) {
            $raw = file_get_contents(storage_path('app/thumb_images/' . $pic->thumb_name));
            $mime = strpos($pic->thumb_name, '.png') !== false ? 'data:image/png;base64,' : 'data:image/jpeg;base64,';
            $dataList[] = [
                'id'   => $pic->id,
                'img'  => $mime . base64_encode($raw),
                'title'=> $pic->title,
            ];
        }

        $tabCount = count($pages);
        // Ajax 部分テンプレートへ渡す
        return view('ajax.list_only', compact('dataList', 'tabCount', 'pageNum'));
    }

    /**
     * Ajax 用：拡大表示用マスター画像取得
     *
     * @param  Request $request
     * @return string (HTML imgタグ)
     */
    public function getmaster(Request $request)
    {
        $pic = Picture::find($request->img_id);
        if (!$pic) {
            return '';
        }
        // ファイル本体を読み込み base64 化
        $path = storage_path('app/main_images/' . $pic->thumb_name);
        $raw = file_get_contents($path);
        $mime = strpos($pic->thumb_name, '.png') !== false ? 'data:image/png;base64,' : 'data:image/jpeg;base64,';
        $src = $mime . base64_encode($raw);
        // 成形して返却
        return "<img src='{$src}' class='w-100' title='{$pic->title}'>";
    }

    /**
     * Ajax 用：画像タイトル更新
     *
     * @param  Request $request
     * @return void
     */
    public function savetitle(Request $request)
    {
        $pic = Picture::find($request->save_id);
        if ($pic) {
            $pic->title = $request->title;
            $pic->save();
        }
        return;
    }

    /**
     * Ajax 用：画像削除
     *
     * @param  Request $request
     * @return void
     */
    public function deletepic(Request $request)
    {
        $pic = Picture::find($request->delete_id);
        if ($pic) {
            // ストレージからファイル削除
            Storage::delete('main_images/' . $pic->thumb_name);
            Storage::delete('thumb_images/' . $pic->thumb_name);
            // DB レコード削除
            $pic->delete();
        }
        return;
    }
}
