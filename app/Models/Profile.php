<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'profiles', function (Blueprint $table) {
                // ───────────────────────────────────────────
                // 主キーID（自動インクリメント）
                // ───────────────────────────────────────────
                $table->id();

                // ───────────────────────────────────────────
                // ユーザーID：users テーブルの id を参照
                // → unique() で一意、comment() でカラムの用途を明示
                // ───────────────────────────────────────────
                $table->unsignedBigInteger('u_id')
                    ->unique()
                    ->comment('ユーザー ID');

                // ───────────────────────────────────────────
                // 郵便番号・住所・電話番号・備考など
                // nullable() で「初回未登録時にも対応」
                // comment() で管理者・チーム間の認識ズレ防止
                // ───────────────────────────────────────────
                $table->string('yubin')
                    ->nullable()
                    ->comment('郵便番号');

                $table->string('jusho1')
                    ->nullable()
                    ->comment('住所1');

                $table->string('jusho2')
                    ->nullable()
                    ->comment('住所2');

                $table->string('jusho3')
                    ->nullable()
                    ->comment('住所3');

                $table->string('tel')
                    ->nullable()
                    ->comment('電話番号');

                $table->text('biko')
                    ->nullable()
                    ->comment('備考');

                // ───────────────────────────────────────────
                // フラグ類：後々のステータス管理や権限制御用
                // default() で未指定時の挙動を確実化
                // ───────────────────────────────────────────
                $table->integer('type_flg')
                    ->default(0)
                    ->comment('タイプフラグ');

                $table->integer('kanri_flg')
                    ->default(0)
                    ->comment('管理フラグ');

                // ───────────────────────────────────────────
                // created_at / updated_at
                // ───────────────────────────────────────────
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // profiles テーブルを削除
        Schema::dropIfExists('profiles');
    }
}
