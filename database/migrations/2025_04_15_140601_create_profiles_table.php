<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * プロフィール情報を格納するテーブル
     */
    public function up(): void
    {
        Schema::create(
            'profiles', function (Blueprint $table) {
                $table->id();
                // usersテーブルとのリレーション用ID（ユニーク制約付き）
                $table->unsignedBigInteger('u_id')->unique()->comment('ユーザーID');

                // 住所・連絡先など基本情報（NULL許容）
                $table->string('yubin')->nullable()->comment('郵便番号');
                $table->string('jusho1')->nullable()->comment('住所1');
                $table->string('jusho2')->nullable()->comment('住所2');
                $table->string('jusho3')->nullable()->comment('住所3');
                $table->string('tel')->nullable()->comment('電話番号');

                // 自己紹介文（長文）
                $table->text('biko')->nullable()->comment('備考');

                // ユーザーの分類やステータス（数値で管理）
                $table->integer('type_flag')->default(0)->comment('タイプフラグ');
                $table->integer('kanri_flag')->default(0)->comment('管理フラグ');

                // 登録・更新日時（Laravel標準）
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     * テーブルを削除
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
