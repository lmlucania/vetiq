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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('公開用ID');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade')->comment('病院ID');
            $table->string('name')->comment('診察メニュー名');
            $table->text('detail')->comment('説明');
            $table->integer('required_time')->comment('所要時間');
            $table->boolean('is_published')->comment('公開フラグ');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
