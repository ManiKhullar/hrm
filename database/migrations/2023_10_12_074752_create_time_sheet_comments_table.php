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
        Schema::create('time_sheet_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('timesheet_id');
            $table->longText('comment_history')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Reject', 'ReferBack'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_sheet_comments');
    }
};
