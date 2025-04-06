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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('variant_type')->nullable(); // Ví dụ: "Loại bìa", "Kích thước"
            $table->string('variant_value')->nullable(); // Ví dụ: "Bìa cứng", "A4", "A5"
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->nullable(); // nếu có giá riêng cho biến thể
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
