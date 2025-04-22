<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'pictures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('u_id')->comment('ユーザーID');
                $table->string('file_name')->comment('ファイル名');
                $table->string('thumb_name')->comment('サムネイル名');
                $table->string('title')->nullable()->comment('タイトル');
                $table->integer('type_flag')->default(0)->comment('タイプフラグ');
                $table->integer('kanri_flag')->default(0)->comment('管理フラグ');
                $table->timestamps();
            }
        );
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};
