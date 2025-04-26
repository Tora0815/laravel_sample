<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

class MembersController extends Controller
{
    /**
     * ユーザープロフィールの表示
     */
    public function modify(Request $request)
    {
        // ログイン中ユーザーIDを強制取得
        $u_id = auth()->id();

        $master_data = User::find($u_id);
        $sub_data = Profile::where('u_id', $u_id)->get();

        return view('users.profile', compact('master_data', 'sub_data'));
    }

    /**
     * ユーザープロフィールの更新
     */
    public function userChange(Request $request)
    {
        if ($request->has('change')) {
            $u_id = auth()->id(); // ログインユーザーID

            // ユーザー名更新
            $user = User::find($u_id);
            if ($user->name !== $request->u_name) {
                $user->name = $request->u_name;
                $user->save();
            }

            // プロフィール情報更新
            $profile = Profile::firstOrNew(['u_id' => $u_id]);
            $profile->u_yubin  = $request->u_yubin;
            $profile->u_jusho1 = $request->u_jusho1;
            $profile->u_jusho2 = $request->u_jusho2;
            $profile->u_jusho3 = $request->u_jusho3;
            $profile->u_tel    = $request->u_tel;
            $profile->u_biko   = $request->u_biko;
            $profile->save();

            return redirect('dashboard');
        }

        return redirect('dashboard');
    }
}
