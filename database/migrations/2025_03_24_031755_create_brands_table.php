<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable(); // Thương hiệu cha
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable(); // Ảnh thương hiệu
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
