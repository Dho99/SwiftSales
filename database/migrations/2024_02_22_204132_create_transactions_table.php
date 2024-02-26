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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customerId');
            $table->unsignedBigInteger('userId');
            $table->json('productId');
            $table->json('quantity');
            $table->json('total');
            $table->integer('subtotal');
            $table->enum('status', ['Process', 'Success', 'Canceled']);
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customerId')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
