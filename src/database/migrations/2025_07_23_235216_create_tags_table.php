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
        Schema::create('tag_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('タグカテゴリー名');
            $table->integer('display_order')->unique()->comment('表示順序');
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_category_id')->constrained('tag_categories')->onDelete('cascade')->comment('タグカテゴリーID');
            $table->string('name')->unique()->comment('タグ名');
            $table->integer('display_order')->comment('表示順序');
            $table->timestamps();

            $table->unique(['tag_category_id', 'display_order']);
        });

        Schema::create('hospital_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade')->comment('病院ID');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade')->comment('タグID');
            $table->timestamps();

            $table->unique(['hospital_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('hospital_tag');
    }
};
