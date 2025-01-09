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
        Schema::create('emp_policy', function (Blueprint $table) {
            $table->id();
            $table->string('hr_policy_leave_mang')->nullable();
            $table->string('hr_process_onboarding')->nullable();
            $table->string('hr_process_offboarding')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy');
    }
};
