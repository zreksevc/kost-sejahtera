<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('emergency_contact')->nullable();
            $table->string('ktp_number')->nullable();
            $table->string('occupation')->nullable();
            $table->string('origin_address')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'alumni'])->default('active');
            $table->date('joined_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
