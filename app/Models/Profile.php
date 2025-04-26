<?php

<<<<<<< HEAD
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles'; // テーブル名（省略可だけど明記しておくと安心）

=======
/**
 * app/Models/Profile.php
 * ─────────────────────────────────────────────
 * ユーザーの拡張プロフィール情報を管理する Eloquent モデル
 *
 * ・users テーブルの追加情報（郵便番号・住所・電話番号・備考など）を扱う
 * ・MembersController や ProfileController から呼び出して利用
 * ・fillable による一括代入（Mass Assignment）を許可
 * ・必要に応じてリレーション定義も追加
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // users テーブルのリレーション定義用

class Profile extends Model
{
    /**
     * 対応するテーブル名
     * デフォルトではクラス名のスネークケース複数形 ("profiles") を使用します
     */
    // protected $table = 'profiles';

    /**
     * 一括代入を許可するカラム
     * ・u_id        : users.id を参照する外部キー
     * ・yubin       : 郵便番号
     * ・jusho1～3   : 住所（都道府県、市区町村・番地、建物名）
     * ・tel         : 電話番号
     * ・biko        : 備考（任意入力）
     * ・type_flg    : タイプフラグ（将来のステータス管理用）
     * ・kanri_flg   : 管理フラグ（将来の権限管理用）
     */
>>>>>>> ed55a1803453edc0d4250481b6a1843aa385d05a
    protected $fillable = [
        'u_id',
        'yubin',
        'jusho1',
        'jusho2',
        'jusho3',
        'tel',
        'biko',
<<<<<<< HEAD
        'kari_flag',
        'kanri_flag',
    ];
=======
        'type_flg',
        'kanri_flg',
    ];

    /**
     * users テーブルとのリレーション（多対１：belongsTo）
     * このプロフィール情報を所有するユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }
>>>>>>> ed55a1803453edc0d4250481b6a1843aa385d05a
}
