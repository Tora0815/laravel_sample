<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Picture;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserPicsController extends Controller
{
    /**
     * Ajax：画像保存
     */
    public function savepics(Request $request)
    {
        // バリデーション
        $request->validate(
            [
            'upfile' => 'required|image|max:5120', // 5MBまで
            ]
        );

        $file = $request->file('upfile');
        $uid = $request->input('u_id');

        // 画像名の生成
        $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();

        // 保存先パス
        $mainPath = storage_path('app/main_images/');
        $thumbPath = storage_path('app/thumb_images/');

        // ディレクトリがなければ作成
        if (!is_dir($mainPath)) { mkdir($mainPath, 0755, true);
        }
        if (!is_dir($thumbPath)) { mkdir($thumbPath, 0755, true);
        }

        // Intervention Image を使って保存
        \Intervention\Image\Facades\Image::make($file)
            ->resize(
                1600, 1600, function ($c) {
                    $c->aspectRatio();
                }
            )->save($mainPath . $filename);

        \Intervention\Image\Facades\Image::make($file)
            ->resize(
                300, 300, function ($c) {
                    $c->aspectRatio();
                }
            )->save($thumbPath . $filename);

        // データベースに登録
        \App\Models\Picture::create(
            [
            'u_id' => $uid,
            'thumb_name' => $filename,
            'title' => null,
            ]
        );

        return response()->json(['message' => '画像を保存しました']);
    }

    /**
     * Ajax：画像一覧取得
     */
    public function getpics(Request $request)
    {
        $file_list = Picture::where('u_id', auth()->id())->orderBy('id')->get();

        $page_count = 24;
        $array_count = 0;
        $page_array = [];

        foreach ($file_list as $file) {
            $page_array[$array_count][] = $file;
            if (count($page_array[$array_count]) >= $page_count) {
                $array_count++;
            }
        }

        $page_num = 0;
        $data_list = $page_array[$page_num] ?? [];
        $tab_count = count($page_array);

        return view('ajax.list_only', compact('data_list', 'tab_count', 'page_num'));
    }

    /**
     * Ajax：画像詳細取得（モーダル用）
     */
    public function getmaster(Request $request)
    {
        $main_path = storage_path('app/main_images/');
        $image_file = Picture::find($request->img_id);

        if (!$image_file) {
            return '';
        }

        $data = file_get_contents($main_path . $image_file->thumb_name);
        $src = 'data:image/png;base64,' . base64_encode($data);

        return '<img src="' . $src . '" class="w-100" title="' . $image_file->title . '">';
    }

    /**
     * Ajax：タイトル保存
     */
    public function savetitle(Request $request)
    {
        $image_file = Picture::find($request->save_id);
        if ($image_file) {
            $image_file->title = $request->title;
            $image_file->save();
        }
        return response()->noContent();
    }

    /**
     * Ajax：画像削除
     */
    public function deletepic(Request $request)
    {
        $image_file = Picture::find($request->delete_id);
        if ($image_file) {
            Storage::delete('main_images/' . $image_file->thumb_name);
            Storage::delete('thumb_images/' . $image_file->thumb_name);
            $image_file->delete();
        }
        return response()->noContent();
    }
}
