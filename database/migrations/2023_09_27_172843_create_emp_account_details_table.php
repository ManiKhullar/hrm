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
        Schema::create('emp_account_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('bank_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('salary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_account_details');
    }
};
