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
        Schema::create('emp_prev_employments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->longText('start_date')->nullable();
            $table->longText('end_date')->nullable();
            $table->longText('company_name')->nullable();
            $table->longText('role')->nullable();
            $table->longText('company_emp_ref_name')->nullable();
            $table->longText('company_emp_ref_email')->nullable();
            $table->longText('company_emp_ref_mobile')->nullable();
            $table->longText('company_emp_ref_role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_prev_employments');
    }
};
