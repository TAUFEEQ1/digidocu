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
        Schema::create('publication_buyers', function (Blueprint $table) {
            $table->id();
            $table->foreign('publication_id')->references('id')->on('documents');
            $table->foreign('buyer_id')->references('id')->on('users');
            $table->string('payment_ref');
            $table->string('status');
            $table->dateTime('purchased_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_buyers');
    }
};
