<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 画像投稿用のpicturesテーブルを作成
     */
    public function up(): void
    {
        Schema::create(
            'pictures', function (Blueprint $table) {
                // 主キーID（Laravel標準）
                $table->id();

                // ユーザーID（usersテーブルとリレーション想定）
                $table->unsignedBigInteger('u_id')->comment('ユーザーID');

                // 画像ファイル名
                $table->string('file_name')->comment('ファイル名');

                // サムネイル名
                $table->string('thumb_name')->comment('サムネイル名');

                // 投稿タイトル（任意入力）
                $table->string('title')->nullable()->comment('タイトル');

                // 種別を表すタイプフラグ（例：通常投稿、特別投稿など）
                $table->integer('type_flag')->default(0)->comment('タイプフラグ');

                // 削除・無効などの管理用フラグ
                $table->integer('kanri_flag')->default(0)->comment('管理フラグ');

                // created_at / updated_at を自動で追加
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     * テーブル削除時の処理
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};
