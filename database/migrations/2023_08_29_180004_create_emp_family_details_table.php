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
        Schema::create('emp_family_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->integer('number_type')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_family_details');
    }
};
