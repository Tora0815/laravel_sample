<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

class MembersController extends Controller
{
    /**
     * ユーザープロフィールの表示
     *
     * ・ログインユーザーIDを受け取り
     * ・Users テーブルから基本情報取得
     * ・Profiles テーブルから追加情報取得
     * ・users.profile ビューへ渡す
     */
    public function modify(Request $request)
    {
        // 1) リクエストからユーザーID取得
        $u_id = $request->u_id;

        // 2) Users テーブルからユーザー情報取得
        $master_data = User::find($u_id);

        // 3) Profiles テーブルから該当レコードを取得
        $sub_data = Profile::where('u_id', $u_id)->get();

        // 4) ビューにデータを渡して表示
        return view('users.profile', compact('master_data', 'sub_data'));
    }

    /**
     * ユーザープロフィールの更新（保存）
     *
     * ・フォームから送られた情報を Profiles テーブルに保存
     * ・完了後、ダッシュボードへリダイレクト
     */
    public function userChange(Request $request)
    {
        // フォームの「変更」ボタン押下時
        if ($request->has('change')) {
            // Userモデル側の名前変更（例）
            $user = User::find($request->u_id);
            if ($user->name !== $request->u_name) {
                $user->name = $request->u_name;
                $user->save();
            }

            // Profile モデル側の詳細を更新 or 作成
            $profile = Profile::firstOrNew(['u_id' => $request->u_id]);
            $profile->u_yubin  = $request->u_yubin;
            $profile->u_jusho1 = $request->u_jusho1;
            $profile->u_jusho2 = $request->u_jusho2;
            $profile->u_jusho3 = $request->u_jusho3;
            $profile->u_tel    = $request->u_tel;
            $profile->u_biko   = $request->u_biko;
            $profile->save();

            // 保存完了後はダッシュボードへ
            return redirect('dashboard');
        }

        // フォームの「キャンセル」押下時
        return redirect('dashboard');
    }
}
