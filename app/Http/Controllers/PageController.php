<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Picture;

class PageController extends Controller
{
    /**
     * トップページ表示
     */
    public function top()
    {
        $image_array = array();
        $user_list = User::all();

        foreach ($user_list as $user) {
            $picture = Picture::where('u_id', $user->id)->orderBy('id', 'desc')->first();

            if ($picture != null) {
                $thumb = file_get_contents(storage_path('app/thumb_images/') . $picture->thumb_name);
                $p_info = pathinfo($picture->thumb_name);

                if ($p_info['extension'] == 'png' || $p_info['extension'] == 'PNG') {
                    $type = "data:image/png;base64,";
                } else {
                    $type = "data:image/jpeg;base64,";
                }

                $image_array[] = array(
                    'id' => $user->id,
                    'user_name' => $user->name,
                    'img' => $type . base64_encode($thumb)
                );
            }
        }

        return view('contents.index', compact('image_array'));
    }

    /**
     * 個別アルバム表示
     */
    public function showAlbum(Request $request)
    {
        $show_user_id = $request->u_id;
        $user = User::find($show_user_id);
        $user_name = $user->name;

        return view('contents.album', compact('show_user_id', 'user_name'));
    }

    /**
     * Ajax用 サムネイルリスト生成
     */
    public function getpics(Request $request)
    {
        $page_count = 24;
        $page_num = $request->page;

        $file_list = Picture::select('id', 'thumb_name', 'title')
            ->where('u_id', $request->u_id)
            ->orderBy('id', 'desc')
            ->get();

        if (count($file_list) < 1) {
            return response("<p class='text-center text-danger'>画像が登録されていません</p>");
        }


        $page_array = array();
        $array_count = 0;
        $count = 0;

        for ($i = 0; $i < count($file_list); $i++) {
            $page_array[$array_count][$count] = $file_list[$i];
            $count++;
            if ($count == $page_count) {
                $count = 0;
                $array_count++;
            }
        }

        $data_list = array();
        foreach ($page_array[$page_num] as $file) {
            $thumb_path = storage_path('app/thumb_images/') . $file->thumb_name;

            if (!file_exists($thumb_path)) {
                continue; // ファイルがなければスキップ
            }

            $thumb = file_get_contents($thumb_path);
            $p_info = pathinfo($file->thumb_name);

            $type = ($p_info['extension'] === 'png' || $p_info['extension'] === 'PNG')
                ? "data:image/png;base64,"
                : "data:image/jpeg;base64,";

            $data_list[] = [
                'id' => $file->id,
                'img' => $type . base64_encode($thumb),
                'title' => $file->title ?? '（タイトルなし）', // ← NULL対策
            ];
        }


        $tab_count = count($page_array);

        return view('ajax.list_only_guest', compact('data_list', 'tab_count', 'page_num'));
    }

    /**
     * Ajax用 抽出表示用マスター画像生成
     */
    public function getmaster(Request $request)
    {
        $main_path = storage_path('app/main_images/');
        $image_file = Picture::find($request->img_id);

        if (!$image_file) {
            return '';
        }

        $p_info = pathinfo($image_file->file_name);

        if ($p_info['extension'] == 'png' || $p_info['extension'] == 'PNG') {
            $type = "data:image/png;base64,";
        } else {
            $type = "data:image/jpeg;base64,";
        }

        $data = file_get_contents($main_path . $image_file->file_name);

        $src = $type . base64_encode($data);
        $str = "<img src='$src' class='w-100' title='{$image_file->title}'>";

        return $str;
    }
}
