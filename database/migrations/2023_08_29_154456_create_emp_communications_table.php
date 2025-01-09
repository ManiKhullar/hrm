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
        Schema::create('emp_communications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('mobile_number')->nullable();
            $table->string('company_email_id')->nullable();
            $table->string('internal_email_id')->nullable();
            $table->string('email_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_communications');
    }
};
