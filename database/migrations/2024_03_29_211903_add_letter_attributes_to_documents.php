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
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->string('sender')->nullable();
            $table->string('subject')->nullable();
            $table->string('sending_entity')->nullable();
            
            $table->unsignedBigInteger('executed_by')->nullable();
            $table->unsignedBigInteger('managed_by')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();

            $table->dateTime('executed_at')->nullable();
            $table->dateTime('managed_at')->nullable();
            $table->dateTime('assigned_at')->nullable();
            
            $table->foreign('executed_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('managed_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
};
