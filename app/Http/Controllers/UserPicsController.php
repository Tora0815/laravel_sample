<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Picture;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class UserPicsController extends Controller
{
    // 画像をストレージに保存 + サムネイル保存
    public function savePics(Request $request)
    {
        $user_id = Auth::id();
        if (!$user_id) {
            return response()->json(['message' => 'ログインが必要です'], 401);
        }

        $params = $request->validate(
            [
            'upload_file' => 'required|file|image|max:500000',
            ]
        );

        $file = $params['upload_file'];

        // 保存ディレクトリを作成（なければ）
        if (!is_dir(storage_path('app/main_images'))) {
            mkdir(storage_path('app/main_images'), 0777, true);
        }
        if (!is_dir(storage_path('app/thumb_images'))) {
            mkdir(storage_path('app/thumb_images'), 0777, true);
        }

        try {
            $manager = new ImageManager(new Driver());

            $temp_name = $file->hashName();

            // 元画像を保存
            $file->storeAs('main_images', $temp_name);

            $main_path = storage_path('app/main_images/');
            $thumb_path = storage_path('app/thumb_images/');

            // サムネイル（160x160）
            $manager->read($file->getRealPath())
                ->resize(
                    160, 160, function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )
                ->save($main_path . $temp_name);

            $thumb_name = pathinfo($temp_name, PATHINFO_FILENAME) . '_thumb.' . pathinfo($temp_name, PATHINFO_EXTENSION);

            // サムネイル（300x300）
            $manager->read($file->getRealPath())
                ->resize(
                    300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )
                ->save($thumb_path . $thumb_name);

            // DB保存
            $picture = new Picture();
            $picture->u_id = $user_id;
            $picture->file_name = $temp_name;
            $picture->thumb_name = $thumb_name;
            $picture->title = '';
            $picture->save();

            return response()->json(['message' => 'アップロード完了！']);

        } catch (\Exception $e) {
            return response()->json(['message' => '画像アップロード中にエラーが発生しました'], 500);
        }
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
    public function getMaster(Request $request)
    {
        //レコード取得
        $image_file = Picture::find($request->img_id);
        if (! $image_file) {
            return response('Not Found', 404);
        }

        //サムネイル画像の Base64 を作成
        $thumbPath = storage_path("app/thumb_images/") . $image_file->thumb_name;
        $data = file_get_contents($thumbPath);
        $src = 'data:image/jpeg;base64,' . base64_encode($data);

        //プレビュー用の <img> タグ（必ず preview-trigger と data-pic-id を付与）
        $html = "<img
        src='{$src}'
        class='w-100 preview-trigger'
        data-pic-id='{$image_file->id}'
        title='{$image_file->title}'>";

        //レスポンスを返す
        return response($html, 200)
        ->header('Content-Type', 'text/html');
    }

    // 画像タイトル設定（Ajaxからの呼び出し）
    public function savetitle(Request $request)
    {
        \Log::debug('saveTitle called', $request->all());
        // dd($request->all());
        $image_file = Picture::find($request->save_id);
        $image_file->title = $request->title;
        $image_file->save();

        return;
    }

    // 画像削除（Ajaxからの呼び出し）
    public function deletePics(Request $request)
    {
        \Log::debug('deletePics called', $request->all());

        $pic = Picture::find($request->delete_id);
        if (! $pic) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        // storage/app 配下のフルパスでファイル削除
        $main_path = storage_path("app/main_images/{$pic->file_name}");
        $thumb_path = storage_path("app/thumb_images/{$pic->thumb_name}");

        if (file_exists($main_path)) {
            unlink($main_path);
            \Log::info("削除成功: $main_path");
        }

        if (file_exists($thumb_path)) {
            unlink($thumb_path);
            \Log::info("削除成功: $thumb_path");
        }

        // DB削除
        $pic->delete();
        \Log::info("DBレコード削除: picture_id={$pic->id}");

        return response()->json(['success' => true]);
    }

}
