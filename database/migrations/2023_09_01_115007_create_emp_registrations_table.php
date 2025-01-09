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
        Schema::create('emp_registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('employee_code');
            $table->string('dob');
            $table->string('gender');
            $table->string('job_title');
            $table->string('employment_type');
            $table->string('blood_group');
            $table->float('special_leave')->nullable();
            $table->float('casual_leave')->nullable();
            $table->float('sick_leave')->nullable();
            $table->dateTime('joining_date');
            $table->enum('accept_policy',['Pending','Accepted'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_registrations');
    }
};
