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
        Schema::create('claim', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('category', ['Mobile', 'Broadband', 'TravelAllowance ', 'Other']);
            $table->string('mobile')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->float('amount');
            $table->enum('status', ['Pending', 'Approve', 'Reject'])->default('Pending');
            $table->longText('description')->nullable();
            $table->integer('approval_by')->default(0);
            $table->longText('manager_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim');
    }
};
