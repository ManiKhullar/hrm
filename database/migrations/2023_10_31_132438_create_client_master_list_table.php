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
        Schema::create('client_master_list', function (Blueprint $table) {
            $table->id();
            $table->string('technology')->nullable();
            $table->string('interview_date')->nullable();
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('client_email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('source')->nullable();
            $table->string('rate')->nullable();
            $table->text('pre_call_notes')->nullable();
            $table->text('meeting_link')->nullable();
            $table->text('post_call_notes')->nullable();
            $table->text('status')->nullable();
            $table->string('interview_taken_by')->nullable();
            $table->string('end_client')->nullable();
            $table->string('interview_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_master_list');
    }
};
