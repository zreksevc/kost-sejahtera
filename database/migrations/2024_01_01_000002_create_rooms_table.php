<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Reguler', 'VIP', 'VVIP'])->default('Reguler');
            $table->unsignedBigInteger('price');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->text('facilities')->nullable(); // JSON string
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
