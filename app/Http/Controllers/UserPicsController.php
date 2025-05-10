<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Picture;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UserPicsController extends Controller
{
    // 画像をストレージに保存 + サムネイル保存
    public function save_pics(Request $request)
    {
        // dd($request->all());

        // 画像形式チェック
        $params = $request->validate(
            [
            'upfile' => 'required|file|image|max:500000',
            ]
        );

        $file = $params['upfile'];

        // 保存用ディレクトリ存在確認
        if (!is_dir(storage_path("app/main_images"))) {
            mkdir(storage_path("app/main_images"));
        }

        if (!is_dir(storage_path("app/thumb_images"))) {
            mkdir(storage_path("app/thumb_images"));
        }

        // パス定義
        $main_path = "main_images";
        $image_path = storage_path("app/main_images/");
        $thumb_path = storage_path("app/thumb_images/");

        // ファイル保存（ランダム名で保存）
        $temp_name = $request->file('upfile')->store($main_path);
        $f_info = getimagesize($file->getRealPath());
        $temp_name = $file->hashName();

        // サムネイル作成（160x160固定サイズ）
        Image::make($file)->resize(
            160, 160, function ($constraint) {
                $constraint->aspectRatio(); // アスペクト比保持
            }
        )->save($image_path . $temp_name);

        $thumb_name = $f_info['filename'] . "_thumb." . $f_info['extension'];
        // サムネイルを別サイズ（300x300）でも保存
        Image::make($file)->resize(
            300, 300, function ($constraint) {
                $constraint->aspectRatio();
            }
        )->save($thumb_path . $thumb_name);

        // DBに保存
        $data = new Picture;
        $data->u_id = $request->u_id;
        $data->file_name = $f_info['basename'];
        $data->thumb_name = $thumb_name;
        $data->save();

        return;
    }

        // サムネイルウィンドウ生成（Ajaxの戻り用）
    public function getpics(Request $request)
    {
        $page_count = 24;

        // **u_idが無ければログアウトさせるorエラーにする**
        if (!$request->filled('u_id')) {
            abort(400, 'ユーザーIDが取得できませんでした');
        }

        $u_id = $request->u_id;
        $page_num = $request->page ?? 0; // pageがnullなら0にする

        $thumb_path = storage_path("app/thumb_images/");
        $file_list = Picture::select('id', 'thumb_name', 'title')
            ->where('u_id', $u_id)
            ->orderBy('id', 'desc')
            ->get();

        if ($file_list->isEmpty()) {
            return;
        }

        $page_array = [];
        $count = 0;
        $array_count = 0;
        for ($i = 0; $i < count($file_list); $i++) {
            $page_array[$array_count][$count] = $file_list[$i];
            $count++;
            if ($count >= $page_count) {
                $count = 0;
                $array_count++;
            }
        }

        // **存在チェックを入れる！**
        if (!isset($page_array[$page_num])) {
            $page_num = 0; // なければ最初のページに戻す
        }

        $data_list = [];
        foreach ($page_array[$page_num] as $file) {
            $thumb = file_get_contents($thumb_path . $file->thumb_name);
            $p_info = pathinfo($file->thumb_name);

            $type = (strtolower($p_info['extension']) == "png") ? "data:image/png;base64," : "data:image/jpeg;base64,";
            $data_list[] = [
            'id' => $file->id,
            'img' => $type . base64_encode($thumb),
            'title' => $file->title
            ];
        }

        $tab_count = count($page_array);

        return view('ajax.list_only', compact('data_list', 'tab_count', 'page_num'));
    }

    // 拡大表示用マスター画像生成（Ajaxの戻り）
    public function getmaster(Request $request)
    {
        $main_path = storage_path("app/main_images/");
        $image_file = Picture::find($request->img_id);

        if (!$image_file) {
            return;
        }

        $p_info = pathinfo($image_file->file_name);

        if ($p_info['extension'] == "png" || $p_info['extension'] == "PNG") {
            $type = "data:image/png;base64,";
        } else {
            $type = "data:image/jpeg;base64,";
        }

        $data = file_get_contents($main_path . $image_file->file_name);
        $src = $type . base64_encode($data);
        $str = "<img src='{$src}' class='w-100' title='{$image_file->title}'>";

        return $str;
    }

    // 画像タイトル設定（Ajaxからの呼び出し）
    public function savetitle(Request $request)
    {
        // dd($request->all());
        $image_file = Picture::find($request->save_id);
        $image_file->title = $request->title;
        $image_file->save();

        return;
    }

    // 画像削除（Ajaxからの呼び出し）
    public function delete(Request $request)
    {
        // dd($request->all());
        $image_file = Picture::find($request->delete_id);
        $main = $image_file->file_name;
        $thumb = $image_file->thumb_name;

        // ストレージからファイル削除
        Storage::delete("main_images/" . $main);
        Storage::delete("thumb_images/" . $thumb);

        // DBレコード削除
        Picture::destroy($request->delete_id);

        return;
    }
}
