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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('公開用ID');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade')->comment('病院ID');
            $table->string('title')->comment('タイトル');
            $table->text('detail')->comment('内容');
            $table->boolean('is_published')->comment('公開フラグ');
            $table->date('published_at')->nullable()->comment('公開日付');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
