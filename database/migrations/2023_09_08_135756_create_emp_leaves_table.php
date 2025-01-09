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
        Schema::create('emp_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('partical_leave')->nullable();
            $table->string('leave_type');
            $table->string('project_manager');
            $table->string('cc')->nullable();
            $table->float('leave_count');
            $table->longText('message');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_leaves');
    }
};
