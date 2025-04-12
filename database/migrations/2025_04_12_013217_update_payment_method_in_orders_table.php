<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentMethodInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Modify the ENUM to include 'momo'
            $table->enum('payment_method', ['cod', 'vnpay', 'momo'])->default('cod')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to the original ENUM values
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod')->change();
        });
    }
}