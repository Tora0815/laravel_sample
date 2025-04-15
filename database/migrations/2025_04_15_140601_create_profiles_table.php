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
            'profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->comment('ユーザーID');
                $table->string('name')->comment('名前');
                $table->integer('gender')->nullable()->comment('性別');
                $table->date('birthday')->nullable()->comment('誕生日'); 
                $table->string('zipcode')->nullable()->comment('郵便番号');
                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }

};
