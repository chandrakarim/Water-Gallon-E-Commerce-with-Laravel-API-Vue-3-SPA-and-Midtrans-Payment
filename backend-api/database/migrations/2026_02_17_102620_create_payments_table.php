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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('payment_method'); // TRANSFER / COD
            $table->string('payment_proof')->nullable();

            $table->enum('status', ['PENDING', 'PAID', 'REJECTED'])->default('PENDING');

            $table->timestamp('paid_at')->nullable();

            $table->string('gateway')->nullable(); // MIDTRANS / MANUAL / COD
            $table->string('transaction_id')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable(); // qris, gopay, va
            $table->timestamp('expired_at')->nullable();
            $table->json('callback_payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
