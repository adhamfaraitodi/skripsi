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
        Schema::create('menu_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->noActionOnDelete();
            $table->foreignId('order_id')->constrained('orders')->noActionOnDelete();
            $table->string('name', 50);
            $table->smallInteger('quantity');
            $table->bigInteger('price');
            $table->bigInteger('discount');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_orders');
    }
};
