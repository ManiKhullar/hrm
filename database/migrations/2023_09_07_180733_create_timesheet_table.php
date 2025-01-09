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
        Schema::create('timesheet', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('user_id');
            $table->integer('manager_id')->default(0);
            $table->string('hours');
            $table->string('minutes');
            $table->date('select_date');
            $table->longText('description')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Reject', 'ReferBack'])->default('Pending');
            $table->longText('manager_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet');
    }
};
