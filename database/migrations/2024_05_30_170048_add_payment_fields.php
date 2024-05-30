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
        Schema::table('publication_buyers', function (Blueprint $table) {
            //
            $table->string('mobile_network');
            $table->string('mobile_no');
            $table->string('payment_notes')->nullable();
            $table->dateTime('paid_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publication_buyers', function (Blueprint $table) {
            //
            $table->dropColumn(['mobile_network','mobile_no','payment_notes','paid_at']);
        });
    }
};
