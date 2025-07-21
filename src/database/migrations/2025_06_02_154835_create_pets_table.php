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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ユーザーID');
            $table->string('name')->comment('名前');
            $table->tinyInteger('gender')->comment('性別');
            $table->timestamp('birthday')->nullable()->comment('生年月日');
            $table->timestamp('started_care_at')->nullable()->comment('飼育開始日');
            $table->text('remark')->nullable()->comment('備考');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
