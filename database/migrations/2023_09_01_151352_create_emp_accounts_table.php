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
        Schema::create('emp_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('profile_pic')->nullable();
            $table->string('addhar_number')->nullable();
            $table->string('addhar_doc_file')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_doc_file')->nullable();
            $table->string('offer_letter')->nullable();
            $table->string('relieving_latter')->nullable();
            $table->string('resignation_letter')->nullable();
            $table->string('appointment_latter')->nullable();
            $table->string('bank_statment')->nullable();
            $table->string('salary_slip1')->nullable();
            $table->string('salary_slip2')->nullable();
            $table->string('salary_slip3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_accounts');
    }
};
