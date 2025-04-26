<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Picture;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UserPicsController extends Controller
{
    /**
     * ユーザー画像のアップロードとサムネイル作成
     */
    public function upload(Request $request)
    {
        // バリデーション（表示力の最大倍率）を保つ
        $request->validate(
            [
            'upfile' => 'required|file|image|max:500000',
            ]
        );

        $file = $request->file('upfile');

        // サムネイル保存用パスを作成
        $main_path = storage_path('app/main_images/');
        $thumb_path = storage_path('app/thumb_images/');

        if (!is_dir($main_path)) { mkdir($main_path, 0755, true);
        }
        if (!is_dir($thumb_path)) { mkdir($thumb_path, 0755, true);
        }

        // メイン画像を保存
        $main_name = $file->store('main_images');

        // サムネイル画像を作成
        $f_info = pathinfo($file->getRealPath());
        $temp_name = md5(file_get_contents($file->getRealPath()));
        $temp_name .= '.' . $f_info['extension'];

        Image::make($file)
            ->resize(
                1600, 1600, function ($constraint) {
                    $constraint->aspectRatio();
                }
            )
            ->save($main_path . $temp_name);

        Image::make($file)
            ->resize(
                300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                }
            )
            ->save($thumb_path . $temp_name);

        // DBに登録
        $picture = new Picture();
        $picture->u_id = auth()->id();
        $picture->thumb_name = $temp_name;
        $picture->save();

        return;
    }

    /**
     * Ajax用 サムネイルリスト表示
     */
    public function getpics(Request $request)
    {
        $file_list = Picture::where('u_id', auth()->id())
            ->orderBy('id')
            ->get();

        $page_count = 24; // 1ページの表示件数
        $array_count = 0;
        $page_array = array();

        foreach ($file_list as $file) {
            $page_array[$array_count][] = $file;
            if (count($page_array[$array_count]) >= $page_count) {
                $array_count++;
            }
        }

        return view('ajax.list_only', compact('page_array'));
    }

    /**
     * Ajax用 抽出表示用マスター画像生成
     */
    public function getmaster(Request $request)
    {
        $main_path = storage_path('app/main_images/');
        $image_file = Picture::find($request->img_id);

        if (!$image_file) { return '';
        }

        $data = file_get_contents($main_path . $image_file->thumb_name);
        $src = 'data:image/png;base64,' . base64_encode($data);

        return '<img src="' . $src . '" class="w-100" title="' . $image_file->title . '">';
    }

    /**
     * Ajax用 タイトル書き換え
     */
    public function savetitle(Request $request)
    {
        $image_file = Picture::find($request->save_id);

        if ($image_file) {
            $image_file->title = $request->title;
            $image_file->save();
        }

        return;
    }

    /**
     * Ajax用 画像削除
     */
    public function deletepic(Request $request)
    {
        $image_file = Picture::find($request->delete_id);

        if ($image_file) {
            $main_path = storage_path('app/main_images/');
            $thumb_path = storage_path('app/thumb_images/');

            Storage::delete('main_images/' . $image_file->thumb_name);
            Storage::delete('thumb_images/' . $image_file->thumb_name);

            $image_file->delete();
        }

        return;
    }
}
