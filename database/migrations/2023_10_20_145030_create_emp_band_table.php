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
        Schema::create('emp_band', function (Blueprint $table) {
            $table->id();
            $table->string('emp_band');
            $table->float('basic_salary')->default(0.0);
            $table->float('house_rent_allounce')->default(0.0);
            $table->float('transport_allounce')->default(0.0);
            $table->float('special_allounce')->default(0.0);
            $table->float('extra_pay')->default(0.0);
            $table->enum('tds_type',['Yes','No']);
            $table->float('tds')->default(0.0);
            $table->enum('status',[0,1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_band');
    }
};
