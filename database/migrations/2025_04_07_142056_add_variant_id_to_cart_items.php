<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};
