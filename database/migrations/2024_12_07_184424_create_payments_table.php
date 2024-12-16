<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->decimal('amount', 10, 2);
            $table->string('payment_type')->default('deposit'); // deposit: đặt cọc, final: thanh toán cuối
            $table->string('payment_method')->default('vnpay');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_ref')->nullable();
            $table->string('status');
            $table->timestamp('payment_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
