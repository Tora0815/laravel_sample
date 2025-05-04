<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Picture;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserPicsController extends Controller
{
    /**
     * Ajax：画像保存
     */
    public function savepics(Request $request)
    {
        // バリデーション（画像ファイル、最大5MB）
        $request->validate(
            [
            'upfile' => 'required|image|max:5120',
            ]
        );

        $file = $request->file('upfile');
        $uid = $request->input('u_id');

        if (!$file || !$uid) {
            return response()->json(['error' => 'ファイルまたはユーザーIDが不足しています'], 400);
        }

        try {
            Log::debug('▶ 保存開始');

            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();

            $mainPath = storage_path('app/main_images/');
            $thumbPath = storage_path('app/thumb_images/');

            if (!is_dir($mainPath)) { mkdir($mainPath, 0755, true);
            }
            if (!is_dir($thumbPath)) { mkdir($thumbPath, 0755, true);
            }

            Log::debug('▶ メイン画像保存前');
            $manager = new ImageManager(new Driver());
            $manager->read($file->getRealPath())
                ->scale(width: 1600)
                ->save($mainPath . $filename);

            Log::debug('▶ サムネイル保存前');
            $manager->read($file->getRealPath())
                ->scale(width: 300)
                ->save($thumbPath . $filename);

            Log::debug('▶ DB登録前');
            Picture::create(
                [
                'u_id' => $uid,
                'file_name' => $originalName,
                'thumb_name' => $filename,
                'title' => null,
                'type_flag' => 0,
                'kanri_flag' => 0,
                ]
            );

            Log::debug('✅ 完了');

            return response()->json(['message' => '画像を保存しました'], 200);

        } catch (\Exception $e) {
            Log::error('❌ エラー発生: ' . $e->getMessage());
            return response()->json(['error' => '画像保存時エラー: ' . $e->getMessage()], 500);
        }
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
