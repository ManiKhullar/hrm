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
        Schema::create('login_session', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('status')->default('Login');
            $table->enum('location',['WFO','WFH'])->default('WFO');
            $table->string('comment')->nullable();
            $table->float('work_hours')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_session');
    }
};
