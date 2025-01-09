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
        Schema::create('emp_skill', function (Blueprint $table) {
            $table->id();
            $table->integer('skill_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->enum('skill_level',['Beginner','Proficient','Expert']);
            $table->float('experience');
            $table->enum('status',[0,1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_skill');
    }
};
