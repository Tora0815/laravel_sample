<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            'pictures', function (Blueprint $table) {
                $table->id(); // 主キー

                // ユーザーID（紐付け用）
                $table->unsignedBigInteger('u_id')->comment('ユーザーID');

                // 各種画像ファイル情報
                $table->string('file_name')->comment('ファイル名');
                $table->string('thumb_name')->comment('サムネイル名');
                $table->string('title')->comment('タイトル');

                // 管理フラグ・分類フラグなど
                $table->integer('type_flag')->default(0)->comment('タイプフラグ');
                $table->integer('kanri_flag')->default(0)->comment('管理フラグ');

                $table->timestamps(); // 作成日時・更新日時
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('pictures');
    }
}
