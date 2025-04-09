<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id'); // Số hóa đơn hoặc mã đơn hàng
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending'); // pending, success, failed
            $table->string('transaction_no')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('response_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
