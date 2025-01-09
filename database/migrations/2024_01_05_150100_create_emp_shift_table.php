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
        Schema::create('emp_shift', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name')->nullable();
            $table->string('timezone')->nullable();
            $table->enum('status',['Enable','Disable'])->default('Enable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_shift');
    }
};
