<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

class MembersController extends Controller
{
    /**
     * ユーザープロフィールの表示
     * プロフィール編集画面の表示（GET /profile）
     */
    public function modify()
    {
        // ログイン中ユーザーIDを取得
        $u_id = Auth::id();

        // ユーザーの基本情報を取得
        $master_data = User::find($u_id);

        // プロフィール詳細情報を取得
        $sub_data = Profile::where('u_id', $u_id)->get();

        // 編集画面へデータを渡して表示
        return view('users.profile', compact('master_data', 'sub_data'));
    }

    /**
     * プロフィール更新処理（PATCH /profile）
     *
     * ・フォーム送信された情報を Profiles に保存（更新 or 新規作成）
     * ・完了後、ダッシュボードへリダイレクト
     */
    public function userChange(Request $request)
    {
        $u_id = Auth::id();

        // 1) ユーザー基本情報の更新（名前のみ）
        $user = User::find($u_id);
        $user->name = $request->input('u_name');
        $user->save();

        // 2) プロフィール詳細情報の更新 or 新規作成
        $profile = Profile::firstOrNew(['u_id' => $u_id]);
        $profile->yubin   = $request->input('u_yubin');
        $profile->jusho1  = $request->input('u_jusho1');
        $profile->jusho2  = $request->input('u_jusho2');
        $profile->jusho3  = $request->input('u_jusho3');
        $profile->tel     = $request->input('u_tel');
        $profile->biko    = $request->input('u_biko');
        $profile->save();

        // 更新完了後はダッシュボードへリダイレクト
        return redirect()->route('dashboard');
    }
}
