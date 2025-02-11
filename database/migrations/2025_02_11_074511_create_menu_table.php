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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->noActionOnDelete();
            $table->string('name',50);
            $table->string('description',255);
            $table->smallInteger('stock');
            $table->smallInteger('sell');
            $table->smallInteger('favorite');
            $table->enum('role',['food','drink','addon']);
            $table->bigInteger('price');
            $table->bigInteger('discount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
