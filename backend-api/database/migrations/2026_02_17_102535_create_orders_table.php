<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            // ================= BASIC =================
            $table->string('invoice_number')->unique();
            $table->string('midtrans_order_id')->nullable()->index();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();

            $table->foreignId('courier_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // ================= TOTAL =================
            $table->unsignedBigInteger('total_price')->default(0);

            // ================= STATUS =================
            $table->string('status'); // ditentukan oleh service

            // ================= PAYMENT =================
            $table->string('payment_method');
            $table->string('payment_status')->default('UNPAID');

            // snap midtrans
            $table->string('payment_url')->nullable();
            $table->string('snap_token')->nullable();

            // midtrans callback
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->json('midtrans_payload')->nullable();
            $table->timestamp('paid_at')->nullable();

            // waktu order
            $table->timestamp('ordered_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
