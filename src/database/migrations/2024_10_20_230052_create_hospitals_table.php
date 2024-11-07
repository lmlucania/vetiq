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
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('公開用ID');
            $table->string('name')->comment('病院名');
            $table->string('zipcode')->comment('郵便番号');
            $table->string('address')->comment('住所');
            $table->string('phone')->comment('電話番号');
            $table->boolean('is_published')->comment('公開フラグ');
            $table->timestamp('deleted_at')->nullable()->comment('削除日時');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
