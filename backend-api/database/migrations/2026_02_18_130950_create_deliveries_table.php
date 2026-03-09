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
        Schema::create('deliveries', function (Blueprint $table) {
                $table->id();

                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('courier_id')->references('id')->on('users');

                $table->timestamp('picked_at')->nullable();
                $table->timestamp('delivered_at')->nullable();

                $table->string('delivery_photo')->nullable();
                $table->text('notes')->nullable();

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliverys');
    }
};
