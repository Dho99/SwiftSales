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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('batch');
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('supplierId');
            $table->unsignedBigInteger('categoryId');
            $table->json('images');
            $table->integer('stock');
            $table->integer('buyPrice');
            $table->integer('sellPrice');
            // $table->boolean('isExpired');
            $table->date('expiredDate');
            $table->unsignedBigInteger('userId');
            $table->timestamps();

            $table->foreign('supplierId')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('categoryId')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
