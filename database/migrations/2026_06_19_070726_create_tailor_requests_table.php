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
        Schema::create('tailor_requests', function (Blueprint $table) {
            $table->id();
            $table->longText('original_content')->nullable();
            $table->string('job_url')->nullable();
            $table->longText('job_description')->nullable();
            $table->longText('tailored_content')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tailor_requests');
    }
};
