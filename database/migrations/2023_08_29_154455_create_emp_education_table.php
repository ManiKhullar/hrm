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
        Schema::create('emp_education', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('highschool')->nullable();
            $table->string('intermediate')->nullable();
            $table->string('graduation')->nullable();
            $table->string('post_graduation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_education');
    }
};
